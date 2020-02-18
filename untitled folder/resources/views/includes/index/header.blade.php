<nav class="ui fluid fixed container">
    <div class="ui segment">
        <div class="ui huge secondary  menu">
            <a class="item" href="{{ url('dashboard')}}">
                <img alt="SendMunk Logo" src="{{ asset('SM_Avi.png') }}" style="width:30%;">
            </a>
            <div class="right menu">
                <a style="background:inherit; border: 1px solid #ff163f; color:#ff163f; border-radius: 30px;" class="{{ Request::is('register') ? 'active' : 'ui' }} item" href="{{ url('register') }}">
                    Register
                </a>
                <a style="background:#ff163f; border: 1px solid #ff163f; border-radius: 30px; color: #ffffff;" class="{{ Request::is('login') || Request::is('/') ? 'active' : 'ui' }} item" href="{{ url('login') }}">
                    Log In
                </a>
            </div>
        </div>
    </div>
</nav>
