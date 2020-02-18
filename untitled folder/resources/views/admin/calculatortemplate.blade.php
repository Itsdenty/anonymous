@extends('layouts.admin')
@section('title', 'Sendmunk | Admin Calculator Templates')

@section('content')
    <a id="create_calculator_icon" href="#" class="publish right floated ui primary button"><i class="plus icon"></i>Create New Calculator Template</a>
    <h1>Calculator Template(s)</h1>
    <hr/>
    @if(!$calculators->isEmpty())
    <table class="ui table">
        <thead>
            <tr>
                <th>Calculator's Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($calculators as $form)
            <tr>
                <td>{{ $form->title }}</td>
                <td>
                    <a href="{{ url('admin/editcalculatortemplate').'/'.$form->id }}"><i data-content="Edit Calculator Template" class="edit icon"></i></a>
                    <a href="{{ url('delete/form').'/'.$form->id }}"><i data-content="Delete Calculator Template" class="delete icon"></i></a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="ui message"><p>You don't have any Calculator Template(s)  yet</p></div>
    @endif

    {{-- Create New Form Modal --}}
    <div id="create_calculator_modal" class="ui tiny modal">
        <div class="header">Create New Calculator Template</div>
        <div class="content">
            <form action="{{ url('admin/createcalculatortemplate') }}" id="create_new_calculator" class="ui form" method="post" role="form">
                {{ csrf_field() }}
                <div class="field">
                    <label>Calculator Title</label>
                    <input id="calculator_title" type="text" name="title" required/>
                </div>
            </form>
        </div>
        <div class="actions">
            <div class="ui cancel button">Cancel</div>
            <input id="create_calculator_btn" type="submit" value="Create" form="create_new_calculator" class="ui primary button" />
        </div>
    </div>

@endsection

@section("footerscripts")
    <script>
        $('#create_calculator_icon').on('click', function(){
            $('#calculator_title').val('');
            $('#create_calculator_modal').modal('show');
        });

        $('i').popup();
    </script>
@endsection