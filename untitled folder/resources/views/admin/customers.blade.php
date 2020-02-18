@extends('layouts.admin')
@section('title', 'Sendmunk | Admin Customers')

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

    <h1>Customers</h1>
    <hr/>
    <table class="ui table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Date Signed Up</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->created_at }}</td>
                <td>
                    <a class="ui button" href="{{ url('admin/edit/user').'/'.$user->id }}">Edit</a>
                    <a class="ui button" href="{{ url('admin/delete/user').'/'.$user->id}}">Delete</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection

@section('footerscripts')
    <script>
        $('.message .close').on('click', function() {
            $(this).closest('.message').transition('fade');
        });
    </script>
@endsection