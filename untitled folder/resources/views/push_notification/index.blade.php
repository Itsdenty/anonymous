@extends('layouts.user')
@section('title', 'Sendmunk | Push Notifications')

@section('styles')
    <style>
        .circle_desktop {
            display: flex;  
            justify-content: space-around;
            margin-bottom: 20px;
        }

        .circle.output{
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 2px solid black;
            font-size: 40px;
            line-height: 100px;
            text-align: center;
        } 
        .circle > .forms{
            font-size: 12px;
            margin-top: -25px;
        }
       
        @media only screen and (max-width: 768px) {
            .circle_desktop {
                display: block;
            }
            .circle.output{
                width: 120px;
                height: 120px;
                border-radius: 50%;
                border: 2px solid black;
                font-size: 40px;
                line-height: 100px;
                text-align: center;
                margin: auto auto 10px auto;
            } 

            .circle_layout{
                margin-left: 40px;
            }

        }
    </style>
@endsection

@section('content')
    @if(!$form)
    <div class="ui vertical">
        <a id="create_company_icon" href="#" class="publish center floated ui primary button" style="font-size: 20px; text-align: center;">
            <i class="plus icon"></i>Add website/ Blog
        </a>
    </div>
    
    <br/>
    @else

    <div class="ui vertical segment">  
        <a id="create_notification_icon" href="#" class="publish right floated ui button">
            <i class="plus icon"></i>Send Notification
        </a>
        <a id="reset_notification_icon" href="#" class="publish right floated ui button">
            <i class="sync icon"></i>Reset
        </a>
        <a id="edit_popup_icon" href="{{ url('edit_push') }}" class="publish right floated ui button">
            <i class="edit icon"></i>Edit Popup
        </a>
        <h2>Website Name: {{ $form->title}}  |
            <a href="{{ url('sitecode').'/'.$form->id }}" style="margin-left: 10px; font-size: 16px;" class=""><i data-content="View Site Code" class="">Site Code</i></a>
        </h2> 
    </div>
    <div class="ui basic segment circle_layout" style="background-image: linear-gradient(#FEFEFE, #EAEAEA); padding-top: 25px; padding-bottom: 25px;  border-radius: 20px;">
        <div class="circle_desktop">
            <div class="circle output">{{ $form->visitors ? $form->visitors->sum('visit_count') : 0 }}
                <p class="forms">Visitors</p>
            </div>
            <div class="circle output">{{ $form->clicks ? $form->clicks->count() : 0 }}
                <p class="forms">Clicks</p>
            </div>
            <div class="circle output">{{ $push_count }}
                <p class="forms">Susbscribers</p>
            </div>
            <div class="circle output">{{ $form->messages->count() }}
                <p class="forms">Messages</p>
            </div>
        </div>

        @if($form->messages->count() >= 0)
        <br><br>
        
        <table class="ui table">
            <thead>
                <tr>
                    <th>Message Title</th>
                    <th>Send Date</th>
                    <th>Sent To</th>
                    <th>Delivered</th>
                </tr>
            </thead>
            <tbody>
                @foreach($form->messages as $message)
                <tr>
                    <td>{{ $message->title }}</td>
                    <td>{{ $message->send_date }}</td>
                    <td>{{ $message->push_count_recent }}</td>
                    <td>{{ $message->delivered }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="ui message"><p>You have not sent any messages yet.</p></div>
        @endif
    </div>

    <div id="create_notification_modal" class="ui tiny modal">
        <div class="content">
            <form action="{{ url('notifications') }}" class="ui form" method="post" role="form"  enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="hidden" id="form_id" name="form_id" value="{{ $form->id }}">
                <input type="hidden" id="company" name="company" value="{{ $form->title }}">
                <div class="field">
                    <label>Title of Notification</label>
                    <input type="text" id="title" name="title" placeholder="Title of Notification" required>
                </div>
                <div class="field">
                    <label>Body of the Notification</label>
                    <textarea type="text" id="body" name="body" placeholder="Body of the Notification" rows="5" required></textarea>
                </div>
                <div class="field">
                    <label>Enter the particular URL you want to redirect to</label>
                    <input type="url" id="website" name="website" placeholder="https://example.com" required>
                </div>
                <div class="field">
                    <label>Choose Image to Use</label>
                    <input type="file" accept="image/*"  name="photo" id="photo" required>
                </div>

                <div class="field">
                    <label style="margin-bottom: 10px;">When to Send:</label>
                    <div class="ui">
                    <span style="margin-right: 20px;">
                        <input type="radio" name="sending" value="now" checked>
                        <label>Send Now</label>
                    </span>
                    <span>
                        <input type="radio" name="sending" value="later">
                        <label>Send Later</label>
                    </span>
                    </div>
                </div>
                <div id="send_date_div" class="field">
                    <input type="datetime-local" name="send_date" />
                </div>

                <button class="ui button" type="submit">Confirm</button>
            </form>
        </div>
    </div>

    {{-- Reset Push Modal --}}
    <div id="reset_push_modal" class="ui basic mini modal">
        <div class="ui icon header">
            <i class="trash icon"></i>
            Reset Push Notification
        </div>
        <div class="content">
            <p>Are you sure you want to reset push notificaton?</p>
        </div>
        <div class="actions">
            <div class="ui basic cancel inverted button">
            <i class="remove icon"></i> No
            </div>
            <a href="{{ url('delete/form').'/'.$form->id }}" class="ui blue ok inverted button">
                <i class="checkmark icon"></i> Yes
            </a>
        </div>
    </div>
    @endif


    {{-- Create New Form Modal --}}
    <div id="create_push_modal" class="ui tiny modal">
        <div class="header">Add website/ Blog</div>
        <div class="content">
            <form action="{{ url('create_push') }}" id="create_new_push" class="ui form" method="post" role="form">
                {{ csrf_field() }}
                <input type="hidden" name="poll_type" value="popover" />
                <div class="field">
                    <label>Website Name</label>
                    <div id="company_input_div" class="ui icon input">
                        <input id="company_name_input" type="text" name="title" required/>
                        <i class="icon"></i>
                    </div>
                    <small id="check_company_result"></small>
                </div>
            </form>
        </div>
        <div class="actions">
            <div class="ui cancel button">Cancel</div>
            <input id="create_push_btn" type="submit" value="Create" form="create_new_push" class="publish ui primary button" />
        </div>
    </div>
@endsection

@section('footerscripts')
    <script>
        $('#form_type').dropdown();
        $('#send_date_div').hide();

        $('#create_company_icon').on('click', function(){
            $('#action_title').val('');
            $('#action_type').dropdown('clear');
            $('#create_push_modal').modal('show');
        });

        $('#company_name_input').bind('change paste keyup', function(){
            $('#create_push_btn').prop('disabled', true);
            let company_name = $(this).val();
            $('#company_name_input').val(company_name.toLowerCase().replace(/\s/g, '_'));
            if($('#company_name_input').val()){
                $('#check_company_result').text('');
                $('#company_input_div').addClass('loading');
                $.ajax({
                    url: "{{ url('/') }}"+"/company/check/" + $('#company_name_input').val(),
                    method: 'get',
                    success: function(){
                        $('#check_company_result').text('Company available').css('color', "green");
                        $('#create_push_btn').prop('disabled', false);
                        $('#company_input_div').removeClass('loading');
                    },
                    error: function(){
                        $('#check_company_result').text('Company not available').css('color', "red");
                        $('#create_push_btn').prop('disabled', true);
                        $('#company_input_div').removeClass('loading');
                    }
                });
            }
            else
            {
                $('#check_company_result').text('');
            }
        });

        $('i').popup();

        // Set Current Date Time
        $.fn.setNow = function (onlyBlank) {
            var now = new Date($.now())
                , year
                , month
                , date
                , hours
                , minutes
                , seconds
                , formattedDateTime
                ;

            year = now.getFullYear();
            month = now.getMonth().toString().length === 1 ? '0' + (now.getMonth() + 1).toString() : now.getMonth() + 1;
            date = now.getDate().toString().length === 1 ? '0' + (now.getDate()).toString() : now.getDate();
            hours = now.getHours().toString().length === 1 ? '0' + now.getHours().toString() : now.getHours();
            minutes = now.getMinutes().toString().length === 1 ? '0' + now.getMinutes().toString() : now.getMinutes();
            seconds = now.getSeconds().toString().length === 1 ? '0' + now.getSeconds().toString() : now.getSeconds();

            formattedDateTime = year + '-' + month + '-' + date + 'T' + hours + ':' + minutes + ':' + "00";

            if ( onlyBlank === true && $(this).val() ) {
                return this;
            }

            $(this).val(formattedDateTime);

            return this;
        }

        $(function () {
            $('#create_notification_icon').on('click', function(e){
                e.preventDefault();
                $('input[type="datetime-local"]').setNow();
                $('#create_notification_modal').modal('show');
            });

            $('input[name="sending"]').on('change', function(){
                let sending_value = $(this).val();
                if(sending_value == 'later')
                {
                    $('#send_date_div').show();
                }
                else
                {
                    $('#send_date_div').hide();
                }
            });

            $('#reset_notification_icon').on('click', function(e){
                e.preventDefault();
                $('#reset_push_modal').modal('show');
            });

        });
    </script>
@endsection