@extends('layouts.admin')
@section('title', 'Sendmunk | Email Templates')

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
        <a id="create_template_icon" href="#" class="right floated ui button"><i class="plus icon"></i>New Template</a>
        <h2>Email Templates</h2>
    </div>

    <br/>
    @if(!$templates->isEmpty())
    <div class="ui cards">
        @foreach($templates as $template)
        <div class="card">
            <div class="content">
                <div class="header">{{ $template->name }}</div>
                <br/>
                <div class="image">
                    <img src="{{ $template->screenshot != null ? url($template->screenshot) : url('img/default.jpg') }}" width="100%" />
                </div>
            </div>
            <div class="ui bottom attached buttons">
                <a href="{{ url('admin/view/template'). '/' . $template->id }}" class="ui button"><i class="edit icon"></i> Edit</a>
                <a href="{{ url('admin/delete/template'). '/' . $template->id }}" class="ui button"><i class="delete icon"></i> Delete</a>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="ui segment">
        <div class="ui message"><p>You don't have any Template(s) yet</p></div>
    </div>
    @endif

    <div id="create_template_modal" class="ui tiny modal">
        <div class="header">Create New Template</div>
        <div class="content">
            <form action="{{ url('admin/createtemplate') }}" id="create_new_template" class="ui form" method="post" role="form">
                {{ csrf_field() }}
                <div class="field">
                    <label>Name of Template</label>
                    <input id="name" type="text" name="name" required/>
                </div>
            </form>
        </div>
        <div class="actions">
            <div class="ui cancel button">Cancel</div>
            <input id="create_form_btn" type="submit" value="Create" form="create_new_template" class="ui primary button" />
        </div>
    </div>
@endsection

@section('footerscripts')
<script>
    $('#create_template_icon').on('click', function(){
        $('#template_title').val('');
        $('#create_template_modal').modal('show');
    });
</script>
@endsection