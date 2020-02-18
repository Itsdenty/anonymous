@extends('layouts.user')
@section('title', 'Sendmunk | Contents')

@section('styles')
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylsheet" type="text/css" href="{{ url('css/jquery-ui.css') }}"/>
    <link rel="stylesheet" href="{{ asset('emoji-picker-master/lib/css/emoji.css') }}" />
    <link rel="stylesheet" href="{{ asset('tam-emoji/css/emoji.css') }}" rel="stylesheet">  
  
    <style>
        .subject_div:before{
            width: 0px !important;
        }
        .emoji-picker-icon{
            position:relative;
            right: 20px;
            top: 1px;
        }
        .emoji-menu{
            position:absolute;
            right: 50%;
            top: 10%;
        } 
        @media only screen and (min-width:1200px){
            .emoji-menu{
            position:absolute;
            right: 10%;
            top: 100%;
        } 
        }
        .emoji-wysiwyg-editor{
            width:75%;
        }
        .emoji-wysiwyg-editor:focus{
            outline: none;
        }
        .emoji-wysiwyg-editor::-webkit-scrollbar{
            display: none;
        } 
        #shift{
            margin-left: -15px;
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
        <a id="create_content_icon" href="{{ url('sequence/'.$sequence->id.'/createcontent') }}" class="right floated ui button"><i class="plus icon"></i>Add Email</a>
        <h2>{{ $sequence->title }}</h2>
    </div>

    <br/>
    <div class="ui secondary menu" style="margin: 0">
        <div class="item">
          <b class="sortSaving" style="display: none;"><i class="icon spinner"></i>Saving ...</b>
          <b class="sortSaved" style="color: #4183c4; display: none;"><i class="icon check circle"></i>Saved</b>
        </div>
    </div>

    <div class="ui segment">
        @if(!$sequence->sequenceEmails->isEmpty())
        <div id="contentlist" class="ui-sortable">
            @foreach($sequence->sequenceEmails->sortBy('sort_id') as $email)
            <div class="ui menu ui-sortable-handle" id="{{ $email->id }}">
                <a class="item" data-content="Drag to sort email">
                    <b><i class="move icon"></i></b>
                </a>
                <div class="item subject_div" style="width: 30%">
                    <a data-content="Drag to sort email" class="edit_content" href="{{ url('sequence/'.$sequence->id.'/editcontent'. '/' . $email->id) }}">
                        <b>
                            <i class="icon star" style="color: #555"></i>
                            <span style="color: #000" data-step="1">{{ $email->subject }}</span>
                        </b>
                    </a>
                </div>
                <div class="right menu">
                    <div class="item">
                        <div style="text-align: center">
                            <strong>{{ $email->contacts()->wherePivot('opened', true)->count() == 0 || $email->contacts()->wherePivot('sent', true)->count() == 0 ? '0' :  number_format((float)$email->contacts()->wherePivot('opened', true)->count() / $email->contacts()->wherePivot('sent', true)->count() * 100, 2, '.', '')}}%</strong>
                            <br/>
                            <small>OPEN RATE</small>
                        </div>
                    </div>
                    <div class="item">
                        <div style="text-align: center">
                            <strong>{{ $email->links->map(function($link){return $link->contacts;})->flatten()->unique('id')->count() == 0 || $email->contacts()->wherePivot('sent', true)->count() == 0 ? '0' :  number_format((float)$email->links->map(function($link){return $link->contacts;})->flatten()->unique('id')->count() / $email->contacts()->wherePivot('sent', true)->count() * 100, 2, '.', '')}}%</strong>
                            <br/>
                            <small>CLICK RATE</small>
                        </div>
                    </div>
                    <div class="item">
                        <div style="text-align: center">
                            <strong>{{ $email->contacts()->wherePivot('sent', true)->count() }}</strong>
                            <br/>
                            <small>SENDS</small>
                        </div>
                    </div>
                    <div class="item">
                        <div style="text-align: center">
                            <strong>{{ $email->contacts()->wherePivot('unsubscribed', true)->count() }}</strong>
                            <br/>
                            <small>UNSUBSCRIBERS</small>
                        </div>
                    </div>

                    <div class="item">
                        <div class="ui toggle checkbox">
                            <input data-content="Email Status" data-email="{{ $email->id }}" class="email_status" type="checkbox" {{ $email->status == 'active' ? 'checked' : '' }} />
                            <label></label>
                        </div>
                    </div>
                    <a data-content="Duplicate Email" class="item" href="{{ url('duplicate/emailcontent'. '/'. $email->id) }}"><i class="copy icon"></i></a>
                    <a data-content="Email Report" class="item" href="{{ url('view_email_analysis').'/'.$email->id }}"><i class="assistive listening systems icon"></i></a>
                    <a data-content="Delete Email" class="item" href="{{ url('delete/emailcontent'. '/'. $email->id) }}"><i class="delete icon"></i></a>
                </div>
            </div>
            @endforeach
        </div> 
        @else
        <div class="ui message"><p>You don't have any Email(s) in this sequence yet</p></div>
        @endif
    </div>

    <div id="create_email_modal" class="ui small modal">
        <i class="close icon"></i>
        <div class="header">
            Create Email
        </div>
        <div class="scrolling content">
            <form class="ui form" id="create_email_form" action="{{ url('createsequencecontent') }}" method="POST" role="form">
                {{ csrf_field() }}
                <input type="hidden" name="sequence_id" value="{{ $sequence->id }}" />
                <div class="field">
                    <label>Email Subject</label>
                    <div class="ui labeled input">
                        <input data-emojiable="true" id="subject" type="text" placeholder="Enter Subject" name="subject" />
                        <div id="shift" class="ui dropdown label">
                            <div class="text">Personalize</div>
                            <i class="dropdown icon"></i>
                            <div class="menu">
                                <div class="personalize item">Contact's name</div>
                                <div class="personalize item">Contact's email address</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="field">
                    <label>Send</label>
                    <select id="send_time" name="send_time" class="ui dropdown" required>
                        <option value="">Select when to send</option>
                        <option value="now">Immediately</option>
                        <option value="delay">Delay</option>
                    </select>
                </div>
                <div id="delay_inputs" class="fields">
                    <div class="field">
                        <br/>
                        <label>After Last Mail</label>
                    </div>
                    <div class="field">
                        <div class="ui action input">
                            <input id="time_value" type="number" placeholder="After Last Email" min="0" name="time_value" />
                            <select class="ui dropdown" name="time_unit">
                                <option value="minute">Minutes</option>
                                <option value="hour">Hours</option>
                                <option value="day">Days</option>
                            </select>
                        </div>
                    </div>
                </div>   
                <div class="ui segment">
                    <div class="field">
                        <label>Email Content</label>
                        <textarea  name="email_content" id="create-email-content"></textarea>
                    </div>
                </div>
            </form>
        </div>
        <div class="actions">
            <button type="button" class="ui large cancel button">Cancel</button>
            <button type="submit" form="create_email_form" class="ui large primary button">Create</button>
        </div>
    </div>

    <div id="edit_email_modal" class="ui small modal">
        <i class="close icon"></i>
        <div class="header">
            Edit Email
        </div>
        <div class="scrolling content">
            <form class="ui form" id="edit_email_form" action="{{ url('createsequencecontent') }}" method="POST" role="form">
                {{ csrf_field() }}
                <input type="hidden" name="sequence_id" value="{{ $sequence->id }}" />
                <input id="edit_content_id" type="hidden" name="content_id" />
                <div class="field">
                    <label>Email Subject</label>
                    <div class="ui right labeled input">
                        <input data-emojiable="true"  id="edit_subject" type="text" placeholder="Enter Subject" name="subject" />
                        <div id="shift" class="ui dropdown label" >
                            <div class="text">Personalize</div>
                            <i class="dropdown icon"></i>
                            <div class="menu">
                                <div class="personalize item">Contact's name</div>
                                <div class="personalize item">Contact's email address</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="field">
                    <label>Send</label>
                    <select id="edit_send_time" name="send_time" class="ui dropdown" required>
                        <option value="">Select when to send</option>
                        <option value="now">Immediately</option>
                        <option value="delay">Delay</option>
                    </select>
                </div>
                <div id="edit_delay_inputs" class="fields">
                    <div class="field">
                        <br/>
                        <label>After Last Mail</label>
                    </div>
                    <div class="field">
                        <div class="ui action input">
                            <input id="edit_time_value" type="number" placeholder="After Last Email" min="0" name="time_value" />
                            <select id="edit_time_unit" class="ui dropdown" name="time_unit">
                                <option value="minute">Minutes</option>
                                <option value="hour">Hours</option>
                                <option value="day">Days</option>
                            </select>
                        </div>
                    </div>
                </div>   
                <div class="ui segment">
                    <div class="field">
                        <label>Email Content</label>
                        <textarea  name="email_content" id="edit-email-content"></textarea>
                    </div>
                </div>
            </form>
        </div>
        <div class="actions">
            <button type="button" class="ui large cancel button">Cancel</button>
            <button type="submit" form="edit_email_form" class="ui large primary button">Update</button>
        </div>
    </div>
