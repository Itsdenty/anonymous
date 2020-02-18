@extends('layouts.user')
@section('title', 'Sendmunk | Edit RSS Campaign')

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
        <h2>Edit RSS Campaign</h2>
    </div>
    <br/>
    <div class="ui segment">
        <form class="ui form" id="campaign_form" action="{{ url('createfeed') }}" method="POST" role="form">
            {{ csrf_field() }}
            <input type="hidden" name="feed_id" value="{{ $rss_feed->id }}" />
            <div class="field">
                <label>Feed URL</label>
                <input type="text" name="url" placeholder="Enter Feed URL" value="{{ $rss_feed->url }}" required />
            </div>
            <div class="field">
                <label>Who will this Campaign be from?</label>
                <select name="from_reply_id" id="from_reply_email" class="ui dropdown" required>
                    @foreach($from_reply_emails as $email)
                    <option data-email="{{ $email->email }}" value="{{ $email->id }}">{{ $email->email }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field">
                <label>Choose Sending Integration</label>
                <select class="ui dropdown" name="integration" required>
                    @foreach($smtps as $smtp)
                    <option value="smtp_{{ $smtp->id }}">{{ $smtp->title }} (SMTP)</option>
                    @endforeach

                    @foreach($mail_apis as $api)
                    <option value="api_{{ $api->id }}">{{ $api->title }} (Mail API)</option>
                    @endforeach
                </select>
            </div>
            <div class="ui segment">
                @include('includes.user.rssfilter')
            </div>
            <div class="field">
                <label>Feed Option</label>
                <select id="settings" class="ui dropdown" name="settings" required>
                    <option value="single">Single (A campaign would be sent each time a new article is detected in the feed)</option>
                    <option value="digest">Digest (A Daily or Weekly campaign would be sent with recent articles)</option>
                </select>
            </div>
            <div class="field digest_extra_options">
                <label>Digest Option</label>
                <select id="digest_option" class="ui dropdown" name="digest_option" required>
                    <option value="daily">Daily</option>
                    <option value="weekly">Weekly</option>
                </select>
            </div>
            <div class="field day_week">
                <label>Day of the Week</label>
                <select id="day" class="ui dropdown" name="day" required>
                    <option value="monday">Monday</option>
                    <option value="tuesday">Tuesday</option>
                    <option value="wednesday">Wednesday</option>
                    <option value="thursday">Thursday</option>
                    <option value="friday">Friday</option>
                    <option value="saturday">Saturday</option>
                    <option value="sunday">Sunday</option>
                </select>
            </div>
            <br/>
            <button type="submit" class="ui primary huge button">Save & Start Campaign</button>
        </form>
    </div>

@endsection

@section('footerscripts')
    <script>
        $('.message .close').on('click', function() {
            $(this).closest('.message').transition('fade');
        });

        $('.menu .item').tab();

        $('i').popup();

        $('.digest_extra_options').hide();
        $('.day_week').hide();

        $('#settings').on('change', function(){
            let current_option = $(this).val();
            if(current_option == 'digest')
            {
                $('.digest_extra_options').show();
                $('#digest_option').trigger('change');
            }
            else
            {
                $('.digest_extra_options').hide();
                $('.day_week').hide();
            }
        });

        $('#digest_option').on('change', function(){
            let current_option = $(this).val();
            if(current_option == 'weekly')
            {
                $('.day_week').show();
            }
            else
            {
                $('.day_week').hide();
            }
        });

        $(document).ready(function(){
            let from_reply_id = "{{ $rss_feed->from_reply_id }}";
            let smtp_id = "{{ $rss_feed->smtp_id }}";
            let api_id = "{{ $rss_feed->mail_api_id }}";
            let settings = "{{ $rss_feed->settings }}";
            let digest_option = "{{ $rss_feed->digest_option }}";
            let day = "{{ $rss_feed->day }}";

            if(from_reply_id)
            {
                $("#from_reply_email").val('{{ $rss_feed->from_reply_id }}').change();
            }
            if(smtp_id || api_id)
            {
                $("#integration").val('{{ $rss_feed->smtp_id ? "smtp_".$rss_feed->smtp_id : ($rss_feed->mail_api_id ? "api_".$rss_feed->mail_api_id : "") }}').change();
            }
            if(settings)
            {
                $("#settings").val('{{ $rss_feed->settings }}').change();
            }
            if(digest_option)
            {
                $("#digest_option").val('{{ $rss_feed->digest_option }}').change();
            }
            if(day)
            {
                $("#day").val('{{ $rss_feed->day }}').change();
            }
        });
    </script>

    @include('includes.user.rssfilterscript')
@endsection
