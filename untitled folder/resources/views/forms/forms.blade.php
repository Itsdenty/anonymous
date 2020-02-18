@extends('layouts.user')
@section('title', 'Sendmunk | Basic Forms')

@section('styles')
    <link rel="stylesheet" type="text/css" href='{{ url("css/daterangepicker.css") }}'/>
    <style>
        #datepicker{
            background:white;
            float:right;
            width:30%;
            padding: 2px;
        }
        @media (max-width: 767px) {
            #datepicker{
                width:80%;
            }
        }

        .summary{
            background: white;
            border: 2px solid #E8E9EE;
            text-align: center;
            padding: 10px 0px;
        }
    </style>
@endsection

@section('content')
    <div class="ui vertical segment">
        <a id="create_form_icon" href="#" class="publish right floated ui button"><i class="plus icon"></i>Create New Form</a>
        <h2>Basic Forms</h2>
    </div>
    <br/>
    <div class="ui vertically stackable grid">
        <div class="four column row">
            <div class="summary column">
                <h3>Total Subscribers</h3>
                <h2>{{ $subs_count }}</h2>
            </div>
            <div class="summary column">
                <h3>No. of Forms</h3>
                <h2>{{ $forms_count }}</h2>
            </div>
            <div class="summary column">
                <h3>No. of Visits</h3>
                <h2>{{ $vis_count }}</h2>
            </div>
            <div class="summary column">
                <h3>Conversion Rate</h3>
                <h2>{{ $conversion_rate }}%</h2>
            </div>
        </div>
    </div>
    <br/>
    <div>
        <div>
            <div style="" class="column" id="datepicker" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc;">
                <i class="calendar icon"></i>&nbsp;
                <span></span> <i class="caret down icon"></i>
            </div>
        </div>
    </div>
    <br/>
    <canvas id="myChart" width="400" height="150"></canvas>
    <br/>
    @if(!$forms->isEmpty())
    <table class="ui table">
        <thead>
            <tr>
                <th>Form Name</th>
                <th>Form Type</th>
                <th style="text-align: center">Visits</th>
                <th style="text-align: center">Subscribers</th>
                <th style="text-align: center">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($forms as $form)
            <tr>
                <td>{{ $form->title }}</td>
                <td>{{ $form->form_type }}</td>
                <td style="text-align: center">{{ $form->visitors ? $form->visitors->sum('visit_count') : 0 }}</td>
                <td style="text-align: center"><a href="{{ url('subscribers/form').'/'.$form->id }}">{{ $form->subscriptions ? $form->subscriptions->count() : 0 }} <i data-content="View Subscriber(s)" class="download icon"></i></a></td>
                <td style="text-align: center">
                    <a href="{{ url('editform').'/'.$form->id }}"><i data-content="Edit Form" class="edit icon"></i></a>
                    <a href="{{ url('delete/form').'/'.$form->id }}"><i data-content="Delete Form" class="delete icon"></i></a>
                    <a href="{{ url('sitecode').'/'.$form->id }}"><i data-content="View Site Code" class="code icon"></i></a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="ui message"><p>You don't have any Form(s) yet</p></div>
    @endif

    {{-- Create New Form Modal --}}
    <div id="create_form_modal" class="ui tiny modal">
        <div class="header">Create New Form</div>
        <div class="content">
            <form action="{{ url('createform') }}" id="create_new_form" class="ui form" method="post" role="form">
                {{ csrf_field() }}
                <div class="field">
                    <label>Form Title</label>
                    <input id="form_title" type="text" name="title" required/>
                </div>
                <div class="field">
                    <label>Form Type</label>
                    <select name="form_type" id="form_type" class="ui dropdown" required>
                        <option value="">Select Form Type</option>
                        <option value="popover">Popover</option>
                        <option value="embedded">Embedded</option>
                        <option value="topbar">Topbar</option>
                        <option value="scrollbox">Scrollbox</option>
                        <option value="welcome_mat">Welcome Mat</option>
                    </select>
                </div>
            </form>
        </div>
        <div class="actions">
            <div class="ui cancel button">Cancel</div>
            <input id="create_form_btn" type="submit" value="Create" form="create_new_form" class="ui primary button" />
        </div>
    </div>

@endsection

@section('footerscripts')
    <script type="text/javascript" src="{{ url('js/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/daterangepicker.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/Chart.min.js') }}"></script>
    <script>
        $('#form_type').dropdown();

        $('#create_form_icon').on('click', function(){
            $('#form_title').val('');
            $('#form_type').dropdown('clear');
            $('#create_form_modal').modal('show');
        });

        $('i').popup();

        var start = moment().subtract(1, 'months');
        var end = moment();

        function cb(start, end) {
            $('#datepicker span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }

        $('#datepicker').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            "opens": "left"
        }, cb);

        cb(start, end);

        $('#datepicker').on('apply.daterangepicker', function(ev, picker){
            let starting = picker.startDate.format('YYYY-MM-DD');
            let ending = picker.endDate.format('YYYY-MM-DD');
            $.ajax({
                url: "{{ url('forms_analysis') }}",
                method: "POST",
                data: {"start_date": starting, "end_date": ending, _token: '{{ csrf_token() }}'},
                success: function (data){
                    removeData(myChart);
                    
                    let subs = data.subscriptions;
                    let visits = data.visits;

                    myChart.data.labels = data.labels;
                    myChart.data.datasets[0].data = data.subscriptions;
                    myChart.data.datasets[1].data = data.visits;

                    myChart.update();
                }
            });
        });

        function removeData(chart) {
            chart.data.labels.pop();
            chart.data.datasets.forEach((dataset) => {
                dataset.data.pop();
            });
            chart.update();
        }

        function addData(chart, label, data) {
            chart.data.labels.push(label);
            chart.data.datasets.forEach((dataset) => {
                dataset.data.push(data);
            });
            chart.update();
        }

        // Chart
        var ctx = document.getElementById("myChart");
        var myChart = new Chart(ctx, {
            type: 'line',
            responsive: true,
            data: {
                labels: {!! json_encode($labels) !!},
                datasets: [{
                    label: 'Subscribers',
                    data: {{ json_encode($subscriptions) }},
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                    ],
                    borderColor: [
                        'rgba(255,99,132,1)',
                    ],
                    borderWidth: 1
                },
                {
                    label: 'Visitors',
                    data: {{ json_encode($visits) }},
                    borderColor: [
                        
                    ],
                    borderWidth: 1
                }
                ]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero:true
                        }
                    }]
                }
            }
        });
    </script>
@endsection