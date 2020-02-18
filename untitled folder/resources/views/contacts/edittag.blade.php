@extends('layouts.user')
@section('title', 'Sendmunk | Edit Tag')

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
        <h2>Edit Tag</h2>
    </div>
    <br/>
    <div class="ui segment">
        <h3>{{ $tag->name }}</h3>
        <hr/>
        <form class="ui form" id="edit_tag_form" action="{{ url('tag/update') }}" method="POST" role="form">
            {{ csrf_field() }}
            <input type="hidden" name="tag_id" value="{{ $tag->id }}" />
            <div class="field">
                <label>Tag Name</label>
                <input type="text" name="name" value="{{ $tag->name }}" required />
            </div>
            <button type="submit" class="ui large button">Update</button>
            <button id="delete_tag" type="button" class="ui large button">Delete</button>
        </form>
    </div>
    <div class="ui segment">
        <h3>Tag Contacts</h3>
        <hr/>
        @if(!$tag->contacts->isEmpty())
        <table class="ui table">
            <thead>
                <tr>
                    <th>NAME</th>
                    <th>EMAIL</th>
                    <th width="15%" style="text-align: center">SUBCRIPTION DATE</th>
                    <th width="15%" style="text-align: center">ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tag->contacts as $contact)
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
                    <td style="text-align: center">
                        <a href="{{ url('tag').'/'.$tag->id.'/'.'remove'.'/'.$contact->id }}"><i data-content="Remove Contact" class="trash icon"></i></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="ui message"><p>You don't have any Contact(s) in this Tag yet</p></div>
        @endif
    </div>

    {{-- Delete Tag Modal --}}
    <div id="delete_tag_modal" class="ui basic mini modal">
        <div class="ui icon header">
            <i class="trash icon"></i>
            Delete Tag
        </div>
        <div class="content">
            <p>Are you sure you want to delete this Tag?</p>
        </div>
        <div class="actions">
            <div class="ui basic cancel inverted button">
            <i class="remove icon"></i> No
            </div>
            <a href="{{ url('tag/delete').'/'.$tag->id }}" class="ui blue ok inverted button">
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

        $('#delete_tag').on('click', function(e){
            $('#delete_tag_modal').modal('show');
        });
    </script>
@endsection
