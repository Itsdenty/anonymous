<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        @if($form->background_image)
        <meta property="og:image" content="{{ url($form->background_image) }}">
        <meta property="og:image:width" content="560">
        <meta property="og:image:height" content="315">
        @endif

        <link rel="icon" href='{{ url("favicon.ico") }}'/>
        <link rel="stylesheet" type="text/css" href='{{ url("Semantic-UI-CSS-master/semantic.min.css") }}' />
        <link rel="stylesheet" type="text/css" href='{{ url("summernote/summernote-lite.css") }}' />
        <title>{{ strip_tags($form->headline) }}</title>
        <style>
            body{
                position : fixed;
                top : 0;
                left : 0;
                width : 100%;
                height: 100vh;
            }
            .content{
                width: 60%;
                height: 60%;
                position: absolute;
                top: 0;
                bottom: 0;
                left: 0;
                right: 0;
                margin: auto;
            }

            .close-popup{
                display: none !important;
            }
        </style>
    </head>
    <body>
        <div class="optin_block_page"></div>


        <script src="{{ asset("js/jquery-3.2.1.min.js") }}"></script>   
        <script src='{{ asset("Semantic-UI-CSS-master/semantic.min.js") }}'></script>
        <script>
            var formdata;

            $(document).on('submit','.optin_modal_box #form_preview',function(e){
                e.preventDefault();
                var fdata = $(this).serialize();
                fdata += '&form_id={{ $form->id }}';
                $.ajax({
                    url: '{{ url("api/v1/subscribe") }}',
                    method : 'post',
                    data : fdata,
                    headers: {'X-CSRF-Token': '{{ csrf_token() }}'},
                    success: function(data){
                        $('.optin_modal_box #form_preview').hide();
                        $('.optin_modal_box #success_preview').fadeIn();

                        if(formdata["redirect_url"])
                        {
                            window.location.href = formdata["redirect_url"];
                        }
                    },
                    error: function(data){
                        console.log('error');
                    }
                });
            });

            $.ajax({
                url: '{{ url("api/v1/formdata")."/".$form->id }}',       
                method: 'get',
                success: function(data){
                    var rules = JSON.parse(data.rules);
                    formdata = JSON.parse(data.form);
                    // var questions = JSON.parse(data.questions);
                    // var results = JSON.parse(data.results);
                    var questions = null;
                    var results = null;
                    // console.log(formdata);

                    var current_url = location.href;


                    add_popup_box(data.theme);
                    add_welcomemat_styles();
                

                    $('.optin_modal_box #form_preview').css('width', '100%');
                    $('.optin_modal_box #success_preview').css('width', '100%');
                    $('.optin_modal_box #question_preview').css('width', '100%');
                    $('.optin_modal_box #subscribe_preview').css('width', '100%');
                    $('.optin_modal_box #form_preview, .optin_modal_box #success_preview, .optin_modal_box #question_preview, .optin_modal_box #subscribe_preview').css('height', '100%');

                    
                    if(formdata['background_image'] != null)
                    {
                        $('.optin_modal_box .background_image').css('background-image',"url(" + "{{ url('/') }}" + "/" + formdata['background_image'] + ")");
                        $('.optin_modal_box .background_image').css('background-size', 'cover');				
                    }
                    $('.optin_modal_box #form_preview #headline').html(formdata['headline']);
                    $('.optin_modal_box #form_preview #description').html(formdata['description']);
                    $('.optin_modal_box #success_preview #success_headline').html(formdata['success_headline']);
                    $('.optin_modal_box #success_preview #success_description').html(formdata['success_description']);
                    $('.optin_modal_box #form_preview #start_action_button').attr("href", formdata['redirect_url']);
                    $('.optin_modal_box #form_preview #footnote').html(formdata['foot_note']);
                    $('.optin_modal_box #subscribe_preview #footnote').html(formdata['foot_note']);
                    $('.optin_modal_box #form_preview').css('background-color', formdata['background_color']);
                    $('.optin_modal_box #success_preview').css('background-color', formdata['background_color']);
                    $('.optin_modal_box #question_preview').css('background-color', formdata['background_color']);
                    $('.optin_modal_box #subscribe_preview').css('background-color', formdata['background_color']);
                    $('.optin_modal_box #form_preview, .optin_modal_box #success_preview, .optin_modal_box #question_preview, .optin_modal_box #subscribe_preview').css('border-style', formdata['border_style']);
                    $('.optin_modal_box #form_preview, .optin_modal_box #success_preview, .optin_modal_box #subscribe_preview, .optin_modal_box #question_preview').css('border-width', formdata['border_size'] + 'px');
                    $('.optin_modal_box #form_preview, .optin_modal_box #success_preview, .optin_modal_box #subscribe_preview, .optin_modal_box #question_preview').css('border-color', formdata['border_color']);
                    $('.optin_block_page').css('background', hexToRgb(formdata['background_overlay']));
                    $('.optin_modal_box #form_preview #submit_button').css('background', formdata['button_color']);
                    $('.optin_modal_box #form_preview #start_button').css('background', formdata['button_color']);
                    $('.optin_modal_box #form_preview #start_action_button').css('background', formdata['button_color']);

                    $('.optin_modal_box #question_preview .question_btn').css('background', formdata['button_color']);
                    $('.optin_modal_box #subscribe_preview #skip_btn').css('background', formdata['button_color']);
                    $('.optin_modal_box #subscribe_preview #subscribe_btn').css('background', formdata['button_color']);
                    $('.optin_modal_box #form_preview #submit_button').css('color', formdata['button_text_color']);
                    $('.optin_modal_box #form_preview #start_button').css('color', formdata['button_text_color']);
                    $('.optin_modal_box #question_preview .question_btn').css('color', formdata['button_text_color']);
                    $('.optin_modal_box #subscribe_preview #skip_btn').css('color', formdata['button_text_color']);
                    $('.optin_modal_box #subscribe_preview #subscribe_btn').css('color', formdata['button_text_color']);
                    $('.optin_modal_box #form_preview #start_action_button').css('color', formdata['button_text_color']);
                    $('.optin_modal_box #form_preview #submit_button, .optin_modal_box #form_preview #start_button, .optin_modal_box #question_preview .question_btn, .optin_modal_box #subscribe_preview #subscribe_btn, .optin_modal_box #subscribe_preview #skip_btn, .optin_modal_box #form_preview #start_action_button').css('font-size', formdata['button_font_size']  + 'px');
                    $('.optin_modal_box #form_preview #submit_button, .optin_modal_box #form_preview #start_button, .optin_modal_box #question_preview .question_btn, .optin_modal_box #subscribe_preview #subscribe_btn, .optin_modal_box #subscribe_preview #skip_btn, .optin_modal_box #form_preview #start_action_button').css('font-family', formdata['button_font_family']);
                    $('.optin_modal_box #form_preview #email').attr('placeholder', formdata['email_placeholder']);
                    $('.optin_modal_box #form_preview #email_label').text(formdata['email_label']);
                    $('.optin_modal_box #form_preview #submit_button').text(formdata['button_text']);
                    $('.optin_modal_box #form_preview #start_action_button').text(formdata['button_text']);
                    $('.optin_modal_box #success_preview').hide();
                    
                    $('.optin_block_page').show();
                    $('.optin_modal_box').fadeIn();
                    

                    if(formdata['poll_type'] == 'popover' || formdata['poll_type'] == 'welcome_mat')
                    {
                        var current_index = 0;
                        var answer_storage = {};
                        var option_value_storage = {};

                        $(document).on('click', '#start_button', function(e){
                            e.preventDefault();
                            localStorage.clear();

                            if(questions.length > 0)
                            {
                                var option_selected = 
                                $('.optin_modal_box #question_preview #question_header').text(questions[0]['title']);
                                $('.optin_modal_box #question_preview #question_header').css('font-size', formdata["option_font_size"]  + 'px');
						        $('.optin_modal_box #question_preview #question_header').css('color', formdata["option_color"]);
                                $('.optin_modal_box #question_preview #question_header').css('font-family', formdata["option_font_family"]);
                        
                                $.each(questions[current_index]['options'], function(index, value){
                                    if (questions[current_index]['options_type'] == "image")
                                    {
                                        if (value['image_url'] == null)
                                        {
                                            $('.optin_modal_box #question_preview #option_preview').append('<div class="ui radio checkbox field"><input type="radio" name="option" value="' + value['id'] + '" data-nextquestionid="' + value['next_question_id'] + '" data-optionvalue="' + value['option_value'] + '" required="required" ><label><img src="{{ url("/") }}/optionimages/defaultoption.png" /></label></div>');
                                        }
                                        else
                                        {
                                            $('.optin_modal_box #question_preview #option_preview').append('<div class="ui radio checkbox field"><input type="radio" name="option" value="' + value['id'] + '" data-nextquestionid="' + value['next_question_id'] + '" data-optionvalue="' + value['option_value'] + '" required="required" ><label><img src="{{ url("/") }}/optionimages/' + value['image_url'] +'" /></label></div>');
                                        }
                                    }
                                    else
                                    {
                                        $('.optin_modal_box #question_preview #option_preview').append('<div class="ui radio checkbox field"><input type="radio" name="option" value="' + value['id'] + '" data-nextquestionid="' + value['next_question_id'] + '" data-optionvalue="' + value['option_value'] + '" required="required" ><label style="font-size: '+ formdata["option_font_size"] +'px; font-family: ' + formdata["option_font_family"] + '; color: ' + formdata["option_color"] + '">' + value['title'] + '</label></div>');
                                    }
                                });
                                $('.optin_modal_box #question_preview #prev_btn').hide();
                                if(questions.length > 1)
                                {
                                    $('.optin_modal_box #question_preview #finish_btn').hide();
                                }
                                if(questions.length == 1)
                                {
                                    $('.optin_modal_box #question_preview #prev_btn').hide();
                                    $('.optin_modal_box #question_preview #next_btn').hide();
                                    $('.optin_modal_box #question_preview #finish_btn').show();
                                }
                            }

                            $('.optin_modal_box #form_preview').remove();
                            $('.optin_modal_box #question_preview').fadeIn();	
                        });

                        $(document).on('click', '#next_btn', function(e){
                            e.preventDefault()
                            var optionSelected = $('.optin_modal_box #question_preview #option_preview input[name="option"]:checked').val();
                            var nextQuestionId = $('.optin_modal_box #question_preview #option_preview input[name="option"]:checked').data('nextquestionid');
                            var option_value = $('.optin_modal_box #question_preview #option_preview input[name="option"]:checked').data('optionvalue');

                            if (optionSelected != null) {
                                answer_storage[questions[current_index]['id']] = optionSelected;
                                option_value_storage[questions[current_index]['id']] = option_value;
                            }
                            else
                            {
                                answer_storage[questions[current_index]['id']] = 0;
                                option_value_storage[questions[current_index]['id']] = 0;
                            }


                            current_index = nextQuestionIndex(questions, nextQuestionId, current_index);

                            if(questions.length > current_index)
                            {
                                $('.optin_modal_box #question_preview #option_preview').empty();
                                $('.optin_modal_box #question_preview #question_header').text(questions[current_index]['title']);
                                $('.optin_modal_box #question_preview #question_header').css('font-size', formdata["option_font_size"]  + 'px');
						        $('.optin_modal_box #question_preview #question_header').css('color', formdata["option_color"]);
						        $('.optin_modal_box #question_preview #question_header').css('font-family', formdata["option_font_family"]);
                                
                                $.each(questions[current_index]['options'], function(index, value){
                                    if (questions[current_index]['options_type'] == "image")
                                    {
                                        if (value['image_url'] == null)
                                        {
                                            $('.optin_modal_box #question_preview #option_preview').append('<div class="ui radio checkbox field"><input type="radio" name="option" value="' + value['id'] + '" data-nextquestionid="' + value['next_question_id'] + '" data-optionvalue="' + value['option_value'] + '" required="required" ><label><img src="{{ url("/") }}/optionimages/defaultoption.png" /></label></div>');
                                        }
                                        else
                                        {
                                            $('.optin_modal_box #question_preview #option_preview').append('<div class="ui radio checkbox field"><input type="radio" name="option" value="' + value['id'] + '" data-nextquestionid="' + value['next_question_id'] + '" data-optionvalue="' + value['option_value'] + '" required="required" ><label><img src="{{ url("/") }}/optionimages/' + value['image_url'] +'" /></label></div>');
                                        }
                                    }
                                    else
                                    {
                                        $('.optin_modal_box #question_preview #option_preview').append('<div class="ui radio checkbox field"><input type="radio" name="option" value="' + value['id'] + '" data-nextquestionid="' + value['next_question_id'] + '" data-optionvalue="' + value['option_value'] + '" required="required" ><label style="font-size: '+ formdata["option_font_size"] +'px; font-family: ' + formdata["option_font_family"] + '; color: ' + formdata["option_color"] + '">' + value['title'] + '</label></div>');
                                    }
                                });
                                $('.optin_modal_box #question_preview #prev_btn').hide();
                                if(current_index  < questions.length - 1)
                                {
                                    $('.optin_modal_box #question_preview #finish_btn').hide();
                                    $('.optin_modal_box #question_preview #prev_btn').show();
                                    $('.optin_modal_box #question_preview #next_btn').show();
                                }
                                else
                                {
                                    $('.optin_modal_box #question_preview #prev_btn').show();
                                    $('.optin_modal_box #question_preview #next_btn').hide();
                                    $('.optin_modal_box #question_preview #finish_btn').show();
                                }
                            }
                        });

                        $(document).on('click', '#prev_btn', function(e){
                            e.preventDefault()
                            

                            var previousQuestionId = Object.keys(answer_storage).pop();
                            var previousOptionId = answer_storage[previousOptionId];

                            current_index = previousQuestionIndex(questions, previousQuestionId, current_index)

                            if(questions.length > current_index)
                            {
                                $('.optin_modal_box #question_preview #option_preview').empty();
                                $('.optin_modal_box #question_preview #question_header').text(questions[current_index]['title']);
                                $('.optin_modal_box #question_preview #question_header').css('font-size', formdata["option_font_size"]  + 'px');
						        $('.optin_modal_box #question_preview #question_header').css('color', formdata["option_color"]);
                                $('.optin_modal_box #question_preview #question_header').css('font-family', formdata["option_font_family"]);
                        
                                $.each(questions[current_index]['options'], function(index, value){
                                    if (questions[current_index]['options_type'] == "image")
                                    {
                                        if (value['image_url'] == null)
                                        {
                                            $('.optin_modal_box #question_preview #option_preview').append('<div class="ui radio checkbox field"><input type="radio" name="option" value="' + value['id'] + '" data-nextquestionid="' + value['next_question_id'] + '" data-optionvalue="' + value['option_value'] + '" required="required" ><label><img src="{{ url("/") }}/optionimages/defaultoption.png" /></label></div>');
                                        }
                                        else
                                        {
                                            $('.optin_modal_box #question_preview #option_preview').append('<div class="ui radio checkbox field"><input type="radio" name="option" value="' + value['id'] + '" data-nextquestionid="' + value['next_question_id'] + '" data-optionvalue="' + value['option_value'] + '" required="required" ><label><img src="{{ url("/") }}/optionimages/' + value['image_url'] +'" /></label></div>');
                                        }
                                    }
                                    else
                                    {
                                        $('.optin_modal_box #question_preview #option_preview').append('<div class="ui radio checkbox field"><input type="radio" name="option" value="' + value['id'] + '" data-nextquestionid="' + value['next_question_id'] + '" data-optionvalue="' + value['option_value'] + '" required="required" ><label style="font-size: '+ formdata["option_font_size"] +'px; font-family: ' + formdata["option_font_family"] + '; color: ' + formdata["option_color"] + '">' + value['title'] + '</label></div>');
                                    }
                                });

                                if(current_index == 0)
                                {
                                    $('.optin_modal_box #question_preview #prev_btn').hide();
                                }
                                else
                                {
                                    $('.optin_modal_box #question_preview #prev_btn').show();
                                }
                                
                                if(current_index  < questions.length - 1)
                                {
                                    $('.optin_modal_box #question_preview #next_btn').show();
                                }
                                else
                                {
                                    $('.optin_modal_box #question_preview #next_btn').hide();
                                }
                                if(current_index == questions.length - 1)
                                {
                                    $('.optin_modal_box #question_preview #finish_btn').show();
                                }
                                else
                                {
                                    $('.optin_modal_box #question_preview #finish_btn').hide();
                                }
                            }

                            delete answer_storage[previousQuestionId];
                            delete option_value_storage[previousQuestionId];
                        });

                        $(document).on('click', '.optin_modal_box #question_preview #finish_btn', function(e){
                            e.preventDefault();
                            var optionSelected = $('.optin_modal_box #question_preview #option_preview input[name="option"]:checked').val();
                            var option_value = $('.optin_modal_box #question_preview #option_preview input[name="option"]:checked').data('optionvalue');

                            if (optionSelected != null) {
                                answer_storage[questions[current_index]['id']] = optionSelected;
                                option_value_storage[questions[current_index]['id']] = option_value;
                            }
                            else
                            {
                                answer_storage[questions[current_index]['id']] = 0;
                                option_value_storage[questions[current_index]['id']] = 0;
                            }

                            $('.optin_modal_box #question_preview').remove();
                            $('.optin_modal_box #subscribe_preview').fadeIn();

                        });

                        $(document).on('click', '.optin_modal_box #subscribe_preview #skip_btn', function(e){
                            e.preventDefault();
                            // Get response to questions
                            if(formdata['form_type'] == 'poll')
                            {
                                showPollResult(answer_storage);						
                            }
                            else if(formdata['form_type'] == 'quiz')
                            {
                                showOutcomeResult(answer_storage);
                            }
                            else if(formdata['form_type'] == 'calculator')
                            {
                                showCalculatorResult(option_value_storage, results, formdata['success_description']);
                            }
                        });

                        $(document).on('submit','.optin_modal_box #subscribe_preview #subscribe_form', function(e){
                            e.preventDefault();
                            var fdata = $(this).serialize();
                            fdata += '&form_id={{ $form->id }}';
                            $.ajax({
                                url: '{{ url("api/v1/subscribe") }}',
                                method : 'post',
                                data : fdata,
                                headers: {'X-CSRF-Token': '{{ csrf_token() }}'},
                                success: function(data){
                                    console.log('success');
                                },
                                error: function(data){
                                    console.log('error');
                                }
                            });

                            if(formdata['form_type'] == 'poll')
                            {
                                showPollResult(answer_storage);						
                            }
                            else if(formdata['form_type'] == 'quiz')
                            {
                                showOutcomeResult(answer_storage);
                            }
                            else if(formdata['form_type'] == 'calculator')
                            {
                                showCalculatorResult(option_value_storage, results, formdata['success_description']);
                            }
                        });
                    }
                },
                error: function(data){
                    console.log('error');
                }
            });
            
            function add_popup_box(theme){
                var pop_up = $('<div class="optin_modal_box">' + theme + '</div>')
                $(pop_up).appendTo('.optin_block_page');

                $('.close-popup').click(function(){
                    // $(this).parent().fadeOut().remove();
                    // $('.optin_block_page').fadeOut().remove(); 
                    $(this).parent().fadeOut();
                    $('.optin_block_page').fadeOut();                
                });
            }

            function add_welcomemat_styles(){
                $('.optin_modal_box').css({
                    // 'position' : 'fixed',
                    // 'top' : '0',
                    // 'left' : '0',
                    // 'display' : 'none',
                    'width' : '100%',
                    'height': '100vh',
                    'background' : '#f2f2f2',
                });
            }

            function hexToRgb(hex) {
                var bigint = parseInt(hex, 16);
                var r = (bigint >> 16) & 255;
                var g = (bigint >> 8) & 255;
                var b = bigint & 255;

                return "rgb(" + r + "," + g + "," + b + "," + "0.7)";
            }

            function showPollResult(answer_storage){
                $.ajax({
                    dataType: "json",
                    data: answer_storage,
                    url: '{{ url("api/v1/option/store") }}',
                    method: 'post',
                    headers: {'X-CSRF-Token': '{{ csrf_token() }}'},
                    success: function(data){
                        $('.optin_modal_box #subscribe_preview').remove();
                        var result = JSON.parse(data.result);
                        var supportedVotes = JSON.parse(data.supportedVotes);
                        var totalVotes = JSON.parse(data.totalVotes);

                        var percentageVotes = totalVotes != 0 ? (supportedVotes / totalVotes * 100).toFixed(2) : 0;

                        $('.optin_modal_box #success_preview .statistic #percentage_votes').text(percentageVotes + "%");

                        $('.optin_modal_box #success_preview #poll_result').empty();

                        $.each(result, function(index, value){
                            $('.optin_modal_box #success_preview #poll_result').append('<li>'+ value['title'] +'</li>')
                            var total = value['total'];
                            $.each(value['options'], function(index, value){
                                $('.optin_modal_box #success_preview #poll_result').append('<p>' + value['title'] +'</p>');
                                var votePercent = 100 * value['count'] / total;
                                $('.optin_modal_box #success_preview #poll_result').append('<div class="ui tiny blue progress" data-percent="'+ votePercent +'"><div class="bar"></div><div class="label">'+ votePercent.toFixed(1) +'% ('+ value[
                                    "count"] +' votes)</div></div>');
                            });
                        });

                        $('.progress').progress();					        	


                        $('.optin_modal_box #success_preview').fadeIn();

                        setTimeout( function(){ 					
                            if(formdata["redirect_url"])
                            {
                                window.location.href = formdata["redirect_url"];
                            }
                        }  , 3000 );
                    },
                    error: function(data)
                    {
                        console.log(data);
                    }

                });
            }

            function showOutcomeResult(answer_storage){
                $.ajax({
                    dataType: "json",
                    data: answer_storage,
                    url: '{{ url("api/v1/outcome") }}',
                    method: 'post',
                    headers: {'X-CSRF-Token': '{{ csrf_token() }}'},
                    success: function(data){
                        console.log(data);
                        $('.optin_modal_box #subscribe_preview').remove();

                        $('.optin_modal_box #success_preview #success_headline').html(data['outcome']['title']);
                        $('.optin_modal_box #success_preview #success_description').html(data['outcome']['description']);

                        $('.optin_modal_box #success_preview').fadeIn();

                        setTimeout( function(){ 					
                            if(formdata["redirect_url"])
                            {
                                window.location.href = formdata["redirect_url"];
                            }
                        }  , 3000 );
                    },
                    error: function(data)
                    {
                        console.log(data);
                    }

                });
            }

            function showCalculatorResult(option_value_storage, results, description){
                var q_values = {};
                var result_values = {};
                var startIndex = 1;
                $.each(option_value_storage, function(index, value){
                    q_values['Q' + startIndex] = value;
                    startIndex++;
                });

                $.each(results, function(index, value){
                    var formular_input = value['formular'];
                    var result = 0;
                    for(var key in q_values)
                    {
                        if(q_values.hasOwnProperty(key))
                        {
                            var re = new RegExp(key, "g");
					        formular_input = formular_input.replace(re , q_values[key]);
                        }
                    }
                    try
                    {
                        result = eval(formular_input);                  
                    }
                    catch(err)
                    {
                        // console.log("Syntax error");
                        result = 0;
                    }
                    q_values['R' + (index + 1)] = result;
                    result_values['@{{R' + (index + 1) + '}}'] = result.toFixed(2);
                    results[index]['result'] = result;
                });

                var result_description = "";

                if(description)
                {
                    result_description = description;
                    
                    $.each(result_values, function(index, value){
                        result_description = result_description.replace(index, value);
                    });
                }

                $('.optin_modal_box #success_preview .content').html(result_description);

                $('.optin_modal_box #subscribe_preview').remove();
                $('.optin_modal_box #success_preview').fadeIn();

                setTimeout( function(){ 					
                    if(formdata["redirect_url"])
                    {
                        window.location.href = formdata["redirect_url"];
                    }
                }  , 3000 );
            }

            function nextQuestionIndex(questions, nextQuestionId, currentIndex){
                if(nextQuestionId == null)
                {
                    return (currentIndex + 1);
                }

                var index = questions.findIndex(obj => obj.id == nextQuestionId);

                return index;
            }

            function previousQuestionIndex(questions, previousQuestionId, currentIndex){
                if(previousQuestionId == null)
                {
                    return (currentIndex - 1);
                }

                var index = questions.findIndex(obj => obj.id == previousQuestionId);

                return index;
            }
        </script>          
    </body>
</html>