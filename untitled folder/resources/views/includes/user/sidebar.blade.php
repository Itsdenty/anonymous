<div id="sidebar" class="ui left vertical menu visible sidebar">
    <div class="logo-header header">
        <a class="item" href="{{ url('dashboard')}}">
            <img alt="SendMunk Logo" src="{{ asset('SM_Avi.png') }}">
        </a>
    </div>
    <div class="item">
        <div class="header">Switch subaccount</div>
        <div class="menu">
            <div class="ui form field item">
                <select class="ui fluid dropdown" id="subaccount-select">
                    @foreach($user->subAccounts as $account)
                    <option value="{{ $account->id }}" {{ $account->id==$user->current_sub_account_id ? 'selected' : '' }}>{{ $account->account_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="ui accordion">
        <a class="item {{ Request::is('dashboard') ? 'active' : '' }}" href="{{ url('dashboard')}}">
            <i class="tachometer alternate icon"></i> Dashboard
        </a>
        <a class="item {{ Request::is('campaigns') ? 'active' : '' }}" href="{{ url('campaigns')}}">
            <i class="bullhorn icon"></i> Campaigns
        </a>
        <a class="item {{ Request::is('sequences') ? 'active' : '' }}" href="{{ url('sequences')}}">
            <i class="sort amount down icon"></i> Sequences
        </a>
        <div class="title item">
            <div class="header"><i class="dropdown icon"></i> SMS/MMS</div>
        </div>
        <div class="content">
            <a class="setting-item item {{ Request::is('sms_messages') ? 'active' : '' }}" href="{{ url('sms_messages') }}">
                <i class="envelope icon"></i> Messages
            </a>
            <a class="setting-item item {{ Request::is('bot') ? 'active' : '' }}" href="{{ url('bot') }}">
                <i class="smile icon"></i> Bot
            </a>
            <a class="setting-item item {{ Request::is('sms_campaigns') || Request::is('create/*/sms_campaign') || Request::is('edit/*/sms_campaign') ? 'active' : '' }}" href="{{ url('sms_campaigns') }}">
                <i class="bullhorn icon"></i> Campaigns
            </a>
        </div>
        @if(!$main_user->member || $main_user->member->role == 1)
        <div class="title item">
            <div class="header"><i class="dropdown icon"></i> Forms</div>
        </div>
        <div class="content">
            <a class="setting-item item {{ Request::is('forms') || Request::is('createform/*') || Request::is('editform/*') ? 'active' : '' }}" href="{{ url('forms')}}">
                <i class="text file icon"></i> Basic Forms
            </a>
            <a class="setting-item item {{ Request::is('polls') || Request::is('createpoll/*') || Request::is('editpoll/*') ? 'active' : '' }}" href="{{ url('polls')}}">
                <i class="paper plane icon"></i> Polls
            </a>
            <a class="setting-item item {{ Request::is('quizzes') || Request::is('createquiz/*') || Request::is('editquiz/*') ? 'active' : '' }}" href="{{ url('quizzes') }}">
                <i class="chart pie icon"></i> Quizzes
            </a>
            <a class="setting-item item {{ Request::is('calculators') || Request::is('createcalculator/*') || Request::is('editcalculator/*') ? 'active' : '' }}" href="{{ url('calculators') }}">
                <i class="calculator icon"></i> Calculators
            </a>
        </div>
        <a class="item {{ Request::is('push_notification') ? 'active' : '' }}" href="{{ url('push_notification') }}">
            <i class="chart bell icon"></i> Push Notifications
        </a>
        <a class="item {{ Request::is('contacts') ? 'active' : '' }}" href="{{ route('contacts') }}">
            <i class="address book icon"></i> Contacts
        </a>
        @endif
        <a class="item {{ Request::is('templates') ? 'active' : '' }}" href="{{ url('templates') }}">
            <i class="suitcase icon"></i> Templates
        </a>
        <div class="title item">
            <div class="header"><i class="dropdown icon down"></i> Automation</div>
        </div>
        <div class="content">
            <a class="setting-item item {{ Request::is('visual_automation') ? 'active' : '' }}"  href="{{ url('visual_automation') }}">
                <i class="eye icon"></i> Visual Automation
            </a>
            <a class="setting-item item {{ Request::is('rss') ? 'active' : '' }}" href="{{ url('rss') }}">
                <i class="rss square icon"></i> RSS to Campaign
            </a>
        </div>
        <div class="title item">
            <div class="header"><i class="dropdown icon"></i> Settings</div>
        </div>
        <div class="content">
            @if($main_user->role_id != 3)
            <a class="setting-item item {{ Request::is('subaccount') ? 'active' : '' }}" href="{{ url('subaccount')}}"><i class="user icon"></i> Subaccount</a>
            <a class="setting-item item {{ Request::is('team') ? 'active' : '' }}" href="{{ url('team') }}"><i class="users icon"></i> Team</a>
            @endif
            <a class="setting-item item {{ Request::is('from_reply') ? 'active' : '' }}" href="{{ url('from_reply') }}"><i class="envelope icon"></i>From &amp; Reply Email</a>
            <a class="setting-item item {{ Request::is('integration') ? 'active' : '' }}" href="{{ url('integration') }}"><i class="plug icon"></i>Integration</a>
            <a class="setting-item item {{ Request::is('sms_mms_settings') ? 'active' : '' }}" href="{{ url('sms_mms_settings') }}"><i class="setting icon"></i>SMS/MMS Settings</a>
            @if(!$main_user->member || $main_user->member->role == 1)
            <a class="setting-item item {{ Request::is('contact_attributes') ? 'active' : '' }}" href="{{ url('contact_attributes') }}"><i class="universal access icon"></i>Contact Attributes</a>
            @endif
            <a class="setting-item item {{ Request::is('settings') ? 'active' : '' }}" href="{{ url('settings') }}"><i class="wrench icon"></i> Account Settings</a>
            <a class="setting-item item {{ Request::is('unsubscribe_page_settings') ? 'active' : '' }}" href="{{ url('unsubscribe_page_settings') }}"><i class="sticky note icon"></i> Unsubscribe Page Settings</a>
        </div>
        <a class="item" href="{{ url('logout') }}">
            <i class="sign out icon"></i> Sign Out
        </a>
    </div>
</div>

@section('footerscripts')

@endsection
