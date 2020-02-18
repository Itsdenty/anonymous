
@extends('layouts.user')
@section('title', 'Sendmunk | Visual Automation')

@section('styles')
    <style>
        .template_btn {
            color: #fff !important;
        }
    </style>
@endsection

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
        <a id="create_workflow_icon" href="#" class="right floated ui button"><i class="plus icon"></i>New Workflow</a>
        <h2>Visual Automation</h2>
    </div>

    <br/>

    <div class="ui segment">
        <div class="ui message"><p>You don't have any Automation Workflow(s) yet</p></div>
    </div>

    <div id="create_workflow_modal" class="ui tiny modal">
        <div class="header">Create New Workflow</div>
        <div class="content">
            <form action="{{ url('create_workflow') }}" id="create_new_template" class="ui form" method="post" role="form">
                {{ csrf_field() }}
                <div class="field">
                    <label>Name of Workflow</label>
                    <input id="name" type="text" name="name" required/>
                    
                    <label style="margin-top: 4%;">Choose Trigger</label>
                    <select name="trigger_type" class="ui dropdown" required onchange="java_script_:show(this.options[this.selectedIndex].value)">
                        <option value="">Choose trigger</option>
                        <option value="contact">Contact Added</option>
                        <option value="tag">Tag Added</option>
                    </select>
                    <div class="contact box" style="margin-top:4%;">
                        <select name="contacts" multiple="" class="ui fluid dropdown">
                            <option value="">Choose contacts</option>
                            <option value="all">All contacts</option>
                            <option value="glo">Glo</option>
                            <option value="mtn">MTN</option>
                            <option value="airtel">Airtel</option>
                            <option value="9mobile">9Mobile</option>
                        </select>
                    </div>
                    <div class="tag box" style="margin-top:4%;">
                        <input list="tags" name="tags">
                        <datalist id="tags" class="ui fluid">
                            <option value="Gender">
                            <option value="Automation">
                            <option value="Free">
                            <option value="Delay">
                            <option value="Email">
                        </datalist>
                    </div>
                </div>
            </form>
        </div>
        <div class="actions" style="margin-top:4%;">
            <div class="ui cancel button">Cancel</div>
            <input id="create_form_btn" type="submit" value="Create" form="create_new_template" class="ui primary button" />
        </div>
    </div>
@endsection

@section('footerscripts')
    <script>
        $('.message .close').on('click', function() {
            $(this).closest('.message').transition('fade');
        });

        $('i').popup();

    $('#create_workflow_icon').on('click', function(){
        $('#template_title').val('');
        $('#create_workflow_modal').modal('show');
    });

    $("select").change(function(){
        $(this).find("option:selected").each(function(){
            var optionValue = $(this).attr("value");
            if(optionValue){
                $(".box").not("." + optionValue).hide();
                $("." + optionValue).show();
            } else{
                $(".box").hide();
            }
        });
    }).change();
    </script>
@endsection
