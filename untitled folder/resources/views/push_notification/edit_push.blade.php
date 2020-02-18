@extends('layouts.user')
@section('title', 'Sendmunk | Edit Push Popup')

@section('content')
    <div class="ui vertical segment">
        <h2>Edit Push Popup</h2>    
    </div>
    <br/>
    <div id="form_navbar" class="ui pointing secondary stackable menu">
        <a class="item active" data-tab="themes">Themes</a>
        <a class="item" data-tab="message">Message</a>
        <a class="item" data-tab="appearance">Appearance</a>
        <a class="item" data-tab="behavior">Behavior</a>

        <div class="item">
            <button type="submit" form="update_form" class="publish ui primary button">Publish</button>
        </div>
    </div>
    <br/>
    <div class="ui stackable grid">
        <div class="formview six wide column">
            <form id="update_form" action="{{ url('updateform') }}" class="ui form" method="post" role="form">
                {{ csrf_field() }}
                <input type="hidden" name="form_id" value="{{ $form->id }}" />
                <div class="ui tab  active" data-tab="themes">
                    <div class="field">
                        <label>Choose Theme</label>
                        <select name="theme_id" class="ui dropdown">
                            @foreach($themes as $theme)
                            <option value="{{ $theme->id }}" {{ $theme->id==$current_theme->id ? 'selected' : '' }}>{{ $theme->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="ui tab" data-tab="message">
                    <h4>Theme</h4>
                    <div class="field" data-control="summernote-container">
                        <label>Headline</label>
                        <textarea class="summernote" name="headline" id="headline_input">{{ $form->headline }}</textarea>
                    </div>
                    
                    <div class="field" data-control="summernote-container">
                        <label>Description</label>
                        <textarea class="summernote" name="description" id="description_input">{{ $form->description }}</textarea>
                    </div>

                    <div class="field">
                        <label>Button Text</label>
                        <input name="button_text" id="start_action_button_input" type="text" value="{{ $form->button_text }}" />
                    </div>

                    <div class="field">
                        <label>Your Subdomain</label>
                        {{-- <input name="redirect_url" id="redirect_url" type="text" value="{{'https://' . $form->title . '.Sendmunkapp.com'}}" readonly/> --}}
                    @if(app()->environment() === "production")
                    <input name="redirect_url" id="redirect_url" type="text" value="{{ 'https://' . $form->title . '.sendmunk.com/notif/' . $form->id}}" readonly/>
                    @else
                    <input name="redirect_url" id="redirect_url" type="text" value="{{ 'http://' . $form->title . '.localhost:8000/notif/' . $form->id}}" readonly/>
                    @endif

                    </div>
                </div>
                <div class="ui tab" data-tab="appearance">
                    <div class="ui styled accordion" style="background:#fff !important;">
                        <div class="active title">
                            <i class="dropdown icon"></i>
                            Color
                        </div>
                        <div class="active content">
                            <h4>Theme</h4>
                            <div class="two fields">
                                <div class="field">
                                    <label>Background Color</label>
                                    {{-- @if($form->form_type == 'scrollbox' || $form->form_type == 'popover') --}}
                                    <input name="background_color" id="bg_color_input" type="color" value="{{ $form->background_color }}" />
                                    {{-- @endif --}}
                                </div>
                                <div class="field">
                                    <label>Background Image</label>
                                    <button id="bg_image_btn" type="button"><i class="upload icon"></i></button>
                                    <input  name="background_image" id="bg_image_input" type="file" hidden />
                                    <button id="remove_image_btn" type="button"><i class="remove icon"></i></button>
                                </div>
                            </div>
                            @if($form->form_type == 'popover')
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
                        @if($form->form_type == 'scrollbox')
                        <div class="title">
                            <i class="dropdown icon"></i>
                            Position
                        </div>
                        <div class="content">
                            @if($form->form_type == 'scrollbox')
                            <p>Do you want the scroll box to appear on left or right side of the screen?</p>
                            <input type="radio" name="position" id="left" value="left" />
                            <label for="left">Left</label>
                            &nbsp;&nbsp;&nbsp;
                            <input type="radio" name="position" id="right" value="right" checked />
                            <label for="right">Right</label>
                            @endif
                            @if($form->form_type == 'topbar')
                            <input type="radio" name="position" id="top" value="top" checked />
                            <label for="top">Top of Page</label>
                            &nbsp;&nbsp;&nbsp;
                            <input type="radio" name="position" id="bottom" value="bottom" />
                            <label for="bottom">Bottom of Page</label>
                            @endif
                        </div>
                        @endif
                        @if($form->form_type == 'popover')
                        <div class="title">
                            <i class="dropdown icon"></i>
                            Size
                        </div>
                        <div class="content">
                            <p>What would you like the size of this popup to be?</p>
                            <input type="radio" name="size" id="small_size" value="small" />
                            <label for="small_size">Small</label>&nbsp;&nbsp;&nbsp;
                            <input type="radio" name="size" id="medium_size" value="medium" checked />
                            <label for="medium_size">Medium</label>&nbsp;&nbsp;&nbsp;
                            <input type="radio" name="size" id="large_size" value="large" />
                            <label for="large_size">Large</label>
                        </div>
                        @endif
                    </div>
                </div>
                
                <div class="ui tab" data-tab="behavior">
                    <div class="ui styled accordion" style="background:#fff !important;">
                        @if($form->poll_type == 'popover')
                        <div class="active title">
                            <i class="dropdown icon"></i>
                            When to Show
                        </div>
                        <div class="active content">
                            @if($form->poll_type == 'popover')
                            <div class="field">
                                <div class="ui toggle checkbox">
                                    <input type="checkbox" name="page_load" {{ ($form->page_load) ? "checked" : "" }} />
                                    <label>SHOW ON PAGE LOAD</label>
                                </div>
                            </div>
                            <div class="field">
                                <div class="ui toggle checkbox">
                                    <input type="checkbox" name="page_exit" {{ ($form->page_exit) ? "checked" : "" }} />
                                    <label>SHOW ON EXIT</label>
                                </div>
                            </div>
                            <div class="field">
                                <div class="ui toggle checkbox">
                                    <input type="checkbox" name="first_visit" {{ ($form->first_visit) ? "checked" : "" }} />
                                    <label>SHOW ON FIRST VISIT</label>
                                </div>
                            </div>
                            <h4>LOADING DELAY</h4>
                            <p>This is how long the page should wait before showing the popup (defaults to 0 seconds for no delay).</p>
                            <div class="field">
                                <div class="ui right labeled input">
                                    <input name="loading_delay" value="{{ $form->loading_delay }}" type="number" min="0" />
                                    <div class="ui basic label">Seconds</div>
                                </div>
                            </div>
                            <h4>FREQUENCY</h4>
                            <p>Dont show the popup to the same visitor again until this many days have passed (defaults to 7 days).</p>
                            <div class="field">
                                <div class="ui right labeled input">
                                    <input name="frequency" value="{{ $form->frequency }}" type="number" min="0" />
                                    <div class="ui basic label">Days</div>
                                </div>
                            </div>
                            @endif
                        </div>
                        @endif
                        <div class="title">
                            <i class="dropdown icon"></i>
                            Display Rules
                        </div>
                        <div class="content">
                            <p>Which devices do you want the form to show on?</p>
                            <div class="field">
                                <div class="ui toggle checkbox">
                                    <input type="checkbox" name="desktop_device" {{ ($form->desktop_device) ? "checked" : "" }} />
                                    <label>Desktop Devices</label>
                                </div>
                            </div>
                            <div class="field">
                                <div class="ui toggle checkbox">
                                    <input type="checkbox" name="mobile_device" {{ ($form->mobile_device) ? "checked" : "" }} />
                                    <label>Mobile Devices</label>
                                </div>
                            </div>
                            <div class="field">
                                <div class="ui toggle checkbox">
                                    <input type="checkbox" name="tablet_device" {{ ($form->tablet_device) ? "checked" : "" }} />
                                    <label>Tablet Devices</label>
                                </div>
                            </div>
                            <p>Which pages do you want the form to show on?</p>
                            <small>If no rules are added, it will show on all pages.</small>
                            <div id="form_rules">
                                @if(!$rules->isempty())
                                    @foreach($rules as $rule)
                                    <div class="ui tiny message">
                                        <a href="{{ url('delete/rule').'/'.$rule->id }}" class="right floated ui icon button delete_rule"><i class="delete icon"></i></a>
                                        <div class="header">{{ $rule->show }} {{ $rule->match }}</div>
                                        <div>{{ $rule->page_name }}</div>
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                            <div id="rules"></div>
                            <br/>
                            <div id="display_rule_div">
                                <a href="#" id="add_rule" class="ui primary button">New Display Rule</a>
                            </div>
                        </div>
                    </div>
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
            $('#content_preview').css('background', $('#bg_color_overlay').val());

            $('#start_action_button').text($('#start_action_button_input').val());
            $('.background_image').css('background-image','{{ $form->background_image == null ? "none" : "url(" . url("")."/".$form->background_image .")" }}');
            $('.background_image').css('background-size', 'cover');
            $('.background_image').css('background-position', 'center');
        });

        $('#success_preview').hide();

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
        });
        $('#bg_color_overlay').bind('input', function(){
            $('#content_preview').css('background', $('#bg_color_overlay').val());
        });
        
        
        $('#bg-color_button').bind('input', function(){
            $('#start_action_button').css('background', $('#bg-color_button').val());
        });
        $('#bg-color_text').bind('input', function(){
            $('#start_action_button').css('color', $('#bg-color_text').val());
        });

        $('#email_input').bind('input', function(){
            $('#email').attr('placeholder', $('#email_input').val());
        });
        $('#email_label_input').bind('input', function(){
            $('#email_label').text($('#email_label_input').val());
        });
        $('#start_action_button_input').bind('input', function(){
            $('#start_action_button').text($('#start_action_button_input').val());
        });

        $('#success_headline_input, #success_description_input').bind('blur', function(){
            $('#success_preview').hide();
            $('#form_preview').show();
        });
        $('#success_headline_input, #success_description_input').bind('focus', function(){
            $('#form_preview').hide();
            $('#success_preview').show();
        });

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
                    console.log(data['form_id']);
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