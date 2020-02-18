@extends('layouts.user')
@section('title', 'Sendmunk | Unsubscribe Page Settings')

@section('content')
    @if(Session::has('error'))
    <div class="ui warning message">
        <i class="close icon"></i>
        <div class="header">
            {{ Session::get('error') }}
        </div>
    </div>
    @endif
    @if(Session::has('status'))
    <div class="ui positive message">
        <i class="close icon"></i>
        <div class="header">
            {{ Session::get('status') }}
        </div>
    </div>
    @endif

    <div class="ui vertical segment">
        <a id="edit_page_icon" href="#" class="right floated ui button"><i class="pencil icon"></i>Edit Page</a>
        <a id="create_reason_icon" href="#" class="publish right floated ui button"><i class="plus icon"></i>Add Reason</a>
        <h2>Unsubscribe Page Settings</h2>
    </div>
    <br/>
    <div class="ui vertically stackable grid">
        <div class="three column row">
            <div class="summary column">
                <h3><i class="users icon"></i></h3>
                <h2>{{ $num_of_contacts }}</h2>
                <h3>No. of Contacts</h3>
            </div>
            <div class="summary column">
                <h3><i class="ban icon"></i></h3>
                <h2>{{ $num_of_unsubscribers }}</h2>
                <h3>No. of Unsubscribers</h3>
            </div>
            <div class="summary column">
                <h3><i class="ban icon"></i></h3>
                <h2>{{ $num_of_contacts == 0 ? '0' : number_format((float)$num_of_unsubscribers / $num_of_contacts * 100, 2, '.', '') }}%</h2>
                <h3>Percentage of Unsubscribers</h3>
            </div>
        </div>
    </div>
    <br/>
    @if(!$reasons->isEmpty())
    <canvas id="myChart" width="400" height="150"></canvas>
    <br/>
    <table class="ui table">
        <thead>
            <tr>
                <th width="80%">Reason Title</th>
                <th style="text-align: center">Count</th>
                <th style="text-align: center">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reasons as $reason)
            <tr>
                <td>{{ $reason->title }}</td>
                <td style="text-align: center">{{ $reason->count }}</td>
                <td style="text-align: center">
                    <a class="editreason" data-id="{{ $reason->id }}" data-title="{{ $reason->title }}" href="#"><i data-content="Edit Reason" class="edit icon"></i></a>
                    <a href="{{ url('delete/reason').'/'.$reason->id }}"><i data-content="Delete Reason" class="delete icon"></i></a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="ui message"><p>You don't have any unsubscribe reason(s)</p></div>
    @endif

    {{-- Add New Reason Modal --}}
    <div id="add_reason_modal" class="ui tiny modal">
        <div class="header">Add Reason</div>
        <div class="content">
            <form action="{{ url('reasons') }}" id="add_new_reason" class="ui form" method="post" role="form">
                {{ csrf_field() }}
                <div class="field">
                    <label>Title</label>
                    <input id="reason_title" type="text" name="title" placeholder="Title" required />
                </div>
            </form>
        </div>
        <div class="actions">
            <div class="ui cancel button">Cancel</div>
            <input id="add_reason_btn" type="submit" value="Create" form="add_new_reason" class="ui primary button" />
        </div>
    </div>

    {{-- Edit New Reason Modal --}}
    <div id="edit_reason_modal" class="ui tiny modal">
        <div class="header">Edit Reason</div>
        <div class="content">
            <form action="{{ url('updatereason') }}" id="edit_reason" class="ui form" method="post" role="form">
                {{ csrf_field() }}
                <input type="hidden" id="edit_reason_id" name="id" />
                <div class="field">
                    <label>Title</label>
                    <input id="edit_reason_title" type="text" name="title" placeholder="Title" required />
                </div>
            </form>
        </div>
        <div class="actions">
            <div class="ui cancel button">Cancel</div>
            <input id="edit_reason_btn" type="submit" value="Update" form="edit_reason" class="ui primary button" />
        </div>
    </div>

    {{-- Edit Unsubscribe Page Modal --}}
    <div id="edit_page_modal" class="ui modal">
        <div class="header">Unsubscribe Page Content</div>
        <div class="content">
            <form action="{{ url('updatepagecontent') }}" id="update_page_content" class="ui form" method="post" role="form">
                {{ csrf_field() }}
                <textarea id="page_content" name="unsubscribe_page_content">{!! $user->currentAccount->unsubscribe_page_content !!}</textarea>
            </form>
        </div>
        <div class="actions">
            <div class="ui cancel button">Cancel</div>
            <input id="edit_page_btn" type="submit" value="Update" form="update_page_content" class="ui primary button" />
        </div>
    </div>
@endsection

@section('footerscripts')
    <script type="text/javascript" src="{{ url('js/Chart.min.js') }}"></script>
    <script>
        $('.message .close').on('click', function() {
            $(this).closest('.message').transition('fade');
        });

        $('#create_reason_icon').on('click', function(){
            $('#add_new_reason').trigger('reset');
            $('#add_reason_modal').modal('show');
        });

        $('.editreason').on('click', function(e){
            e.preventDefault();

            $('#edit_reason')[0].reset();
            $('#edit_reason_id').val($(this).data('id'));
            $('#edit_reason_title').val($(this).data('title'));
            $('#edit_reason_modal').modal('show');
        });

        $('#edit_page_icon').on('click', function(e){
            e.preventDefault();
            $('#edit_page_modal').modal('show');
        });

        var ctx = document.getElementById("myChart");
        if(ctx)
        {
            var myChart = new Chart(ctx, {
                type: 'bar',
                responsive: true,
                data: {
                    labels: {!! json_encode($labels) !!},
                    datasets: [{
                        label: 'Contacts',
                        data: {{ json_encode($reason_values) }},
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                        ],
                        borderColor: [
                            'rgba(255,99,132,1)',
                        ],
                        borderWidth: 1
                    }]
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
        }

        $('#page_content').summernote({
            toolbar:[
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript',  'subscript']],
                ['para', ['ul', 'ol', 'style', 'height', 'paragraph']],
                ['color', ['color']],
                ['insert', ['picture', 'link', 'video', 'table', 'hr']],
                ['view', ['fullscreen', 'undo', 'redo', 'codeview', 'help']],
            ],
            height: 200,
            placeholder: "Click the Code View Button (</>) to paste your HTML Code"
        });
    </script>
@endsection