@extends('layouts.admin')
@section('title', 'Sendmunk | Admin Poll  Templates')

@section('content')
    <a id="create_poll_icon" href="#" class="publish right floated ui primary button"><i class="plus icon"></i>Create New Poll Template</a>
    <h1>Poll Template(s)</h1>
    <hr/>
    @if(!$polls->isEmpty())
    <table class="ui table">
        <thead>
            <tr>
                <th>Polls Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($polls as $form)
            <tr>
                <td>{{ $form->title }}</td>
                <td>
                    <a href="{{ url('admin/editpolltemplate').'/'.$form->id }}"><i data-content="Edit Poll Template" class="edit icon"></i></a>
                    <a href="{{ url('delete/form').'/'.$form->id }}"><i data-content="Delete Poll Template" class="delete icon"></i></a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="ui message"><p>You don't have any Poll Template(s)  yet</p></div>
    @endif

    {{-- Create New Form Modal --}}
    <div id="create_poll_modal" class="ui tiny modal">
        <div class="header">Create New Poll Template</div>
        <div class="content">
            <form action="{{ url('admin/createpolltemplate') }}" id="create_new_poll" class="ui form" method="post" role="form">
                {{ csrf_field() }}
                <div class="field">
                    <label>Poll Title</label>
                    <input id="poll_title" type="text" name="title" required/>
                </div>
            </form>
        </div>
        <div class="actions">
            <div class="ui cancel button">Cancel</div>
            <input id="create_poll_btn" type="submit" value="Create" form="create_new_poll" class="ui primary button" />
        </div>
    </div>

@endsection

@section("footerscripts")
    <script>
        $('#create_poll_icon').on('click', function(){
            $('#poll_title').val('');
            $('#create_poll_modal').modal('show');
        });

        $('i').popup();
    </script>
@endsection