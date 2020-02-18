@extends('layouts.user')
@section('title', 'Sendmunk | Campaigns')

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
        <a id="create_campaign_icon" href="{{ url('createcampaign') }}" class="right floated ui button"><i class="plus icon"></i>New Campaign</a>
        <h2>Campaigns</h2>
    </div>
    <br/>
    <div class="ui vertically stackable grid">
        <div class="four column row">
            <div class="summary column">
                <h3><i class="users icon"></i></h3>
                <h2>{{ $num_of_contacts }}</h2>
                <h3>No. of Contacts</h3>
            </div>
            <div class="summary column">
                <h3><i class="eye icon"></i></h3>
                <h2>{{ $num_of_opens }}</h2>
                <h3>No. of Openers</h3>
            </div>
            <div class="summary column">
                <h3><i class="hand pointer outline icon"></i></h3>
                <h2>{{ $num_of_clicks }}</h2>
                <h3>No. of Clickers</h3>
            </div>
            <div class="summary column">
                <h3><i class="ban icon"></i></h3>
                <h2>{{ $num_of_unsubscribers }}</h2>
                <h3>No. of Unsubscribers</h3>
            </div>
        </div>
    </div>
    <br/>

    <div class="ui segment">
        @if(!$campaigns->isEmpty())
        <table class="ui table" style="background:#fff;">
            <thead>
                <tr>
                    <th width="30%">TITLE</th>
                    <th style="text-align: center">OPENS</th>
                    <th style="text-align: center">CLICKS</th>
                    <th style="text-align: center">UNSUB.</th>
                    <th style="text-align: center">SENT</th>
                    <th style="text-align: center">STATUS</th>
                    <th style="text-align: center">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($campaigns as $campaign)
                <tr>
                    <td><a href="{{ ($campaign->status == 'draft' || $campaign->status == 'later') ? url('edit/'.$campaign->id.'/campaign') : url('view_analysis').'/'.$campaign->id}}"><h4>{{ $campaign->title }}</h4></a>{{ $campaign->status != 'draft' ? 'Sent Date: '.(new DateTime($campaign->send_date))->format('M j, Y H:i:s') : 'Created: '.(new DateTime($campaign->created_at))->format('M j, Y H:i:s') }}</td>
                    <td style="text-align: center">{{ $campaign->contacts()->wherePivot('opened', true)->count() }} / {{ $campaign->contacts()->wherePivot('opened', true)->count() == 0 || $campaign->contacts()->wherePivot('sent', true)->count() == 0 ? '0' :  number_format((float)$campaign->contacts()->wherePivot('opened', true)->count() / $campaign->contacts()->wherePivot('sent', true)->count() * 100, 2, '.', '')}}%</td>
                    <td style="text-align: center">{{ $campaign->links->map(function($link){return $link->contacts;})->flatten()->unique('id')->count() }} / {{ $campaign->links->map(function($link){return $link->contacts;})->flatten()->unique('id')->count() == 0 || $campaign->contacts()->wherePivot('sent', true)->count() == 0 ? '0' :  number_format((float)$campaign->links->map(function($link){return $link->contacts;})->flatten()->unique('id')->count() / $campaign->contacts()->wherePivot('sent', true)->count() * 100, 2, '.', '')}}%</td>
                    <td style="text-align: center">{{ $campaign->contacts()->wherePivot('unsubscribed', true)->count() }} / {{ $campaign->contacts()->wherePivot('unsubscribed', true)->count() == 0 || $campaign->contacts()->wherePivot('sent', true)->count() == 0 ? '0' :  number_format((float)$campaign->contacts()->wherePivot('unsubscribed', true)->count() / $campaign->contacts()->wherePivot('sent', true)->count() * 100, 2, '.', '')}}%</td>
                    <td style="text-align: center">{{ $campaign->contacts()->wherePivot('sent', true)->count() }}/{{ $campaign->contacts->count() }}</td>
                    <td style="text-align: center">{{ ucfirst($campaign->status) }}</td>
                    <td style="text-align: center">
                        <a href="{{ url('view_analysis').'/'.$campaign->id }}"><i data-content="Campaign Report" class="assistive listening systems icon"></i></a>
                        <a href="{{ url('duplicate/'.$campaign->id.'/campaign') }}"><i data-content="Duplicate Campaign" class="copy icon"></i></a>
                        @if($campaign->status == 'draft' || $campaign->status == 'later')
                        <a href="{{ url('edit/'.$campaign->id.'/campaign') }}"><i data-content="Edit Campaign" class="edit icon"></i></a>
                        @endif
                        @if($campaign->status != 'progress')
                        <a href="{{ url('delete/'.$campaign->id.'/campaign') }}"><i data-content="Delete Campaign" class="delete icon"></i></a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $campaigns->links('includes.pagination') }}
        @else
        <div class="ui message"><p>You don't have any Campaign(s) yet</p></div>
        @endif
    </div>
@endsection

@section('footerscripts')
    <script>
        $('.message .close').on('click', function() {
            $(this).closest('.message').transition('fade');
        });

        $('i').popup();
    </script>
@endsection
