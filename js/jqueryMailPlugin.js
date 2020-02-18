$(document).on("click",function(e){
 	if(!$(e.target).hasClass("show-steps")){
 		$(".workflow-steps").remove();
 	}
});


function showSteps(e){
    var id = $(e).attr("id").split("addstep")[1];
    $('#addstep'+id).popover({
        html:true,
        trigger:"manual",
        placement:"auto",
        container:".ml-workflow-builder-area",
        viewport:{selector:".ml-workflow-builder-area",padding:30},
        template:'<div class="popover workflow-steps" role="tooltip"><div class="arrow"></div><div class="popover-content"></div></div>',
        content:function(){
            var content =   '<div class="steps-popup ng-scope">'+
                                '<h3 class="ng-binding">Add a next step to your workflow</h3>'+
                                '<ul>'+
                                    '<li onclick="addEmailStep('+id+');">'+
                                        '<svg class="icon icon-mail">'+
                                            '<use xlink:href="img/workflow.svg#icon-mail"></use>'+
                                        '</svg>'+
                                        '<div class="ng-binding">Email</div>'+
                                    '</li>'+
                                    '<li onclick="addDelayStep('+id+');">'+
                                        '<svg class="icon icon-delay">'+
                                            '<use xlink:href="img/workflow.svg#icon-delay"></use>'+
                                        '</svg>'+
                                        '<div class="ng-binding">Delay</div>'+
                                    '</li>'+
                                    '<li onclick="addConditionStep('+id+');">'+
                                        '<svg class="icon icon-conclusion">'+
                                            '<use xlink:href="img/workflow.svg#icon-conclusion"></use>'+
                                        '</svg>'+
                                        '<div class="ng-binding">Condition</div>'+
                                    '</li>'+
                                    '<li onclick="addActionStep('+id+');">'+
                                        '<svg class="icon icon-cog">'+
                                            '<use xlink:href="img/workflow.svg#icon-cog"></use>'+
                                        '</svg>'+
                                        '<div class="ng-binding">Action</div>'+
                                    '</li>'+
                                '</ul>'+
                            '</div>';
            return content;
        }
    });

    $('#addstep'+id).popover("show");
  
  //console.log(d);
}

/*******************************************************************
	This will add email step to the canvas
********************************************************************/ 
function addEmailStep(id){
	var dt = Date.now();
	$("#addstep"+id).parent().parent().parent().append('<div class="ng-scope ng-isolate-scope email-step">'+
	    '<ul class="wf-steps-subtree ng-scope">'+
	        '<li>'+
	            '<div class="step-container step-incomplete">'+
	                '<div class="step-content email step-selected" onclick="showEmailSidebar();">'+
	                    '<div class="ng-hide">'+
	                      '<div class="sk-spinner sk-spinner-three-bounce">'+
	                        '<div class="sk-bounce1"></div>'+
	                        '<div class="sk-bounce2"></div>'+
	                        '<div class="sk-bounce3"></div>'+
	                      '</div>'+
	                    '</div>'+
	                    '<a class="remove-step" href="javascript:;" onclick="removeStep(\'email\');">'+
	                      '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>'+
	                    '</a>'+
	                    '<div class="step-details">'+
	                        '<div class="email-screenshot">'+
	                            '<img step_id="4750506" class="ng-isolate-scope" src="img/workflow_screenshot.png">'+
	                        '</div>'+

	                        '<div class="define-content ng-binding ng-scope">Define email content</div>'+
	                    '</div>'+
	                '</div>'+
	                '<div class="add-step">'+
	                    '<a href="javascript:;" type="button" class="btn btn-add ng-hide">'+
	                      '<svg class="icon icon-action">'+
	                        '<use xlink:href="img/workflow.svg#icon-action"></use>'+
	                      '</svg>'+
	                    '</a>'+

	                    '<a href="javascript:;" type="button" class="btn btn-add show-steps" onclick="showSteps(this);" id="addstep'+dt+'">'+
	                      '<svg class="icon icon-action show-steps">'+
	                        '<use xlink:href="img/workflow.svg#icon-action"></use>'+
	                      '</svg>'+
	                    '</a>'+
	                '</div>'+
	            '</div>'+
	        '</li>'+
	    '</ul>'+
	'</div>');

	$(".workflow-steps").remove();
	$(".first-step-explanation").remove();
	showEmailSidebar();
}
//end

/*******************************************************************
	This will add delay step to the canvas
********************************************************************/ 
function addDelayStep(id){
	var dt = Date.now();
	$("#addstep"+id).parent().parent().parent().append('<div class="ng-scope ng-isolate-scope delay-step">'+
	    '<ul class="wf-steps-subtree ng-scope">'+
	        '<li>'+
	            '<div class="step-container step-incomplete">'+
	                '<div class="step-content delay step-selected" onclick="showDelaySidebar();">'+
	                    '<a class="remove-step" href="javascript:;" onclick="removeStep(\'delay\');">'+
	                      '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>'+
	                    '</a>'+
	                    '<div class="set-delay ng-binding ng-scope">Set delay</div>'+
	                '</div>'+
	                '<div class="add-step">'+
	                    '<a href="javascript:;" type="button" class="btn btn-add ng-hide">'+
	                      '<svg class="icon icon-action">'+
	                        '<use xlink:href="img/workflow.svg#icon-action"></use>'+
	                      '</svg>'+
	                    '</a>'+

	                    '<a href="javascript:;" type="button" class="btn btn-add show-steps" onclick="showSteps(this);" id="addstep'+dt+'">'+
	                      '<svg class="icon icon-action show-steps">'+
	                        '<use xlink:href="img/workflow.svg#icon-action"></use>'+
	                      '</svg>'+
	                    '</a>'+
	                '</div>'+
	            '</div>'+
	        '</li>'+
	    '</ul>'+
	'</div>');

	$(".workflow-steps").remove();
	$(".first-step-explanation").remove();
	showDelaySidebar();
}
//end

