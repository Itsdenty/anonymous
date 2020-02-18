@extends('layouts.admin')
@section('title', 'Sendmunk | Admin Quiz Templates')

@section('content')
    <a id="create_quiz_icon" href="#" class="publish right floated ui primary button"><i class="plus icon"></i>Create New Quiz Template</a>
    <h1>Quiz Template(s)</h1>
    <hr/>
    @if(!$quizzes->isEmpty())
    <table class="ui table">
        <thead>
            <tr>
                <th>Quiz Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($quizzes as $form)
            <tr>
                <td>{{ $form->title }}</td>
                <td>
                    <a href="{{ url('admin/editquiztemplate').'/'.$form->id }}"><i data-content="Edit Quiz Template" class="edit icon"></i></a>
                    <a href="{{ url('delete/form').'/'.$form->id }}"><i data-content="Delete Quiz Template" class="delete icon"></i></a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="ui message"><p>You don't have any Quiz Template(s)  yet</p></div>
    @endif

    {{-- Create New Form Modal --}}
    <div id="create_quiz_modal" class="ui tiny modal">
        <div class="header">Create New Quiz Template</div>
        <div class="content">
            <form action="{{ url('admin/createquiztemplate') }}" id="create_new_quiz" class="ui form" method="post" role="form">
                {{ csrf_field() }}
                <div class="field">
                    <label>Quiz Title</label>
                    <input id="quiz_title" type="text" name="title" required/>
                </div>
            </form>
        </div>
        <div class="actions">
            <div class="ui cancel button">Cancel</div>
            <input id="create_quiz_btn" type="submit" value="Create" form="create_new_quiz" class="ui primary button" />
        </div>
    </div>

@endsection

@section("footerscripts")
    <script>
        $('#create_quiz_icon').on('click', function(){
            $('#quiz_title').val('');
            $('#create_quiz_modal').modal('show');
        });

        $('i').popup();
    </script>
@endsection