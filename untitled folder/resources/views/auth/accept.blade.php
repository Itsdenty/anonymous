@extends('layouts.index')
@section('title', 'Sendmunk | Register')

@section('content')
<div class="ui fluid container">

</div>
<div class="ui container">
    <div class="ui vertically stackable grid">
        <div class="three column row">
            <div class="column"></div>
            <div class="column">
                <h2 class="center-item">Create an Account</h2>
                <form class="ui form" method="post" action="{{ url('registermember') }}" role="form" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="member_id" value="{{ $member->id }}" />
                    <input type="hidden" name="email" value="{{ $member->email }}" />
                    <div class="field">
                        <label>Email</label>
                        <input type="email" value="{{ $member->email }}" placeholder="Email Address" disabled/>
                    </div>
                    <div class="field">
                        <label>Name</label>
                        <input type="text" name="name" placeholder="Name" required/>
                    </div>
                    <div class="field">
                        <label>Password</label>
                        <input id="p-password" type="password" name="password" placeholder="Password" required/>
                    </div>
                    <div class="field">
                        <label>Confirm Password</label>
                        <input id="confirm-password" type="password" placeholder="Confirm Password" required />
                        <small id="match-message"></small>
                    </div>
    
                    <button id="submit-btn" type="submit" class="fluid ui primary large button">Register</button>
                </form>
            </div>
            <div class="column"></div>
        </div>
    </div>
</div>

<script
    src="https://code.jquery.com/jquery-3.1.1.min.js"
    integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
    crossorigin="anonymous">
</script>
<script>
    $('#confirm-password').on('keyup', function(){
        var password = $("#p-password").val();
        var confirmPassword = $("#confirm-password").val();

        if(password === confirmPassword && (password != "" && confirmPassword != "")){
            $('#match-message').html('Password Match').css('color', 'green');
            $('#submit-btn').removeAttr("disabled");
        } else if(password != "" && confirmPassword != "") {
            $('#match-message').html('Password Mismatch').css('color', 'red');
            $('#submit-btn').attr("disabled", "disabled");
        }
        else{
            $('#match-message').html('');
            $('#submit-btn').attr("disabled", "disabled");
        }
    });
</script>
@endsection