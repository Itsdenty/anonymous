@extends('layouts.user')
@section('title', 'Sendmunk | Team Members')

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
        <h2>Team</h2>
    </div>
    <br/>
    <div class="ui segment">
        <h3>Add Team Members</h3>
        <hr/>
        <form class="ui form" method="post" action="{{ url('team') }}" role="form">
            {{ csrf_field() }}
            <div class="field">
                <label>Email</label>
                <input type="email" name="email" placeholder="Enter email address to send invite" required />
            </div>
            <div class="field">
                <label>Role</label>
                <select name="role" class="ui basic floating dropdown" required style="background: #fff;">
                    <option value="">Role</option>
                    <option value="1">Owner</option>
                    <option value="2">Assistant</option>
                </select>
            </div>
            <button type="submit" class="ui button">Send Invite</button>
        </form>
    </div>
    <br>
    <h3>Team Members</h3>
    <hr/>
    @if(!$members->isEmpty())
    <table class="ui celled stackable table">
        <thead>
            <tr>
                <th width="30%">Name</th>
                <th width="30%">Email</th>
                <th width="15%">Role</th>
                <th width="10%">Status</th>
                <th style="text-align: center" width="15%">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($members as $member)
            <tr>
                <td>{{ $member->name }}</td>
                <td>{{ $member->email }}</td>
                <td>{{ $member->role == 1 ? "Owner" : "Assistant" }}</td>
                <td>{{ $member->confirmed ? 'Verified' : 'Pending' }}</td>
                <td style="text-align: center">
                    <a href="#" class="edit_member" data-id="{{ $member->id }}" data-email="{{ $member->email }}" data-role="{{ $member->role }}" data-content="Edit Member's Role"><i class="edit icon"></i></a>
                    <a href="{{ url('delete/member').'/'.$member->id }}" data-content="Delete Member"><i class="delete icon"></i></a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="ui message"><p>You have not invited any member(s)</p></div>
    @endif

    {{-- Edit Member Role --}}
    <div id="edit_member_modal" class="ui mini modal">
        <div class="header">Edit Member Role</div>
        <div class="content">
            <form action="{{ url('update_member_role') }}" id="edit_member_role" class="ui form" method="post" role="form">
                {{ csrf_field() }}
                <input type="hidden" name="member_id" id="edit_member_id" />
                <div class="field">
                    <label>Email</label>
                    <input type="email" id="edit_member_email" disabled />
                </div>
                <div class="field">
                    <label>Role</label>
                    <select id="edit_role_dropdown" class="ui dropdown" name="role">
                        <option value="">Role</option>
                        <option value="1">Owner</option>
                        <option value="2">Assistant</option>
                    </select>
                </div>
            </form>
        </div>
        <div class="actions">
            <div class="ui cancel button">Cancel</div>
            <input id="edit_role_btn" type="submit" value="Update" form="edit_member_role" class="ui primary button" />
        </div>
    </div>
@endsection

@section('footerscripts')
    <script>
        $('.message .close').on('click', function() {
            $(this).closest('.message').transition('fade');
        });

        $('a').popup();

        $('.edit_member').on('click', function(e){
            e.preventDefault();
            let member_id = $(this).data('id');
            let member_email = $(this).data('email');
            let member_role = $(this).data('role');

            $('#edit_member_id').val(member_id);
            $('#edit_member_email').val(member_email);
            $('#edit_role_dropdown').val(member_role).trigger('change');

            $('#edit_member_modal').modal('show');
        });
    </script>
@endsection
