@extends('layouts.user')
@section('title', 'Sendmunk | SMS/MMS Settings')

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
            <h2>SMS/MMS Settings</h2>
    </div>
    <br/>

    <div class="ui segment">
        <a id="add_sms_icon" href="#" class="right floated ui button"><i class="plus icon"></i>Add Twilio gateway</a>
        <h3>Twilio SMS/MMS gateway</h3>
        <hr/>
        @if(!$sms_mms_integrations->isEmpty())
        <table class="ui table">
            <thead>
                <tr>
                    <th width="30%">Account SID</th>
                    <th width="30%">Auth Token</th>
                    <th width="20%">Twilio Number</th>
                    <th width="20%" style="text-align: center">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sms_mms_integrations as $config)
                <tr>
                    <td>{{ $config->account_sid }}</td>
                    <td>{{ $config->auth_token }}</td>
                    <td>{{ $config->twilio_number }}</td>
                    <td style="text-align: center">
                        <a class="editconfig" data-id="{{ $config->id }}" data-number="{{ $config->twilio_number }}" data-sid="{{ $config->account_sid }}" data-token="{{ $config->auth_token }}" href="#"><i data-content="Edit SMS/MMS Config" class="edit icon"></i></a>
                        <a href="{{ url('delete/sms_mms').'/'.$config->id }}"><i data-content="Delete SMS/MMS Config" class="delete icon"></i></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="ui message"><p>You don't have any SMS/MMS Config(s) yet</p></div>
        @endif
    </div>

    <div class="ui segment">
        <h3>Receive SMS/MMS Webhooks</h3>
        <hr/>
        <p style="text-align: justify;">
            HTTPS endpoints/ Webhooks are used to recieve SMS/MMS from SMS/MSS providers side.
        </p>
        <br/>
        <div class="ui vertically stackable grid">
            <div class="two column row">
                <div class="column ui form">
                    <div class="ui field">
                        <label>Twilio SMS/MMS Webhook Url</label>
                        <div class="ui action input">
                            <input type="text" value="{{ url('receive_messages') }}" readonly="readonly">
                            <button class="copy_url ui right labeled icon button">
                              <i class="copy icon"></i>
                              Copy
                            </button>
                        </div>
                    </div>
                </div>
                <div class="column">
                </div>
            </div>
        </div>
    </div>

    {{-- Create SMS/MMS config Modal --}}
    <div id="create_sms_config_modal" class="ui tiny modal">
        <div class="header">Add SMS/MMS Config</div>
        <div class="content">
            <form action="{{ url('create_sms_mms') }}" id="create_new_sms" class="ui form" method="post" role="form">
                {{ csrf_field() }}
                <div class="field">
                    <label>Account SID</label>
                    <input type="text" name="account_sid" placeholder="Enter Account SID" required/>
                </div>
                <div class="field">
                    <label>Auth Token</label>
                    <input type="text" name="auth_token" placeholder="Enter Auth Token" required />
                </div>
                <div class="field">
                    <label>Twilio Number</label>
                    <input type="text" name="twilio_number" placeholder="Enter Twilio Number (eg. +123456789)" required />
                </div>
            </form>
        </div>
        <div class="actions">
            <div class="ui cancel button">Cancel</div>
            <input id="create_sms_btn" type="submit" value="Create" form="create_new_sms" class="ui button" />
        </div>
    </div>

    {{-- Edit SMS/MMS config Modal --}}
    <div id="edit_sms_config_modal" class="ui tiny modal">
        <div class="header">Edit SMS/MMS Config</div>
        <div class="content">
            <form action="{{ url('create_sms_mms') }}" id="edit_sms_mms" class="ui form" method="post" role="form">
                {{ csrf_field() }}
                <input type="hidden" name="sms_mms_id" id="sms_mms_id"/>
                <div class="field">
                    <label>Account SID</label>
                    <input id="edit_account_sid" type="text" name="account_sid" placeholder="Enter Account SID" required/>
                </div>
                <div class="field">
                    <label>Auth Token</label>
                    <input id="edit_auth_token" type="text" name="auth_token" placeholder="Enter Auth Token" required />
                </div>
                <div class="field">
                    <label>Twilio Number</label>
                    <input id="edit_twilio_number" type="text" name="twilio_number" placeholder="Enter Twilio Number (eg. +123456789)" required />
                </div>
            </form>
        </div>
        <div class="actions">
            <div class="ui cancel button">Cancel</div>
            <input id="edit_sms_btn" type="submit" value="Update" form="edit_sms_mms" class="ui button" />
        </div>
    </div>

    
@endsection

@section('footerscripts')
    <script type="text/javascript" src="{{ url("js/notify.min.js") }}"></script>
    <script>
        $('.message .close').on('click', function() {
            $(this).closest('.message').transition('fade');
        });

        $('i').popup();

        $('#add_sms_icon').on('click', function(){
            $('#create_new_sms')[0].reset();
            $('#create_sms_config_modal').modal('show');
        });

        $('.editconfig').on('click', function(e){
            e.preventDefault();

            $('#edit_sms_mms')[0].reset();
            $('#sms_mms_id').val($(this).data('id'));
            $('#edit_account_sid').val($(this).data('sid'));
            $('#edit_auth_token').val($(this).data('token'));
            $('#edit_twilio_number').val($(this).data('number'));

            $('#edit_sms_config_modal').modal('show');
        });

        $('.copy_url').click(function(){
            var code = $(this).closest('div.ui.action.input').find('input').val();
            var $temp = $('<input>');
            $("body").append($temp);
            $temp.val(code).select();
            document.execCommand("copy");
            $temp.remove();
            $.notify('Webhook url copied to clipboard', 'success');
        });

    </script>
@endsection
