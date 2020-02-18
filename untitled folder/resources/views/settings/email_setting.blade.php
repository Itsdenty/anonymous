@extends('layouts.main')

@section('title', 'Sendmunk | From & Reply Email')

@section('content')

<div class="block-header">
    <div class="row">
        <div class="col-lg-5 col-md-5 col-sm-12">
            <h2>Sender Email Settings
            </h2>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-sm-12">
            <div class="card">
                <div class="body">
                  <div class="card">
                    <div class="card-header">
                            @if(Session::has('error'))
                            <div id="error-alert" class="alert alert-dismissible fade show" role="alert" style="background:#ff7c93">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                                {{ Session::get('error') }}
                            </div>
                            @endif
                            @if(Session::has('status'))
                            <div id="status-alert" class="alert alert-dismissible fade show" role="alert" style="background:#93f3b0">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                                {{ Session::get('status') }}
                            </div>
                            @endif
                        <h5 class="title">Add Email Address</h5>
                    </div>
                    <form method="post" action="{{ url('from_reply') }}" role="form">
                    {{ csrf_field() }}
                        <div class="card-body">
                            <input type="email" name="email" class="form-control" placeholder="Email address" required>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary btn-round waves-effect">Add</button>
                        </div>
                    </form>
                  </div>
                  @if(!$emails->isEmpty())
            <table>
                <thead>
                    <tr>
                        <th width="35%">Email</th>
                        <th width="15%">Status</th>
                        <th width="15%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($emails as $email)
                    <tr>
                        <td>{{ $email->email }}</td>
                        <td>{{ $email->confirmed ? 'Verified' : 'Pending' }}</td>
                        <td><a href="{{ url('delete/email').'/'.$email->id }}" class="btn btn-primary btn-round waves-effect">Delete</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="alert alert-danger text-center justify-content-center mt-5 mx-auto" style="width:60%; line-height:8px">
                <strong>You have not added any email address yet</strong>
            </div>
            @endif
                </div>
            </div>
        </div>
        </div>
    </div>
    <script>
    window.setTimeout(function() {
    $("#error-alert").fadeTo(500, 0).slideUp(500, function(){
        $(this).remove();
    });
    $("#status-alert").fadeTo(500, 0).slideUp(500, function(){
        $(this).remove();
    });
    }, 6000);
    </script>
@endsection
