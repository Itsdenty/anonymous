@extends('layouts.main')

@section('title', 'Sendmunk | Campaigns')

@section('content')
<div class="block-header">
    <div class="row">
        <div class="col-lg-5 col-md-5 col-sm-12">
            <h2>Campaigns
            </h2>
        </div>
            <ul class="breadcrumb float-md-right">
                <li class="breadcrumb-item"><a href="index.html"><i class="zmdi zmdi-home"></i> SendMunk</a></li>
                <li class="breadcrumb-item active">Campaigns</li>
            </ul>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-sm-12">
            <div class="card">
                <div class="body">
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-3 col-sm-12 text-center">
                            <div class="body">
                                <h2 class="number count-to m-t-0 m-b-5" data-from="0" data-to="{{ $num_of_contacts }}" data-speed="1000" data-fresh-interval="700">501</h2>
                                <p class="text-muted">Contacts</p>
                                <span id="linecustom1">1,4,2,6,5,2,3,8,5,2</span>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 text-center">
                            <div class="body">
                                <h2 class="number count-to m-t-0 m-b-5" data-from="0" data-to="{{ $num_of_opens }}" data-speed="2000" data-fresh-interval="700">2501</h2>
                                <p class="text-muted ">Openers</p>
                                <span id="linecustom2">2,9,5,5,8,5,4,2,6</span>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 text-center">
                            <div class="body">
                                <h2 class="number count-to m-t-0 m-b-5" data-from="0" data-to="{{ $num_of_clicks }}" data-speed="2000" data-fresh-interval="700">743</h2>
                                <p class="text-muted">Clickers</p>
                                <span id="linecustom3">1,5,3,6,6,3,6,8,4,2</span>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 text-center">
                            <div class="body">
                                <h2 class="number count-to m-t-0 m-b-5" data-from="0" data-to="{{ $num_of_unsubscribers }}" data-speed="2000" data-fresh-interval="700">60</h2>
                                <p class="text-muted">Unsubscribers</p>
                                <span id="linecustom4">1,5,3,6,6,3,6,8,4,2</span>
                            </div>
                        </div>
                </div>
            </div>
        </div>
        <div class="body">
            <a class="btn btn-simple btn-round waves-effect" role="button" href="{{ url('createcampaign') }}"> Create New Campaign </a>
            <div class="alert alert-danger text-center justify-content-center mt-5 mx-auto" style="width:60%; line-height:8px">
                <strong>You have not created any campaigns yet</strong>
            </div>
        </div>
    </div>
@endsection