/*******************************************************************
	This will add condition step to the canvas
********************************************************************/ 
function addConditionStep(id){
	var dt = Date.now();
	var conditionIndex = parseInt($(".wf-builder").attr("data-index"));
	var wfbW = parseInt($(".wf-builder").width());

	if(conditionIndex >= 1){ 
		$(".wf-builder").css("width",wfbW + 260);
		$(".wf-builder").attr("data-index",conditionIndex + 1); 
	}
	else{ $(".wf-builder").attr("data-index",conditionIndex + 1); }

	$("#addstep"+id).parent().parent().parent().append('<div class="ng-scope ng-isolate-scope condition-step">'+
	    '<ul class="wf-steps-subtree decision ng-scope">'+
	        '<li>'+
	            '<div class="step-container step-incomplete">'+
	                '<div class="step-content condition step-selected" onclick="showConditionSidebar();">'+
	                    '<a class="remove-step" href="javascript:;" onclick="removeStep(\'condition\');">'+
	                      '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>'+
	                    '</a>'+

	                    '<div class="diamond"></div>'+
	                    '<svg class="icon">'+
	                      '<use xlink:href="img/workflow.svg#icon-conclusion"></use>'+
	                    '</svg>'+

	                   '<div class="define-content mb0 mt0 ng-binding ng-scope">Define condition</div>'+
	                '</div>'+

	                '<div class="ng-scope"></div>'+

	                '<ul class="wf-steps-subtree condition">'+
	                    '<li class="decision-steps">'+
	                        '<div class="condition-rule yes">'+
	                            '<svg class="icon icon-action">'+
	                              '<use xlink:href="img/workflow.svg#icon-thumbs-up"></use>'+
	                            '</svg>'+
	                        '</div>'+
	                        '<ul class="wf-steps-subtree">'+
	                            '<li>'+
	                                '<div class="step-container">'+
	                                    '<div class="add-step only-condition">'+
	                                        '<a href="javascript:;" type="button" class="btn btn-add ng-hide">'+
	                                            '<svg class="icon icon-action">'+
	                                              '<use xlink:href="img/workflow.svg#icon-action"></use>'+
	                                            '</svg>'+
	                                        '</a>'+

	                                        '<a href="javascript:;" type="button" class="btn btn-add show-steps" onclick="showSteps(this);" id="addstep'+dt+'">'+
	                                            '<svg class="icon icon-action show-steps">'+
	                                              '<use xlink:href="img/workflow.svg#icon-action"></use>'+
	                                            '</svg>'+
	                                        '</a>'+
	                                    '</div>'+
	                                '</div>'+
	                            '</li>'+
	                        '</ul>'+
	                    '</li>'+
	                    '<li class="decision-steps">'+
	                        '<div class="condition-rule no">'+
	                            '<svg class="icon icon-action">'+
	                              '<use xlink:href="img/workflow.svg#icon-thumbs-down"></use>'+
	                            '</svg>'+
	                        '</div>'+
	                        '<ul class="wf-steps-subtree">'+
	                            '<li>'+
	                              '<div class="step-container">'+
	                                '<div class="add-step only-condition">'+
	                                  '<a href="javascript:;" type="button" class="btn btn-add ng-hide">'+
	                                    '<svg class="icon icon-action">'+
	                                      '<use xlink:href="img/workflow.svg#icon-action"></use>'+
	                                    '</svg>'+
	                                  '</a>'+

	                                  '<a href="javascript:;" type="button" class="btn btn-add show-steps" onclick="showSteps(this);" id="addstep'+(dt*2)+'">'+
	                                    '<svg class="icon icon-action show-steps">'+
	                                      '<use xlink:href="img/workflow.svg#icon-action"></use>'+
	                                    '</svg>'+
	                                  '</a>'+
	                                '</div>'+
	                              '</div>'+
	                            '</li>'+
	                        '</ul>'+
	                    '</li>'+
	               '</ul>'+
	            '</div>'+
	        '</li>'+
	    '</ul>'+
	'</div>');

	$(".workflow-steps").remove();
	$(".first-step-explanation").remove();
	showConditionSidebar();
}
//end

/*******************************************************************
	This will add action step to the canvas
********************************************************************/ 
function addActionStep(id){
	var dt = Date.now();
	$("#addstep"+id).parent().parent().parent().append('<div class="ng-scope ng-isolate-scope action-step">'+
	    '<ul class="wf-steps-subtree ng-scope">'+
	        '<li>'+
	            '<div class="step-container step-incomplete">'+
	                '<div class="step-content action step-selected" onclick="showActionSidebar();">'+
	                    '<a class="remove-step" href="javascript:;" onclick="removeStep(\'action\');">'+
	                      '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>'+
	                    '</a>'+

	                    '<div class="step-details">'+
	                        '<svg class="icon mb15 mt15">'+
	                            '<use xlink:href="img/workflow.svg#icon-cog"></use>'+
	                        '</svg>'+
	                        '<div class="define-content mt0 ng-binding ng-scope">Define action</div>'+
	                    '</div>'+
	                '</div>'+
	                '<div class="add-step ng-scope">'+
	                    '<a href="javascript:;" type="button" class="btn btn-add ng-hide">'+
	                      '<svg class="icon icon-action">'+
	                        '<use xlink:href="img/workflow.svg#icon-action"></use>'+
	                      '</svg>'+
	                    '</a>'+

	                    '<a href="javascript:;" type="button" class="btn btn-add show-steps" onclick="showSteps(this);" id="addstep'+dt+'">'+
	                      '<svg class="icon icon-action show-steps">'+
	                        '<use xlink:href="img/workflow.svg#icon-action"></use>'+
	                      '</svg>'+
	                    '</a>'+
	                '</div>'+

	                '<div class="ng-scope"></div>'+
	            '</div>'+
	        '</li>'+
	    '</ul>'+
	'</div>');

	$(".workflow-steps").remove();
	$(".first-step-explanation").remove();
	showActionSidebar();
}
//end

/*******************************************************************
	This will remove step from the canvas
********************************************************************/
function removeStep(type){
	if(type == "condition"){
		var conditionIndex = parseInt($(".wf-builder").attr("data-index"));
		var w = parseInt($(".wf-builder").width());

		if(conditionIndex > 1){
			$(".wf-builder").attr("data-index",conditionIndex - 1);
			$(".wf-builder").css("width",w - 260);
		}
	}

	$("."+type+"-step").remove();
}
//end

