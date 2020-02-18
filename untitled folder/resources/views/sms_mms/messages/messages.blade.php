@extends('layouts.user')
@section('title', 'Sendmunk | SMS/MMS Messages')

@section('styles')
    <link rel="stylesheet" type="text/css" href='{{ url("css/sms-mms.css") }}'/>
    <link rel="stylesheet" href="{{ asset('emoji-picker-master/lib/css/emoji.css') }}" />
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
    <style>
            /* .emoji-picker-icon{
            position:relative;
            right: ;
            bottom: -100%;
        } */
        .emoji-menu{
            position:absolute;
            bottom:100%;
        }
        .emoji-wysiwyg-editor{
            width:96%;
            padding-right: 40px;
            border: none;
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
    <div class="window">
        <aside class="conv-list-view">
            <header class="conv-list-view__header">
                <label>&nbsp;Select SMS gateway</label>
                <select id="integration_select" class="ui dropdown">
                    @foreach($integrations as $integration)
                    <option value="{{ $integration->id }}" {{ $integration->current_settings ? "selected" : "" }}>{{ $integration->twilio_number }}</option>
                    @endforeach
                </select>
            </header>
            <ul class="conv-list">
                @foreach($contacts as $contact)
                <li data-id="{{ $contact->id }}" data-name="{{ $contact->name }}" data-phone="{{ $contact->phone }}" class="contacts">
                    <div class="status">
                    {!! $contact->contactSmsMessages->where('unread', true)->first() ? '<i class="status__indicator--unread-message"></i>' : ''!!}
                    <div class="meta">
                        <div class="meta__name">{{ $contact->name }}</div>
                        <div class="meta__sub--dark">{{ $contact->phone }}</div>
                    </div>
                    </div>
                </li>
                @endforeach
            </ul>
        </aside>
        <section class="chat-view">
            <header class="chat-view__header">
                <div class="cf">
                    <div class="status">
                        <div class="meta">
                            <div id="header_name" class="meta__name"> </div>
                            <div id="header_phone" class="meta__sub--light"> </div>
                        </div>
                    </div>
                </div>
            </header>
            <section class="message-view">
                
            </section>
            <footer class="chat-view__input">
                {{-- <div class="input"><input /><span class="input__emoticon"></span></div> --}}
                <div class="input"><input id="input_content" data-emojiable="true"/></div>
                <div class="status">
                    <button id="send_button">Send</button>
                </div>
            </footer>
        </section>
    </div>
@endsection

@section('footerscripts')
    <script src="{{ url('emoji-picker-master/lib/js/config.js') }}"></script>
    <script src="{{ url('emoji-picker-master/lib/js/util.js') }}"></script>
    <script src="{{ url('emoji-picker-master/lib/js/jquery.emojiarea.js') }}"></script>
    <script src="{{ url('emoji-picker-master/lib/js/emoji-picker.js') }}"></script>
    <script>
        $('.ui.dropdown').dropdown();

        $('.contacts').on('click', function(){
            $('.contacts').removeClass('selected');
            $(this).addClass('selected');
            $(this).find('.status__indicator--unread-message').remove();
            generateMessages($(this).data('id'), $(this).data('name'), $(this).data('phone'));
        });

        function generateMessages(contact_id, contact_name, contact_phone){
            $('#header_name').text(contact_name);
            $('#header_phone').text(contact_phone);

            $('.message-view').empty();
            $.ajax({
                url: '{{ url("get_sms_messages")}}' + "/" + contact_id,
                method: 'get',
                success: function(response){
                    $.each(response, function(index, value){
                        let new_content = "";
                        if(value["received"] == 0)
                        {
                            new_content += '<div class="message--send">';
                            new_content += '<div class="message__bubble--send">';
                            new_content += value["content"];
                            new_content += '</div>';
                            new_content += '</div>';
                        }
                        else
                        {
                            new_content += '<div class="message">';
                            new_content += '<div class="message__bubble">';
                            new_content += value["content"];
                            new_content += '</div>';
                            new_content += '</div>';
                        }
                        new_content += '<div class="cf"></div';
                        $('.message-view').append(new_content);
                    });
                },
                error: function(){
                    console.log("an error occurred");
                }
            });
        }

        $(document).ready(function(){
            $(".contacts").first().click();
        });

        $('#integration_select').on('change', function() {
            var current_integration_id = $(this).val();
            $.ajax({
                url: "{{ url('set_active_integration') }}",
                method: "POST",
                data: {"current_integration_id":current_integration_id, _token: '{{ csrf_token() }}'},
                success: function (data){
                    location.reload(true);
                }
            });
        });

        $('#send_button').on('click', function(e){
            e.preventDefault();
            let message = $('#input_content').val();
            if(message != "")
            {
                let contact_id = $('.contacts.selected').first().data("id");
                if(contact_id)
                {
                    $.ajax({
                        url: "{{ url('send_contact_message') }}",
                        method: "POST",
                        data: {"contact_id":contact_id, 'message': message, _token: '{{ csrf_token() }}'},
                        success: function (data){
                            let new_content="";
                            new_content += '<div class="message--send">';
                            new_content += '<div class="message__bubble--send">';
                            new_content += message;
                            new_content += '</div>';
                            new_content += '</div>';
                            new_content += '<div class="cf"></div';

                            $('#input_content').val("");
                            $('.message-view').append(new_content);
                        }
                    });
                }
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
@endsection
