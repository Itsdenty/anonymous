@extends('layouts.user')
@section('title', 'Sendmunk | Create SMS/MMS Campaign')

@section('styles')
    <link rel="stylesheet" href="{{ asset('emoji-picker-master/lib/css/emoji.css') }}" />
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
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
        <h2>Create SMS/MMS Campaign</h2>
    </div>
    <br/>
    <div class="ui segment">
        <form class="ui form" id="sms_campaign_form" action="{{ url('updatesmscampaign') }}" method="POST" role="form" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type="hidden" name="sms_campaign_id" value="{{ $sms_campaign->id }}" />
            <div class="field">
                <label>Campaign Name</label>
                <input type="text" name="title" value="{{ $sms_campaign->title }}" required />
            </div>
            <div class="field">
                <label>Choose Sending SMS/MMS Integration</label>
                <select class="ui dropdown" name="integration" required>
                    @foreach($sms_mms_integrations as $integration)
                    <option value="{{ $integration->id }}">{{ $integration->twilio_number }} (Twilio Number)</option>
                    @endforeach
                </select>
            </div>
            <div class="ui segment">
                @include('includes.user.smsfilter')
            </div>
            <div class="field">
                <label>Message Body</label>
                <p class="emoji-picker-container">
                    <textarea data-emojiable="true" data-emoji-input="unicode" name="content" placeholder="Enter Message Body" rows="5"></textarea>
                </p>
            </div>
            <div>
                <p style="text-align: right">Number of Characters: <span id="num_of_characters">0</span></p>
            </div>
            <div class="field">
                    <label>MMS Image</label>
                    <input type="file" name="mms_pic" accept="image/*" />
                </div>
                @if($sms_campaign->image_url)
                <div id="picture_div" class="ui vertically stackable grid">
                    <div class="three column row">
                        <div class="column"></div>
                        <div class="column">
                            <div class="ui small image">
                                <img style="width:100%" id="profilePic" src="{{ url($sms_campaign->image_url) }}" alt="MMS Image" />
                            </div>
                            <a id="remove_mms_image" href="{{ url('delete/mms_image') . '/' . $sms_campaign->id }}" class="ui button">Remove</a>
                        </div>
                        <div class="column"></div>
                    </div>
                </div>
                @endif
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
            <hr/>
            <button type="button" class="ui huge button save_campaign">Save as Draft</button>
            <button type="submit" class="ui primary huge button">Confirm</button>
        </form>
    </div>

@endsection

@section('footerscripts')
    <script type="text/javascript" src="{{ url('js/notify.min.js') }}"></script>
    <script src="{{ url('emoji-picker-master/lib/js/config.js') }}"></script>
    <script src="{{ url('emoji-picker-master/lib/js/util.js') }}"></script>
    <script src="{{ url('emoji-picker-master/lib/js/jquery.emojiarea.js') }}"></script>
    <script src="{{ url('emoji-picker-master/lib/js/emoji-picker.js') }}"></script>
    <script>
        $('#send_date_div').hide();

        $('.message .close').on('click', function() {
            $(this).closest('.message').transition('fade');
        });

        $('.menu .item').tab();

        $('i').popup();

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
            $('input[type="datetime-local"]').setNow();

            $('input[name="sending"]').on('change', function(){
                let sending_value = $(this).val();
                if(sending_value == 'later')
                {
                    $('input[type="datetime-local"]').setNow();
                    $('#send_date_div').show();
                }
                else
                {
                    $('#send_date_div').hide();
                }
            });

            window.emojiPicker = new EmojiPicker({
                emojiable_selector: '[data-emojiable=true]',
                assetsPath: '{{ url("emoji-picker-master/lib/img/") }}',
                popupButtonClasses: 'fa fa-smile-o'
            });

            window.emojiPicker.discover();

            $('.emoji-wysiwyg-editor').bind('change paste keyup', function(){
                let content_length = $(this).text().length;
                $('#num_of_characters').text(content_length);
            });
        });

        $('.save_campaign').on('click', function(e){
            e.preventDefault();
            var save_button = $(this);
            save_button.addClass("loading");
            var campaign_form = $('#sms_campaign_form')[0];
            var form_data = new FormData(campaign_form);
            $('.button').prop('disabled', true);
            $.ajax({
                url: '{{ url("savesmscampaign") }}',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                headers: {'X-CSRF-Token': '{{ csrf_token() }}'},
                type: 'post',
                success: function(response){
                    save_button.removeClass("loading");
                    $.notify('SMS/MMS Campaign Saved Successfully', 'success');
                    setTimeout(() => {
                        window.location.href = "{{ url('sms_campaigns') }}";
                    }, 3000);
                },
                error: function(response){
                    save_button.removeClass("loading");

                    $('.button').prop('disabled', false);
                    console.log("error");
                }
            })
        });

        $('#remove_mms_image').on('click', function(e){
            e.preventDefault();
            let button = $(this);
            button.addClass('loading');
            let url = $(this).attr('href');
            $.ajax({
                url: url,
                method: 'get',
                success: function(){
                    button.removeClass('loading');
                    $('#picture_div').remove();
                },
                error: function(){
                    button.removeClass('loading');
                }
            });
        });
    </script>

    @include('includes.user.smsfilterscript')
@endsection