/*******************************************************************
	This will show email sidebar
********************************************************************/
function showEmailSidebar(){
	$(".incomplete-steps").addClass("ng-hide");
	$("h3.ng-binding").addClass("ng-hide");
	$("h3.email-header").removeClass("ng-hide");

	if($("form.ng-pristine").find(".sidebar-content-bottom ").length == 0){
		$("form.ng-pristine").append('<div class="sidebar-content-bottom ng-scope">'+
    		'<button class="btn ng-binding" type="submit">Save</button>'+
   			'<a class="cancel pull-right ng-binding ng-scope" href="javascript:;" onclick="showDefaultSidebar();">Cancel</a>'+
		'</div>');
	}

	$("form.ng-pristine .sidebar-content").css("bottom","72px");
	$("form.ng-pristine .sidebar-content .sidebar-include").html('<div class="ng-scope ng-isolate-scope email-scope">'+
	    '<div class="row">'+
	        '<div class="col-xs-12">'+
	            '<div class="form-group pb0">'+
	                '<label class="ng-binding">Subject</label>'+
	            '</div>'+
	            '<div class="input-group personalisation">'+
	                '<div class="form-control emoji-container insert">'+
	                    '<div type="text" class="ng-pristine ng-valid fake-input ng-scope" contenteditable="true"></div><input type="text" value="" emoji="" class="ng-pristine ng-valid" style="visibility: hidden;"><div class="emojiPickerIconWrap input-group-btn"><button type="button" class="emojiPickerIcon black"></button></div>'+
	                '</div>'+

	                '<div class="input-group-btn">'+
	                    '<button type="button" class="btn btn-default dropdown-toggle ng-binding" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Insert <span class="caret"></span></button>'+
	                    '<ul class="dropdown-menu dropdown-menu-right">'+
	                        '<li class="ng-scope"><a href="javascript:;" class="ng-binding">Email</a></li>'+
	                        '<li class="ng-scope"><a href="javascript:;" class="ng-binding">Name</a></li>'+
	                        '<li class="ng-scope"><a href="javascript:;" class="ng-binding">Last name</a></li>'+
	                        '<li class="ng-scope"><a href="javascript:;" class="ng-binding">Company</a></li>'+
	                        '<li class="ng-scope"><a href="javascript:;" class="ng-binding">Country</a></li>'+
	                        '<li class="ng-scope"><a href="javascript:;" class="ng-binding">City</a></li>'+
	                        '<li class="ng-scope"><a href="javascript:;" class="ng-binding">Phone</a></li>'+
	                        '<li class="ng-scope"><a href="javascript:;" class="ng-binding">State</a></li>'+
	                        '<li class="ng-scope"><a href="javascript:;" class="ng-binding">ZIP</a></li>'+
	                    '</ul>'+
	                '</div>'+
	            '</div>'+
	        '</div>'+
	    '</div>'+

	    '<div class="row">'+
	        '<div class="col-xs-12">'+
	            '<div class="form-group">'+
	                '<label class="ng-binding">Who is it from?</label>'+
	                '<div class="row">'+
	                    '<div class="col-xs-12">'+
	                        '<input type="text" class="form-control pull-left mb15 ng-pristine ng-valid" placeholder="Sender Name" value="">'+
	                    '</div>'+
	                '</div>'+
	                '<div class="row">'+
	                    '<div class="col-xs-12">'+
	                        '<input type="text" class="form-control pull-left validate-email ng-pristine ng-valid" placeholder="Sender Email" value="">'+
	                    '</div>'+
	                '</div>'+
	            '</div>'+
	        '</div>'+
	    '</div>'+

	    '<div class="row">'+
	        '<div class="col-xs-12">'+
	            '<div class="form-group">'+
	                '<label class="ng-binding">Email content</label>'+
	                '<div class="row">'+
	                    '<div class="col-xs-12 ng-scope">'+
	                        '<a href="javascript:;" class="btn btn-block btn-default ng-binding">Design email</a>'+

	                        '<div class="email-content-loader ng-hide">'+
	                            '<div class="sk-spinner sk-spinner-three-bounce">'+
	                                '<div class="sk-bounce1"></div>'+
	                                '<div class="sk-bounce2"></div>'+
	                                '<div class="sk-bounce3"></div>'+
	                            '</div>'+
	                        '</div>'+
	                    '</div>'+
	                '</div>'+
	            '</div>'+
	        '</div>'+
	    '</div>'+

	    '<div class="row">'+
	        '<div class="col-xs-12">'+
	          '<div class="form-group">'+
	            '<label class="ng-binding">Google Analytics</label>'+
	            '<div class="ml-custom-checkbox color-green">'+
	              '<div class="form-check">'+
	                '<label class="custom-checkbox-label">'+
	                  '<input class="custom-check-input" type="checkbox">'+
	                  '<span class="ng-binding">Use Google Analytics link tracking to track clicks from your campaign.</span>'+
	                '</label>'+
	                '<div class="custom-checkbox-description ng-binding">Requires Google Analytics on your website.</div>'+
	              '</div>'+
	            '</div>'+
	          '</div>'+
	        '</div>'+
	    '</div>'+

	    '<div class="row">'+
	      '<div class="col-xs-12">'+
	        '<div class="form-group">'+
	          '<label class="ng-binding">Language <a href="javascript:;" class="pull-right" data-content="The language you choose will be used in your unsubscribe page." data-placement="top" data-toggle="popover" data-container="body" data-trigger="hover" data-original-title="" title=""><img width="18" height="18" src="img/ask-40.png"></a></label>'+
	          '<div class="select-wrap full-width ng">'+
	            '<span class="ng-binding">English</span>'+
	            '<select class="form-control ng-pristine ng-valid"><option value="47">(arabic)العربية</option><option value="71">(hebrew)עברית</option><option value="67">(persian)فارسی</option><option value="69">Català</option><option value="43">Český</option><option value="70">Chinese</option><option value="36">Dansk</option><option value="15">Deutsch</option><option value="31">Eesti</option><option value="5" selected="selected">English</option><option value="23">Español</option><option value="24">Español (Mexican)</option><option value="37">Finnish</option><option value="21">Français</option><option value="53">Français - Québec</option><option value="41">Hrvatski</option><option value="22">Italiano</option><option value="30">Latviešu</option><option value="1">Lietuviškai</option><option value="65">Magyar</option><option value="28">Nederlands</option><option value="57">Norsk</option><option value="3">Polski</option><option value="38">Português</option><option value="39">Português (Brazil)</option><option value="55">Română</option><option value="45">Slovenski</option><option value="25">Slovensky</option><option value="26">Svenska</option><option value="51">Türkçe</option><option value="49">Ελληνικά</option><option value="61">Български</option><option value="59">Македонски</option><option value="4">Русский</option><option value="63">Српски</option><option value="27">Українська</option></select>'+
	          '</div>'+
	        '</div>'+
	      '</div>'+
	    '</div>'+
	'</div>');
}
//end

