@extends('layouts.user')
@section('title', 'Sendmunk | Create Sequence')

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
        <h2>Create Sequence</h2>
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
            <button class="ui primary huge button">Create</button>
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
    </script>

    @include('includes.user.sequencefilterscript')
@endsection
