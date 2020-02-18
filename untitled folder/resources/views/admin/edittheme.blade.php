@extends('layouts.admin')
@section('title', 'Sendmunk | Edit Theme')

@section('content')
    <div class="ui vertical segment">
        <h2>Edit Theme</h2>    
    </div>
    <br/>
    <div id="form_navbar" class="ui pointing secondary stackable menu">
        <a class="item active" data-tab="appearance">Appearance</a>
        <div class="item">
            <button id="update_theme_btn" type="submit" form="update_theme" class="publish ui primary button">Save Theme</button>
        </div>
    </div>
    <br/>
    <div class="ui stackable grid">
        <div class="formview six wide column">
            <form id="update_theme" action="{{ url('admin/updatetheme') }}" class="ui form" method="post" role="form">
                {{ csrf_field() }}
                <input type="hidden" name="form_id" value="{{ $theme->id }}" />
                <div class="ui tab active" data-tab="appearance">
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
                                    <input name="background_color" id="bg_color_input" type="color" value="{{ $theme->background_color }}" />
                                </div>
                            </div>
                            @if($theme->poll_type == 'popover')
                            <div class="field">
                                <label>Background Overlay</label>
                                <input name="background_overlay" id="bg_color_overlay" type="color" value="{{ $theme->background_overlay }}" />                 
                            </div>
                            @endif
                            <h4>Submit Button</h4>
                            <div class="two fields">
                                <div class="field">
                                    <label>Background</label>
                                    <input name="button_color" id="bg-color_button" type="color" value="{{ $theme->button_color }}" />
                                </div>
                                <div class="field">
                                    <label>Text</label>
                                    <input name="button_text_color" id="bg-color_text" type="color" value="{{ $theme->button_text_color }}" />
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
                                    <input id="border_size" name="border_size" type="number" value="{{ $theme->border_size }}"/>
                                    <div class="ui basic label">px</div>
                                </div></div>
                            </div>
                            <div class="inline fields">
                                <label>Border Color</label>
                                <input name="border_color" type="color" id="border_color" value="{{ $theme->border_color }}" />
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
                                        <input id="button_font_size" name="button_font_size" type="number" value="{{ $theme->button_font_size }}" />
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
                    </div>
                </div>
            </form>
        </div>
        <div id="content_preview" class="formview ten wide column" style="background: grey;">
            {!! $theme->content !!}
        </div>
    </div>
    <br/><br/>
@endsection

@section('footerscripts')
    <script>
        $("#border_style").val('{{ $theme->border_style }}').change();
        $("#button_font_family").val('{{ $theme->button_font_family }}').change();

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
            $('#start_button, .question_btn, #skip_btn, #subscribe_btn, #submit_button').css('background', $('#bg-color_button').val());
        });
        $('#bg-color_text').bind('input', function(){
            $('#start_button, .question_btn, #skip_btn, #subscribe_btn, #submit_button').css('color', $('#bg-color_text').val());
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


        $('.ui.dropdown').dropdown();

        $('#update_theme').submit(function(e){
            e.preventDefault();
            
            $('#update_theme_btn').prop('disabled', true);
            $("#update_theme_btn").addClass("loading");

            var fdata = $(this);
            var form_data = new FormData(fdata[0]);
            form_data.append('theme_id', '{{ $theme->id }}');
            form_data.append('content', $('#content_preview').html());

            $.ajax({
                url: '{{ url("admin/updatetheme") }}',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                headers: {'X-CSRF-Token': '{{ csrf_token() }}'},
                type: 'post',
                success: function(response){
                    $('#update_theme_btn').prop('disabled', false);
                    $("#update_theme_btn").removeClass("loading");
                    window.location.href = "{{ url('admin/themes') }}";
                },
                error: function(response){
                    console.log("error");
                    $('#update_theme_btn').prop('disabled', false);
                    $("#update_theme_btn").removeClass("loading");
                }
            })

        });
    </script>
@endsection