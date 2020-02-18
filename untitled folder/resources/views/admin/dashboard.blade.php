@extends('layouts.admin')
@section('title', 'Sendmunk | Admin Dashboard')

@section('content')
    <h1>Dashboard</h1>
    <hr/>
    <div class="ui stackable grid">
        <div class="four column row">
            <div class="column" style="text-align: center;">
                <div class="ui raised segment">
                    <h2>No of Customers</h2>
                    <h1 style="color: #F47921;">{{ $users_count }}</h1>
                </div>
            </div>
            <div class="column" style="text-align: center;">
                <div class="ui raised segment">
                    <h2>No of Forms</h2>
                    <h1 style="color: #F47921;">{{ $forms_count }}</h1>
                </div>
            </div>
            <div class="column" style="text-align: center;">
                <div class="ui raised segment">
                    <h2>No of Visitors</h2>
                    <h1 style="color: #F47921;">{{ $visitors_count }}</h1>
                </div>
            </div>
            <div class="column" style="text-align: center;">
                <div class="ui raised segment">
                    <h2>No of Form Subscribers</h2>
                    <h1 style="color: #F47921;">{{ $subscribers_count }}</h1>
                </div>
            </div>
        </div>
        <div class="four column row">
            <div class="column" style="text-align: center;">
                <div class="ui raised segment">
                    <h2>No of Campaigns</h2>
                    <h1 style="color: #F47921;">{{ $campaigns_count }}</h1>
                </div>
            </div>
            <div class="column" style="text-align: center;">
                <div class="ui raised segment">
                    <h2>No of SMS/MMS Campaign</h2>
                    <h1 style="color: #F47921;">{{ $sms_campaign_count }}</h1>
                </div>
            </div>
            <div class="column" style="text-align: center;">
                <div class="ui raised segment">
                    <h2>No of Contacts</h2>
                    <h1 style="color: #F47921;">{{ $contacts_count }}</h1>
                </div>
            </div>
            <div class="column" style="text-align: center;">
                <div class="ui raised segment">
                    <h2>No of Unsubscribers</h2>
                    <h1 style="color: #F47921;">{{ $unsubscribers_count }}</h1>
                </div>
            </div>
        </div>
    </div>

@endsection