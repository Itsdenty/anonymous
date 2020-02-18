@extends('layouts.user')
@section('title', 'Sendmunk | From & Reply Email')

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
        <h2>From & Reply Email</h2>
    </div>
    <br/>
    <div class="ui segment">
        <h3>Add Email</h3>
        <hr/>
        <form class="ui form" method="post" action="{{ url('from_reply') }}" role="form">
            {{ csrf_field() }}
            <div class="field">
                <input type="email" name="email" placeholder="Enter email address" required />
            </div>
            <button type="submit" class="publish ui button">Add</button>
        </form>
    </div>
    <br>
    <h3>Emails</h3>
    <hr/>
    @if(!$emails->isEmpty())
    <table class="ui celled stackable table">
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
                <td><a href="{{ url('delete/email').'/'.$email->id }}" class="ui button">Delete</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="ui message"><p>You have not added any email</p></div>
    @endif

@endsection

@section('footerscripts')
    <script>
        $('.message .close').on('click', function() {
            $(this).closest('.message').transition('fade');
        });
    </script>
@endsection
