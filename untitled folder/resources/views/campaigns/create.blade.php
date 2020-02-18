@extends('layouts.user')
@section('title', 'Sendmunk | Create Campaign')

@section('styles')
    <link rel="stylesheet" type="text/css" href='{{ url("css/daterangepicker.css") }}'/>
    <link rel="stylesheet" href="{{ asset('emoji-picker-master/lib/css/emoji.css') }}" />
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="{{ asset('tam-emoji/css/emoji.css') }}" rel="stylesheet">
    <style>
        /* remove top and bottom padding */
        .note-color .note-dropdown-menu {
            min-width: 100px !important;
            padding: 0;
        }
        /* hide background color picker */
        .note-color .note-palette:first-child {
            display: none !important;
        }
        /* remove palette title */
        .note-color .note-palette-title {
            display: none;
        }
        .emoji-picker-icon{
            position:relative;
            right: 20px;
            top: 5px;
        }
        .emoji-menu{
            position:absolute;
            right: 20%;
            top: 100%;
        }
        @media only screen and (min-width:1200px){
            .emoji-menu{
            position:absolute;
            right: 10%;
            top: 100%;
        } 
        }
        .emoji-wysiwyg-editor{
            width:100%;
        }
        .emoji-wysiwyg-editor:focus{
            outline: none;
        }
        .emoji-wysiwyg-editor::-webkit-scrollbar{
            display: none;
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
        <h2>Create Campaign</h2>
    </div>
    <br/>
    <div class="ui pointing secondary stackable menu">
        <a class="item active" data-tab="setup">Setup</a>
        <a id="content_tab" class="item" data-tab="content">Editor Type</a>
        <a id="preview_tab" class="item" data-tab="preview">Preview</a>
    </div>
    <form id="campaign_form" action="{{ url('updatecampaign') }}" method="POST" role="form">
        {{ csrf_field() }}
        <input type="hidden" name="campaign_id" value="{{ $campaign->id }}">
        <div class="ui tab active" data-tab="setup">
            <div class="ui stackable grid">
                <div class="ten wide column ui segment">
                    <div class="ui form">
                        <div class="field">
                            <label>Campaign Name</label>
                            <input type="text" name="title" value="{{ $campaign->title }}"/>
                        </div>
                        <div class="field">
                            <label>Who will this campaign be from</label>
                            <select name="from_reply_id" id="from_reply_email" class="ui dropdown">
                                @foreach($from_reply_emails as $email)
                                <option data-email="{{ $email->email }}" value="{{ $email->id }}">{{ $email->email }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field">
                            <label>Choose Sending Integration</label>
                            <select class="ui dropdown" name="integration">
                                @foreach($smtps as $smtp)
                                <option value="smtp_{{ $smtp->id }}">{{ $smtp->title }} (SMTP)</option>
                                @endforeach

                                @foreach($mail_apis as $api)
                                <option value="api_{{ $api->id }}">{{ $api->title }} (Mail API)</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="ui segment">
                            {{-- <h3>Who will receive the campaign</h3> --}}
                            {{-- <div style="display:flex" class="field">
                                <label style="width: 70%">Select your subscribers</label>
                                <label style="width: 30%" class="ui right floated">0 subscribers</label>
                            </div> --}}
                            {{-- <div id="noti">
                            <filter-campaigns :campaign = "{{ $campaign }}"></filter-campaigns>
                            </div> --}}
                            @include('includes.user.filter')
                        </div>
                    </div>
                </div>
                <div class="six wide column">
                    <div class="ui form">
                        <div class="field">
                            @if($campaign->status == 'draft')
                            <label>This campaign is currently a draft, when sent it would be delivered to <span class="campaign_contacts_count">{{ $contacts->count() }}</span> recepients</label>
                            @elseif($campaign->status == 'later')
                            <label>This campaign has been scheduled, when sent it would be delivered to <span class="campaign_contacts_count">{{ $contacts->count() }}</span> recepients</label>
                            @endif
                        </div>
                        <a href="{{ url('duplicate/'.$campaign->id.'/campaign') }}" class="ui large fluid button">Duplicate Campaign</a>
                    </div>
                </div>
            </div>
            <hr/>
            <button type="button" id="next_step_1" class="ui right floated big button">Next Step</button>
        </div>
        <div class="ui tab" data-tab="content">
            <div class="ui stackable grid">
                <div class="ten wide column ui segment">
                    <div class="ui form">
                        <div class="ui segment">
                            <div style="width: 30%" class="ui slider checkbox">
                                <input type="checkbox" name="ab_test" id="ab_test" />
                                <label>AB Test</label>
                            </div>
                            <br/><br/>
                            <div class="field">
                                <label>Email Subject</label>
                                <div class="ui right labeled input">
                                    <input data-emojiable="true" id="subject_a" type="text" placeholder="Enter Subject" name="subject_a" />
                                    <div class="ui dropdown label">
                                        <div class="text">Personalize</div>
                                        <i class="dropdown icon"></i>
                                        <div class="menu">
                                            <div class="personalize item">Contact's name</div>
                                            <div class="personalize item">Contact's email address</div>
                                        </div>
                                    </div>
                                </div>
                                <div id="subject_b_div">
                                    <br/>
                                    <div class="ui right labeled input">
                                        <input id="subject_b" type="text" placeholder="Enter Subject 2" name="subject_b" />
                                        <div class="ui dropdown label">
                                            <div class="text">Personalize</div>
                                            <i class="dropdown icon"></i>
                                            <div class="menu">
                                                <div class="personalize item">Contact's name</div>
                                                <div class="personalize item">Contact's email address</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ui segment">
                            <div class="field">
                            <label>Choose Your Editor</label>
                            <div class="ui placeholder segment">
                                <div class="ui two column stackable center aligned grid">
                                    <div class="ui vertical divider">Or</div>
                                    <div class="middle aligned row">
                                    <div class="column" id="rich-text-editor" style="cursor:pointer;">
                                        <div class="ui icon header">
                                        <i class="code icon"></i>
                                        Rich Text Editor / Paste Your Code
                                        </div>
                                        <div id="rich_text_editor_modal" class="ui modal">
                                            <div class="header">Rich Text Editor</div>
                                            <div class="content">
                                                <textarea id="email-content"></textarea>
                                            </div>
                                            <div class="actions">
                                                <div id="approve_rich_editor" class="ui approve primary button">Approve</div>
                                                <div class="ui cancel button">Cancel</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="column" style="cursor:pointer;">
                                    <a id="dragdrop_link" href="{{ url('dragdropeditor').'/'.$campaign->id }}" target="_top">
                                        <div class="ui icon header">
                                            <i class="magic icon"></i>
                                            Drag and Drop Editor / Templates
                                            </div>
                                        </div></a>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="six wide column">
                    <div class="ui form">
                        <div class="field">
                            @if($campaign->status == 'draft')
                            <label>This campaign is currently a draft, when sent it would be delivered to <span class="campaign_contacts_count">{{ $contacts->count() }}</span> recepients</label>
                            @elseif($campaign->status == 'later')
                            <label>This campaign has been scheduled, when sent it would be delivered to <span class="campaign_contacts_count">{{ $contacts->count() }}</span> recepients</label>
                            @endif
                            <br/>
                            <label><i class="eye icon"></i> PREVIEW</label>
                        </div>
                        <div class="ui fluid large buttons">
                            <button id="browser_preview" type="button" class="ui button"><i class="browser icon"></i> Browser</button>
                            <button id="mail_preview" type="button" class="ui button"><i class="mail icon"></i> Email</button>
                        </div>
                        <br/><br/>
                        <a href="{{ url('duplicate/'.$campaign->id.'/campaign') }}" class="ui large fluid button">Duplicate Campaign</a>
                    </div>
                </div>
            </div>
            <hr/>
            <div class="ui right floated big buttons">
                <button type="button" class="ui button save_campaign">Save</button>
                <button type="button" id="next_step_2" class="ui button">Next Step</button>
            </div>
        </div>
        <div class="ui tab" data-tab="preview">
            <div class="ui stackable grid">
                <div class="ten wide column ui segment">
                    <h3 id="preview_subject">Here</h3>
                    <p id="sender_email"></p>
                    {{-- <div class="ui form segment">
                        <div class="field">
                            <label>Preview as a subscriber</label>
                            <p>See what this email will look like for a specific subscriber in your account.</p>
                            <select class="ui dropdown">
                                <option value="">Subscriber's email address</option>
                            </select>
                        </div>
                    </div> --}}
                    <strong><label>Email Content</label></strong>
                    <div style="min-height:300px" id="preview-container" class="ui segment">
                            {!! $campaign->content !!}
                    </div>
                </div>
                <div class="six wide column">
                    <div class="ui form">
                        {{-- <input id="send_later" name="send_later" type="checkbox" hidden/> --}}
                        <div class="field">
                            @if($campaign->status == 'draft')
                            <label>This campaign is currently a draft, when sent it would be delivered to <span class="campaign_contacts_count">{{ $contacts->count() }}</span> recepients</label>
                            @elseif($campaign->status == 'later')
                            <label>This campaign has been scheduled, when sent it would be delivered to <span class="campaign_contacts_count">{{ $contacts->count() }}</span> recepients</label>
                            @endif
                            <br/>
                            <label><i class="mail icon"></i> Status: draft</label>
                            <br/>
                            <label><i class="users icon"></i> <span class="campaign_contacts_count">{{ $contacts->count() }}</span> recepients</label>
                            <br/>
                            {{-- <p>Send <strong id="send_label">Immediately</strong> (<a id="schedule_link" href="#">schedule</a> <span id="later_link"><a id="clear_schedule" href="#">Reset</a> | <a id="reschedule_link" href="#">Re-schedule</a></span>)</p>
                            <input type="text" value="" name="schedule_date" id="schedule_date" readonly hidden/>
                            <br/><br/> --}}

                            <div class="grouped fields">
                                <div class="field">
                                    <div class="ui radio checkbox">
                                        <input type="radio" name="send_later" value="0" checked required/>
                                        <label><strong>Send it now</strong><br/><p>Send your campaign now</p></label>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="ui radio checkbox">
                                        <input type="radio" name="send_later" value="1" required />
                                        <label>
                                            <strong>Schedule for a specific time</strong><br/>
                                            <p>Schedule your campaign to be sent in the future</p>

                                            <small class="schedule_items">Date/Time - {{ $user->profile->timezone->text }}</small>
                                            <p class="schedule_items"><input type="text" value="{{ $campaign->send_date }}" name="schedule_date" id="schedule_date" readonly /></p>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" id="send_campaign" form="campaign_form" class="ui big primary fluid large button">Confirm</button>
                        <br/><br/><br/>
                        <button  type="button" class="ui fluid button">Duplicate Campaign</button>
                    </div>
                </div>
            </div>
            <hr/>
            <div class="ui right floated big buttons">
                <button type="button" class="ui button save_campaign">Save Draft</button>
            </div>
        </div>
    </form>

@endsection

@section('footerscripts')
    <script type="text/javascript" src="{{ url('js/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/daterangepicker.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/notify.min.js') }}"></script>
    <script src="{{ url('emoji-picker-master/lib/js/config.js') }}"></script>
    <script src="{{ url('emoji-picker-master/lib/js/util.js') }}"></script>
    <script src="{{ url('emoji-picker-master/lib/js/jquery.emojiarea.js') }}"></script>
    <script src="{{ url('emoji-picker-master/lib/js/emoji-picker.js') }}"></script>
    <script src="{{ url('tam-emoji/js/config.js') }}"></script>
    <script src="{{ url('tam-emoji/js/tam-emoji.min.js') }}"></script>
    
    <script>
        // add tam-emoji source
        document.emojiSource = '{{ url("tam-emoji/img") }}';

        $('.message .close').on('click', function() {
            $(this).closest('.message').transition('fade');
        });

        $('.menu .item').tab();

        $('i').popup();

        let active_tab = sessionStorage.getItem("Sendmunk_drapdrop_activetab");
        if(active_tab)
        {
            $(active_tab).trigger('click');
            sessionStorage.removeItem("Sendmunk_drapdrop_activetab");
        }

        $('#email-content').summernote({
            toolbar:[
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript',  'subscript']],
                ['para', ['ul', 'ol', 'style', 'height', 'paragraph']],
                ['color', ['color']],
                ['insert', ['picture', 'link', 'video', 'table', 'hr', 'emoji']],
                ['view', ['fullscreen', 'undo', 'redo', 'codeview', 'help']],
                ['personalize', ['personalize']]
            ],
            buttons: {
                personalize: function personalize(context){
                    var ui = $.summernote.ui;
                    var personalizeBtn = ui.buttonGroup([
                        ui.button({
                            className: 'dropdown-toggle',
                            contents: 'Personalize <i class="dropdown icon"></i>',
                            tooltip: "Insert Merge Tags",
                            data: {
                                toggle: 'dropdown'
                            }
                        }),
                        ui.dropdown({
                            className: 'menu summernote-list',
                            contents: '<div class="ui link list"><a id="personalize_1" class="item">Subscriber&rsquo;s name</a><a id="personalize_2" class="item">Subscriber&rsquo;s name (with fallback)</a><a id="personalize_3" class="item">Subscriber&rsquo;s email address</a></div>',
                            callback: function($dropdown) {
                                $dropdown.find('a').each(function() {
                                    $(this).click(function() {
                                        context.invoke('editor.restoreRange');
                                        context.invoke('editor.focus');

                                        var element_id = $(this).attr('id');
                                        if(element_id == "personalize_1")
                                        {
                                            context.invoke('editor.insertText', ' @{{ $subscriber_name }} ');
                                        }
                                        else if(element_id == "personalize_2")
                                        {
                                            context.invoke('editor.pasteHTML', '<p>@'+'if($subscriber_name)<br>  Hello @{{ $subscriber_name }},<br>@' + 'else <br>  Hello,<br>@'+'endif</p>');
                                        }
                                        if(element_id == "personalize_3")
                                        {
                                            context.invoke('editor.insertText', ' @{{ $subscriber_email }} ');
                                        }
                                    });
                                });
                            }
                        })

                        // return personalizeBtn.render();
                    ]);

                    return personalizeBtn.render();
                }
            },
            callbacks: {
                onBlur: function() {
                    $(this).summernote('editor.saveRange');
                }
            },
            height: 200,
            placeholder: "Click the Code View Button (</>) to paste your HTML Code"
        });

        $('#ab_test').change(function(){
            if ($(this).is(':checked')) {
                $('#subject_b_div').show();
            }
            else
            {
                $('#subject_b_div').hide();
            }
        });

        $('#from_reply_email').change(function(){
            let email = $(this).find(":selected").data('email');
            $('#sender_email').text('From: "' + email + '" <' + email +'>');
        });


        $('#next_step_1').on('click', function(){
            $('#content_tab').trigger('click');
        });

        $('#next_step_2').on('click', function(){
            $('#preview_tab').trigger('click');
        });

        // $('#send_later').change(function(){
        //     if ($(this).is(':checked')) {
        //         $('#schedule_link').hide();
        //         $('#later_link').show();
        //         $('#send_campaign').text("Schedule Campaign");
        //     }
        //     else
        //     {
        //         $('#later_link').hide();
        //         $('#schedule_link').show();
        //         $('#send_campaign').text("Send Campaign");
        //     }
        // });

        var min = moment();

        function cb(start, end) {
            $('#datepicker span').html(start.format('MMMM D, YYYY'));
        }

        $('#schedule_date').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            minDate: min,
            "timePicker": true,
            "timePicker24Hour": true,
            "opens": "left",
            locale: {
                format: 'YYYY-MM-DD hh:mm:ss'
            }
        });

        $('#schedule_date').on('apply.daterangepicker', function(ev, picker){
            let date_time = picker.startDate.format('YYYY-MM-DD HH:mm:ss');

            $('#send_label').text('at ' + date_time);
            $('#schedule_date').val(date_time);
            // $('#schedule_date').hide();
            $("input[name='send_later'][value='1']").prop( "checked", true );
            // $('#send_later').trigger('change');
        });

        $('#schedule_date').on('cancel.daterangepicker', function(ev, picker){
            // $('#schedule_date').hide();
        });

        $("input[type='radio'][name='send_later']").change(function(){
            if($(this).val() == '1')
            {
                $('.schedule_items').show();
            }
            else
            {
                $('.schedule_items').hide();
            }
        });

        // $('#schedule_link, #reschedule_link').on('click', function(e){
        //     e.preventDefault();
        //     $('#schedule_date').show();
        //     $('#schedule_date').trigger('click');
        // });

        // $('#clear_schedule').on('click', function(e){
        //     e.preventDefault();
        //     $('#schedule_date').hide();
        //     $('#send_label').text('Immediately');
        //     $( "#send_later" ).prop( "checked", false );
        //     $('#send_later').trigger('change');
        // });

        $('.personalize').on('click', function(e){
            e.preventDefault();
            let itemLabel = $(this).text();

            if(itemLabel == "Contact's email address")
            {
                $(this).closest('.input').find('input').val(function(){
                    return this.value + " @{{ $subscriber_email }}";
                });
            }
            else if(itemLabel == "Contact's name")
            {
                $(this).closest('.input').find('input').val(function(){
                    return this.value + " @{{ $subscriber_name }}";
                });
            }
            //reset dropdown;
            setTimeout(() => {
                $(this).closest('.ui.dropdown').dropdown('restore defaults');
            }, 0);
        });

        $('#preview_tab').on('click', function(){
            let subject_value = $('#subject_a').val();
            if(subject_value)
            {
                $('#preview_subject').text(subject_value);
            }
            else
            {
                $('#preview_subject').text('(No Subject)')
            }

            // $('#preview-container').empty();
            // $('#preview-container').append($('#email-content').summernote('code'));
        });

        $('#rich-text-editor').on('click', function() {
            $('#rich_text_editor_modal').modal('show');
        });

        $(document).ready(function(){
            $('.schedule_items').hide();
            $('#ab_test').trigger('change');
            $('#from_reply_email').trigger('change');
            $('#send_later').trigger('change');
            $("input[type='radio'][name='send_later'][value='" + $("input[type='radio'][name='send_later']").val() + "']").trigger('change');

            let subject_value = $('#subject_a').val();
            if(subject_value)
            {
                $('#preview_subject').text(subject_value);
            }
            else
            {
                $('#preview_subject').text('(No Subject)')
            }
        });

        $('.save_campaign').on('click', function(e){
            e.preventDefault();
            var save_button = $(this);
            save_button.addClass("loading");
            var campaign_form = $('#campaign_form')[0];
            var form_data = new FormData(campaign_form);
            $('.button').prop('disabled', true);
            $.ajax({
                url: '{{ url("savecampaign") }}',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                headers: {'X-CSRF-Token': '{{ csrf_token() }}'},
                type: 'post',
                success: function(response){
                    save_button.removeClass("loading");
                    $.notify('Campaign Saved Successfully', 'success');
                    setTimeout(() => {
                        window.location.href = "{{ url('campaigns') }}";
                    }, 3000);
                },
                error: function(response){
                    save_button.removeClass("loading");

                    $('.button').prop('disabled', false);
                    console.log("error");
                }
            })
        });

        $('#browser_preview').on('click', function(e){
            var x = window.open('', '', 'location=no,toolbar=0');
            x.document.body.innerHTML = $('#email-content').summernote('code');
        });

        $('#mail_preview').on('click', function(e){
            var mail_button = $(this);
            mail_button.addClass("loading");
            var campaign_form = $('#campaign_form')[0];
            var form_data = new FormData(campaign_form);
            $.ajax({
                url: '{{ url("send_sender") }}',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                headers: {'X-CSRF-Token': '{{ csrf_token() }}'},
                type: 'post',
                success: function(response){
                    mail_button.removeClass("loading");
                    $.notify('Campaign sent to '+ $('#from_reply_email').find('option:selected').text() +' Successfully', 'success');
                },
                error: function(response){
                    mail_button.removeClass("loading");
                    console.log("error");
                }
            });
        });

        sessionStorage.removeItem("Sendmunk_drapdrop_redirect");

        $('#dragdrop_link').on('click', function(){
            let current_url = window.location.href;
            sessionStorage.setItem("Sendmunk_drapdrop_redirect", current_url);
        });

        $('#approve_rich_editor').on('click', function(){
            let rich_content = $('#email-content').val();
            if(rich_content != "")
            {
                $.ajax({
                    url: '{{ url("save_rich_content") }}',
                    data: {campaign_id : "{{ $campaign->id }}", content : rich_content},
                    headers: {'X-CSRF-Token': '{{ csrf_token() }}'},
                    type: 'post',
                    success: function(response){
                        window.location.reload();
                        sessionStorage.setItem("Sendmunk_drapdrop_activetab", '#preview_tab');
                    },
                    error: function(response){
                        // console.log('error');
                    }
                });
            }
        });
        $(function() {
        window.emojiPicker = new EmojiPicker({
          emojiable_selector: '[data-emojiable=true]',
          assetsPath: '{{ url("emoji-picker-master/lib/img/") }}',
          popupButtonClasses: 'fa fa-smile-o'
        });

        window.emojiPicker.discover();
      });
    </script>


    @include('includes.user.filterscript')

@endsection