/*******************************************************************
	This will show delay sidebar
********************************************************************/
function showDelaySidebar(){
	$(".incomplete-steps").addClass("ng-hide");
	$("h3.ng-binding").addClass("ng-hide");
	$("h3.delay-header").removeClass("ng-hide");

	if($("form.ng-pristine").find(".sidebar-content-bottom ").length == 0){
		$("form.ng-pristine").append('<div class="sidebar-content-bottom ng-scope">'+
    		'<button class="btn ng-binding" type="submit">Save</button>'+
   			'<a class="cancel pull-right ng-binding ng-scope" href="javascript:;" onclick="showDefaultSidebar();">Cancel</a>'+
		'</div>');
	}

	$("form.ng-pristine .sidebar-content").css("bottom","72px");
	$("form.ng-pristine .sidebar-content .sidebar-include").html('<div class="ng-scope delay-scope">'+
	    '<div class="row">'+
	        '<div class="col-sm-12">'+
	            '<div class="form-group">'+
	                '<label class="ng-binding">Wait</label>'+
	                '<div class="display-flex">'+
	                    '<input type="text" class="form-control pull-left mr10 validate-number ng-pristine ng-valid number">'+
	                    '<div class="select-wrap ng number-width">'+
	                        '<span class="ng-binding ng-scope">day(s)</span>'+
	                        '<select class="form-control ng-pristine ng-valid">'+
	                            '<option value="minutes" class="ng-binding">minute(s)</option>'+
	                            '<option value="hours" class="ng-binding">hour(s)</option>'+
	                            '<option value="days" class="ng-binding">day(s)</option>'+
	                            '<option value="weeks" class="ng-binding">week(s)</option>'+
	                            '<option value="months" class="ng-binding">month(s)</option>'+
	                            '<option disabled="" value=""></option>'+
	                            '<option value="next" class="ng-binding">specific time of the day</option>'+
	                            '<option value="next_weekday" class="ng-binding">specific day of the week</option>'+
	                            '<option value="next_monthday" class="ng-binding">specific day of the month</option>'+
	                            '<option value="next_date" class="ng-binding">specific date of the year</option>'+
	                        '</select>'+
	                    '</div>'+
	                '</div>'+
	            '</div>'+
	        '</div>'+
	    '</div>'+
	'</div>');
}
//end

/*******************************************************************
	This will show condition sidebar
********************************************************************/
function showConditionSidebar(){
	$(".incomplete-steps").addClass("ng-hide");
	$("h3.ng-binding").addClass("ng-hide");
	$("h3.condition-header").removeClass("ng-hide");

	if($("form.ng-pristine").find(".sidebar-content-bottom ").length == 0){
		$("form.ng-pristine").append('<div class="sidebar-content-bottom ng-scope">'+
    		'<button class="btn ng-binding" type="submit">Save</button>'+
   			'<a class="cancel pull-right ng-binding ng-scope" href="javascript:;" onclick="showDefaultSidebar();">Cancel</a>'+
		'</div>');
	}

	$("form.ng-pristine .sidebar-content").css("bottom","72px");
	$("form.ng-pristine .sidebar-content .sidebar-include").html('<div class="ng-scope condition-scope">'+
	  '<div class="ng-scope">'+
	    '<div class="condition-sidebar">'+
	      '<h4 class="ng-binding">Create a condition</h4>'+
	      '<p class="mb30 ng-binding">Add up to 5 conditions. Specify if any or all need to be true for condition to be met.</p>'+
	        '<div class="ml-custom-checkbox color-green mb30">'+
	            '<div class="form-check">'+
	              '<label class="custom-radio-label">'+
	                '<input class="custom-check-input ng-pristine ng-valid" type="radio" value="any_rule" ng-model="sidebar.data.matching_type" checked="" name="0CM">'+
	                '<span class="ng-binding">Any rule</span>'+
	              '</label>'+
	              '<div class="custom-radio-description ng-binding">Select one or a few conditions where ANY rule can match the criteria.</div>'+
	            '</div>'+
	            '<div class="form-check">'+
	              '<label class="custom-radio-label">'+
	                '<input class="custom-check-input ng-pristine ng-valid" type="radio" value="all_rules" name="0CN">'+
	                '<span class="ng-binding">All rules</span>'+
	              '</label>'+
	              '<div class="custom-radio-description ng-binding">Select one or a few conditions where ALL rules must match the criteria.</div>'+
	            '</div>'+
	        '</div>'+
	        '<div class="ng-scope condition-dropdown" id="condition-dropdown-1">'+
	            '<div class="condition-box ng-scope">'+
	              '<h4 class="ng-binding">Condition</h4>'+
	              '<a href="javascript:;" class="delete-condition ng-scope" id="2" onclick="deleteCondition(this);">'+
	                '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>'+
	              '</a>'+
	              '<div class="dropdown">'+
	                '<button class="btn validate-check-attribute-value ng-binding" type="button" data-toggle="dropdown" data-check-value="">Select <span class="caret"></span></button>'+

	                '<style>.validate-check-attribute-value.has-error {border-color:red !important;}</style>'+
	                '<ul class="dropdown-menu">'+
	                  '<li class="ng-scope"><a href="#" class="ng-binding">Campaign activity</a></li>'+
	                  '<li class="ng-scope"><a href="#" class="ng-binding">Workflow activity</a></li>'+
	                  '<li class="ng-scope"><a href="#" class="ng-binding">Custom fields</a></li>'+
	                  '<li class="ng-scope"><a href="#" class="ng-binding">Group membership</a></li>'+
	                  '<li class="ng-scope"><a href="#" class="ng-binding">Segment membership</a></li>'+
	                '</ul>'+
	              '</div>'+
	            '</div>'+
	        '</div>'+
	        '<a href="#" class="pull-left mt15 ng-binding ng-scope addConditionDropdown" onclick="addCondition();">Add another condition</a>'+
	    '</div>'+
	  '</div>'+
	'</div>');
}
//end

