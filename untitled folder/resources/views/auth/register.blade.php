@extends('layouts.index')
@section('title', 'Sendmunk | Register')

@section('content')
    <div class="ui container">
        <div class="ui vertically stackable grid">
            <div class="three column row">
                <div class="column"></div>
                <div class="column">
                    @if ($errors->any())
                        <div class="ui warning message">
                            <i class="close icon"></i>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

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

                    <h2 style="text-align: center;">Create an Account</h2>
                    <form class="ui form" method="post" action="{{ url('register') }}" role="form" enctype="multipart/form-data">
                    {{ csrf_field() }}
                        <div class="field">
                            <label>Name</label>
                            <input type="text" name="name" placeholder="Name" required/>
                        </div>
                        <div class="field">
                            <label>Email</label>
                            <input type="email" name="email" placeholder="Email Address" required/>
                        </div>
                        <div class="field">
                            <label>Password</label>
                            <input id="p-password" type="password" name="password" placeholder="Password" required/>
                        </div>
        
                        <button id="submit-btn" type="submit" class="fluid ui large button">Register</button>
                        <p style="text-align: center;">Already Signed up? <a href="{{ url('login') }}">Log in</a> here</p>
                    </form>
                </div>
                <div class="column"></div>
            </div>
        </div>
    </div>
@endsection

@section('footerscripts')
    <script>
        $('.message .close').on('click', function() {
            $(this).closest('.message').transition('fade');
        });
    </script>
@endsection