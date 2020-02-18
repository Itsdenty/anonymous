@extends('layouts.index')
@section('title', 'Reset Password | Sendmunk')

@section('content')
    <div class="ui container">
        <div class="ui vertically stackable grid">
            <div class="three column row">
                <div class="column"></div>
                <div class="column">
                    <br>
                    <form class="ui form" method="post" action="{{ route('password.request') }}" role="form">
                        {{ csrf_field() }}
                        <input type="hidden" name="token" value="{{ $token }}">
                        <div class="field">
                            <label>Email</label>
                            <input type="email" name="email" placeholder="Enter your Email Address" required/>
                        </div>
                        <div class="field">
                            <label>password</label>
                            <input type="password" name="password" placeholder="New Password" required/>
                        </div>
                        <div class="field">
                            <label>Password</label>
                            <input type="password" name="password_confirmation" placeholder="Confirm Password" required/>
                        </div>
                        <button  type="submit" class="fluid ui large button">Reset Password</button>
                    </form>
                </div>
                <div class="column"></div>
            </div>
        </div>
    </div>
@endsection