@endsection

@section('footerscripts')
    <script src="{{ url('emoji-picker-master/lib/js/config.js') }}"></script>
    <script src="{{ url('emoji-picker-master/lib/js/util.js') }}"></script>
    <script src="{{ url('emoji-picker-master/lib/js/jquery.emojiarea.js') }}"></script>
    <script src="{{ url('emoji-picker-master/lib/js/emoji-picker.js') }}"></script>
    <script src="{{ url('tam-emoji/js/config.js') }}"></script>
    <script src="{{ url('tam-emoji/js/tam-emoji.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/notify.min.js') }}"></script>    
    <script type="text/javascript" src="{{ url('js/sortable/jquery-1.4.4.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/sortable/jquery.ui.core.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/sortable/jquery.ui.widget.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/sortable/jquery.ui.mouse.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/sortable/jquery.ui.sortable.js') }}"></script>
    <script>
        $j = jQuery.noConflict(true);
        // add tam-emoji source
        document.emojiSource = "{{ url('tam-emoji/img') }}";

        $('.message .close').on('click', function() {
            $(this).closest('.message').transition('fade');
        });

        $('a, i, .email_status').popup();

        (function($){
            $( "#contentlist" ).sortable({
                update: function( event, ui ) {
                    $(".sortSaving").show();
                    $(".sortSaved").hide();
                    let newOrder = $( "#contentlist" ).sortable("toArray");
                    $.ajax({
                        url: "{{ url('sequence/'.$sequence->id.'/sortemail') }}",
                        type:"post",
                        data: {order : newOrder,  _token: '{{csrf_token()}}'},
                        success: function(response){
                            $(".sortSaving").hide(); 
                            $(".sortSaved").show();
                            setTimeout(()=>{
                            $(".sortSaved").hide();
                            window.location.reload();
                            },2000);
                        },
                        error: function(response){

                        }
                    });
                }
            });

        })($j);

        $('#create-email-content, #edit-email-content').summernote({
            toolbar:[
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript',  'subscript']],
                ['para', ['ul', 'ol', 'style', 'height', 'paragraph']],
                ['color', ['color']],
                ['insert', ['picture', 'link', 'video', 'table', 'hr', 'emoji']],
                ['view', ['fullscreen', 'undo', 'redo', 'codeview', 'help']],
                ['personalize', ['personalize']],
                ['tool', ['undo', 'redo', 'codeview']]
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

        $('#send_time').on('change', function(){
            let sent_time = $(this).find(":selected").val();
            if(sent_time == 'now')
            {
                $('#delay_inputs').hide();
                $('#time_value').val('0');
            }
            else if(sent_time == 'delay')
            {
                $('#delay_inputs').show();
            }
            else
            {
                $('#delay_inputs').hide();
            }
        });

        $('#edit_send_time').on('change', function(){
            let sent_time = $(this).find(":selected").val();
            if(sent_time == 'now')
            {
                $('#edit_delay_inputs').hide();
                $('#edit_time_value').val('0');
            }
            else if(sent_time == 'delay')
            {
                $('#edit_delay_inputs').show();
            }
            else
            {
                $('#edit_delay_inputs').hide();
            }
        });

        $('#create_content_icon').on('click', function(e){
            e.preventDefault();
            $('#create_email_form').trigger('reset');
            $('#send_time').dropdown('restore defaults');
            $('#send_time').trigger('change');
            $('#create-email-content').summernote('code','');
            $('#create_email_modal').modal('show');
        });

        $('.edit_content').on('click', function(e){
            e.preventDefault();
            let url = $(this).attr('href');
            $.ajax({
                url: url,
                method: 'get',
                success: function(response){
                    let email = JSON.parse(response.email);
                    $('#edit_email_form').trigger('reset');
                    $('#edit_subject').val(email['subject']);
                    $('#edit_send_time').val(email['send_time']).trigger('change');
                    $('#edit_time_unit').val(email['time_unit']).trigger('change');
                    $('#edit_time_value').val(email['time_value']);
                    $('#edit_content_id').val(email['id']);
                    $('#edit-email-content').summernote('code', email['content']);
                    $('#edit_email_modal').modal('show');
                },
                error: function(response){
                    $('#edit_email_modal').modal('hide');
                    $.notify('An error occurred while trying to edit email', 'error');
                }
            });
        })

        $('.email_status').on('change', function(){
            let status = $(this).is(':checked');
            let checkbox = $(this);
            let email_id = $(this).data('email');
            if(status)
            {
                $.ajax({
                    url: "{{ url('activatesequenceemail') }}" + '/' + email_id,
                    method: 'get',
                    success: function(){
                        $.notify('Email Activated', 'success');
                    },
                    error: function(){
                        checkbox.prop('checked', false);
                        $.notify('An error occurred while activating sequence email', 'error');
                    }
                });
            }
            else
            {
                $.ajax({
                    url: "{{ url('deactivatesequenceemail') }}" + '/' + email_id,
                    method: 'get',
                    success: function(){
                        $.notify('Email Dectivated', 'success');
                    },
                    error: function(){
                        checkbox.prop('checked', true);
                        $.notify('An error occurred while deactivating sequence email', 'error');
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
      $('div.personalize').hide();
      $('div#shift').click(function(){
          $('div.personalize').show();
      });

      </script>
@endsection
