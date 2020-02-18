@extends('layouts.user')
@section('title', 'Sendmunk | Subaccount')

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

        <div class="ui segment">
            <h3>Current Account: <span class="ui circular label">{{ $current_account_name }}</span></h3>
            <hr/>
            <form class="ui form" method="post" action="{{ url('setaccount') }}" role="form">
                {{ csrf_field() }}
                <label>Select Default Account</label>
                <div class="inline fields">
                    <div class="field">
                        <select class="ui dropdown" name="current_account_id">
                            @foreach($sub_accounts as $account)
                            <option value="{{ $account->id }}" {{ $account->id==$user->current_sub_account_id ? 'selected' : '' }}>{{ $account->account_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <button type="submit" class="ui button">Apply Changes</button>
                    </div>
                </div>
            </form>
        </div>
        
        <h3>Add a Subaccount</h3>
        <hr/>
        <button id="sub_account" class="ui button">Add Subaccount</button>

        <h3>Subaccount(s)</h3>
        <hr/>
        <table class="ui celled stackable table">
            <thead>
                <tr>
                    <th width="80%">Subaccount Name</th>
                    <th width="20%">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sub_accounts as $account)
                <tr>
                    <td>{{ $account->account_name }}</td>
                    @if($account->confirmed)
                    <td>Not Editable</td>
                    @else
                    <td><a href="{{ url('delete/account').'/'. $account->id}}" class="ui button">Delete</a></td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>


        {{-- Add Subaccount modal --}}
        <div class="ui tiny modal">
            <i class="close icon"></i>
            <div class="header">
                Add Subaccount
            </div>
            <div class="content">
                <form id="add_subaccount_form" class="ui form" method="post" action="{{ url('addaccount') }}" role="form">
                    {{ csrf_field() }}
                    <label>Subaccount Name</label>
                    <div class="field">
                        <input type="text" name="sub_account_name" placeholder="Enter SubAccount Name" required/>
                    </div>
                </form>
            </div>
            <div class="actions">
            <div class="ui fluid buttons ">
                <button class="ui cancel button">Cancel</button>
                <div class="or"></div>
                <button type="submit" form="add_subaccount_form" class="ui primary button">Add</button>
            </div>
            </div>
        </div>


@endsection

@section('footerscripts')
    <script>
        $('.message .close').on('click', function() {
            $(this).closest('.message').transition('fade');
        });

        $('#sub_account').on('click', function(){
            $('.tiny.modal').modal('show');
        });

        $('.ui.dropdown').dropdown();
    </script>
@endsection