/*******************************************************************
	This will show action sidebar
********************************************************************/
function showActionSidebar(){
	$(".incomplete-steps").addClass("ng-hide");
	$("h3.ng-binding").addClass("ng-hide");
	$("h3.action-header").removeClass("ng-hide");

	if($("form.ng-pristine").find(".sidebar-content-bottom ").length == 0){
		$("form.ng-pristine").append('<div class="sidebar-content-bottom ng-scope">'+
    		'<button class="btn ng-binding" type="submit">Save</button>'+
   			'<a class="cancel pull-right ng-binding ng-scope" href="javascript:;" onclick="showDefaultSidebar();">Cancel</a>'+
		'</div>');
	}

	$("form.ng-pristine .sidebar-content").css("bottom","72px");
	$("form.ng-pristine .sidebar-content .sidebar-include").html('<div class="ng-scope action-scope">'+
	    '<div class="action-sidebar">'+
	        '<h4 class="ng-binding">Choose an action</h4>'+
	        '<p class="mb15 ng-binding">Select which action to apply when a subscriber reaches the next action step.</p>'+

	        '<div class="dropdown mb30">'+
	            '<button class="btn validate-check-attribute-value ng-binding" type="button" data-toggle="dropdown" data-check-value="">Select<span class="caret"></span>'+
	            '</button>'+

	            '<ul class="dropdown-menu mb30 with-description">'+
	                '<li class="ng-scope">'+
	                    '<a href="#" onclick="updateCustomField(\'Update custom field\');">'+
	                        '<span class="title ng-binding">Update custom field</span>'+
	                        '<span class="description ng-binding">Update subscriber field with a custom value.</span>'+
	                    '</a>'+
	                '</li>'+
	                '<li class="ng-scope">'+
	                    '<a href="#" onclick="copyToGroup(\'Copy to a group\');">'+
	                        '<span class="title ng-binding">Copy to a group</span>'+
	                        '<span class="description ng-binding">Copy subscriber to another group while remaining in the original group.</span>'+
	                    '</a>'+
	                '</li>'+
	                '<li class="ng-scope">'+
	                    '<a href="#" onclick="moveToGroup(\'Move to a group\');">'+
	                        '<span class="title ng-binding">Move to a group</span>'+
	                        '<span class="description ng-binding">Move a subscriber from the selected group to a new group.</span>'+
	                    '</a>'+
	                '</li>'+
	                '<li class="ng-scope">'+
	                    '<a href="#" onclick="removeFromGroup(\'Remove from group\');">'+
	                        '<span class="title ng-binding">Remove from group</span>'+
	                        '<span class="description ng-binding">Remove a subscriber from a selected group.</span>'+
	                    '</a>'+
	                '</li>'+
	                '<li class="ng-scope">'+
	                    '<a href="#" onclick="markAsUnsubscribed(\'Mark as unsubscribed\');">'+
	                        '<span class="title ng-binding">Mark as unsubscribed</span>'+
	                        '<span class="description ng-binding">Subscriber becomes inactive and is no longer part of the workflow.</span>'+
	                   '</a>'+
	                '</li>'+
	                '<li class="ng-scope">'+
	                    '<a href="#" onclick="moveToAnotherStep(\'Move to another step\');">'+
	                        '<span class="title ng-binding">Move to another step</span>'+
	                        '<span class="description ng-binding">Move subscriber to a different step in the workflow.</span>'+
	                    '</a>'+
	                '</li>'+
	            '</ul>'+
	        '</div>'+
	    '</div>'+
	'</div>');
}
//end

/***********************************************************************
This will show default sidebar
************************************************************************/
function showDefaultSidebar(){
	$(".incomplete-steps").removeClass("ng-hide");
	$("h3.ng-binding").addClass("ng-hide");

	$("form.ng-pristine").find(".sidebar-content-bottom").remove();
	$("form.ng-pristine .sidebar-content").css("bottom","0px");
	$("form.ng-pristine .sidebar-content .sidebar-include").html('<div class="ng-scope">'+
	    '<div class="form-group workflow-report separator">'+
	        '<div class="row mb30">'+
	            '<div class="col-xs-6">'+
	                '<h4 class="ng-binding">Completed</h4>'+
	                '<span class="number ng-binding">0</span>'+
	            '</div>'+

	            '<div class="col-xs-6">'+
	                '<h4 class="ng-binding">Queue</h4>'+
	                '<span class="number ng-binding">0</span>'+
	            '</div>'+
	        '</div>'+

	        '<div class="row mb30">'+
	            '<div class="col-xs-12 ng-scope">'+
	                '<a class="btn btn-block btn-default ng-binding">View workflow activity</a>'+
	            '</div>'+
	        '</div>'+
	    '</div>'+

	    '<div class="form-group workflow-report">'+
	        '<div class="row mb30 ng-scope">'+
	            '<div class="col-xs-12">'+
	                '<div class="notification">'+
	                  '<p class="ng-binding">This workflow does not have any sent emails yet.</p>'+
	                '</div>'+
	            '</div>'+
	       '</div>'+

	        '<div class="row mb15">'+
	            '<div class="col-xs-12">'+
	                '<h4 class="ng-binding">Total emails sent</h4>'+
	                '<span class="number green ng-binding ng-scope">0</span>'+
	            '</div>'+
	        '</div>'+

	        '<div class="row mb15">'+
	            '<div class="col-xs-6">'+
	                '<h4 class="ng-binding">Avg. open rate</h4>'+
	                '<span class="number green ng-scope">-</span>'+
	            '</div>'+

	            '<div class="col-xs-6">'+
	                '<h4 class="ng-binding">Avg. click rate</h4>'+
	                '<span class="number blue ng-scope">-</span>'+
	            '</div>'+
	        '</div>'+

	        '<div class="row mb30">'+
	            '<div class="col-xs-6">'+
	                '<h4 class="ng-binding">Avg. unsubscribe rate</h4>'+
	                '<span class="number red ng-scope">-</span>'+
	            '</div>'+

	            '<div class="col-xs-6">'+
	                '<h4 class="ng-binding">Avg. bounce rate</h4>'+
	                '<span class="number red ng-scope">-</span>'+
	            '</div>'+
	        '</div>'+

	        '<div class="row mb30">'+
	          '<div class="col-xs-12">'+
	            '<a class="btn btn-block btn-default ng-binding">View full report</a>'+
	          '</div>'+
	        '</div>'+
	    '</div>'+
	'</div>');
	
}
//end

