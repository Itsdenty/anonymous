@extends('layouts.user')
@section('title', 'Sendmunk | Contact Profile')

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
        <a id="delete_contact_icon" href="#" class="right floated ui button"><i class="delete icon"></i>Delete Contact</a>
        @if(!$contact->unsubscribed)
        <a id="unsubscribe_contact_icon" href="#" class="right floated ui button"><i class="trash icon"></i>Unsubscribe Contact</a>
        @endif
        <button type="button" class="right floated ui button" disabled>Contact Added at {{ (new DateTime(date('Y-m-d H:i', (strtotime($contact->created_at) + ($user->profile->timezone->offset * 60 * 60)))))->format('M j, Y H:i:s') }}</button>
        <h2>Contact Profile</h2>
    </div>
    <br/>
    <div class="ui segment">
        <h3>Contact Details</h3>
        <hr/>
        <form class="ui form" id="campaign_form" action="{{ url('contact/update') }}" method="POST" role="form">
            {{ csrf_field() }}
            <input type="hidden" name="contact_id" value="{{ $contact->id }}" />
            <div class="field">
                <label>First Name</label>
                <input type="text" name="firstname" value="{{ $contact->firstname }}" placeholder="First Name" />
            </div>
            <div class="field">
                <label>Last Name</label>
                <input type="text" name="lastname" value="{{ $contact->lastname }}" placeholder="Last Name" />
            </div>
            <div class="field">
                <label>Email</label>
                <input type="email" name="email" value="{{ $contact->email }}" placeholder="Email Address" required />
            </div>
            <div class="field">
                <label>Phone No.</label>
                <input type="text" name="phone" value="{{ $contact->phone }}" placeholder="Phone No." />
            </div>
            <div class="field">
                <label>Tag(s)</label>
                <select name="tags[]" multiple="" class="ui fluid dropdown">
                    <option value="">Add Tag(s)</option>
                    @foreach($current_account_tags as $tag)
                    <option value="{{ $tag->id }}" {{ $contact_tags->contains($tag) ? "selected" : "" }}>{{ $tag->name }}</option>
                    @endforeach
                </select>
            </div>
            @foreach($contact_attributes as $attribute)
            <div class="field">
                <label>{{ ucwords(str_replace('_', ' ', $attribute->name)) }}</label>
                <input type="{{ $attribute->type }}" name="custom_{{ $attribute->id }}" placeholder="{{ ucwords(str_replace('_', ' ', $attribute->name)) }}" value="{{ $contact->contactAttributes->find($attribute->id) ? $contact->contactAttributes->find($attribute->id)->pivot->value : "" }}" />
            </div>
            @endforeach
        
            <button class="ui large button">Update</button>
        </form>
    </div>
    <br/>
    <div class="ui vertically stackable grid">
        <div class="three column row">
            <div class="summary column">
                <h2>{{ $contact->campaigns()->wherePivot('sent', true)->count() }} / {{ $contact->campaigns()->wherePivot('sent', true)->count() == 0 ? "0" : number_format((float)$contact->campaigns()->wherePivot('sent', true)->count() / $contact->campaigns()->wherePivot('sent', true)->count() * 100, 2, '.', '') }}%</h2>
                <h3>Email Sent</h3>
            </div>
            <div class="summary column">
                <h2>{{ $contact->campaigns()->wherePivot('opened', true)->count() }} / {{ $contact->campaigns()->wherePivot('sent', true)->count() == 0 ? "0" : number_format((float)$contact->campaigns()->wherePivot('opened', true)->count() / $contact->campaigns()->wherePivot('sent', true)->count() * 100, 2, '.', '') }}%</h2>
                <h3>Email Opened</h3>
            </div>
            <div class="summary column">
                <h2>{{ $contact->contactLinks->count() }} / {{ $total_links_count == 0 ? "0" : number_format((float)$contact->contactLinks->count() / $total_links_count * 100, 2, '.', '') }}%</h2>
                <h3>Clicked</h3>
            </div>
        </div>
    </div>
    <br/>
    <div class="ui segment">
        <h3>Contact Activities</h3>
        <hr>
        @if(!$contact_activities->isEmpty())
        <br/>
        <div>
            <div>
                <div style="float:right">
                    <select id="current_activity" class="ui dropdown">
                        <option value="all">All Activities</option>
                        <option value="clicks">Clicks</option>
                        <option value="opens">Opens</option>
                        <option value="sent_emails">Sent Emails</option>
                        <option value="bounces">Bounces</option>
                        <option value="unsubscribes">Unsubscribes</option>
                        <option value="automation">Automation</option>
                        <option value="sms">SMS</option>
                    </select>
                </div>
                <label style="float: right">&nbsp;&nbsp;&nbsp;&nbsp;Show&nbsp;</label>
                <div class="column" id="datepicker" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc;">
                    <i class="calendar icon"></i>&nbsp;
                    <span></span> <i class="caret down icon"></i>
                </div>
                <label style="float: right">Date&nbsp;&nbsp;</label>
            </div>
        </div>
        <br/><br/>
        <table id="activities_table" class="ui table">
            <tbody>
                @foreach($contact_activities->SortByDesc('id') as $activity)
                <tr>
                    <td width="80%">{!! $activity->content !!}</td>
                    <td>{{ (new DateTime(date('Y-m-d H:i', (strtotime($activity->created_at) + ($user->profile->timezone->offset * 60 * 60)))))->format('M j, Y H:i:s') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="ui message"><p>This Contact does not have any activity yet</p></div>
        @endif
    </div>

    {{-- Unsubscribe Modal --}}
    <div id="unsubscribe_contact_modal" class="ui basic mini modal">
        <div class="ui icon header">
            <i class="trash icon"></i>
            Unsubscribe Contact
        </div>
        <div class="content">
            <p>Are you sure you want to Unsubscribe this contact, this action cannot be reversed?</p>
        </div>
        <div class="actions">
            <div class="ui basic cancel inverted button">
            <i class="remove icon"></i> No
            </div>
            <a href="{{ url('unsubscribe_contact').'/'.$contact->id }}" class="ui blue ok inverted button">
                <i class="checkmark icon"></i> Yes
            </a>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div id="delete_contact_modal" class="ui basic mini modal">
            <div class="ui icon header">
                <i class="trash icon"></i>
                Delete Contact
            </div>
            <div class="content">
                <p>Are you sure you want to Delete this contact, this action cannot be reversed?</p>
            </div>
            <div class="actions">
                <div class="ui basic cancel inverted button">
                <i class="remove icon"></i> No
                </div>
                <a href="{{ url('contact/delete').'/'.$contact->id }}" class="ui blue ok inverted button">
                    <i class="checkmark icon"></i> Yes
                </a>
            </div>
        </div>

@endsection

@section('footerscripts')
    <script type="text/javascript" src="{{ url('js/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/daterangepicker.js') }}"></script>
    <script>
        $('.message .close').on('click', function() {
            $(this).closest('.message').transition('fade');
        });

        $('.menu .item').tab();

        $('i').popup();

        var start = moment("1970-01-01");
        var end = moment();

        var starting = start.format('YYYY-MM-DD');
        var ending = end.format('YYYY-MM-DD');

        function cb(start, end) {
            $('#datepicker span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }

        $('#datepicker').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
                'Any': [moment("1970-01-01"), moment()],
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
            starting = picker.startDate.format('YYYY-MM-DD');
            ending = picker.endDate.format('YYYY-MM-DD');
            let show_option = $("#current_activity").val();
            updateActivites(starting, ending, show_option);
        });

        $('#current_activity').on('change', function(){
            let show_option = $(this).val();
            updateActivites(starting, ending, show_option);
        });


        function updateActivites(startDate, endDate, showOption){
            $.ajax({
                url: "{{ url('update_activities') }}",
                method: "POST",
                data: {"start_date" : startDate, "end_date" : endDate, 'contact_id' : "{{ $contact->id }}", "show_option" : showOption, _token: '{{ csrf_token() }}'},
                success: function (data){
                    $('#activities_table tbody').empty();
                    console.log(data.activities);
                    $.each(data.activities, function(index, value){
                        $('#activities_table tbody').append('<tr><td width="80%">' + value["content"] + '</td><td>' + value["created_at"] + '</td></tr>')
                    });
                },
                error: function(data){
                    console.log(data)
                }
            });
        }

        $('#unsubscribe_contact_icon').on('click', function(e){
            $('#unsubscribe_contact_modal').modal('show');
        });

        $('#delete_contact_icon').on('click', function(e){
            $('#delete_contact_modal').modal('show');
        });

    </script>
@endsection
