$j = jQuery.noConflict(true);


(function($){
	var form_id = $('#optin_script').attr('data-form-id');
	var redirect_url = $('#optin_script').attr('data-redirect_url');
	var formdata;

	// $("head").prepend("<link id='semantic_style' rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.3/semantic.min.css' type='text/css'>");
	$("head").prepend("<link id='semantic_style' rel='stylesheet' href='https://sendmunk.com/Semantic-UI-CSS-master/semantic-optin.css' type='text/css'>");
	// $("body").append("<script src='https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.3/semantic.min.js' type='text/javascript'></script>");
	// // $("<script src='https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.3/semantic.min.js' type='text/javascript'></script>").insertAfter('#jquery_script');
	$("head").append('<style>.radio input[type="radio"], .radio-inline input[type="radio"], .checkbox input[type="checkbox"], .checkbox-inline input[type="checkbox"] {margin-left: 0px !important;}</style>');

	$(document).on('submit','.optin_modal_box #form_preview',function(e){
		e.preventDefault();
		var fdata = $(this).serialize();
		fdata += '&form_id=' + form_id;
		$.ajax({
				url : 'https://sendmunk.com/api/v1/subscribe',
				method : 'post',
				data : fdata,
				success: function(data){
					$('.optin_modal_box #form_preview').hide();
					$('.optin_modal_box #success_preview').fadeIn();
 
				 setTimeout( function(){ 
					$(this).parent().fadeOut().remove();
					$('.optin_block_page').fadeOut().remove(); 

					if(formdata["redirect_url"])
					{
						window.location.href = formdata["redirect_url"];
					}
				 }  , 3000 );
				},
				error: function(data){
					console.log('error');
				}
		});
	 });
 
	 
 
	 $.ajax({
		 url: 'https://sendmunk.com/api/v1/formdata/' + form_id,
		 method: 'get',
		 success: function(data){
			var rules = JSON.parse(data.rules);
			formdata = JSON.parse(data.form);
			var questions = JSON.parse(data.questions);
			var results = JSON.parse(data.results);
			var visit = data.visit;
			var push_check = data.push_check;


			var current_url = location.href;


			if(rules.length > 0)
			{
				for(var i = 0; i < rules.length; i++)
				{
					if(rules[i]['show'] == "DONT SHOW ON")
					{
						if(rules[i]['match'] == "URLS CONTAINING")
						{
							if(current_url.indexOf(rules[i]['page_name']) !== -1)
							{
								return;
							}
						}
						else
						{
							if(current_url == rules[i]['page_name'])
							{
								return;
							}
						}
					}
					else if(rules[i]['show'] == "SHOW ON")
					{
						if(rules[i]['match'] == "URLS CONTAINING")
						{
							if(current_url.indexOf(rules[i]['page_name']) !== -1)
							{
								break;
							}
						}
						else
						{
							if(current_url == rules[i]['page_name'])
							{
								break;
							}
						}
					}
				}
			}

			let isMobile = window.matchMedia("only screen and (max-width: 760px)").matches;
			let isTab = window.matchMedia("only screen and (min-width: 768px) and (max-width: 1024px)").matches;
			let isDesktop = window.matchMedia("only screen and (min-width: 1025px)").matches;

			if(!formdata['mobile_device'] && isMobile)
			{
				return;
			}
			if(!formdata['tablet_device'] && isTab)
			{
				return;
			}
			if(!formdata['desktop_device'] && isDesktop)
			{
				return;
			}

			if(formdata['tracking_pixel'] != null)
			{
				$("body").prepend(formdata['tracking_pixel']);
			}

			if((formdata['form_type'] == 'push_notification' && formdata['poll_type'] == 'popover' && !(/constructor/i.test(window.HTMLElement) || (function (p) { return p.toString() === "[object SafariRemoteNotification]"; })(!window['safari'] || safari.pushNotification) ))){

				var ipData;
				$.ajax({
						'async': false,
						'type': "GET",
						'global': false,
						'url': "https://api.ipify.org",
						'success': function (ip) {
								ipData = ip;
						}
				});
				console.log('the new data: ', ipData);

					add_block_page();
					add_popup_box(data.theme);
					add_popup_styles();	
			}
			

			else if(formdata['form_type'] == 'popover' || (formdata['form_type'] == 'poll' && formdata['poll_type'] == 'popover') || (formdata['form_type'] == 'quiz' && formdata['poll_type'] == 'popover') || (formdata['form_type'] == 'calculator' && formdata['poll_type'] == 'popover')  || (formdata['form_type'] == 'facebook' && formdata['poll_type'] == 'popover') || (formdata['form_type'] == 'twitter' && formdata['poll_type'] == 'popover') || (formdata['form_type'] == 'pinterest' && formdata['poll_type'] == 'popover') || (formdata['form_type'] == 'action' && formdata['poll_type'] == 'popover'))
			{
				add_block_page();
				add_popup_box(data.theme);
				add_popup_styles();		
			}
			else if(formdata['form_type'] == 'scrollbox' || formdata['poll_type'] == 'scrollbox')
			{
				add_block_page();
				add_popup_box(data.theme);
				add_scrollbox_styles();
			}
			else if(formdata['form_type'] == 'topbar')
			{
				add_top_bar_page();
				add_popup_box(data.theme);
				add_topbar_styles();
			}
			else if(formdata['form_type'] == 'welcome_mat' || (formdata['form_type'] == 'poll' && formdata['poll_type'] == 'welcome_mat') || (formdata['form_type'] == 'quiz' && formdata['poll_type'] == 'welcome_mat') || (formdata['form_type'] == 'calculator' && formdata['poll_type'] == 'welcome_mat'))
			{
				add_top_bar_page();
				add_popup_box(data.theme);
				add_welcomemat_styles();
			}
			else if(formdata['form_type'] == 'embedded')
			{
				add_popup_box(data.theme);
				add_embedded_styles();
			}

			if(isMobile)
			{
				$('.optin_modal_box').css('width', '90%');
			}

			if(data.branding)
			{
				$('.optin_branding').show();
				$('.optin_branding').css('display', 'block');
			}

			if(data.enable_gdpr)
			{
				$('.optin_modal_box #form_preview #gdpr_field').css('display', 'block');
				$('.optin_modal_box #form_preview #submit_button, .optin_modal_box #form_preview #subscribe_btn').attr("disabled", "disabled");
			}
 
			$('.optin_modal_box #form_preview').css('width', '100%');
			$('.optin_modal_box #success_preview').css('width', '100%');
			$('.optin_modal_box #question_preview').css('width', '100%');
			$('.optin_modal_box #subscribe_preview').css('width', '100%');
			if(formdata['background_image'] != null)
			{
				$('.optin_modal_box .background_image').css('background-image',"url(" + "https://sendmunk.com" + "/" + formdata['background_image'] + ")");
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
			$('.optin_modal_box #form_preview #phone').attr('placeholder', formdata['phone_placeholder']);
			$('.optin_modal_box #form_preview #phone_label').text(formdata['phone_label']);
			if(formdata['show_phone_field'])
			{
				$('.optin_modal_box #form_preview #phone_field').css('display', 'block');
			}
			else
			{
				$('.optin_modal_box #form_preview #phone_field').css('display', 'none');
			}
			$('.optin_modal_box #form_preview #submit_button').text(formdata['button_text']);
			$('.optin_modal_box #form_preview #start_action_button').text(formdata['button_text']);

			$('.optin_modal_box #success_preview').hide();
			$('.optin_block_page').hide();
			// $('.optin_modal_box').fadeIn();

			// if(formdata.hasOwnProperty('page_exit'))
			if(formdata['form_type'] == 'popover' || (formdata['form_type'] == 'poll' && formdata['poll_type'] == 'popover') || (formdata['form_type'] == 'quiz' && formdata['poll_type'] == 'popover') || (formdata['form_type'] == 'calculator' && formdata['poll_type'] == 'popover'))
			{
				if(formdata['page_exit'])
				{
					var mouseX = 0;
					var mouseY = 0;
					var popupCounter = 0;

					document.addEventListener("mousemove", function(e) {
						mouseX = e.clientX;
						mouseY = e.clientY;
					});

					$(document).mouseleave(function () {
						if (mouseY < 100) {
							if (popupCounter < 1) {
								$('.optin_block_page').show();
								$('.optin_modal_box').fadeIn();
								$('.optin_modal_box #form_preview').show();
							}
							popupCounter ++;
						}
					});
				}

				// if(formdata.hasOwnProperty('page_exit'))
				if(formdata['page_load'] || formdata['first_visit'])
				{
					setTimeout(function() {
						$('.optin_block_page').show();
						$('.optin_modal_box').fadeIn();
					}, parseInt(formdata['loading_delay'])*1000);
				}
			}
			else if(formdata['form_type'] == 'topbar')
			{
				setTimeout(function() {
					$('.optin_block_page').show();
					$('.optin_modal_box').fadeIn();
				}, parseInt(formdata['loading_delay'])*1000);
			}
			else if(formdata['form_type'] == 'scrollbox')
			{
				var scrollboxCounter = 0;
				if(document.body.scrollHeight > document.body.clientHeight)
				{
					$(document).scroll(function(e){
						var scrollAmount = $(window).scrollTop();
						var documentHeight = $(document).height();
						var windowHeight = $(window).height();
	
						var scrollRatio = parseFloat(formdata['trigger_point']) / 100;
	
						if(scrollAmount >= (documentHeight - windowHeight)*scrollRatio ){
							if (scrollboxCounter < 1) {
								$('.optin_block_page').show();
								$('.optin_modal_box').fadeIn();
							}
							scrollboxCounter ++;
						}
	
					});					
				}
				else
				{
					$('.optin_block_page').show();
					$('.optin_modal_box').fadeIn();
				}
			}
			else
			{
				$('.optin_block_page').show();
				$('.optin_modal_box').fadeIn();
			}

			if(formdata['poll_type'] == 'popover' || formdata['poll_type'] == 'welcome_mat')
			{
				var current_index = 0;
				var answer_storage = {};
				var option_value_storage = {};

				$(document).on('click', '.optin_modal_box #form_preview #start_button', function(e){
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
									$('.optin_modal_box #question_preview #option_preview').append('<div class="ui radio checkbox field"><input type="radio" name="option" value="' + value['id'] + '" data-nextquestionid="' + value['next_question_id'] + '" data-optionvalue="' + value['option_value'] + '" required="required" ><label><img src="https://sendmunk.com/optionimages/defaultoption.png" /></label></div>');
								}
								else
								{
									$('.optin_modal_box #question_preview #option_preview').append('<div class="ui radio checkbox field"><input type="radio" name="option" value="' + value['id'] + '" data-nextquestionid="' + value['next_question_id'] + '" data-optionvalue="' + value['option_value'] + '" required="required" ><label><img src="https://sendmunk.com/' + value['image_url'] +'" /></label></div>');
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

				$(document).on('click', '.optin_modal_box #question_preview #next_btn', function(e){
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
									$('.optin_modal_box #question_preview #option_preview').append('<div class="ui radio checkbox field"><input type="radio" name="option" value="' + value['id'] + '" data-nextquestionid="' + value['next_question_id'] + '" data-optionvalue="' + value['option_value'] + '" required="required" ><label><img src="https://sendmunk.com/optionimages/defaultoption.png" /></label></div>');
								}
								else
								{
									$('.optin_modal_box #question_preview #option_preview').append('<div class="ui radio checkbox field"><input type="radio" name="option" value="' + value['id'] + '" data-nextquestionid="' + value['next_question_id'] + '" data-optionvalue="' + value['option_value'] + '" required="required" ><label><img src="https://sendmunk.com/' + value['image_url'] +'" /></label></div>');
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

				$(document).on('click', '.optin_modal_box #question_preview #prev_btn', function(e){
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
									$('.optin_modal_box #question_preview #option_preview').append('<div class="ui radio checkbox field"><input type="radio" name="option" value="' + value['id'] + '" data-nextquestionid="' + value['next_question_id'] + '" data-optionvalue="' + value['option_value'] + '" required="required" ><label><img src="https://sendmunk.com/optionimages/defaultoption.png" /></label></div>');
								}
								else
								{
									$('.optin_modal_box #question_preview #option_preview').append('<div class="ui radio checkbox field"><input type="radio" name="option" value="' + value['id'] + '" data-nextquestionid="' + value['next_question_id'] + '" data-optionvalue="' + value['option_value'] + '" required="required" ><label><img src="https://sendmunk.com/' + value['image_url'] +'" /></label></div>');
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
						showCalculatorResult(answer_storage, option_value_storage, results, formdata['success_description']);
					}
				});

				$(document).on('submit','.optin_modal_box #subscribe_preview #subscribe_form', function(e){
					e.preventDefault();
					var fdata = $(this).serialize();
					fdata += '&form_id=' + form_id;
					$.ajax({
						url : 'https://sendmunk.com/api/v1/subscribe',
						method : 'post',
						data : fdata,
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
						showCalculatorResult(answer_storage, option_value_storage, results, formdata['success_description']);
					}
				});
			}
		},
		error: function(data){
			console.log('error');
		}
	});
 
	function add_popup_styles(){
		$('.optin_modal_box').css({
			'position' : 'relative',
			'margin' : 'auto',
			'margin-top': '70px',
			'display' : 'none',
			'width' : '50%',
			'border-radius' : '10px',
			'background' : '#f2f2f2',
			'z-index' : '16777271',
		});
		var pageWidth = $(document).width();

		$('.optin_block_page').css({
			'position':'fixed',
			'top':'0',
			'left':'0',
			'background-color':'rgba(0,0,0,0.6)',
			// 'opacity': '0.6',
			'min-height': '100vh',
			'width':pageWidth,
			'z-index':'16777270'
		});
	}

	function add_scrollbox_styles(){
		$('.optin_modal_box').css({
			'position' : 'fixed',
			'bottom' : '0',
			'right' : '0',
			'display' : 'none',
			'width' : '450px',
			'border-radius' : '10px',
			'background' : '#f2f2f2',
			'z-index':'16777270'
		});
	}

	function add_topbar_styles(){
		$('.optin_modal_box').css({
			'position' : 'fixed',
			'top' : '0',
			'left' : '0',
			'display' : 'none',
			'width' : '100%',
			'background' : '#f2f2f2',
			'z-index' : '16777270',
		});
	}

	function add_welcomemat_styles(){
		$('.optin_modal_box').css({
			'position' : 'fixed',
			'top' : '0',
			'left' : '0',
			'display' : 'none',
			'width' : '100%',
			'height': '100vh',
			'background' : '#f2f2f2',
			'z-index' : '16777270',
		});
	}

	function add_embedded_styles(){
		$('.optin_modal_box').css({
			'display' : 'none',
			'width' : '100%',
		});
	}

	function add_block_page(){
		var block_page = $('<div class="optin_block_page"></div>');

		$(block_page).appendTo('body');
	}

	function add_top_bar_page(){
		var block_page = $('<div class="optin_block_page"></div>');

		$(block_page).prependTo('body');
	}

	function add_popup_box(theme){
		var pop_up = $('<div class="optin_modal_box">' + theme + '</div>')
		$(pop_up).appendTo('.optin_block_page');

		$('.close-popup').click(function(){
			// $(this).parent().fadeOut().remove();
			// $('.optin_block_page').fadeOut().remove(); 
			// $(this).parent().fadeOut();
			$('.optin_block_page').fadeOut();                
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
			url: 'https://sendmunk.com/api/v1/option/store',
			method: 'post',
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
	        			// $('.optin_modal_box #success_preview #poll_result').append('<div class="ui tiny blue progress" data-percent="'+ votePercent +'"><div class="bar"></div><div class="labelp">'+ votePercent.toFixed(1) +'% ('+ value["count"] +' votes)</div></div>');
						$('.optin_modal_box #success_preview #poll_result').append('<progress style="width: 100%" value="'+ votePercent +'" max="100"></progress>');
						$('.optin_modal_box #success_preview #poll_result').append('<div class="labelp" style="text-align:center;">'+ votePercent.toFixed(1) +'% ('+ value["count"] +' votes)</div>');
	        		});
	        	});

	        	// $('.progress').progress();						        	


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
			url: 'https://sendmunk.com/api/v1/outcome',
			method: 'post',
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

	function showCalculatorResult(answer_storage, option_value_storage, results, description){
		$.ajax({
	        dataType: "json",
	        data: answer_storage,
	        url: 'http://localhost:8000/api/v1/option/store',
	        method: 'post',
			success: function(data){},
			error: function(data){}
		});
		
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
			result_values['{{R' + (index + 1) + '}}'] = result.toFixed(2);
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

	// function myFunction() { 
	// 		if((navigator.userAgent.indexOf("Opera") || navigator.userAgent.indexOf('OPR')) != -1 ) 
	// 	{
	// 			alert('Opera');
	// 	}
	// 	else if(navigator.userAgent.indexOf("Chrome") != -1 )
	// 	{
	// 			alert('Chrome');
	// 	}
	// 	else if(navigator.userAgent.indexOf("Safari") != -1)
	// 	{
	// 			alert('Safari');
	// 	}
	// 	else if(navigator.userAgent.indexOf("Firefox") != -1 ) 
	// 	{
	// 				alert('Firefox');
	// 	}
	// 	else if((navigator.userAgent.indexOf("MSIE") != -1 ) || (!!document.documentMode == true )) //IF IE > 10
	// 	{
	// 		alert('IE'); 
	// 	}  
	// 	else 
	// 	{
	// 			alert('unknown');
	// 	}
	// 	}


		// var myWindow;

		// function openWin() {
		// myWindow = window.open("", "myWindow", "width=200,height=100");
		// myWindow.document.write("<p>This is 'myWindow'</p>");
		// }

		// function closeWin() {
		// myWindow.close();
		// }


	$(document).on('click','.optin_modal_box #form_preview #start_action_button', function(e){
		e.preventDefault();
		var fdata = $(this).serialize();
		fdata += '&form_id=' + form_id + '&redirect_url=' + redirect_url;
		$.ajax({
			url : 'https://sendmunk.com/api/v1/clicks',
			method : 'post',
			data : fdata,
			success: function(data){
				console.log(data);
				window.open(data.redirect_url, 'popUp', 'width=400, height=400', 'centerscreen');
				$('.optin_modal_box #form_preview').hide();
				// window.close();
				// window.location.href = data.redirect_url;
			},
			error: function(data){
				console.log('error');
			}
		});
	});

	$(document).on('change', '.optin_modal_box #form_preview #gdpr_field #gdpr', function(){
		if($(this).prop('checked') == true)
		{
			$('.optin_modal_box #form_preview #submit_button, .optin_modal_box #form_preview #subscribe_btn').removeAttr("disabled");
		}
		else
		{
			$('.optin_modal_box #form_preview #submit_button, .optin_modal_box #form_preview #subscribe_btn').attr("disabled", "disabled");
		}
	});

 })($j);