/*******************************************************************
Update subscriber field with a custom value.
********************************************************************/
function updateCustomField(value){
	var _str = value.toLowerCase();
	var str = _str.replace(" ","_");
	$(".validate-check-attribute-value").html(value).attr("data-check-value",str);

	$(".action-sidebar").find(".row").remove();
	$(".action-sidebar").append('<div class="row ng-scope actionSidebarCustomField">'+
	    '<div class="col-xs-12">'+
	        '<div class="form-group next-row ng-hide">'+
	            '<div class="email-content-loader trigger-error-on-visible">'+
	                '<div class="sk-spinner sk-spinner-three-bounce">'+
	                    '<div class="sk-bounce1"></div>'+
	                    '<div class="sk-bounce2"></div>'+
	                    '<div class="sk-bounce3"></div>'+
	                '</div>'+
	            '</div>'+
	        '</div>'+

	        '<div class="dropdown ng-scope">'+
	            '<label class="ng-binding">Custom field<a class="create-new pull-right ng-binding" href="javascript:;">Create a new field</a>'+
	            '</label>'+
	            '<div class="display-flex create-new-field-holder ng-hide">'+
	              '<div class="input">'+
	                '<input class="form-control ng-pristine ng-valid" type="text" placeholder="Name your custom field">'+
	              '</div>'+
	            '</div>'+
	            '<div class="display-flex create-new-field-holder mt15 ng-hide">'+
	                '<div class="input">'+
	                    '<div class="dropdown">'+
	                        '<button class="btn ng-binding" type="button" data-toggle="dropdown">Field type: Text<span class="caret"></span></button>'+
	                        '<ul class="dropdown-menu">'+
	                            '<li class="ng-scope"><a href=""><span class="title ng-binding">Text</span></a></li>'+
	                            '<li class="ng-scope"><a href=""><span class="title ng-binding">Number</span></a></li>'+
	                            '<li class="ng-scope"><a href=""><span class="title ng-binding">Date</span></a></li>'+
	                        '</ul>'+
	                    '</div>'+
	                '</div>'+
	                '<div class="create"><a href="#" class="btn btn-primary ng-binding">Create</a></div>'+
	                '<div class="cancel"><a class="btn ng-binding" href="#">Cancel</a></div>'+
	            '</div>'+

	            '<button class="btn validate-check-attribute-value ng-binding ng-scope" type="button" data-toggle="dropdown" data-check-value="{&quot;id&quot;:&quot;12&quot;,&quot;title&quot;:&quot;Company&quot;,&quot;var&quot;:&quot;company&quot;,&quot;type&quot;:&quot;TEXT&quot;}">Company<span class="caret"></span></button>'+
	            '<ul class="dropdown-menu mb30 with-search ng-scope ng-isolate-scope">'+
	                '<div class="search-bar ng-hide">'+
	                    '<span class="glyphicon glyphicon-search ng-scope" aria-hidden="true" style="cursor: pointer"></span>'+
	                    '<input class="form-control ng-pristine ng-valid" type="text" placeholder="Search..">'+
	                '</div>'+

	                '<div style="max-height: 300px;overflow:auto;">'+
	                    '<li class="ng-scope"><a href="#" class="ng-binding">Name</a></li>'+
	                    '<li class="ng-scope"><a href="#" class="ng-binding">Last name</a></li>'+
	                    '<li class="ng-scope selected"><a href="#" class="ng-binding">Company</a></li>'+
	                    '<li class="ng-scope"><a href="#" class="ng-binding">Country</a></li>'+
	                    '<li class="ng-scope"><a href="#" class="ng-binding">City</a></li>'+
	                    '<li class="ng-scope"><a href="#" class="ng-binding">Phone</a></li>'+
	                    '<li class="ng-scope"><a href="#" class="ng-binding">State</a></li>'+
	                    '<li class="ng-scope"><a href="#" class="ng-binding">ZIP</a></li>'+
	                '</div>'+
	            '</ul>'+
	        '</div>'+
	        '<div class="ng-scope">'+
	            '<div class="ng-scope">'+
	                '<div class="ng-scope">'+
	                    '<input type="text" class="form-control ng-pristine ng-valid" placeholder="Please enter a value">'+
	                '</div>'+
	            '</div>'+
	        '</div>'+
	    '</div>'+
	'</div>');
}
//end

/***********************************************************************
Copy subscriber to another group while remaining in the original group.
************************************************************************/
function copyToGroup(value){
	var _str = value.toLowerCase();
	var str = _str.replace(" ","_");
	$(".validate-check-attribute-value").html(value).attr("data-check-value",str);

	$(".action-sidebar").find(".row").remove();
	$(".action-sidebar").append('<div class="row ng-scope actionSidebarSubscriberGroup">'+
	    '<div class="col-xs-12">'+
	        '<div class="form-group next-row ng-hide">'+
	            '<div class="email-content-loader trigger-error-on-visible">'+
	                '<div class="sk-spinner sk-spinner-three-bounce">'+
	                    '<div class="sk-bounce1"></div>'+
	                    '<div class="sk-bounce2"></div>'+
	                    '<div class="sk-bounce3"></div>'+
	                '</div>'+
	            '</div>'+
	        '</div>'+

	        '<div class="dropdown ng-scope">'+
	            '<label class="ng-binding">Subscriber group<a class="create-new pull-right ng-binding">Create a new group</a></label>'+

	            '<div class="display-flex create-new-group-holder ng-hide" style="">'+
	                '<div class="input">'+
	                    '<input class="form-control ng-pristine ng-valid" type="text" placeholder="Group name" style="">'+
	                '</div>'+

	                '<div class="create">'+
	                    '<a href="#" class="btn btn-primary ng-binding">Create</a>'+
	                '</div>'+

	                '<div class="cancel">'+
	                    '<a class="btn ng-binding" href="#">Cancel</a>'+
	                '</div>'+
	            '</div>'+

	            '<button ng-show="!actionData.inlineGroupCreation" class="btn validate-check-attribute-value ng-binding" type="button" data-toggle="dropdown" data-check-value="{&quot;name&quot;:&quot;sddd ddfdfd&quot;,&quot;account_id&quot;:1177,&quot;updated&quot;:&quot;2019-09-13 16:54:35&quot;,&quot;date&quot;:&quot;2019-09-13 16:54:35&quot;,&quot;id&quot;:59040592,&quot;excluded&quot;:false,&quot;total&quot;:0}" aria-expanded="false">sddd ddfdfd<span class="caret"></span></button>'+

	            '<ul class="dropdown-menu mb30 with-search with-description ng-isolate-scope">'+
	                '<div class="search-bar ng-hide">'+
	                    '<span class="glyphicon glyphicon-search ng-scope" aria-hidden="true" style="cursor: pointer" ng-click="doSearch()"></span>'+

	                    '<input class="form-control ng-pristine ng-valid" type="text" placeholder="Search..">'+
	                '</div>'+

	                '<div style="max-height: 300px;overflow:auto;">'+
	                    '<li class="ng-scope">'+
	                        '<a href="#" class="ng-binding">'+
	                            '<div class="title">sddd</div>'+
	                            '<div class="description small">0 subscribers • Created on 2019-09-13 16:54:21</div>'+
	                        '</a>'+
	                    '</li>'+
	                    '<li class="ng-scope selected">'+
	                        '<a href="#" class="ng-binding">'+
	                            '<div class="title">sddd ddfdfd</div>'+
	                            '<div class="description small">0 subscribers • Created on 2019-09-13 16:54:35</div>'+
	                        '</a>'+
	                    '</li>'+
	                '</div>'+
	            '</ul>'+
	        '</div>'+
	    '</div>'+
	'</div>');
}
//end

