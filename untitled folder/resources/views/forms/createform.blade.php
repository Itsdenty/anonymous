@extends('layouts.user')
@section('title', 'Sendmunk | Forms')

@section('styles')
    <style>
    .appearance {
        background: #fff;
    }
    #submit_button {
        background: #fff !important;
    }
    #submit_button:hover {
        background: #ff163f !important;
        color: #fff !important;
    }

    </style>
@endsection

@section('content')
    <div class="ui vertical segment">
        <h2>Create Form</h2>
    </div>
    <br/>
    <div id="form_navbar" class="ui pointing secondary stackable menu">
        <a class="item active" data-tab="themes">Themes</a>
        <a class="item" data-tab="message">Message</a>
        <a class="item" data-tab="appearance">Appearance</a>
        <a class="item" data-tab="fields">Fields</a>
        <a class="item" data-tab="behavior">Behavior</a>
        <div class="item">
            <button type="submit" form="update_form" class="publish ui primary button">Publish Form</button>
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
                        <select id="form_theme" name="theme_id" class="ui dropdown">
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
                        <textarea class="summernote" name="headline" id="headline_input">Join Our Newsletter</textarea>
                    </div>
                    @if($form->form_type == 'scrollbox' || $form->form_type == 'popover' || $form->form_type == 'embedded' || $form->form_type == 'welcome_mat')
                    <div class="field" data-control="summernote-container">
                        <label>Description</label>
                        <textarea class="summernote" name="description" id="description_input">Signup today for free and be the first to get notified on new updates.</textarea>
                    </div>
                    <div class="field" data-control="summernote-container">
                        <label>Foot Note</label>
                        <textarea class="summernote" name="foot_note" id="footnote_input">And don't worry, we hate spam too! you can unsubscribe at anytime</textarea>
                    </div>
                    @endif
                    <h4>Thank You Message</h4>
                    <div class="field" data-control="summernote-container">
                        <label>Success Screen Headline</label>
                        <textarea class="summernote" name="success_headline" id="success_headline_input">Thank you</textarea>
                    </div>
                    @if($form->form_type == 'scrollbox' || $form->form_type == 'popover' || $form->form_type == 'embedded' || $form->form_type == 'welcome_mat')
                    <div class="field" data-control="summernote-container">
                        <label>Success Screen Message</label>
                        <textarea class="summernote" name="success_description" id="success_description_input">Thank you for subscribing with us.</textarea>
                    </div>
                    @endif
                </div>
                <div class="ui tab" data-tab="appearance">
                    <div class="ui styled accordion">
                        <div class="active title appearance">
                            <i class="dropdown icon"></i>
                            Color
                        </div>
                        <div class="active content appearance">
                            <h4>Theme</h4>
                            <div class="two fields">
                                <div class="field">
                                    <label>Background Color</label>
                                    @if($form->form_type == 'scrollbox' || $form->form_type == 'popover' || $form->form_type == 'embedded' || $form->form_type == 'welcome_mat')
                                    <input name="background_color" id="bg_color_input" type="color" value="{{$form->background_color}}" />
                                    @endif
                                    @if($form->form_type == 'topbar')
                                    <input name="background_color" id="bg_color_input" type="color" value="{{$form->background_color}}" />
                                    @endif
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
                                    @if($form->form_type == 'scrollbox' || $form->form_type == 'popover' || $form->form_type == 'embedded' || $form->form_type == 'welcome_mat')
                                    <input name="button_color" id="bg-color_button" type="color" value="{{ $form->button_color }}" />
                                    @endif
                                    @if($form->form_type == 'topbar')
                                    <input name="button_color" id="bg-color_button" type="color" value="{{ $form->button_color }}" />
                                    @endif
                                </div>
                                <div class="field">
                                    <label>Text</label>
                                    <input name="button_text_color" id="bg-color_text" type="color" value="{{$form->button_text_color}}" />
                                </div>
                            </div>
                        </div>
                        <div class="title appearance">
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
                                    <input id="border_size" name="border_size" type="number" />
                                    <div class="ui basic label">px</div>
                                </div></div>
                            </div>
                            <div class="inline fields">
                                <label>Border Color</label>
                                <input name="border_color" type="color" id="border_color" />
                            </div>
                        </div>
                        <div class="title appearance">
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
                        @if($form->form_type == 'scrollbox' || $form->form_type == 'topbar')
                        <div class="title appearance">
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
                        <div class="title appearance">
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
                <div class="ui tab" data-tab="fields">
                    {{-- <a href="#" class="right floated ui button">Add New Field</a> --}}
                    <h4>Form Fields</h4>
                    <div class="ui styled accordion">
                        <div class="active title appearance">
                            <i class="dropdown icon"></i>
                            Email
                        </div>
                        <div class="active content appearance">
                            <div class="field">
                                <label>LABEL</label>
                                <input name="email_label" id="email_label_input" type="text" value="Email" />
                            </div>
                            <div class="field">
                                <label>PLACEHOLDER TEXT</label>
                                <input name="email_placeholder" id="email_input" type="text" value="Enter your email" />
                            </div>
                        </div>
                        <div class="title appearance">
                            <i class="dropdown icon"></i>
                            Phone
                        </div>
                        <div class="content appearance">
                            <div class="field">
                                <div class="ui toggle checkbox">
                                    <input type="checkbox" id="show_phone_field" name="show_phone_field" />
                                    <label>Show Phone Field</label>
                                </div>
                            </div>
                            <div class="field">
                                <label>LABEL</label>
                                <input name="phone_label" id="phone_label_input" type="text" value="Phone" />
                            </div>
                            <div class="field">
                                <label>PLACEHOLDER TEXT</label>
                                <input name="phone_placeholder" id="phone_input" type="text" value="Enter your phone no." />
                            </div>
                        </div>
                    </div>
                    <h4>Button Text</h4>
                    <div class="field">
                        <input name="button_text" id="submit_button_input" type="text" value="Subscribe" />
                    </div>
                </div>
                <div class="ui tab" data-tab="behavior">
                    <div class="ui styled accordion">
                        @if($form->form_type == 'scrollbox' || $form->form_type == 'popover')
                        <div class="active title appearance">
                            <i class="dropdown icon"></i>
                            When to Show
                        </div>
                        <div class="active content appearance">
                            @if($form->form_type == 'scrollbox')
                            <h4>TRIGGER POINT</h4>
                            <p>Show when a user has scrolled selected percent (%) of your page</p>
                            <div class="field">
                                <div class="ui right labeled input">
                                    <input name="trigger_point" value="0" type="number" min="0" max="100" />
                                    <div class="ui basic label">%</div>
                                </div>
                            </div>
                            <h4>HIDE DURATION</h4>
                            <p>After a user closes the box, how many days should it stay hidden?</p>
                            <div class="field">
                                <div class="ui right labeled input">
                                    <input name="hide_duration" type="number" min="0" />
                                    <div class="ui basic label">days</div>
                                </div>
                            </div>
                            <h4>AUTO HIDE</h4>
                            <div class="ui toggle checkbox">
                                <input type="checkbox" name="autohide">
                                <label>Hide box again when visitor scrolls back up</label>
                            </div>
                            @endif
                            @if($form->form_type == 'popover')
                            <div class="field">
                                <div class="ui toggle checkbox">
                                    <input type="checkbox" name="page_load" checked />
                                    <label>SHOW ON PAGE LOAD</label>
                                </div>
                            </div>
                            <div class="field">
                                <div class="ui toggle checkbox">
                                    <input type="checkbox" name="page_exit" />
                                    <label>SHOW ON EXIT</label>
                                </div>
                            </div>
                            <div class="field">
                                <div class="ui toggle checkbox">
                                    <input type="checkbox" name="first_visit" checked />
                                    <label>SHOW ON FIRST VISIT</label>
                                </div>
                            </div>
                            <h4>LOADING DELAY</h4>
                            <p>This is how long the page should wait before showing the popup (defaults to 0 seconds for no delay).</p>
                            <div class="field">
                                <div class="ui right labeled input">
                                    <input name="loading_delay" value="3" type="number" min="0" />
                                    <div class="ui basic label">Seconds</div>
                                </div>
                            </div>
                            <h4>FREQUENCY</h4>
                            <p>Dont show the popup to the same visitor again until this many days have passed (defaults to 7 days).</p>
                            <div class="field">
                                <div class="ui right labeled input">
                                    <input name="frequency" value="7" type="number" min="0" />
                                    <div class="ui basic label">Days</div>
                                </div>
                            </div>
                            @endif
                        </div>
                        @endif
                        @if($form->form_type == 'topbar')
                        <div class="active title appearance">
                            <i class="dropdown icon"></i>
                            Closing The Bar
                        </div>
                        <div class="active content appearance">
                            <h4>ALLOW CLOSING</h4>
                            <div class="ui toggle checkbox">
                                <input id="allow_closing" type="checkbox" name="allow_closing" checked />
                                <label>Yes - Allow users to close the bar</label>
                            </div>
                            <h4>LOADING DELAY</h4>
                            <p>This is how long the page should wait before showing the Topbar (defaults to 0 seconds for no delay).</p>
                            <div class="field">
                                <div class="ui right labeled input">
                                    <input name="loading_delay" value="0" type="number" min="0" value="{{ $form->loading_delay }}" />
                                    <div class="ui basic label">Seconds</div>
                                </div>
                            </div>
                            <h4>HIDE DURATION</h4>
                            <p>After a user closes the box, how many days should it stay hidden?</p>
                            <div class="field">
                                <div class="ui right labeled input">
                                    <input name="hide_duration" type="number" min="0" />
                                    <div class="ui basic label">days</div>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="title appearance">
                            <i class="dropdown icon"></i>
                            After User Subscribes
                        </div>
                        <div class="content appearance">
                            <h4>REDIRECT ON SUBSCRIBE (OPTIONAL)</h4>
                            <p>Specify a URL to redirect to after a visitor has successfully subscribed</p>
                            <div class="field">
                                <input name="redirect_url" type="url" placeholder="http://example.com/example" />
                            </div>
                        </div>
                        <div class="title appearance">
                            <i class="dropdown icon"></i>
                            Display Rules
                        </div>
                        <div class="content appearance">
                            <p>Which devices do you want the form to show on?</p>
                            <div class="field">
                                <div class="ui toggle checkbox">
                                    <input type="checkbox" name="desktop_device" checked />
                                    <label>Desktop Devices</label>
                                </div>
                            </div>
                            <div class="field">
                                <div class="ui toggle checkbox">
                                    <input type="checkbox" name="mobile_device" checked />
                                    <label>Mobile Devices</label>
                                </div>
                            </div>
                            <div class="field">
                                <div class="ui toggle checkbox">
                                    <input type="checkbox" name="tablet_device" checked />
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
                        @if(!$user->is_stripe_user)
                            @if($user->subplan)
                            @if($user->subplan->fe == 2 || $user->subplan->oto1 == 1 || $user->subplan->oto2 == 1 || $user->subplan->oto3 == 1 || $user->subplan->oto4 == 1)
                            <div class="title appearance">
                                <i class="dropdown icon"></i>
                                Tracking Pixel
                            </div>
                            <div class="content appearance">
                                <p>You can paste IMG, IFRAME, or SCRIPT tag pixel.</p>
                                <p><strong>VIEW PIXEL</strong></p>
                                <p>This pixel is fired when visitor is shown this form</p>
                                <div class="field">
                                <textarea name="tracking_pixel" rows="2"></textarea>
                                </div>
                            </div>
                            @endif
                            @endif
                        @else
                        <div class="title appearance">
                            <i class="dropdown icon"></i>
                            Tracking Pixel
                        </div>
                        <div class="content appearance">
                            <p>You can paste IMG, IFRAME, or SCRIPT tag pixel.</p>
                            <p><strong>VIEW PIXEL</strong></p>
                            <p>This pixel is fired when visitor is shown this form</p>
                            <div class="field">
                            <textarea name="tracking_pixel" rows="2"></textarea>
                            </div>
                        </div>
                        @endif
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

            $('#form_preview').css('background', $('#bg_color_input').val());
            $('#success_preview').css('background', $('#bg_color_input').val());
            $('#content_preview').css('background', $('#bg_color_overlay').val());
            $('#submit_button').css('background', $('#bg-color_button').val());
            $('#submit_button').css('color', $('#bg-color_text').val());
            $('#email').attr('placeholder', $('#email_input').val());
            $('#email_label').text($('#email_label_input').val());
            $('#submit_button').text($('#submit_button_input').val());
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
            $('#submit_button').css('background', $('#bg-color_button').val());
        });
        $('#bg-color_text').bind('input', function(){
            $('#submit_button').css('color', $('#bg-color_text').val());
        });
        $('#email_input').bind('input', function(){
            $('#email').attr('placeholder', $('#email_input').val());
        });
        $('#email_label_input').bind('input', function(){
            $('#email_label').text($('#email_label_input').val());
        });
        $('#phone_input').bind('input', function(){
            $('#phone').attr('placeholder', $('#phone_input').val());
        });
        $('#phone_label_input').bind('input', function(){
            $('#phone_label').text($('#phone_label_input').val());
        });
        $('#show_phone_field').change(function(){
            if($(this).prop('checked') == true)
            {
                $('#phone_field').css('display', 'block');
            }
            else
            {
                $('#phone_field').css('display', 'none');
            }
        });
        $('#submit_button_input').bind('input', function(){
            $('#submit_button').text($('#submit_button_input').val());
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

         $('#border_style').change(function(){
            $('#form_preview').css('border-style', $(this).val());
            $('#success_preview').css('border-style', $(this).val());
        });

        $("#border_size").bind('input', function(){
            $('#form_preview').css('border-width', $(this).val()  + 'px');
            $('#success_preview').css('border-width', $(this).val()  + 'px');
        });

        $("#border_color").bind('input', function(){
            $('#form_preview').css('border-color', $(this).val());
            $('#success_preview').css('border-color', $(this).val());
        });

        $("#button_font_size").bind('input', function(){
            $('#submit_button, #start_button, .question_btn, #subscribe_btn, #skip_btn, #start_action_button').css('font-size', $(this).val()  + 'px');
        });

        $('#button_font_family').change(function(){
            $('#submit_button, #start_button, .question_btn, #subscribe_btn, #skip_btn, #start_action_button').css('font-family', $(this).val());
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

        $('#form_theme').on('change', function() {
            var current_theme_id = $(this).val();
            $.ajax({
                url: "{{ url('settheme') }}",
                method: "POST",
                data: {"theme_id":current_theme_id, "form_id": '{{ $form->id }}', _token: '{{ csrf_token() }}'},
                success: function (data){
                    location.reload(true);
                }
            });
        });
    </script>
@endsection
