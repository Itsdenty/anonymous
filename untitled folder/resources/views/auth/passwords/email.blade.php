@extends('layouts.index')
@section('title', 'Reset Password | Sendmunk')

@section('content')
    <div class="ui container">
        <div class="ui vertically stackable grid">
            <div class="three column row">
                <div class="column"></div>
                <div class="column">
                    @if (session('status'))
                        <div class="ui success message">
                            <i class="close icon"></i>
                            <div class="header">
                                {{ session('status') }}
                            </div>
                        </div>
                    @endif

                    <br>
                    <form class="ui form" method="post" action="{{ route('password.email') }}" role="form">
                        {{ csrf_field() }}
                        <div class="field">
                            <label>Email</label>
                            <input type="email" name="email" placeholder="Email Address" value="{{ old('email') }}" required/>
                        </div>
                        <button type="submit" class="fluid ui large button">Send Password Reset Link</button>
                    </form>
                </div>
                <div class="column"></div>
            </div>
        </div>
    </div>
@endsection
