@extends('layouts.user')
@section('title', 'Sendmunk | Edit Segment')

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
        <h2>Edit Segment</h2>
    </div>
    <br/>
    <div class="ui segment">
        <h3>{{ $segment->name }}</h3>
        <hr/>
        <form class="ui form" id="edit_tag_form" action="{{ url('segment/update') }}" method="POST" role="form">
            {{ csrf_field() }}
            <input type="hidden" name="segment_id" value="{{ $segment->id }}" />
            <div class="field">
                <label>Segment Name</label>
                <input type="text" name="name" value="{{ $segment->name }}" required />
            </div>
            <button type="submit" class="ui large button">Update</button>
            <button id="delete_segment" type="button" class="ui large button">Delete</button>
        </form>
    </div>
    <div class="ui segment">
        <h3>Segment Contacts</h3>
        <hr/>
        @if(!$segment->contacts->isEmpty())
        <table class="ui table">
            <thead>
                <tr>
                    <th>NAME</th>
                    <th>EMAIL</th>
                    <th width="15%" style="text-align: center">SUBCRIPTION DATE</th>
                </tr>
            </thead>
            <tbody>
                @foreach($segment->contacts as $contact)
                <tr>
                    <td>
                        @if($contact->unsubscribed)
                        <del>{{ $contact->name }}</del>
                        @else
                        {{ $contact->name }}
                        @endif
                    </td>
                    <td>
                        @if($contact->unsubscribed)
                        <del>{{ $contact->email }}</del>
                        @else
                        {{ $contact->email }}
                        @endif
                    </td>
                    <td style="text-align: center">{{ $contact->sub_date }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="ui message"><p>You don't have any Contact(s) in this Segment yet</p></div>
        @endif
    </div>

    {{-- Delete Tag Modal --}}
    <div id="delete_segment_modal" class="ui basic mini modal">
        <div class="ui icon header">
            <i class="trash icon"></i>
            Segment Tag
        </div>
        <div class="content">
            <p>Are you sure you want to delete this Segment?</p>
        </div>
        <div class="actions">
            <div class="ui basic cancel inverted button">
            <i class="remove icon"></i> No
            </div>
            <a href="{{ url('segment/delete').'/'.$segment->id }}" class="ui blue ok inverted button">
                <i class="checkmark icon"></i> Yes
            </a>
        </div>
    </div>

@endsection

@section('footerscripts')
    <script>
        $('.message .close').on('click', function() {
            $(this).closest('.message').transition('fade');
        });

        $('.menu .item').tab();

        $('i').popup();

        $('#delete_segment').on('click', function(e){
            $('#delete_segment_modal').modal('show');
        });
    </script>
@endsection
