@extends('layouts.admin')
@section('title', 'Sendmunk | Themes')

@section('content')
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

    <a id="create_theme_icon" href="#" class="publish right floated ui primary button"><i class="plus icon"></i>Create New Theme</a>
    <h1>Theme(s)</h1>
    <hr/>
    @if(!$themes->isEmpty())
    <table class="ui table">
        <thead>
            <tr>
                <th>Themes Name</th>
                <th>Form Type</th>
                <th>Type (Poll, Quiz, Calculators)</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($themes as $theme)
            <tr>
                <td>{{ $theme->name }}</td>
                <td>{{ $theme->form_type }}</td>
                <td>{{ $theme->poll_type }}</td>
                <td>
                    <a href="{{ url('admin/edittheme').'/'.$theme->id }}"><i data-content="Edit Theme" class="edit icon"></i></a>
                    <a href="{{ url('admin/deletetheme').'/'.$theme->id }}"><i data-content="Delete Theme" class="delete icon"></i></a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="ui message"><p>You don't have any Theme(s)  yet</p></div>
    @endif

    {{-- Create New Form Modal --}}
    <div id="create_poll_modal" class="ui tiny modal">
        <div class="header">Create New Theme</div>
        <div class="content">
            <form action="{{ url('admin/createtheme') }}" id="create_new_theme" class="ui form" method="post" role="form">
                {{ csrf_field() }}
                <div class="field">
                    <label>Name</label>
                    <input id="name" type="text" name="name" required/>
                </div>
                <div class="field">
                    <label>Form Type</label>
                    <select name="form_type" id="form_type" class="ui dropdown" required>
                        <option value="">Select Form Type</option>
                        <option value="popover">Popover</option>
                        <option value="embedded">Embedded</option>
                        <option value="topbar">Topbar</option>
                        <option value="scrollbox">Scrollbox</option>
                        <option value="welcome_mat">Welcome Mat</option>
                        <option value="poll">Poll</option>
                        <option value="quiz">Quiz</option>
                        <option value="calculator">Calculator</option>
                    </select>
                </div>
                <div class="field">
                    <label>Type (Polls, Quizzes and Calculators only)</label>
                    <select name="poll_type" id="poll_type" class="ui dropdown">
                        <option value="">Select Type</option>
                        <option value="popover">Popover</option>
                        <option value="welcome_mat">Welcome Mat</option>
                        <option value="scrollbox">Scrollbox</option>
                    </select>
                </div>
            </form>
        </div>
        <div class="actions">
            <div class="ui cancel button">Cancel</div>
            <input id="create_theme_btn" type="submit" value="Create" form="create_new_theme" class="ui primary button" />
        </div>
    </div>

@endsection

@section("footerscripts")
    <script>
        $('#create_theme_icon').on('click', function(){
            $('#create_poll_modal').modal('show');
        });

        $('i').popup();
    </script>
@endsection