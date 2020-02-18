@extends('layouts.user')
@section('title', 'Sendmunk | Account Settings')

@section('content')
    <div class="ui container">
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
            <h2>Account Settings</h2>
        </div>
        <br/>
        <div class="ui segment">
            <h3>Profile</h3>
            <hr/>
            <form action="{{ url('settings') }}" class="ui form" method="post" role="form">
                {{ csrf_field() }}
                <div class="field">
                    <label>Address</label>
                    <input type="text" name="address" value="{{ isset($profile->address) ? $profile->address : '' }}" placeholder="Enter Address" required />
                </div>
                <div class="field">
                    <label>Country</label>
                    <div class="ui search selection dropdown">
                        <input type="hidden" name="country_id" value="{{ isset($profile->country_id) ? $profile->country_id : '' }}" required>
                        <i class="dropdown icon"></i>
                        <div class="default text">Select Country</div>
                        <div class="menu">
                        @foreach($countries as $country)
                        <div class="item {{ isset($profile->country_id) ? ($profile->country_id == $country->id ? 'active selected': '') :  ''}}" data-value="{{ $country->id }}"><i class="{{ strtolower($country->code) }} flag"></i>{{ $country->name }}</div>
                        @endforeach
                    </div>
                </div>
                <div class="field">
                    <label>State</label>
                    <input type="text" value="{{ isset($profile->state) ? $profile->state : '' }}" name="state" placeholder="Enter State" required />
                </div>
                <div class="field">
                    <label>City</label>
                    <input type="text" value="{{ isset($profile->city) ? $profile->city : '' }}" name="city" placeholder="Enter City" required />
                </div>
                <div class="field">
                    <label>Zip / Postal Code</label>
                    <input type="text" value="{{ isset($profile->zip_code) ? $profile->zip_code : '' }}" name="zip_code" placeholder="Enter Zip / Postal Code" />
                </div>
                <div class="field">
                    <label>Time Zone</label>
                    <select class="ui dropdown" name="time_zone_id" required>
                        <option value="">Select Time Zone</option>
                        @foreach($time_zones as $time_zone)
                        <option value="{{ $time_zone->id }}" {{ isset($profile->time_zone_id) ? ($profile->time_zone_id == $time_zone->id ? 'selected': '') :  ''}}>{{ $time_zone->text }}</option>
                        @endforeach
                    </select>
                </div>
                <button class="ui large button" type="submit">Update Profile</button>
            </form>
        </div>
    </div>
    <div class="ui segment">
        <h3>Change Password</h3>
        <hr/>
        <form action="{{ url('changepassword') }}" class="ui form" method="post" role="form">
            {{ csrf_field() }}
            <input type="hidden" name="user_id" value="{{ $main_user->id }}" />
            <div class="four fields">
            <div class="field">
                <label>Current Password</label>
                <input type="password" name="current_password" required/>
            </div>
            <div class="field">
                <label>New Password</label>
                <input id="p-password" type="password" name="new_password" required/>
            </div>
            <div class="field">
                <label>Retype New Password</label>
                <input id="confirm-password" type="password" required />
                <small id="match-message"></small>
            </div>
            <div class="field">
                <label>&nbsp;</label>
                <button id="submit-btn" type="submit" class="ui fluid button">Change</button></div>
            </div>
        </form>
    </div>
    <div class="ui segment">
        <h3>Double Opt-in Settings</h3>
        <hr/>
        <p>Double opt-in adds an additional step to the email subscription opt-in process, requiring a user to verify their email address to confirm interest. Users receive a confirmation email in which they need to confirm their wish to be added to your mailing list</p>
        <div class="ui toggle checkbox">
            <input id="double-optin" type="checkbox" name="double_optin" {{ $user->double_optin ? 'checked ' : '' }} >
            <label>Enable Double Opt-in</label>
        </div>
    </div>
    <div class="ui segment">
        <h3>Opt-in GDPR Settings</h3>
        <hr/>
        <p>GDPR regulates how individuals and organizations may obtain, use, store, and eliminate personal data. Enabling GDPR consent checkbox adds an unticked checkbox with the clickable statement 'I agree to the Data Storage and Data Processing Policies' on all opt-in forms which leads to our data storage and processing policies.</p>
        <div class="ui toggle checkbox">
            <input id="gdpr" type="checkbox" name="gdpr" {{ $user->gdpr ? 'checked ' : '' }} >
            <label>Enable GDPR Consent Checkbox</label>
        </div>
    </div>

@endsection

@section('footerscripts')
    <script type="text/javascript" src="{{ url("js/notify.min.js") }}"></script>
    <script>
        $('.message .close').on('click', function() {
            $(this).closest('.message').transition('fade');
        });

        $('#sub_account').on('click', function(){
            $('.tiny.modal').modal('show');
        });

        $('.ui.dropdown').dropdown();

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

        $('#double-optin').change(function(){
            if($(this).is(':checked')){
                $.ajax({
                    url: '{{ url("enabledoubleoptin") }}',
                    method: 'GET',
                    success: function(){
                        // show success message
                        $.notify('Double Optin Enabled', 'success');
                    },
                    error: function(){
                        // show error message
                        console.log('error');
                    }
                });
            }
            else{
                $.ajax({
                    url: '{{ url("disabledoubleoptin") }}',
                    method: 'GET',
                    success: function(){
                        // show success message
                        $.notify('Double Optin Disabled', 'success');
                    },
                    error: function(){
                        // show error message
                        console.log('error');
                    }
                });
            }
        });

        $('#gdpr').change(function(){
            if($(this).is(':checked')){
                $.ajax({
                    url: '{{ url("enablegdpr") }}',
                    method: 'GET',
                    success: function(){
                        // show success message
                        $.notify('GDPR Enabled', 'success');
                    },
                    error: function(){
                        // show error message
                        console.log('error');
                    }
                });
            }
            else{
                $.ajax({
                    url: '{{ url("disablegdpr") }}',
                    method: 'GET',
                    success: function(){
                        // show success message
                        $.notify('GDPR Disabled', 'success');
                    },
                    error: function(){
                        // show error message
                        console.log('error');
                    }
                });
            }
        });
    </script>
@endsection
