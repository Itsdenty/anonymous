@extends('layouts.user')
@section('title', 'Sendmunk | RSS to Campaign')

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
        <a id="create_template_icon" href="{{ url('createfeed') }}" class="right floated ui button"><i class="plus icon"></i>New RSS Campaign</a>
        <h2>RSS to Campaign</h2>
    </div>

    <br/>

    <div class="ui segment">
        @if(!$rss_feeds->isEmpty())
        <table class="ui celled stackable table">
            <thead>
                <tr>
                    <th width="55%">Feed URL</th>
                    <th width="15%">Option</th>
                    <th width="15%">No. Of Contacts</th>
                    <th style="text-align: center" width="15%">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rss_feeds as $feed)
                <tr>
                    <td>{{ $feed->url }}</td>
                    <td>{{ $feed->settings == 'single' ? 'Single' : 'Digest' }}</td>
                    <td>{{ $feed->contacts->count() }}</td>
                    <td style="text-align: center">
                        <a href="{{ url('delete/feed').'/'.$feed->id }}" class="delete-feed"><i class="delete icon"></i></a>
                        <a href="{{ url('edit/feed').'/'.$feed->id }}" class="edit-feed"><i class="edit icon"></i></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="ui message"><p>You don't have any RSS Feed(s) yet</p></div>
        @endif
    </div>
@endsection

@section('footerscripts')
    <script type="text/javascript" src="{{ url('js/notify.min.js') }}"></script>
    <script>
        $('.message .close').on('click', function() {
            $(this).closest('.message').transition('fade');
        });

        $('i').popup();
    </script>
@endsection
