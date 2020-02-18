@extends('layouts.admin')
@section('title', 'Sendmunk | Edit Poll')

@section('content')
    <div class="ui vertical segment">
        <h2>Edit Poll Template</h2>    
    </div>
    <br/>
    <div id="form_navbar" class="ui pointing secondary stackable menu">
        <a class="item active" data-tab="message">Message</a>
        <a class="item" data-tab="questions">Questions</a>
        <a class="item" data-tab="appearance">Appearance</a>
        <a class="item" data-tab="fields">Fields</a>
        <div class="item">
                <button type="submit" form="update_template" class="publish ui primary button">Update Template</button>
        </div>
    </div>
    <br/>
    <div class="ui stackable grid">
        <div class="formview six wide column">
            <form id="update_template" action="{{ url('admin/updatetemplate') }}" class="ui form" method="post" role="form">
                {{ csrf_field() }}
                <input type="hidden" name="form_id" value="{{ $form->id }}" />
                <div class="ui tab active" data-tab="message">
                    <h4>Welcome Screen</h4>
                    <div class="field" data-control="summernote-container">
                        <label>Heading</label>
                        <textarea class="summernote" name="headline" id="headline_input">{{ $form->headline }}</textarea>
                    </div>
                    <div class="field" data-control="summernote-container">
                        <label>Subheading</label>
                        <textarea class="summernote" name="description" id="description_input">{{ $form->description }}</textarea>
                    </div>
                    <div class="field">
                        <label>Button Text</label>
                        <input name="button_text" id="start_button_input" type="text" value="{{ $form->button_text }}" />
                    </div>
                    <h4>Result</h4>
                    <div class="field" data-control="summernote-container">
                        <label>Result Headline</label>
                        <textarea class="summernote" id="success_headline_input" name="success_headline">{{ $form->success_headline }}</textarea>
                    </div>
                    <div class="field" data-control="summernote-container">
                        <label>Result Description</label>
                        <textarea class="summernote" id="success_description_input" name="success_description">{{ $form->success_description }}</textarea>
                    </div>
                </div>
                <div class="ui tab" data-tab="appearance">
                    <div class="ui styled accordion">
                        <div class="active title">
                            <i class="dropdown icon"></i>
                            Color
                        </div>
                        <div class="active content">
                            <h4>Theme</h4>
                            <div class="two fields">
                                <div class="field">
                                    <label>Background Color</label>
                                    <input name="background_color" id="bg_color_input" type="color" value="{{ $form->background_color }}" />
                                </div>
                                <div class="field">
                                    <label>Background Image</label>
                                    <button id="bg_image_btn" type="button"><i class="upload icon"></i></button>
                                    <input  name="background_image" id="bg_image_input" type="file" hidden />
                                    <button id="remove_image_btn" type="button"><i class="remove icon"></i></button>
                                </div>
                            </div>
                            @if($form->poll_type == 'popover')
                            <div class="field">
                                <label>Background Overlay</label>
                                <input name="background_overlay" id="bg_color_overlay" type="color" value="{{ $form->background_overlay }}" />                 
                            </div>
                            @endif
                            <h4>Submit Button</h4>
                            <div class="two fields">
                                <div class="field">
                                    <label>Background</label>
                                    <input name="button_color" id="bg-color_button" type="color" value="{{ $form->button_color }}" />
                                </div>
                                <div class="field">
                                    <label>Text</label>
                                    <input name="button_text_color" id="bg-color_text" type="color" value="{{ $form->button_text_color }}" />
                                </div>
                            </div>
                        </div>
                        <div class="title">
                            <i class="dropdown icon"></i>
                            Border
                        </div>
                        <div class="content">
                            <div class="inline fields">
                                <label>Border Style</label>
                                <select id="border_style" name="border_style" class="ui dropdown">
                                    <option value="none">None</option>
                                    <option value="dotted">Dotted</option>
                                    <option value="dashed">Dashed</option>
                                    <option value="solid">Solid</option>
                                    <option value="double">Double</option>
                                </select>
                            </div>
                            <div class="inline fields">
                                <div class="field"><label>Border Size</label></div>
                                <div class="field">
                                <div class="ui right labeled input">
                                    <input id="border_size" name="border_size" type="number" value="{{ $form->border_size }}" />
                                    <div class="ui basic label">px</div>
                                </div></div>
                            </div>
                            <div class="inline fields">
                                <label>Border Color</label>
                                <input name="border_color" type="color" id="border_color" value="{{ $form->border_color }}" />
                            </div>
                        </div>
                        <div class="title">
                            <i class="dropdown icon"></i>
                            Button
                        </div>
                        <div class="content">
                            <div class="inline fields">
                                <div class="field"><label>Button Font Size</label></div>
                                <div class="field">
                                    <div class="ui right labeled input">
                                        <input id="button_font_size" name="button_font_size" type="number" value="{{ $form->button_font_size }}" />
                                        <div class="ui basic label">px</div>
                                    </div>
                                </div>
                            </div>
                            <div class="inline fields">
                                <label>Button Font Family</label>
                                <select id="button_font_family" name="button_font_family" class="ui dropdown">
                                    <option value="Arial">Arial</option>
                                    <option value="Times New Roman">Times New Roman</option>
                                    <option value="Verdana ">Verdana </option>
                                    <option value="Impact ">Impact </option>
                                    <option value="Comic Sans MS">Comic Sans MS</option>
                                </select>
                            </div>
                        </div>
                        <div class="title">
                            <i class="dropdown icon"></i>
                            Option(s)
                        </div>
                        <div class="content">
                            <div class="inline fields">
                                <div class="field"><label>Option Font Size</label></div>
                                <div class="field">
                                    <div class="ui right labeled input">
                                        <input id="option_font_size" name="option_font_size" type="number" value="{{ $form->option_font_size }}" />
                                        <div class="ui basic label">px</div>
                                    </div>
                                </div>
                            </div>
                            <div class="inline fields">
                                <label>Option Font Family</label>
                                <select id="option_font_family" name="option_font_family" class="ui dropdown">
                                    <option value="Arial">Arial</option>
                                    <option value="Times New Roman">Times New Roman</option>
                                    <option value="Verdana ">Verdana </option>
                                    <option value="Impact ">Impact </option>
                                    <option value="Comic Sans MS">Comic Sans MS</option>
                                </select>
                            </div>
                            <div class="inline fields">
                                <label>Option Color</label>
                                <input name="option_color" type="color" id="option_color" value="{{ $form->option_color }}" />
                            </div>
                        </div>
                        @if($form->form_type == 'poll' && $form->poll_type == 'popover')
                        <div class="title">
                            <i class="dropdown icon"></i>
                            Size
                        </div>
                        <div class="content">
                            <p>What would you like the size of this popup to be?</p>
                            <input type="radio" name="size" id="small_size" value="small" {{ ($form->size == "small") ? "checked" : "" }} />
                            <label for="small_size">Small</label>&nbsp;&nbsp;&nbsp;
                            <input type="radio" name="size" id="medium_size" value="medium" {{ ($form->size == "medium") ? "checked" : "" }} />
                            <label for="medium_size">Medium</label>&nbsp;&nbsp;&nbsp;
                            <input type="radio" name="size" id="large_size" value="large" {{ ($form->size == "large") ? "checked" : "" }} />
                            <label for="large_size">Large</label>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="ui tab" data-tab="fields">
                    <h4>Form Field</h4>
                    <div class="ui styled accordion">
                        <div class="active title">
                            <i class="dropdown icon"></i>
                            Email
                        </div>
                        <div class="active content">
                            <div class="field">
                                <label>LABEL</label>
                                <input name="email_label" id="email_label_input" type="text" value="{{ $form->email_label }}" />
                            </div>
                            <div class="field">
                                <label>PLACEHOLDER TEXT</label>
                                <input name="email_placeholder" id="email_input" type="text" value="{{ $form->email_placeholder }}" />
                            </div>
                            <div class="field" data-control="summernote-container">
                                <label>Heading</label>
                                <textarea class="summernote" name="foot_note" id="footnote_input">{{ $form->footnote }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ui tab" data-tab="questions">
                    <div>
                        <a id="add_question" class="ui primary button" href="#"><i class="plus icon"></i>Add Question</a>
                    </div>
                    <br/>
                    <div id="stored_questions">
                        @foreach($questions as $question)
                            <div class="ui tiny message question_div">
                                <a  href="{{ url("delete/question")."/".$question->id}}" class="right floated ui icon button delete_question"><i class="delete icon"></i></a>
                                <div class="header">{{ $question->title }}</div>
                                <br/>
                                <a data-questionid="{{ $question->id }}" data-optiontype="{{ $question->options_type }}" href="#" class="add_option"><i class="plus icon"></i>Add Options</a>
                                <ul class="stored_options">
                                    @foreach($question->options as $option)
                                    <li>{{ $option->title }} <a class="delete_option" href="{{ url("delete/option")."/".$option->id}}"><i class="delete icon"></i></a></li>
                                    @endforeach
                                </ul>
                                <div class="new_options"></div>
                            </div>
                        @endforeach
                    </div>
                    <br/>
                    <div id="new_questions"></div>
                </div>
            </form>
        </div>
        <div id="content_preview" class="formview ten wide column" style="background: grey;">
            {!! $current_theme->content !!}
        </div>
    </div>
    <br/><br/>
@endsection

@section('footerscripts')
    <script>
        // Set predefined values
        $(function() {
            var selected_value = $("input[name='size']:checked").val();
            if(selected_value == "small")
            {
                $('#form_preview').css('width', '40%');
                $('#success_preview').css('width', '40%');
            }
            else if(selected_value == "medium")
            {
                $('#form_preview').css('width', '70%');
                $('#success_preview').css('width', '70%');
            }
            else
            {
                $('#form_preview').css('width', '100%');
                $('#success_preview').css('width', '100%');
            }
            if($('#allow_closing').is(':checked'))
            {
                $('.close_icon').show();
            }
            else
            {
                $('.close_icon').hide();
            }
            
            $('#headline_input').summernote('code', {!! json_encode($form->headline) !!});
            $('#description_input').summernote('code', {!! json_encode($form->description) !!});
            $('#success_headline_input').summernote('code', {!! json_encode($form->success_headline) !!});
            $('#success_description_input').summernote('code', {!! json_encode($form->success_description) !!});
            $('#footnote_input').summernote('code', {!! json_encode($form->foot_note) !!});

            $('#form_preview').css('background', $('#bg_color_input').val());
            $('#success_preview').css('background', $('#bg_color_input').val());
            $('#question_preview').css('background', $('#bg_color_input').val());
            $('#subscribe_preview').css('background', $('#bg_color_input').val());
            $('#content_preview').css('background', $('#bg_color_overlay').val());
            $('#start_button, .question_btn, #skip_btn, #subscribe_btn').css('background', $('#bg-color_button').val());
            $('#start_button, .question_btn, #skip_btn, #subscribe_btn').css('color', $('#bg-color_text').val());
            $('#submit_button, #start_button, .question_btn, #subscribe_btn, #skip_btn, #start_action_button').css('font-size', $('#button_font_size').val()  + 'px');
            $('#email').attr('placeholder', $('#email_input').val());
            $('#form_preview, #success_preview, #subscribe_preview, #question_preview').css('border-width', $('#border_size').val()  + 'px');
            $('#form_preview, #success_preview, #subscribe_preview, #question_preview').css('border-color', $('#border_color').val());
            $('#email_label').text($('#email_label_input').val());
            $('#start_button').text($('#start_button_input').val());
            $('.background_image').css('background-image','{{ $form->background_image == null ? "none" : "url(" . url("")."/".$form->background_image .")" }}');
            $('.background_image').css('background-size', 'cover');
            $('.background_image').css('background-position', 'center');

            $("#border_style").val('{{ $form->border_style }}').change();
            $("#button_font_family").val('{{ $form->button_font_family }}').change();
            $("#option_font_family").val('{{ $form->option_font_family }}').change();
        });

        $('#success_preview, #question_preview').hide();

        $('.menu .item').tab();
        $('.ui.accordion').accordion();
        // Bind input to preview
        $('#headline_input').on('summernote.change', function(we, contents, $editable) {
            $('#headline').html(contents);
        });
        $('#description_input').on('summernote.change', function(we, contents, $editable) {
            $('#description').html(contents);
        });
        $('#success_headline_input').on('summernote.change', function(we, contents, $editable) {
            $('#success_headline').html(contents);
        });
        $('#success_description_input').on('summernote.change', function(we, contents, $editable) {
            $('#success_description').html(contents);
        });
        $('#footnote_input').on('summernote.change', function(we, contents, $editable) {
            $('#footnote').html(contents);
        });
        $('#bg_color_input').bind('input', function(){
            $('#form_preview').css('background', $('#bg_color_input').val());
            $('#success_preview').css('background', $('#bg_color_input').val());
            $('#question_preview').css('background', $('#bg_color_input').val());
            $('#subscribe_preview').css('background', $('#bg_color_input').val());
        });
        $('#bg_color_overlay').bind('input', function(){
            $('#content_preview').css('background', $('#bg_color_overlay').val());
        });
        $('#bg-color_button').bind('input', function(){
            $('#start_button, .question_btn, #skip_btn, #subscribe_btn').css('background', $('#bg-color_button').val());
        });
        $('#bg-color_text').bind('input', function(){
            $('#start_button, .question_btn, #skip_btn, #subscribe_btn').css('color', $('#bg-color_text').val());
        });
        $('#email_input').bind('input', function(){
            $('#email').attr('placeholder', $('#email_input').val());
        });
        $('#email_label_input').bind('input', function(){
            $('#email_label').text($('#email_label_input').val());
        });
        $('#start_button_input').bind('input', function(){
            $('#start_button').text($('#start_button_input').val());
        });
        
        $('#headline_input, #description_input').bind('focus', function(){
            $('#success_preview, #subscribe_preview, #question_preview').hide();
            $('#form_preview').show();
        });

        $('#success_headline_input, #success_description_input').bind('blur', function(){
            $('#success_preview, #subscribe_preview, #question_preview').hide();
            $('#form_preview').show();
        });
        $('#success_headline_input, #success_description_input').bind('focus', function(){
            $('#form_preview, #subscribe_preview, #question_preview').hide();
            $('#success_preview').show();
        });

        $('#email_label_input, #email_input').bind('focus', function(){
            $('#form_preview, #success_preview, #question_preview').hide();
            $('#subscribe_preview').show();
        })

        $('#small_size,#medium_size,#large_size').change(function(){
            var selected_value = $("input[name='size']:checked").val();
            if(selected_value == "small")
            {
                $('#form_preview').css('width', '40%');
                $('#success_preview').css('width', '40%');
            }
            else if(selected_value == "medium")
            {
                $('#form_preview').css('width', '70%');
                $('#success_preview').css('width', '70%');
            }
            else
            {
                $('#form_preview').css('width', '100%');
                $('#success_preview').css('width', '100%');
            }
        });

        $('#border_style').change(function(){
            $('#form_preview, #success_preview, #question_preview, #subscribe_preview').css('border-style', $(this).val());
        });

        $("#border_size").bind('input', function(){
            $('#form_preview, #success_preview, #question_preview, #subscribe_preview').css('border-width', $(this).val()  + 'px');
        });

        $("#border_color").bind('input', function(){
            $('#form_preview, #success_preview, #question_preview, #subscribe_preview').css('border-color', $(this).val());
        });

        $("#button_font_size").bind('input', function(){
            $('#submit_button, #start_button, .question_btn, #subscribe_btn, #skip_btn, #start_action_button').css('font-size', $(this).val()  + 'px');
        });

        $('#button_font_family').change(function(){
            $('#submit_button, #start_button, .question_btn, #subscribe_btn, #skip_btn, #start_action_button').css('font-family', $(this).val());
        });

        $("#option_font_size").bind('input', function(){
            $('.option_label').css('font-size', $(this).val()  + 'px');
        });

        $('#option_font_family').change(function(){
            $('.option_label').css('font-family', $(this).val());
        });

        $('#option_color').bind('input', function(){
            $('.option_label').css('color', $(this).val());
        });

        $('#allow_closing').change(function(){
            if($('#allow_closing').is(':checked'))
            {
                $('.close_icon').show();
            }
            else
            {
                $('.close_icon').hide();
            }
        });

        $('.ui.dropdown').dropdown();
   
        $('#add_rule').on('click', function(){
            $('#rules').append('<div class="ui segment"><form class="rule_form" class="ui form"><div class="field"><select name="show" class="ui dropdown"><option value="DONT SHOW ON" selected>Dont  show on</option><option value="ONLY SHOW ON">Only show on</option></select></div><div class="field"><select name="match" class="ui dropdown"><option value="URLS CONTAINING" selected>URLs containing</option><option value="URLS EXACTLY MATCHING">URLs exactly matching</option></select></div><div class="field"><input type="text" placeholder="my-sample-page" name="page_name" required /></div><button type="submit" class="ui green button submit_rule_button">Done</button><button class="ui button delete_rule">Delete</button></form></div>');
        });

        $(document).delegate('.submit_rule_button', 'click', function(e){
            e.preventDefault();
            var currentContainer = $(this).closest("div");
            var fdata = $(this).closest("form").serialize();
            fdata += '&form_id='+'{{ $form->id }}';
            $.ajax({
                url: '{{ url("addrule") }}',
                method: 'POST',
                data: fdata,
                headers: {'X-CSRF-Token': '{{ csrf_token() }}'},
                success: function(data){
                    currentContainer.remove();
                    $('#form_rules').append('<div class="ui tiny message"><a id="create_form_icon" href="{{ url("delete/rule")."/"}}'+ data['id'] +'" class="right floated ui icon button"><i class="delete icon"></i></a><div class="header">'+ data["show"] + ' ' + data["match"] + '</div><div>'+ data["page_name"] +'</div></div>');
                },
                error: function(data){
                    console.log("An error occurred");
                }
            });
        });
        $(document).delegate('.delete_rule', 'click', function(e){
            e.preventDefault();
            var currentContainer = $(this).closest("div");
            $.ajax({
                url : $(this).attr('href'),
                method: 'GET',
                success: function(){
                    currentContainer.remove();
                },
                error: function(data){
                    console.log('An error occurred');
                }
            });
        });

        $('#add_question').on('click', function(){
            // $('#new_questions').append('<div><form class="ui form"><div class="ui fluid action input"><input name="title" type="text" placeholder="Please enter your question here" required  /><button type="submit" class="ui icon button save_question"><i class="check icon"></i></button><button class="ui icon button delete_question"><i class="delete icon"></i></button></div></form></div>');
            $('#new_questions').append('<div class="ui segment"><form class="ui form"><div class="field"><label>Title</label><input name="title" type="text" placeholder="Please enter your question here" required /></div><div class="field"><label>Option(s)  Type</label><select name="options_type" class="ui dropdown" required><option value="text">Text</option><option value="image">Image</option></select></div><button class="ui button save_question" type="submit">Submit</button><button type="button" class="ui button delete_question">Cancel</button></form></div>');
        });

        $(document).delegate('.delete_question', 'click', function(e){
            e.preventDefault();
            var currentContainer = $(this).closest("div");
            $.ajax({
                url : $(this).attr('href'),
                method: 'GET',
                success: function(){
                    currentContainer.remove();
                },
                error: function(data){
                    console.log('An error occurred');
                }
            });
        });

        $(document).delegate('.save_question', 'click', function(e){
            e.preventDefault();
            var currentContainer = $(this).closest("div");
            var fdata = $(this).closest("form").serialize();
            fdata += '&form_id='+'{{ $form->id }}';
            $.ajax({
                url: '{{ url("addquestion") }}',
                method: 'POST',
                data: fdata,
                headers: {'X-CSRF-Token': '{{ csrf_token() }}'},
                success: function(data){
                    currentContainer.remove();
                    $('#stored_questions').append('<div class="ui tiny message question_div"><a  href="{{ url("delete/question")."/"}}'+ data['id'] +'" class="right floated ui icon button delete_question"><i class="delete icon"></i></a><div class="header">'+ data["title"] +  '</div><br/><a data-questionid="' + data['id']  + '" data-optiontype="' + data['options_type'] + '" href="#" class="add_option"><i class="plus icon"></i>Add Options</a><ul class="stored_options"></ul><div class="new_options"></div></div>');
                },
                error: function(data){
                    console.log("An error occurred");
                }
            });
        });

        $(document).delegate('.delete_question', 'click', function(e){
            e.preventDefault();
            var currentContainer = $(this).closest("div");
            $.ajax({
                url : $(this).attr('href'),
                method: 'GET',
                success: function(){
                    currentContainer.remove();
                },
                error: function(data){
                    console.log('An error occurred');
                }
            });
        });

        $(document).delegate('.delete_option_form', 'click', function(e){
            e.preventDefault();
            var currentContainer = $(this).closest(".option_form");
            currentContainer.remove();
        });

        $(document).delegate('.add_option', 'click', function(e){
            e.preventDefault();
            var question_id = $(this).data('questionid');
            var options_type = $(this).data('optiontype');
            var optionInput = "";
            if(options_type == 'image')
            {
                optionInput = '<div class="field"><label>Image</label><input name="option_image" type="file" accept="image/*" required /></div>';
            }
            else
            {
                optionInput = '<div class="field"><label>Title</label><input name="title" type="text" placeholder="Enter your option here" required/></div>';
            }
            var currentContainer = $(this).closest("div").find(".new_options");
            currentContainer.append('<div class="option_form" ><form class="ui form" method="post" enctype="multipart/form-data"><input type="hidden" name="question_id" value="' + question_id +'" />' + optionInput + '<button type="submit" class="ui icon button save_option"><i class="check icon"></i>Submit</button><button class="ui icon button delete_option_form"><i class="delete icon"></i>Cancel</button></form></div>');
        });

        $(document).delegate('.save_option', 'click', function(e){
            e.preventDefault();
            var currentContainer = $(this).closest("div");
            var options = $(this).closest(".message").find('.stored_options');
            var fdata = $(this).closest("form");
            var formData = new FormData(fdata[0]);
            console.log(fdata);
            $.ajax({
                url: '{{ url("addoption") }}',
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                data: formData,
                headers: {'X-CSRF-Token': '{{ csrf_token() }}'},
                success: function(data){
                    console.log("success");
                    options.append('<li>' + data["title"] + ' <a class="delete_option" href="{{ url("delete/option")."/"}}' + data["id"] + '"><i class="delete icon"></i></a></li>');
                    currentContainer.remove();
                },
                error: function(data){
                    console.log("An error occurred");
                }
            });
        });

        $(document).delegate('.delete_option', 'click', function(e){
            e.preventDefault();
            var currentContainer = $(this).closest("li");
            $.ajax({
                url : $(this).attr('href'),
                method: 'GET',
                success: function(){
                    currentContainer.remove();
                },
                error: function(data){
                    console.log('An error occurred');
                }
            });
        });

        $('.progress').progress();

        $(document).delegate('.question_div', 'click', function(){
            var question = $(this).find('.header').text();
            var options = $(this).find('.stored_options').children();
            $('#option_preview').empty();
            $('#question_header').text(question);
            $.each(options, function(index, value){
                $('#option_preview').append('<div class="ui radio checkbox field"><input type="radio" name="radio"><label class="option_label">' + $(value).text() + '</label></div>');
            });

            $('.option_label').css('font-size', $('#option_font_size').val()  + 'px');
            $('.option_label').css('color', $('#option_color').val());
            $('.option_label').css('font-family', $("#option_font_family").val());

            $('#form_preview, #question_preview, #subscribe_preview').hide();
            $('#question_preview').show();
        });

        $('#bg_image_btn').on('click', function(e){
            e.preventDefault();
            $('#bg_image_input').click();
        });

        $('#bg_image_input').on('change', function(){
            var file_data = $('#bg_image_input').prop('files')[0];
            var form_data = new FormData();
            form_data.append('file', file_data);
            form_data.append('form_id', '{{ $form->id }}');
            $.ajax({
                url: '{{ url("form/uploadbgimage") }}',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                headers: {'X-CSRF-Token': '{{ csrf_token() }}'},
                type: 'post',
                success: function(response){
                    $('.background_image').css('background-image','url(' + '{{ url("") }}' + '/' + response + ')');
                    $('.background_image').css('background-size', 'cover');
                    $('.background_image').css('background-position', 'center');
                },
                error: function(response){
                    console.log("error");
                }
            })
        });

        $('#remove_image_btn').on('click', function(e){
            e.preventDefault();
            $.ajax({
                url: '{{ url("form/removebgimage")."/".$form->id }}',
                type: 'get',
                success: function(response){
                    $('.background_image').css('background-image','none');
                },
                error: function(response){
                    console.log("error");
                }
            });
        });
    </script>
@endsection