/***********************************************************************
Move a subscriber from the selected group to a new group.
************************************************************************/
function moveToGroup(value){
	var _str = value.toLowerCase();
	var str = _str.replace(" ","_");
	$(".validate-check-attribute-value").html(value).attr("data-check-value",str);

	$(".action-sidebar").find(".row").remove();
	$(".action-sidebar").append('<div class="row ng-scope actionSidebarMoveToGroup">'+
	    '<div class="col-xs-12">'+
	        '<div class="form-group next-row ng-hide">'+
	            '<div class="email-content-loader trigger-error-on-visible">'+
	                '<div class="sk-spinner sk-spinner-three-bounce">'+
	                    '<div class="sk-bounce1"></div>'+
	                    '<div class="sk-bounce2"></div>'+
	                    '<div class="sk-bounce3"></div>'+
	                '</div>'+
	            '</div>'+
	        '</div>'+

	        '<div class="dropdown ng-scope">'+
	            '<label class="ng-binding">Move from</label>'+
	            '<button class="btn validate-check-attribute-value ng-binding" type="button" data-toggle="dropdown" data-check-value="{&quot;id&quot;:&quot;1379&quot;,&quot;name&quot;:&quot;sss List&quot;,&quot;total&quot;:&quot;1&quot;,&quot;date&quot;:&quot;2010-11-13 00:00:00&quot;,&quot;excluded&quot;:&quot;&quot;}"> sss List<span class="caret"></span></button>'+

	            '<ul class="dropdown-menu mb30 with-search with-description ng-isolate-scope">'+
	                '<div class="search-bar ng-hide">'+
	                    '<span class="glyphicon glyphicon-search ng-scope" aria-hidden="true" style="cursor: pointer"></span>'+
	                    '<input class="form-control ng-pristine ng-valid" type="text" placeholder="Search..">'+
	                '</div>'+
	                '<div style="max-height: 300px;overflow:auto;">'+
	                    '<li class="ng-scope selected">'+
	                        '<a href="#" class="ng-binding"><div class="title">sss List</div><div class="description small">1 subscribers • Created on 2010-11-13 00:00:00</div></a>'+
	                    '</li>'+
	                    '<li class="ng-scope">'+
	                        '<a href="#" class="ng-binding"><div class="title">sddd</div><div class="description small">0 subscribers • Created on 2019-09-13 16:54:21</div></a>'+
	                    '</li>'+
	                    '<li class="ng-scope">'+
	                        '<a href="#" class="ng-binding"><div class="title">sddd ddfdfd</div><div class="description small">0 subscribers • Created on 2019-09-13 16:54:35</div></a>'+
	                    '</li>'+
	                '</div>'+
	            '</ul>'+
	        '</div>'+

	        '<div class="dropdown ng-scope">'+
	            '<label class="ng-binding">Move to<a class="create-new pull-right" href="javascript:;">Create a new group</a>'+
	            '</label>'+

	            '<div class="display-flex create-new-group-holder ng-hide" style="">'+
	                '<div class="input">'+
	                    '<input class="form-control ng-pristine ng-valid" type="text" placeholder="Group name" style="">'+
	                '</div>'+

	                '<div class="create">'+
	                    '<a href="#" class="btn btn-primary ng-binding">Create</a>'+
	                '</div>'+

	                '<div class="cancel">'+
	                    '<a class="btn ng-binding" href="#">Cancel</a>'+
	                '</div>'+
	            '</div>'+

	            '<button class="btn validate-check-attribute-value ng-binding" type="button" data-toggle="dropdown" data-check-value="">Select one of your subscriber groups<span class="caret"></span>'+
	            '</button>'+

	            '<ul class="dropdown-menu mb30 with-search with-description ng-isolate-scope">'+
	                '<div class="search-bar ng-hide">'+
	                    '<span class="glyphicon glyphicon-search ng-scope" aria-hidden="true" style="cursor: pointer"></span>'+
	                    '<input class="form-control ng-pristine ng-valid" type="text" placeholder="Search..">'+
	                '</div>'+
	                '<div style="max-height: 300px;overflow:auto;">'+
	                    '<li class="ng-scope">'+
	                        '<a href="#" class="ng-binding"><div class="title">sddd</div><div class="description small">0 subscribers • Created on 2019-09-13 16:54:21</div></a>'+
	                    '</li>'+
	                    '<li class="ng-scope">'+
	                        '<a href="#" class="ng-binding"><div class="title">sddd ddfdfd</div><div class="description small">0 subscribers • Created on 2019-09-13 16:54:35</div></a>'+
	                    '</li>'+
	                '</div>'+
	            '</ul>'+
	        '</div>'+
	    '</div>'+
	'</div>');
}
//end

/***********************************************************************
Remove a subscriber from a selected group.
************************************************************************/
function removeFromGroup(value){
	var _str = value.toLowerCase();
	var str = _str.replace(" ","_");
	$(".validate-check-attribute-value").html(value).attr("data-check-value",str);

	$(".action-sidebar").find(".row").remove();
	$(".action-sidebar").append('<div class="row ng-scope actionSidebarRemoveFromGroup">'+
	    '<div class="col-xs-12">'+
	        '<div class="form-group next-row ng-hide">'+
	            '<div class="email-content-loader trigger-error-on-visible">'+
	                '<div class="sk-spinner sk-spinner-three-bounce">'+
	                    '<div class="sk-bounce1"></div>'+
	                    '<div class="sk-bounce2"></div>'+
	                    '<div class="sk-bounce3"></div>'+
	                '</div>'+
	            '</div>'+
	        '</div>'+

	        '<div class="dropdown ng-scope" ng-if="!groupsLoading">'+
	            '<label class="ng-binding">Subscriber group</label>'+
	            '<button class="btn validate-check-attribute-value ng-binding" type="button" data-toggle="dropdown" data-check-value="">Select one of your subscriber groups<span class="caret"></span></button>'+

	            '<ul class="dropdown-menu mb30 with-search with-description ng-isolate-scope">'+
	                '<div class="search-bar ng-hide">'+
	                    '<span class="glyphicon glyphicon-search ng-scope" aria-hidden="true" style="cursor: pointer"></span>'+
	                    '<input class="form-control ng-pristine ng-valid" type="text" placeholder="Search..">'+
	                '</div>'+
	                '<div style="max-height: 300px;overflow:auto;">'+
	                    '<li class="ng-scope">'+
	                        '<a href="#" class="ng-binding">'+
	                            '<div class="title">sss List</div>'+
	                            '<div class="description small">1 subscribers • Created on 2010-11-13 00:00:00</div>'+
	                        '</a>'+
	                    '</li>'+
	                    '<li class="ng-scope">'+
	                        '<a href="#" class="ng-binding">'+
	                            '<div class="title">sddd</div>'+
	                            '<div class="description small">0 subscribers • Created on 2019-09-13 16:54:21</div>'+
	                        '</a>'+
	                    '</li>'+
	                    '<li class="ng-scope">'+
	                        '<a href="#" class="ng-binding">'+
	                            '<div class="title">sddd ddfdfd</div>'+
	                            '<div class="description small">0 subscribers • Created on 2019-09-13 16:54:35</div>'+
	                        '</a>'+
	                    '</li>'+
	                '</div>'+
	            '</ul>'+
	        '</div>'+
	    '</div>'+
	'</div>');
}
//end

