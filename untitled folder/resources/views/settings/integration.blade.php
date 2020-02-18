@extends('layouts.user')
@section('title', 'Sendmunk | Integrations')

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
            <h2>Integration</h2>
    </div>
    <br/>

    <div class="ui segment">
        <a id="add_smpt_icon" href="#" class="right floated ui button"><i class="plus icon"></i>Configure SMTP</a>
        <h3>SMTP</h3>
        <hr/>
        @if(!$smtps->isEmpty())
        <table class="ui table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Server</th>
                    <th style="text-align: center">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($smtps as $smtp)
                <tr>
                    <td>{{ $smtp->title }}</td>
                    <td>{{ $smtp->server }}</td>
                    <td style="text-align: center">
                    <a class="editsmtp" data-id="{{ $smtp->id }}" data-title="{{ $smtp->title }}" data-smtp_type_id="{{ $smtp->smtp_type_id }}" data-server="{{ $smtp->server }}" data-port="{{ $smtp->port }}" data-user="{{ $smtp->user }}" data-password="{{ $smtp->password }}" data-api_key="{{ $smtp->api_key }}" data-smtp_domain="{{ $smtp->smtp_domain }}" data-domain="{{ $smtp->domain }}" data-is_limited="{{ $smtp->is_limited }}" data-sending_limit="{{ $smtp->sending_limit }}" data-time_value="{{ $smtp->time_value }}" data-time_unit="{{ $smtp->time_unit }}" data-encryption="{{ $smtp->encryption }}" href="#"><i data-content="Edit SMTP Config" class="edit icon"></i></a>
                        <a href="{{ url('delete/smtp').'/'.$smtp->id }}"><i data-content="Delete SMTP Config" class="delete icon"></i></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="ui message"><p>You don't have any SMTP Config(s) yet</p></div>
        @endif
    </div>
    <div class="ui segment">
        <a id="add_mail_api_icon" href="#" class="right floated ui button"><i class="plus icon"></i>Configure Mail API</a>
        <h3>Mail API</h3>
        <hr/>
        @if(!$mail_apis->isEmpty())
        <table class="ui table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Type</th>
                    <th style="text-align: center">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mail_apis as $api)
                <tr>
                    <td>{{ $api->title }}</td>
                    <td>{{ \App\ApiChannel::find($api->api_channel_id)->name }}</td>
                    <td style="text-align: center">
                        <a class="editapi" data-id="{{ $api->id }}" data-title="{{ $api->title }}" data-api_channel_id="{{ $api->api_channel_id }}"  data-domain="{{ $api->domain }}" data-api_key="{{ $api->api_key }}" href="#"><i data-content="Edit Mail API Config" class="edit icon"></i></a>
                        <a href="{{ url('delete/api').'/'.$api->id }}"><i data-content="Delete Mail API Config" class="delete icon"></i></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="ui message"><p>You don't have any Mail API Config(s) yet</p></div>
        @endif
    </div>
    <div class="ui segment">
        <h3>Bounce and Spam Webhooks</h3>
        <hr/>
        <p style="text-align: justify;">
            HTTPS endpoints/ Webhooks are used to get the bounce and spam emails data back into this account from the SMTP providers side. Works with: SMTP2Go, Amazon SES, Sendgrid, Postmark, Mailgun, Sparkpost. It is highly recommended that you set them up with your SMTP to keep your list clean and spam rate low.
        </p>
        <br/>
        <div class="ui vertically stackable grid">
            <div class="two column row">
                <div class="column ui form">
                    <div class="ui field">
                        <label>Campaign Webhook Url</label>
                        <div class="ui action input">
                            <input type="text" value="{{ url('campaignwebhook') }}" readonly="readonly">
                            <button class="copy_url ui right labeled icon button">
                              <i class="copy icon"></i>
                              Copy
                            </button>
                        </div>
                    </div>
                </div>
                <div class="column ui form">
                    <div class="ui field">
                        <label>Sequence Webhook Url</label>
                        <div class="ui action input">
                            <input type="text" value="{{ url('sequencewebhook') }}" readonly="readonly">
                            <button class="copy_url ui right labeled icon button">
                              <i class="copy icon"></i>
                              Copy
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Create smtp config Modal --}}
    <div id="create_smtp_config_modal" class="ui tiny modal">
        <div class="header">Integration - SMTP</div>
        <div class="scrolling content">
            <form action="{{ url('createsmtp') }}" id="create_new_smtp" class="ui form" method="post" role="form">
                {{ csrf_field() }}
                <div class="field">
                    <label>Title</label>
                    <input type="text" name="title" placeholder="Enter Title" required/>
                </div>
                <div class="field">
                    <label>SMTP Server</label>
                    <input type="text" name="server" placeholder="Enter SMTP Server" required />
                </div>
                <div class="field">
                    <label>SMTP Port</label>
                    <input type="text" name="port" placeholder="Enter SMTP Port" required />
                </div>
                <div class="field">
                    <label>SMTP User</label>
                    <input type="text" name="user" placeholder="Enter SMTP User" required />
                </div>
                <div class="field">
                    <label>SMTP Password</label>
                    <input type="text" name="password"  placeholder="Enter SMTP Password" required />
                </div>
                <div class="field">
                    <label>Encryption</label>
                    <select class="ui dropdown" name="encryption">
                        <option value="null" default>NULL</option>
                        <option value="tls">TLS</option>
                        <option value="ssl">SSL</option>
                    </select>
                </div>
                <hr/>
                <div>
                    <label>
                        <em>
                            The configuration setting below allow you to set a limit on email sending speed. For example, to limit sending speed to 2000 emails every 5 minutes, you can set sending limit = 2000, Time value = 5, and Time unit = minute accordingly
                        </em>
                    </label>
                </div>
                <div class="field">
                    <label>Set Limit</label>
                    <select id="set_limit" class="ui dropdown" name="is_limited" required>
                        <option value="0">Unlimited</option>
                        <option value="1">Limited</option>
                    </select>
                </div>
                <div class="limit_fields">
                    <div class="field">
                        <label>Sending Limit</label>
                        <input class="sending_input" type="number" min="1" name="sending_limit" />
                    </div>
                    <div class="field">
                        <label>Time Value</label>
                        <input class="sending_input" type="number" min="1" name="time_value" />
                    </div>
                    <div class="field">
                        <label>Time Unit</label>
                        <select class="ui dropdown" name="time_unit">
                            <option value="seconds">Seconds</option>
                            <option value="minute">Minute</option>
                            <option value="hour">Hour</option>
                            <option value="day">Day</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
        <div class="actions">
            <div class="ui cancel button">Cancel</div>
            <input id="create_smtp_btn" type="submit" value="Create" form="create_new_smtp" class="ui button" />
        </div>
    </div>

    {{-- Edit smtp config Modal --}}
    <div id="edit_smtp_config_modal" class="ui tiny modal">
        <div class="header">Edit SMTP Config</div>
        <div class="scrolling content">
            <form action="{{ url('updatesmtp') }}" id="edit_smtp" class="ui form" method="post" role="form">
                {{ csrf_field() }}
                <input id="edit_smtp_id" type="hidden" name="smtp_id" />
                <div class="field">
                    <label>Title</label>
                    <input id="edit_title" type="text" name="title" placeholder="Enter Title" required/>
                </div>
                <div class="field">
                    <label>SMTP Server</label>
                    <input id="edit_server" type="text" name="server" placeholder="Enter SMTP Server" required />
                </div>
                <div class="field">
                    <label>SMTP Port</label>
                    <input id="edit_port" type="text" name="port" placeholder="Enter SMTP Port" required />
                </div>
                <div class="field">
                    <label>SMTP User</label>
                    <input id="edit_user" type="text" name="user" placeholder="Enter SMTP User" required />
                </div>
                <div class="field">
                    <label>SMTP Password</label>
                    <input id="edit_password" type="text" name="password"  placeholder="Enter SMTP Password" required />
                </div>
                <div class="field">
                    <label>Encryption</label>
                    <select id="edit_encryption" class="ui dropdown" name="encryption">
                        <option value="null" default>NULL</option>
                        <option value="tls">TLS</option>
                        <option value="ssl">SSL</option>
                    </select>
                </div>
                <hr/>
                <div>
                    <label>
                        <em>
                            The configuration setting below allow you to set a limit on email sending speed. For example, to limit sending speed to 2000 emails every 5 minutes, you can set sending limit = 2000, Time value = 5, and Time unit = minute accordingly
                        </em>
                    </label>
                </div>
                <div class="field">
                    <label>Set Limit</label>
                    <select id="edit_set_limit" class="ui dropdown" name="is_limited" required>
                        <option value="0">Unlimited</option>
                        <option value="1">Limited</option>
                    </select>
                </div>
                <div class="limit_fields">
                    <div class="field">
                        <label>Sending Limit</label>
                        <input id="edit_sending_limit" class="sending_input" type="number" min="1" name="sending_limit" />
                    </div>
                    <div class="field">
                        <label>Time Value</label>
                        <input id="edit_time_value" class="sending_input" type="number" min="1" name="time_value" />
                    </div>
                    <div class="field">
                        <label>Time Unit</label>
                        <select id="edit_time_unit" class="ui dropdown" name="time_unit">
                            <option value="seconds">Seconds</option>
                            <option value="minute">Minute</option>
                            <option value="hour">Hour</option>
                            <option value="day">Day</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
        <div class="actions">
            <div class="ui cancel button">Cancel</div>
            <input id="edit_smtp_btn" type="submit" value="Update" form="edit_smtp" class="ui button" />
        </div>
    </div>


    {{-- Create Mail API config Modal --}}
    <div id="create_api_config_modal" class="ui tiny modal">
        <div class="header">Integration - Mail API</div>
        <div class="content">
            <form action="{{ url('createapi') }}" id="create_new_api" class="ui form" method="post" role="form">
                {{ csrf_field() }}
                <div class="field">
                    <label>Title</label>
                    <input id="form_title" type="text" name="title" placeholder="Enter Title" required/>
                </div>
                <div class="field">
                    <label>Api Channel</label>
                    <select id="api_channel" name="api_channel_id" class="ui dropdown" required>
                        @foreach($api_channels as $channel)
                        <option value="{{ $channel->id }}">{{ $channel->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label>API Key</label>
                    <input type="text" name="api_key" placeholder="Enter API Key" required />
                </div>

                <div class="api_domain field">
                    <label>Domain</label>
                    <input id="api_domain" type="text" name="domain" placeholder="Enter Domain" />
                </div>
            </form>
        </div>
        <div class="actions">
            <div class="ui cancel button">Cancel</div>
            <input id="create_api_btn" type="submit" value="Create" form="create_new_api" class="ui button" />
        </div>
    </div>

    {{-- Edit Mail API config Modal --}}
    <div id="edit_api_config_modal" class="ui tiny modal">
        <div class="header">Edit Mail API</div>
        <div class="content">
            <form action="{{ url('updateapi') }}" id="edit_api" class="ui form" method="post" role="form">
                {{ csrf_field() }}
                <input id="edit_api_id" type="hidden" name="api_id" />
                <div class="field">
                    <label>Title</label>
                    <input id="edit_api_title" type="text" name="title" placeholder="Enter Title" required/>
                </div>
                <div class="field">
                    <label>Api Channel</label>
                    <select id="edit_api_channel_id" name="api_channel_id" class="ui dropdown" required>
                        @foreach($api_channels as $channel)
                        <option value="{{ $channel->id }}">{{ $channel->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label>API Key</label>
                    <input id="edit_mail_api_key" type="text" name="api_key" placeholder="Enter API Key" required />
                </div>

                <div class="api_domain field">
                    <label>Domain</label>
                    <input id="edit_api_domain" type="text" name="domain" placeholder="Enter Domain" />
                </div>
            </form>
        </div>
        <div class="actions">
            <div class="ui cancel button">Cancel</div>
            <input id="edit_api_btn" type="submit" value="Update" form="edit_api" class="ui button" />
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

        $('#add_smpt_icon').on('click', function(){
            $('#create_new_smtp')[0].reset();
            $('#create_smtp_config_modal').modal('show');
        });


        $('#set_limit, #edit_set_limit').on('change', function(){
            let set_limit = $(this).val();
            if(set_limit == '0')
            {
                $('.sending_input').prop('required', false);
                $('.limit_fields').hide();
            }
            else
            {
                $('.sending_input').prop('required', true);
                $('.limit_fields').show();
            }
        });

        $('.editsmtp').on('click', function(e){
            e.preventDefault();

            $('#edit_smtp')[0].reset();
            $('#edit_smtp_id').val($(this).data('id'));
            $('#edit_title').val($(this).data('title'));
            $('#edit_smtp_type_id').val($(this).data('smtp_type_id')).trigger('change');
            $('#edit_server').val($(this).data('server'));
            $('#edit_port').val($(this).data('port'));
            $('#edit_user').val($(this).data('user'));
            $('#edit_password').val($(this).data('password'));
            $('#edit_api_key').val($(this).data('api_key'));
            $('#edit_smtp_domain').val($(this).data('smtp_domain'));
            $('#edit_domain').val($(this).data('domain'));
            $('#edit_set_limit').val($(this).data('is_limited')).trigger('change');
            $('#edit_sending_limit').val($(this).data('sending_limit'));
            $('#edit_time_value').val($(this).data('time_value'));
            $('#edit_time_unit').val($(this).data('time_unit')).trigger('change');
            $('#edit_encryption').val($(this).data('encryption')).trigger('change');

            $('#edit_smtp_config_modal').modal('show');
        });

        $('#add_mail_api_icon').on('click', function(){
            $('#create_new_api')[0].reset();
            $('#create_api_config_modal').modal('show');
        });

        $('#api_channel,#edit_api_channel_id').on('change', function(){
            let channel = $(this).val();
            if(channel == 1)
            {
                $('#api_channel').prop('required', false);
                $('.api_domain').hide();
            }
            else
            {
                $('#api_channel').prop('required', true);
                $('.api_domain').show();
            }
        });

        $('.editapi').on('click', function(e){
            e.preventDefault();

            $('#edit_api')[0].reset();
            $('#edit_api_id').val($(this).data('id'));
            $('#edit_api_title').val($(this).data('title'));
            $('#edit_api_channel_id').val($(this).data('api_channel_id')).trigger('change');
            $('#edit_mail_api_key').val($(this).data('api_key'));
            $('#edit_api_domain').val($(this).data('domain'));

            $('#edit_api_config_modal').modal('show');
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

        $(document).ready(function(){
            $('#set_limit').trigger('change');
            $('#api_channel').trigger('change');
        });

    </script>
@endsection
