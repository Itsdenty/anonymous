@extends('layouts.user')
@section('title', 'Sendmunk | Edit Sequence')

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
        <h2>Edit Sequence</h2>
    </div>
    <br/>
    <div class="ui segment">
        <h3>Settings</h3>
        <form class="ui form" id="campaign_form" action="{{ url('createsequence') }}" method="POST" role="form">
            {{ csrf_field() }}
            <input type="hidden" name="sequence_id" value="{{ $sequence->id }}" />
            <div class="field">
                <label>Sequence Name</label>
                <input type="text" name="title" value="{{ $sequence->title }}" required />
            </div>
            <div class="field">
                <label>Who will this sequence be from</label>
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
                @include('includes.user.sequencefilter')
            </div>
            <button class="ui primary huge button">Update</button>
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

        $(document).ready(function(){
            let from_reply_id = "{{ $sequence->from_reply_id }}";
            let smtp_id = "{{ $sequence->smtp_id }}";
            let api_id = "{{ $sequence->mail_api_id }}";

            if(from_reply_id)
            {
                $("#from_reply_email").val('{{ $sequence->from_reply_id }}').change();
            }
            if(smtp_id || api_id)
            {
                $("#integration").val('{{ $sequence->smtp_id ? "smtp_".$sequence->smtp_id : ($sequence->mail_api_id ? "api_".$sequence->mail_api_id : "") }}').change();
            }
        });
    </script>

    @include('includes.user.sequencefilterscript')
@endsection