/***********************************************************************
Subscriber becomes inactive and is no longer part of the workflow.
************************************************************************/
function markAsUnsubscribed(value){
	var _str = value.toLowerCase();
	var str = _str.replace(" ","_");
	$(".validate-check-attribute-value").html(value).attr("data-check-value",str);
	$(".action-sidebar").find(".row").remove();
}
//end

/***********************************************************************
Move subscriber to a different step in the workflow.
************************************************************************/
function moveToAnotherStep(value){
	var _str = value.toLowerCase();
	var str = _str.replace(" ","_");
	$(".validate-check-attribute-value").html(value).attr("data-check-value",str);

	$(".action-sidebar").find(".row").remove();
	$(".action-sidebar").append('<div class="row ng-scope actionSidebarFilterByStep">'+
	    '<div class="col-xs-12">'+
	        '<label class="ng-binding">Filter by step</label>'+
	        '<div class="dropdown">'+
	            '<button class="btn validate-check-attribute-value ng-binding" type="button" data-toggle="dropdown" data-check-value="" aria-expanded="false">Select<span class="caret"></span></button>'+
	            '<ul class="dropdown-menu">'+
	                '<li class="ng-scope"><a href="#" class="ng-binding">Action</a></li>'+
	                '<li class="ng-scope"><a href="#" class="ng-binding">Condition</a></li>'+
	                '<li class="ng-scope"><a href="#" class="ng-binding">Delay</a></li>'+
	                '<li class="ng-scope"><a href="#" class="ng-binding">Email</a></li>'+
	            '</ul>'+
	        '</div>'+
	    '</div>'+
	'</div>');
}
//end

/***********************************************************************
This will add more condition dropdown
************************************************************************/
function addCondition(){
	var len = $(".condition-sidebar").find(".condition-dropdown").length;
	if(len < 4 ){
		var _index = $(".condition-dropdown").last().attr("id").split("-")[2];
		var index = parseInt(_index) + 1;
		$(".condition-dropdown").last().append('<div class="connect-line ng-scope" id="condition-line-'+index+'"><span class="and ng-scope">or</span><hr></div>');

		$(".condition-dropdown").last().after('<div class="ng-scope condition-dropdown" id="condition-dropdown-'+index+'">'+
		    '<div class="condition-box ng-scope">'+
		        '<h4 class="ng-binding">Condition</h4>'+
		        '<a href="javascript:;" class="delete-condition ng-scope" onclick="deleteCondition(this);" id="'+index+'"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>'+
		        '<div class="dropdown">'+
		            '<button class="btn validate-check-attribute-value ng-binding" type="button" data-toggle="dropdown" data-check-value="">Select<span class="caret"></span></button>'+
		            '<style>.validate-check-attribute-value.has-error {border-color: red !important;}</style>'+
		            '<ul class="dropdown-menu">'+
		                '<li class="ng-scope"><a href="#" class="ng-binding">Campaign activity</a></li>'+
		                '<li class="ng-scope"><a href="#" class="ng-binding">Workflow activity</a></li>'+
		                '<li class="ng-scope"><a href="#" class="ng-binding">Custom fields</a></li>'+
		                '<li class="ng-scope"><a href="#" class="ng-binding">Group membership</a></li>'+
		                '<li class="ng-scope"><a href="#" class="ng-binding">Segment membership</a></li>'+
		            '</ul>'+
		        '</div>'+
		    '</div>'+
		'</div>');
	}

	if(len == 4 ){
		$(".addConditionDropdown").addClass("ng-hide");
		var _index = $(".condition-dropdown").last().attr("id").split("-")[2];
		var index = parseInt(_index) + 1;
		$(".condition-dropdown").last().append('<div class="connect-line ng-scope" id="condition-line-'+index+'"><span class="and ng-scope">or</span><hr></div>');

		$(".condition-dropdown").last().after('<div class="ng-scope condition-dropdown" id="condition-dropdown-'+index+'">'+
		    '<div class="condition-box ng-scope">'+
		        '<h4 class="ng-binding">Condition</h4>'+
		        '<a href="javascript:;" class="delete-condition ng-scope" onclick="deleteCondition(this);" id="'+index+'"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>'+
		        '<div class="dropdown">'+
		            '<button class="btn validate-check-attribute-value ng-binding" type="button" data-toggle="dropdown" data-check-value="">Select<span class="caret"></span></button>'+
		            '<style>.validate-check-attribute-value.has-error {border-color: red !important;}</style>'+
		            '<ul class="dropdown-menu">'+
		                '<li class="ng-scope"><a href="#" class="ng-binding">Campaign activity</a></li>'+
		                '<li class="ng-scope"><a href="#" class="ng-binding">Workflow activity</a></li>'+
		                '<li class="ng-scope"><a href="#" class="ng-binding">Custom fields</a></li>'+
		                '<li class="ng-scope"><a href="#" class="ng-binding">Group membership</a></li>'+
		                '<li class="ng-scope"><a href="#" class="ng-binding">Segment membership</a></li>'+
		            '</ul>'+
		        '</div>'+
		    '</div>'+
		'</div>');
	}
}
//end

/***********************************************************************
This will delete condition dropdown
************************************************************************/
function deleteCondition(e){
	var len = $(".condition-sidebar").find(".condition-dropdown").length;
	if(len <= 5 ){$(".addConditionDropdown").removeClass("ng-hide");}
	$(e).parent().parent().remove();
 	var d = $(e).attr("id");
 	$("#condition-line-"+d).remove();
 	// if(num == 2){}
}
//end