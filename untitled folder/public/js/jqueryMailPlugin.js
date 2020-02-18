// Material Select Initialization
$(document).ready(function() {
    $('.mdb-select').materialSelect();
});

function GetSelectedText() {
    var actionItems = document.querySelectorAll("#actions ul > li");
    var actionBox = document.querySelectorAll(".defineAction");
    const acitemsArray = Array.from(actionItems);
    acitemsArray.forEach(function(item) {
        item.addEventListener('click', (event) => {
            event.stopPropagation;
            actionBox.forEach(function(box) {
                box.innerHTML = item.innerHTML;
            })
        }
        )
    })
}

function GetSelectedTextt() {
    var items = document.querySelectorAll(".conditions ul > li");
    var conditionBox = document.querySelectorAll(".defineCondition");
    const itemsArray = Array.from(items);
    itemsArray.forEach(function(item) {
        item.addEventListener('click', (event) => {
            event.stopPropagation;
            conditionBox.forEach(function(box) {
                box.innerHTML = item.textContent;
            })
        }
        )
    })
}

function changeToOr() {
    var rule1 = document.getElementById("anyRules");
    var conjs = document.querySelectorAll(".the-span");
    const conjsArray = Array.from(conjs);
        rule1.addEventListener('change', (event) => {
            conjsArray.forEach(function(conj) {
            event.stopPropagation;
            conj.textContent = "or";
        })
    })
}

function changeToAnd() {
    var rule2 = document.getElementById("allRules");
    var conjs = document.querySelectorAll(".the-span");
    const conjsArray = Array.from(conjs);
        rule2.addEventListener('change', (event) => {
            conjsArray.forEach(function(conj) {
            conj.textContent = "and";
        })
    })
}

$(document).on("click", function(e) {
    if (!$(e.target).hasClass("show-steps")) {
        $(".workflow-steps").remove();
    }
});

function showSteps(e) {
    var id = $(e).attr("id").split("addstep")[1];
    $('#addstep' + id).popover({
        html: true,
        trigger: "manual",
        placement: "auto",
        container: ".ml-workflow-builder-area",
        viewport: { selector: ".ml-workflow-builder-area", padding: 30 },
        template: '<div class="popover workflow-steps" role="tooltip"><div class="arrow"></div><div class="popover-content"></div></div>',
        content: function() {
            var content = '<div class="steps-popup ng-scope">' +
                '<h3 class="ng-binding">Add a next step to your workflow</h3>' +
                '<ul>' +
                '<li onclick="addConditionStep(' + id + ');">' +
                '<img src="assets/images/diagram.svg" style="width:40%;">' +
                '<div class="ng-binding">Condition</div>' +
                '</li>' +
                '<li onclick="addActionStep(' + id + ');">' +
                '<img src="assets/images/settings-gears.svg" style="width:40%;">' +
                '<div class="ng-binding">Action</div>' +
                '</li>' +
                '</ul>' +
                '</div>';
            return content;
        }
    });

    $('#addstep' + id).popover("show");
}

/*******************************************************************
	This will add email step to the canvas
********************************************************************/
function addEmailStep(id) {
    var dt = Date.now();
    $("#addstep" + id).parent().parent().parent().append('<div class="ng-scope ng-isolate-scope email-step">' +
        '<ul class="wf-steps-subtree ng-scope">' +
        '<li>' +
        '<div class="step-container step-incomplete">' +
        '<div class="step-content email step-selected" onclick="showEmailSidebar();">' +
        '<div class="ng-hide">' +
        '<div class="sk-spinner sk-spinner-three-bounce">' +
        '<div class="sk-bounce1"></div>' +
        '<div class="sk-bounce2"></div>' +
        '<div class="sk-bounce3"></div>' +
        '</div>' +
        '</div>' +
        '<a class="remove-step" href="javascript:;" onclick="removeStep(\'email\');">' +
        '<i class="icon-remove"></i>' +
        '</a>' +
        '<div class="step-details">' +
        '<div class="email-screenshot">' +
        '<img step_id="4750506" class="ng-isolate-scope" src="img/workflow_screenshot.png">' +
        '</div>' +

        '<div class="define-content ng-binding ng-scope">Define email content</div>' +
        '</div>' +
        '</div>' +
        '<div class="add-step">' +
        '<a href="javascript:;" type="button" class="btn btn-add ng-hide">' +
        '<svg class="icon icon-action">' +
        '<use xlink:href="assets/images/svg/workflow.svg#icon-action"></use>' +
        '</svg>' +
        '</a>' +

        '<a href="javascript:;" type="button" class="btn btn-add show-steps" onclick="showSteps(this);" id="addstep' + dt + '">' +
        '<svg class="icon icon-action show-steps">' +
        '<use xlink:href="assets/images/svg/workflow.svg#icon-action"></use>' +
        '</svg>' +
        '</a>' +
        '</div>' +
        '</div>' +
        '</li>' +
        '</ul>' +
        '</div>');

    $(".workflow-steps").remove();
    $(".first-step-explanation").remove();
    showEmailSidebar();
}
//end

/*******************************************************************
	This will add delay step to the canvas
********************************************************************/
function addDelayStep(id) {
    var dt = Date.now();
    $("#addstep" + id).parent().parent().parent().append('<div class="ng-scope ng-isolate-scope delay-step">' +
        '<ul class="wf-steps-subtree ng-scope">' +
        '<li>' +
        '<div class="step-container step-incomplete">' +
        '<div class="step-content delay step-selected" onclick="showDelaySidebar();">' +
        '<a class="remove-step" href="javascript:;" onclick="removeStep(\'delay\');">' +
        '<i class="icon-remove"></i>' +
        '</a>' +
        '<div class="set-delay ng-binding ng-scope">Set delay</div>' +
        '</div>' +
        '<div class="add-step">' +
        '<a href="javascript:;" type="button" class="btn btn-add ng-hide">' +
        '<svg class="icon icon-action">' +
        '<use xlink:href="assets/images/svg/workflow.svg#icon-action"></use>' +
        '</svg>' +
        '</a>' +

        '<a href="javascript:;" type="button" class="btn btn-add show-steps" onclick="showSteps(this);" id="addstep' + dt + '">' +
        '<svg class="icon icon-action show-steps">' +
        '<use xlink:href="assets/images/svg/workflow.svg#icon-action"></use>' +
        '</svg>' +
        '</a>' +
        '</div>' +
        '</div>' +
        '</li>' +
        '</ul>' +
        '</div>');

    $(".workflow-steps").remove();
    $(".first-step-explanation").remove();
    showDelaySidebar();
}
//end

/*******************************************************************
	This will add condition step to the canvas
********************************************************************/
function addConditionStep(id) {
    var dt = Date.now();
    var conditionIndex = parseInt($(".wf-builder").attr("data-index"));
    var wfbW = parseInt($(".wf-builder").width());

    if (conditionIndex >= 1) {
        $(".wf-builder").css("width", wfbW + 260);
        $(".wf-builder").attr("data-index", conditionIndex + 1);
    } else { $(".wf-builder").attr("data-index", conditionIndex + 1); }

    $("#addstep" + id).parent().parent().parent().append('<div class="ng-scope ng-isolate-scope condition-step">' +
        '<ul class="wf-steps-subtree decision ng-scope">' +
        '<li>' +
        '<div class="step-container step-incomplete">' +
        '<div class="step-content condition step-selected" onclick="showNewSidebar();">' +
        '<a class="remove-step" href="javascript:;" onclick="removeStep(\'condition\');">' +
        '<i class="icon-remove"></i>' +
        '</a>' +

        '<div class="diamond"></div>' +
        '<svg class="icon">' +
        '<use xlink:href="assets/images/flow.png"></use>' +
        '</svg>' +

        '<div class="define-content mb0 mt0 ng-binding ng-scope defineCondition" id="condition-1">Choose condition</div>' +
        '</div>' +

        '<div class="ng-scope"></div>' +

        '<ul class="wf-steps-subtree condition">' +
        '<li class="decision-steps">' +
        '<div class="condition-rule yes">' +
        '<img src="assets/images/thumbs-up-hand-symbol.svg" style="width: 40%;">' +
        '</div>' +
        '<ul class="wf-steps-subtree">' +
        '<li>' +
        '<div class="step-container">' +
        '<div class="add-step only-condition">' +
        '<a href="javascript:;" type="button" class="btn btn-add ng-hide">' +
        '<svg class="icon icon-action">' +
        '<use xlink:href="assets/images/svg/workflow.svg#icon-action"></use>' +
        '</svg>' +
        '</a>' +

        '<a href="javascript:;" type="button" class="btn btn-add show-steps" onclick="showSteps(this);" id="addstep' + dt + '">' +
        '<svg class="icon icon-action show-steps">' +
        '<use xlink:href="assets/images/svg/workflow.svg#icon-action"></use>' +
        '</svg>' +
        '</a>' +
        '</div>' +
        '</div>' +
        '</li>' +
        '</ul>' +
        '</li>' +
        '<li class="decision-steps">' +
        '<div class="condition-rule no">' +
        '<img src="assets/images/thumbs-down-silhouette.svg" style="width: 40%;">' +
        '</div>' +
        '<ul class="wf-steps-subtree">' +
        '<li>' +
        '<div class="step-container">' +
        '<div class="add-step only-condition">' +
        '<a href="javascript:;" type="button" class="btn btn-add ng-hide">' +
        '<svg class="icon icon-action">' +
        '<use xlink:href="assets/images/svg/workflow.svg#icon-action"></use>' +
        '</svg>' +
        '</a>' +

        '<a href="javascript:;" type="button" class="btn btn-add show-steps" onclick="showSteps(this);" id="addstep' + (dt * 2) + '">' +
        '<svg class="icon icon-action show-steps">' +
        '<use xlink:href="assets/images/svg/workflow.svg#icon-action"></use>' +
        '</svg>' +
        '</a>' +
        '</div>' +
        '</div>' +
        '</li>' +
        '</ul>' +
        '</li>' +
        '</ul>' +
        '</div>' +
        '</li>' +
        '</ul>' +
        '</div>');

    $(".workflow-steps").remove();
    $(".first-step-explanation").remove();
    showNewSidebar();
}
//end

/*******************************************************************
	This will add action step to the canvas
********************************************************************/
function addActionStep(id) {
    var dt = Date.now();
    $("#addstep" + id).parent().parent().parent().append('<div class="ng-scope ng-isolate-scope action-step">' +
        '<ul class="wf-steps-subtree ng-scope">' +
        '<li>' +
        '<div class="step-container step-incomplete">' +
        '<div class="step-content action step-selected" onclick="showActionSidebar();">' +
        '<a class="remove-step" href="javascript:;" onclick="removeStep(\'action\');">' +
        '<i class="icon-remove"></i>' +
        '</a>' +

        '<div class="step-details">' +
        '<svg class="icon">' +
        '<use xlink:href="assets/images/flow.png"></use>' +
        '</svg>' +
        '<div class="define-content mt0 ng-binding ng-scope defineAction"><span>Choose action</span></div>' +
        '</div>' +
        '</div>' +
        '<div class="add-step ng-scope">' +
        '<a href="javascript:;" type="button" class="btn btn-add ng-hide">' +
        '<svg class="icon icon-action">' +
        '<use xlink:href="assets/images/svg/workflow.svg#icon-action"></use>' +
        '</svg>' +
        '</a>' +

        '<a href="javascript:;" type="button" class="btn btn-add show-steps" onclick="showSteps(this);" id="addstep' + dt + '">' +
        '<svg class="icon icon-action show-steps">' +
        '<use xlink:href="assets/images/svg/workflow.svg#icon-action"></use>' +
        '</svg>' +
        '</a>' +
        '</div>' +

        '<div class="ng-scope"></div>' +
        '</div>' +
        '</li>' +
        '</ul>' +
        '</div>');

    $(".workflow-steps").remove();
    $(".first-step-explanation").remove();
    showActionSidebar();
}
//end

/*******************************************************************
	This will remove step from the canvas
********************************************************************/
function removeStep(type) {
    if (type == "condition") {
        var conditionIndex = parseInt($(".wf-builder").attr("data-index"));
        var w = parseInt($(".wf-builder").width());

        if (conditionIndex > 1) {
            $(".wf-builder").attr("data-index", conditionIndex - 1);
            $(".wf-builder").css("width", w - 260);
        }
    }

    $("." + type + "-step").remove();
}
//end

/*******************************************************************
	This will show email sidebar
********************************************************************/
function showEmailSidebar(value) {
   
    var _str = value.toLowerCase();
    var str = _str.replace(" ", "_");
    $(".validate-check-attribute-value").html(value).attr("data-check-value", str);
    $("h3.action-header").addClass("ng-hide");
    $("h3.removetag-header").addClass("ng-hide");
    $("h3.addtag-header").addClass("ng-hide");
    $("h3.delay-header").addClass("ng-hide");
    $("h3.sms-header").addClass("ng-hide");
    $("h3.unsubscribe-header").addClass("ng-hide");
    $("h3.email-header").removeClass("ng-hide");

    $(".action-sidebar").find(".row").remove();
    $(".action-sidebar").append('<div class="row ng-scope actionSidebarRemoveFromGroup">' +
    // $("form.ng-pristine .sidebar-content .sidebar-include").html('<div class="ng-scope ng-isolate-scope email-scope">' +
        '<div class="row">' +
            '<div class="col-xs-12">' +
                '<div class="form-group pb0">' +
                    '<label class="ng-binding">Subject</label>' +
                '</div>' +
        '<div class="input-group personalisation">' +
        '<div class="form-control emoji-container insert">' +
        '<div type="text" class="ng-pristine ng-valid fake-input ng-scope" contenteditable="true"></div><input name="emoji" type="text" value="" emoji="" class="ng-pristine ng-valid" style="visibility: hidden;"><div class="emojiPickerIconWrap input-group-btn"><button type="button" class="emojiPickerIcon black"></button></div>' +
        '</div>' +
        '<div class="input-group-btn">' +
        '<button type="button" class="btn btn-default dropdown-toggle ng-binding" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Insert <span class="caret" style="margin-top: -5px;"></span></button>' +
        '<ul class="dropdown-menu dropdown-menu-right">' +
        '<li class="ng-scope"><a href="javascript:;" class="ng-binding">Email</a></li>' +
        '<li class="ng-scope"><a href="javascript:;" class="ng-binding">Name</a></li>' +
        '<li class="ng-scope"><a href="javascript:;" class="ng-binding">Last name</a></li>' +
        '<li class="ng-scope"><a href="javascript:;" class="ng-binding">Company</a></li>' +
        '<li class="ng-scope"><a href="javascript:;" class="ng-binding">Country</a></li>' +
        '<li class="ng-scope"><a href="javascript:;" class="ng-binding">City</a></li>' +
        '<li class="ng-scope"><a href="javascript:;" class="ng-binding">Phone</a></li>' +
        '<li class="ng-scope"><a href="javascript:;" class="ng-binding">State</a></li>' +
        '<li class="ng-scope"><a href="javascript:;" class="ng-binding">ZIP</a></li>' +
        '</ul>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>' +

        '<div class="row">' +
        '<div class="col-xs-12">' +
        '<div class="form-group">' +
        '<label style="margin-top: 10%;" class="ng-binding">Who is it from?</label>' +
        '<div class="row">' +
        '<div class="dropdown">' +
        '<button style="width:80%; margin-left: 10%;" class="btn validate-check-attribute-value ng-binding" type="button" data-toggle="dropdown"> Sender Name <span class="caret" style="margin-top: -3px;"></span></button>' +
        '<ul class="dropdown-menu mb30">' +
        '<li class="ng-scope"><a href="javascript:;" class="ng-binding">John Doe</a></li>' +
        '<li class="ng-scope"><a href="javascript:;" class="ng-binding">Tine Fash</a></li>' +
        '<li class="ng-scope"><a href="javascript:;" class="ng-binding">Eni Clkkins</a></li>' +
        '</div>' +
        '</div>' +
        '<div class="row">' +
        '<div class="dropdown">' +
        '<button style="width:80%; margin-left: 10%;" class="btn validate-check-attribute-value ng-binding" type="button" data-toggle="dropdown"> Sender Email <span class="caret" style="margin-top: -3px;"></span></button>' +
        '<ul class="dropdown-menu mb30">' +
        '<li class="ng-scope"><a href="javascript:;" class="ng-binding">addd@gmail.com</a></li>' +
        '<li class="ng-scope"><a href="javascript:;" class="ng-binding">work@me.com</a></li>' +
        '<li class="ng-scope"><a href="javascript:;" class="ng-binding">franchise@xyz</a></li>' +
        '<li class="ng-scope"><a href="javascript:;" class="ng-binding">plish@pp.net</a></li>' +
        '</ul>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '<div class="row">' +
        '<div class="col-xs-12">' +
        '<div class="form-group">' +
        '<label class="ng-binding">Email content</label>' +
        '<div class="row">' +
        '<div class="col-xs-12 ng-scope">' +
        '<a href="javascript:;" class="btn btn-default btn-block ng-binding">Design email</a>' +

        '<div class="email-content-loader ng-hide">' +
        '<div class="sk-spinner sk-spinner-three-bounce">' +
        '<div class="sk-bounce1"></div>' +
        '<div class="sk-bounce2"></div>' +
        '<div class="sk-bounce3"></div>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>');
}
//end

/*******************************************************************
	This will show delay sidebar
********************************************************************/
function showDelaySidebar(value) {
    var _str = value.toLowerCase();
    var str = _str.replace(" ", "_");
    $(".validate-check-attribute-value").html(value).attr("data-check-value", str);
    $("h3.action-header").addClass("ng-hide");
    $("h3.removetag-header").addClass("ng-hide");
    $("h3.addtag-header").addClass("ng-hide");
    $("h3.sms-header").addClass("ng-hide");
    $("h3.unsubscribe-header").addClass("ng-hide");
    $("h3.email-header").addClass("ng-hide");
    $("h3.delay-header").removeClass("ng-hide");

    $(".action-sidebar").find(".row").remove();
    $(".action-sidebar").append('<div class="row ng-scope actionSidebarRemoveFromGroup">' +
    '<div class="row">'+
    '<div class="col-sm-12">'+
        '<div class="form-group">'+
            '<label class="ng-binding">Wait</label>'+
            '<div class="display-flex">'+
                '<input type="text" class="form-control pull-left mr10 validate-number ng-pristine ng-valid number">'+
                '<div class="select-wrap ng number-width">'+
                    '<span class="ng-binding ng-scope">day(s)</span><i class="caret pull-right" style="margin-top: -7%; margin-right: 4%;"></i>'+
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
	This will show action sidebar
********************************************************************/
function showActionSidebar() {
    $(".incomplete-steps").addClass("ng-hide");
    $("h3.ng-binding").addClass("ng-hide");
    $("h3.email-header").addClass("ng-hide");
    $("h3.removetag-header").addClass("ng-hide");
    $("h3.addtag-header").addClass("ng-hide");
    $("h3.delay-header").addClass("ng-hide");
    $("h3.sms-header").addClass("ng-hide");
    $("h3.unsubscribe-header").addClass("ng-hide");
    $("h3.action-header").removeClass("ng-hide");

    if ($("form.ng-pristine").find(".sidebar-content-bottom ").length == 0) {
        $("form.ng-pristine").append('<div class="sidebar-content-bottom ng-scope">' +
            '<a href="#" class="btn ng-binding save-btn">Save</a>' +
            '<a class="cancel pull-right ng-binding ng-scope" href="javascript:;" onclick="showDefaultSidebar();">Cancel</a>' +
            '</div>');
    }

    $("form.ng-pristine .sidebar-content").css("bottom", "72px");
    $("form.ng-pristine .sidebar-content .sidebar-include").html('<div class="ng-scope action-scope">' +
        '<div class="action-sidebar">' +
        '<h4 class="ng-binding">Choose an action</h4>' +
        '<p class="mb15 ng-binding">Select which action to apply when a subscriber reaches the next action step.</p>' +

        '<div class="dropdown" id="actions"  onclick="GetSelectedText();">' +
        '<a href="#" class="btn ng-binding dropdown-toggle" data-toggle="dropdown" data-check-value="">Select...<i class="caret" style="margin-top: -2px;"></i>' +
        '</a>' +

        '<ul class="dropdown-menu mb30 with-description">' +
        '<li class="ng-scope">' +
        '<a href="#" onclick="updateTags(\'Add tag\');">' +
        '<span class="title ng-binding">Add tag</span>' +
        '</a>' +
        '</li>' +
        '<li class="ng-scope">' +
        '<a href="#" onclick="removeTag(\'Remove tag\');">' +
        '<span class="title ng-binding">Remove tag</span>' +
        '</a>' +
        '</li>' +
        '<li class="ng-scope">' +
        '<a href="#" onclick="showEmailSidebar(\'Email\');">' +
        '<span class="title ng-binding">Email</span>' +
        '</a>' +
        '</li>' +
        '<li class="ng-scope">' +
        '<a href="#" onclick="showDelaySidebar(\'Delay\');">' +
        '<span class="title ng-binding">Delay</span>' +
        '</a>' +
        '</li>' +
        '<li class="ng-scope">' +
        '<a href="#" onclick="showSMSsidebar(\'SMS\');">' +
        '<span class="title ng-binding">SMS</span>' +
        '</a>' +
        '</li>' +
        '<li class="ng-scope actionitem">' +
        '<a href="#" onclick="markAsUnsubscribed(\'Unsubscribe\');">' +
        '<span class="title ng-binding">Unsubscribe</span>' +
        '</a>' +
        '</li>' +
        '</ul>' +
        '</div>' +
        '</div>' +
        '</div>');
}
//end

function showNewSidebar() {
    $(".incomplete-steps").addClass("ng-hide");
    $("h3.ng-binding").addClass("ng-hide");
    $("h3.action-header").removeClass("ng-hide");

    $("form.ng-pristine .sidebar-content").css("bottom", "72px");
    $("form.ng-pristine .sidebar-content .sidebar-include").html('<div class="ng-scope action-scope">' +
    '<div>' +
    '<div class="condition-sidebar">'+
    '<h4 class="ng-binding">Create a condition</h4>'+
    '<p class="mb30 ng-binding">Specify if any or all need to be true for condition to be met.</p>'+
        '<div class="ml-custom-checkbox color-green mb30">'+
          '<form name="myForm">' +
            '<div class="form-check">'+
            '<label class="custom-radio-label" id="anyRules" onchange="changeToOr();">'+
                '<input class="custom-check-input" type="radio" value="or" name="myRadios">'+
                '<span class="ng-binding">Any rule</span>'+
            '</label>'+
            '<div class="custom-radio-description ng-binding">Select one or a few conditions where ANY rule can match the criteria.</div>'+
            '</div>'+
            '<div class="form-check">'+
            '<label class="custom-radio-label" id="allRules" onc="changeToAnd();">'+
              '<input class="custom-check-input" type="radio" value="and" name="myRadios">'+
              '<span class="ng-binding">All rules</span>'+
            '</label>'+
            '<div class="custom-radio-description ng-binding">Select one or a few conditions where ALL rules must match the criteria.</div>'+
          '</div>'+
          '</form>' +
        '</div>'+
        '<div class="condition-dropdown custom-dropdown1" id="condition-dropdown-1">' +
        '<div class="action-sidebar condition-box ng-scope">' +
        '<h4 class="ng-binding">Condition</h4>' +
        '<p class="mb15 ng-binding">Select which condition to apply when a subscriber reaches the next condition step.</p>' +
        '<div class="dropdown conditions" onclick="GetSelectedTextt();">' +
        '<button class="btn validate-check-attribute-value ng-binding" type="button" data-toggle="dropdown" data-check-value="" onclick="binding();">Select<span class="caret" style="margin-top: -5px;"></span>' +
        '</button>' +
        '<ul class="dropdown-menu mb30 with-description">' +
        '<li class="ng-scope">' +
        '<a href="#" onclick="updateFirst(\'Workflow Activity\');">' +
        '<span class="title ng-binding">Workflow Activity</span>' +
        '</a>' +
        '</li>' +
        '<li class="ng-scope">' +
        '<a href="#" onclick="updateFirstInputt(\'Campaign Activity\');">' +
        '<span class="title ng-binding">Campaign Activity</span>' +
        '</a>' +
        '</li>' +
        '<li class="ng-scope">' +
        '<a href="#" onclick="customFields(\'Custom Fields\');">' +
        '<span class="title ng-binding">Custom Fields</span>' +
        '</a>' +
        '</li>' +
        '<li class="ng-scope">' +
        '<a href="#" onclick="updateTags(\'Tags Added\');">' +
        '<span class="title ng-binding">Tags added</span>' +
        '</a>' +
        '</li>' +
        '<li class="ng-scope">' +
        '<a href="#" onclick="removeTag(\'Tags removed\');">' +
        '<span class="title ng-binding">Tags removed</span>' +
        '</a>' +
        '</li>' +
        '</ul>' +
        '</div>' +
        '</div>' +
        '<a href="#" class="pull-left mt15 ng-binding ng-scope addConditionDropdown" onclick="addCondition();">Add another condition</a>' +
        '</div>');
}
//end


/***********************************************************************
This will add more condition dropdown
************************************************************************/
function addCondition() {
        $(".addConditionDropdown").addClass("ng-hide");
        $(".condition-dropdown").append('<div class="connect-line ng-scope"><span class="and the-span">or</span></div>' +
        '<div class="action-sidebar condition-box ng-scope second-condition">' +
        '<h4 class="ng-binding">Another Condition</h4>' +
        '<a href="javascript:;" class="delete-condition ng-scope" onclick="deleteCondition();"><i class="icon-remove"></i></a>' +
        '<p class="mb15 ng-binding">Select which condition to apply when a subscriber reaches the next condition step.</p>' +
        '<div class="dropdown conditions" onclick="GetSelectedTextt();">' +
        '<button class="btn validate-check-attribute-value ng-binding" type="button" data-toggle="dropdown" data-check-value="" onclick="binding();">Select<span class="caret" style="margin-top: -5px;"></span>' +
        '</button>' +
        '<ul class="dropdown-menu mb30 with-description">' +
        '<li class="ng-scope">' +
        '<a href="#" onclick="updateFirst(\'Workflow Activity\');">' +
        '<span class="title ng-binding">Workflow Activity</span>' +
        '</a>' +
        '</li>' +
        '<li class="ng-scope">' +
        '<a href="#" onclick="updateFirstInputt(\'Campaign Activity\');">' +
        '<span class="title ng-binding">Campaign Activity</span>' +
        '</a>' +
        '</li>' +
        '<li class="ng-scope">' +
        '<a href="#" onclick="customFields(\'Custom Fields\');">' +
        '<span class="title ng-binding">Custom Fields</span>' +
        '</a>' +
        '</li>' +
        '<li class="ng-scope">' +
        '<a href="#" onclick="updateTags(\'Tags Added\');">' +
        '<span class="title ng-binding">Tags added</span>' +
        '</a>' +
        '</li>' +
        '<li class="ng-scope">' +
        '<a href="#" onclick="removeTag(\'Tags removed\');">' +
        '<span class="title ng-binding">Tags removed</span>' +
        '</a>' +
        '</li>' +
        '</ul>' +
        '</div>' +
        '</div>' +
        '<a href="#" class="pull-left mt15 ng-binding ng-scope addConditionDropdownn" onclick="addConditionn();">Add another condition</a>' +
        '</div>');
    }

    function addConditionn() {
        $(".addConditionDropdownn").addClass("ng-hide");
        $(".condition-dropdown").append('<div class="connect-line ng-scope"><span class="andd the-span">or</span></div>' +
        '<div class="action-sidebar condition-box ng-scope third-condition">' +
        '<h4 class="ng-binding">Another Condition</h4>' +
        '<a href="javascript:;" class="delete-condition ng-scope" onclick="deleteConditionn();"><i class="icon-remove"></i></a>' +
        '<p class="mb15 ng-binding">Select which condition to apply when a subscriber reaches the next condition step.</p>' +
        '<div class="dropdown conditions" onclick="GetSelectedTextt();">' +
        '<button class="btn validate-check-attribute-value ng-binding" type="button" data-toggle="dropdown" data-check-value="" onclick="binding();">Select<span class="caret" style="margin-top: -5px;"></span>' +
        '</button>' +
        '<ul class="dropdown-menu mb30 with-description">' +
        '<li class="ng-scope">' +
        '<a href="#" onclick="updateFirst(\'Workflow Activity\');">' +
        '<span class="title ng-binding">Workflow Activity</span>' +
        '</a>' +
        '</li>' +
        '<li class="ng-scope">' +
        '<a href="#" onclick="updateFirstInputt(\'Campaign Activity\');">' +
        '<span class="title ng-binding">Campaign Activity</span>' +
        '</a>' +
        '</li>' +
        '<li class="ng-scope">' +
        '<a href="#" onclick="customFields(\'Custom Fields\');">' +
        '<span class="title ng-binding">Custom Fields</span>' +
        '</a>' +
        '</li>' +
        '<li class="ng-scope">' +
        '<a href="#" onclick="updateTags(\'Tags Added\');">' +
        '<span class="title ng-binding">Tags added</span>' +
        '</a>' +
        '</li>' +
        '<li class="ng-scope">' +
        '<a href="#" onclick="removeTag(\'Tags removed\');">' +
        '<span class="title ng-binding">Tags removed</span>' +
        '</a>' +
        '</li>' +
        '</ul>' +
        '</div>' +
        '</div>' +
        '</div>');
    }

    // function addConditionnn() {
    //     $(".addConditionDropdownnn").addClass("ng-hide");
    //     $(".condition-dropdown").append('<div class="connect-line ng-scope"><span class="anddd">or</span></div>' +
    //     '<div class="action-sidebar condition-box ng-scope fourth-condition">' +
    //     '<h4 class="ng-binding">Another Condition</h4>' +
    //     '<a href="javascript:;" class="delete-condition ng-scope" onclick="deleteConditionnn(this);"><i class="icon-remove"></i></a>' +
    //     '<p class="mb15 ng-binding">Select which condition to apply when a subscriber reaches the next condition step.</p>' +
    //     '<div class="dropdown conditions" onclick="GetSelectedTextt();">' +
    //     '<button class="btn validate-check-attribute-value ng-binding" type="button" data-toggle="dropdown" data-check-value="" onclick="binding();">Select<span class="caret" style="margin-top: -5px;"></span>' +
    //     '</button>' +
    //     '<ul class="dropdown-menu mb30 with-description">' +
    //     '<li class="ng-scope">' +
    //     '<a href="#" onclick="updateFirst(\'Workflow Activity\');">' +
    //     '<span class="title ng-binding">Workflow Activity</span>' +
    //     '</a>' +
    //     '</li>' +
    //     '<li class="ng-scope">' +
    //     '<a href="#" onclick="updateFirstInputt(\'Campaign Activity\');">' +
    //     '<span class="title ng-binding">Campaign Activity</span>' +
    //     '</a>' +
    //     '</li>' +
    //     '<li class="ng-scope">' +
    //     '<a href="#" onclick="customFields(\'Custom Fields\');">' +
    //     '<span class="title ng-binding">Custom Fields</span>' +
    //     '</a>' +
    //     '</li>' +
    //     '<li class="ng-scope">' +
    //     '<a href="#" onclick="updateTags(\'Tags Added\');">' +
    //     '<span class="title ng-binding">Tags added</span>' +
    //     '</a>' +
    //     '</li>' +
    //     '<li class="ng-scope">' +
    //     '<a href="#" onclick="removeTag(\'Tags removed\');">' +
    //     '<span class="title ng-binding">Tags removed</span>' +
    //     '</a>' +
    //     '</li>' +
    //     '</ul>' +
    //     '</div>' +
    //     '</div>' +
    //     '<a href="#" class="pull-left mt15 ng-binding ng-scope addConditionDropdownnnn" onclick="addConditionnnn();">Add another condition</a>' +
    //     '</div>');
    // }

    // function addConditionnnn() {
    //     $(".addConditionDropdownnnn").addClass("ng-hide");
    //     $(".condition-dropdown").append('<div class="connect-line ng-scope"><span class="andddd">or</span></div>' +
    //     '<div class="action-sidebar condition-box ng-scope fifth-condition">' +
    //     '<h4 class="ng-binding">Another Condition</h4>' +
    //     '<a href="javascript:;" class="delete-condition ng-scope" onclick="deleteConditionnnn();"><i class="icon-remove"></i></a>' +
    //     '<p class="mb15 ng-binding">Select which condition to apply when a subscriber reaches the next condition step.</p>' +
    //     '<div class="dropdown conditions" onclick="GetSelectedTextt();">' +
    //     '<button class="btn validate-check-attribute-value ng-binding" type="button" data-toggle="dropdown" data-check-value="" onclick="binding();">Select<span class="caret" style="margin-top: -5px;"></span>' +
    //     '</button>' +
    //     '<ul class="dropdown-menu mb30 with-description">' +
    //     '<li class="ng-scope">' +
    //     '<a href="#" onclick="updateFirst(\'Workflow Activity\');">' +
    //     '<span class="title ng-binding">Workflow Activity</span>' +
    //     '</a>' +
    //     '</li>' +
    //     '<li class="ng-scope">' +
    //     '<a href="#" onclick="updateFirstInputt(\'Campaign Activity\');">' +
    //     '<span class="title ng-binding">Campaign Activity</span>' +
    //     '</a>' +
    //     '</li>' +
    //     '<li class="ng-scope">' +
    //     '<a href="#" onclick="customFields(\'Custom Fields\');">' +
    //     '<span class="title ng-binding">Custom Fields</span>' +
    //     '</a>' +
    //     '</li>' +
    //     '<li class="ng-scope">' +
    //     '<a href="#" onclick="updateTags(\'Tags Added\');">' +
    //     '<span class="title ng-binding">Tags added</span>' +
    //     '</a>' +
    //     '</li>' +
    //     '<li class="ng-scope">' +
    //     '<a href="#" onclick="removeTag(\'Tags removed\');">' +
    //     '<span class="title ng-binding">Tags removed</span>' +
    //     '</a>' +
    //     '</li>' +
    //     '</ul>' +
    //     '</div>' +
    //     '</div>' +
    //     '</div>');
    // }

/***********************************************************************
This will delete condition dropdown
************************************************************************/
function deleteCondition(e) {
    $(".addConditionDropdown").removeClass("ng-hide");
    $(".addConditionDropdownn").addClass("ng-hide");
    $(".connect-line").addClass("ng-hide");
    $(".and").addClass("ng-hide");
    $('.second-condition').addClass("ng-hide");
}

function deleteConditionn(e) {
    $(".addConditionDropdown").addClass("ng-hide");
    $(".addConditionDropdownn").removeClass("ng-hide");
    $(".connect-line").addClass("ng-hide");
    $('.third-condition').addClass("ng-hide"); 
}
    
//end

/***********************************************************************
This will show default sidebar
************************************************************************/
function showDefaultSidebar() {
    $(".incomplete-steps").removeClass("ng-hide");
    $("h3.ng-binding").addClass("ng-hide");

    $("form.ng-pristine").find(".sidebar-content-bottom").remove();
    $("form.ng-pristine .sidebar-content").css("bottom", "0px");
    $("form.ng-pristine .sidebar-content .sidebar-include").html('<div class="ng-scope">' +
        '<div class="form-group workflow-report separator">' +
    
        '<div class="col-xs-12">' +
        '<h4 class="ng-binding">Workflow Name</h4>' +
        '</div>' +
        '<div class="col-xs-12">' +
        '<h4 class="ng-binding">Date created</h4>' +
        '</div>' +

        '<div class="row mb30">' +
        '<div class="col-xs-4" style="margin-top:10%;">'+
        '<h4 class="ng-binding">Completed</h4>'+
        '<span class="number ng-binding">0</span>'+
        '</div>'+

        '<div class="col-xs-4" style="margin-top:10%;">'+
            '<h4 class="ng-binding">Queue</h4>'+
            '<span class="number ng-binding">0</span>'+
        '</div>'+
        
        '<div class="col-xs-4" style="margin-top:10%;">' +
            '<h4 class="ng-binding" style="font-size:11px;">Number of people in workflow</h4>' +
            '<span class="number ng-binding">0</span>' +
        '</div>' +

        '<div class="row mb30">' +
        '<div class="col-xs-12 ng-scope">' +
        '<a class="btn btn-block btn-default ng-binding">View workflow activity</a>' +
        '</div>' +
        '</div>' +
        '</div>' +

        '<div class="form-group workflow-report">' +
        '<div class="row mb30 ng-scope">' +
        '<div class="col-xs-12">' +
        '<div class="notification">' +
        '<p class="ng-binding">This workflow does not have any sent emails yet.</p>' +
        '</div>' +
        '</div>' +
        '</div>'
    );

}
//end

/*******************************************************************
Update Tags.
********************************************************************/
function updateTags(value) {
    var _str = value.toLowerCase();
    var str = _str.replace(" ", "_");
    $(".validate-check-attribute-value").html(value).attr("data-check-value", str);
    $("h3.action-header").addClass("ng-hide");
    $("h3.removetag-header").addClass("ng-hide");
    $("h3.delay-header").addClass("ng-hide");
    $("h3.sms-header").addClass("ng-hide");
    $("h3.email-header").addClass("ng-hide");
    $("h3.unsubscribe-header").addClass("ng-hide");
    $("h3.addtag-header").removeClass("ng-hide");

    $(".action-sidebar").find(".row").remove();
    $(".action-sidebar").append('<div class="row ng-scope actionSidebarCustomField">' +
        '<div class="col-xs-12">' +
        '<div class="form-group next-row ng-hide">' +
        '<div class="email-content-loader trigger-error-on-visible">' +
        '<div class="sk-spinner sk-spinner-three-bounce">' +
        '<div class="sk-bounce1"></div>' +
        '<div class="sk-bounce2"></div>' +
        '<div class="sk-bounce3"></div>' +
        '</div>' +
        '</div>' +
        '</div>' +

        '<div class="dropdown ng-scope">' +
        '<div class="display-flex create-new-field-holder ng-hide">' +
        '<div class="input">' +
        '<input class="form-control ng-pristine ng-valid" type="text" placeholder="Name your custom field">' +
        '</div>' +
        '</div>' +
        '<div style="max-height: 300px;overflow:auto; width: inherit; background: #fff;">' +
        '<label style="margin-top:5%; font-size: 16px;">Choose one or more existing tags with the ctrl key</label>' +
            '<select class="custom-select" style="width: 350px; height:150px;" multiple>' +
            '<option value="" disabled selected>Choose tag</option>' +
            '<option value="1">Tag 1</option>' +
            '<option value="2">Tag 2</option>' +
            '<option value="3">Tag 3</option>' +
            '<option value="4">Tag 4</option>' +
            '<option value="5">Tag 5</option>' +
            '<option value="5">Tag 6</option>' +
            '<option value="5">Tag 7</option>' +
            '<option value="5">Tag 8</option>' +
            '<option value="5">Tag 9</option>' +
            '<option value="5">Tag 10</option>' +   
            '</select>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>');
}
//end

function updateFirst(value) {
    var _str = value.toLowerCase();
    var str = _str.replace(" ", "_");
    $(".validate-check-attribute-value").html(value).attr("data-check-value", str);

    $(".action-sidebar").find(".row").remove();
    $(".action-sidebar").append('<div class="row ng-scope actionSidebarCustomField">' +
        '<div class="col-xs-12">' +
        '<div class="form-group next-row ng-hide">' +
        '<div class="email-content-loader trigger-error-on-visible">' +
        '<div class="sk-spinner sk-spinner-three-bounce">' +
        '<div class="sk-bounce1"></div>' +
        '<div class="sk-bounce2"></div>' +
        '<div class="sk-bounce3"></div>' +
        '</div>' +
        '</div>' +
        '</div>' +

        '<div class="dropdown ng-scope">' +
        '<div class="display-flex create-new-field-holder ng-hide">' +
        '<div class="input">' +
        '<input class="form-control ng-pristine ng-valid" type="text" placeholder="Name your custom field">' +
        '</div>' +
        '</div>' +
        '<div class="display-flex create-new-field-holder mt15 ng-hide">' +
        '<div class="input">' +
        '<div class="dropdown">' +
        '<button class="btn ng-binding" type="button" data-toggle="dropdown">Field type: Text<span class="caret"></span></button>' +
        '<ul class="dropdown-menu">' +
        '<li class="ng-scope"><a href=""><span class="title ng-binding">Text</span></a></li>' +
        '<li class="ng-scope"><a href=""><span class="title ng-binding">Number</span></a></li>' +
        '<li class="ng-scope"><a href=""><span class="title ng-binding">Date</span></a></li>' +
        '</ul>' +
        '</div>' +
        '</div>' +
        '<div class="create"><a href="#" class="btn btn-primary ng-binding">Create</a></div>' +
        '<div class="cancel"><a class="btn ng-binding" href="#">Cancel</a></div>' +
        '</div>' +
        '<div class="dropdown">' +
        '<button class="btn validate-check-attribute-value ng-binding" type="button" data-toggle="dropdown" data-check-value="">Email List<i class="caret" style="margin-top: -2px;"></i>' +
        '</button>' +
        '<ul class="dropdown-menu mb30 with-description">' +
        '<li class="ng-scope">' +
        '<a href="#" onclick="emailState(\'Workflow Activity\');">' +
        '<span class="title ng-binding">1st email</span>' +
        '</a>' +
        '</li>' +
        '<li class="ng-scope">' +
        '<a href="#" onclick="emailState(\'Workflow Activity\');">' +
        '<span class="title ng-binding">2nd email</span>' +
        '</a>' +
        '</li>' +
        '<li class="ng-scope">' +
        '<a href="#" onclick="emailState(\'Workflow Activity\');">' +
        '<span class="title ng-binding">3rd email</span>' +
        '</a>' +
        '</li>' +
        '</ul>' +
        '</div>' +
        '</div>' +
        '</ul>' +
        '</div>' +
        '</div>' +
        '</div>');
}
//end

function updateFirstInputt(value) {
    var _str = value.toLowerCase();
    var str = _str.replace(" ", "_");
    $(".validate-check-attribute-value").html(value).attr("data-check-value", str);

    $(".action-sidebar").find(".row").remove();
    $(".action-sidebar").append('<div class="row ng-scope actionSidebarCustomField">' +
        '<div class="col-xs-12">' +
        '<div class="form-group next-row ng-hide">' +
        '<div class="email-content-loader trigger-error-on-visible">' +
        '<div class="sk-spinner sk-spinner-three-bounce">' +
        '<div class="sk-bounce1"></div>' +
        '<div class="sk-bounce2"></div>' +
        '<div class="sk-bounce3"></div>' +
        '</div>' +
        '</div>' +
        '</div>' +

        '<div class="dropdown ng-scope">' +
        '<div class="display-flex create-new-field-holder ng-hide">' +
        '<div class="input">' +
        '<input class="form-control ng-pristine ng-valid" type="text" placeholder="Name your custom field">' +
        '</div>' +
        '</div>' +
        '<div class="display-flex create-new-field-holder mt15 ng-hide">' +
        '<div class="input">' +
        '<div class="dropdown">' +
        '<button class="btn ng-binding" type="button" data-toggle="dropdown">Field type: Text<span class="caret"></span></button>' +
        '<ul class="dropdown-menu">' +
        '<li class="ng-scope"><a href=""><span class="title ng-binding">Text</span></a></li>' +
        '<li class="ng-scope"><a href=""><span class="title ng-binding">Number</span></a></li>' +
        '<li class="ng-scope"><a href=""><span class="title ng-binding">Date</span></a></li>' +
        '</ul>' +
        '</div>' +
        '</div>' +
        '<div class="create"><a href="#" class="btn btn-primary ng-binding">Create</a></div>' +
        '<div class="cancel"><a class="btn ng-binding" href="#">Cancel</a></div>' +
        '</div>' +
        '<div class="dropdown">' +
        '<button class="btn validate-check-attribute-value ng-binding" type="button" data-toggle="dropdown" data-check-value="">Email List<i class="caret" style="margin-top: -2px;"></i>' +
        '</button>' +
        '<ul class="dropdown-menu mb30 with-description">' +
        '<li class="ng-scope">' +
        '<a href="#" onclick="emailState(\'Campaign Activity\');">' +
        '<span class="title ng-binding">1st email</span>' +
        '</a>' +
        '</li>' +
        '<li class="ng-scope">' +
        '<a href="#" onclick="emailStatus(\'Campaign Activity\');">' +
        '<span class="title ng-binding">2nd email</span>' +
        '</a>' +
        '</li>' +
        '<li class="ng-scope">' +
        '<a href="#" onclick="emailStatus(\'Campaign Activity\');">' +
        '<span class="title ng-binding">3rd email</span>' +
        '</a>' +
        '</li>' +
        '</ul>' +
        '</div>' +
        '</div>' +
        '</ul>' +
        '</div>' +
        '</div>' +
        '</div>');
}
//end



function emailState(value) {
    var _str = value.toLowerCase();
    var str = _str.replace(" ", "_");
    $(".validate-check-attribute-value").html(value).attr("data-check-value", str);

    $(".action-sidebar").find(".row").remove();
    $(".action-sidebar").append('<div class="row ng-scope actionSidebarCustomField">' +
        '<div class="col-xs-12">' +
        '<div class="form-group next-row ng-hide">' +
        '<div class="email-content-loader trigger-error-on-visible">' +
        '<div class="sk-spinner sk-spinner-three-bounce">' +
        '<div class="sk-bounce1"></div>' +
        '<div class="sk-bounce2"></div>' +
        '<div class="sk-bounce3"></div>' +
        '</div>' +
        '</div>' +
        '</div>' +

        '<div class="dropdown ng-scope">' +
        '<div class="display-flex create-new-field-holder ng-hide">' +
        '<div class="input">' +
        '<input class="form-control ng-pristine ng-valid" type="text" placeholder="Name your custom field">' +
        '</div>' +
        '</div>' +
        '<div class="display-flex create-new-field-holder mt15 ng-hide">' +
        '<div class="input">' +
        '<div class="dropdown">' +
        '<button class="btn ng-binding" type="button" data-toggle="dropdown">Field type: Text<span class="caret"></span></button>' +
        '<ul class="dropdown-menu">' +
        '<li class="ng-scope"><a href=""><span class="title ng-binding">Text</span></a></li>' +
        '<li class="ng-scope"><a href=""><span class="title ng-binding">Number</span></a></li>' +
        '<li class="ng-scope"><a href=""><span class="title ng-binding">Date</span></a></li>' +
        '</ul>' +
        '</div>' +
        '</div>' +
        '<div class="create"><a href="#" class="btn btn-primary ng-binding">Create</a></div>' +
        '<div class="cancel"><a class="btn ng-binding" href="#">Cancel</a></div>' +
        '</div>' +
        '<div class="dropdown">' +
        '<button class="btn validate-check-attribute-value ng-binding" type="button" data-toggle="dropdown" data-check-value="">Choose email status<i class="caret" style="margin-top: -2px;"></i>' +
        '</button>' +
        '<ul class="dropdown-menu mb30 with-description">' +
        '<li class="ng-scope">' +
        '<a href="#">' +
        '<span class="title ng-binding">Email Opened</span>' +
        '</a>' +
        '</li>' +
        '<li class="ng-scope">' +
        '<a href="#">' +
        '<span class="title ng-binding">Email was not opened</span>' +
        '</a>' +
        '</li>' +
        '<li class="ng-scope">' +
        '<a href="#" onclick="specificLInk(\'Workflow Activity\');">' +
        '<span class="title ng-binding">Specific link clicked</span>' +
        '</a>' +
        '</li>' +
        '<li class="ng-scope">' +
        '<a href="#">' +
        '<span class="title ng-binding">Any link clicked</span>' +
        '</a>' +
        '</li>' +
        '</ul>' +
        '</div>' +
        '</div>' +
        '</ul>' +
        '</div>' +
        '</div>' +
        '</div>');
}
//end

function emailStatus(value) {
    var _str = value.toLowerCase();
    var str = _str.replace(" ", "_");
    $(".validate-check-attribute-value").html(value).attr("data-check-value", str);

    $(".action-sidebar").find(".row").remove();
    $(".action-sidebar").append('<div class="row ng-scope actionSidebarCustomField">' +
        '<div class="col-xs-12">' +
        '<div class="form-group next-row ng-hide">' +
        '<div class="email-content-loader trigger-error-on-visible">' +
        '<div class="sk-spinner sk-spinner-three-bounce">' +
        '<div class="sk-bounce1"></div>' +
        '<div class="sk-bounce2"></div>' +
        '<div class="sk-bounce3"></div>' +
        '</div>' +
        '</div>' +
        '</div>' +

        '<div class="dropdown ng-scope">' +
        '<div class="display-flex create-new-field-holder ng-hide">' +
        '<div class="input">' +
        '<input class="form-control ng-pristine ng-valid" type="text" placeholder="Name your custom field">' +
        '</div>' +
        '</div>' +
        '<div class="display-flex create-new-field-holder mt15 ng-hide">' +
        '<div class="input">' +
        '<div class="dropdown">' +
        '<button class="btn ng-binding" type="button" data-toggle="dropdown">Field type: Text<span class="caret"></span></button>' +
        '<ul class="dropdown-menu">' +
        '<li class="ng-scope"><a href=""><span class="title ng-binding">Text</span></a></li>' +
        '<li class="ng-scope"><a href=""><span class="title ng-binding">Number</span></a></li>' +
        '<li class="ng-scope"><a href=""><span class="title ng-binding">Date</span></a></li>' +
        '</ul>' +
        '</div>' +
        '</div>' +
        '<div class="create"><a href="#" class="btn btn-primary ng-binding">Create</a></div>' +
        '<div class="cancel"><a class="btn ng-binding" href="#">Cancel</a></div>' +
        '</div>' +
        '<div class="dropdown">' +
        '<button class="btn validate-check-attribute-value ng-binding" type="button" data-toggle="dropdown" data-check-value="">Choose email status<span class="caret" style="margin-top: -5px;"></span>' +
        '</button>' +
        '<ul class="dropdown-menu mb30 with-description">' +
        '<li class="ng-scope">' +
        '<a href="#">' +
        '<span class="title ng-binding">Email Opened</span>' +
        '</a>' +
        '</li>' +
        '<li class="ng-scope">' +
        '<a href="#">' +
        '<span class="title ng-binding">Email was not opened</span>' +
        '</a>' +
        '</li>' +
        '<li class="ng-scope">' +
        '<a href="#" onclick="specificLInkClicked(\'Campaign Activity\');">' +
        '<span class="title ng-binding">Specific link clicked</span>' +
        '</a>' +
        '</li>' +
        '<li class="ng-scope">' +
        '<a href="#">' +
        '<span class="title ng-binding">Any link clicked</span>' +
        '</a>' +
        '</li>' +
        '</ul>' +
        '</div>' +
        '</div>' +
        '</ul>' +
        '</div>' +
        '</div>' +
        '</div>');
}
//end

function customFields(value) {
    var _str = value.toLowerCase();
    var str = _str.replace(" ", "_");
    $(".validate-check-attribute-value").html(value).attr("data-check-value", str);

    $(".action-sidebar").find(".row").remove();
    $(".action-sidebar").append('<div class="row ng-scope actionSidebarCustomField">' +
        '<div class="col-xs-12">' +
        '<div class="form-group next-row ng-hide">' +
        '<div class="email-content-loader trigger-error-on-visible">' +
        '<div class="sk-spinner sk-spinner-three-bounce">' +
        '<div class="sk-bounce1"></div>' +
        '<div class="sk-bounce2"></div>' +
        '<div class="sk-bounce3"></div>' +
        '</div>' +
        '</div>' +
        '</div>' +

        '<div class="dropdown ng-scope">' +
        '<div class="display-flex create-new-field-holder ng-hide">' +
        '<div class="input">' +
        '<input class="form-control ng-pristine ng-valid" type="text" placeholder="Name your custom field">' +
        '</div>' +
        '</div>' +
        '<div class="display-flex create-new-field-holder mt15 ng-hide">' +
        '<div class="input">' +
        '<div class="dropdown">' +
        '<button class="btn ng-binding" type="button" data-toggle="dropdown">Field type: Text<span class="caret"></span></button>' +
        '<ul class="dropdown-menu">' +
        '<li class="ng-scope"><a href=""><span class="title ng-binding">Text</span></a></li>' +
        '<li class="ng-scope"><a href=""><span class="title ng-binding">Number</span></a></li>' +
        '<li class="ng-scope"><a href=""><span class="title ng-binding">Date</span></a></li>' +
        '</ul>' +
        '</div>' +
        '</div>' +
        '<div class="create"><a href="#" class="btn btn-primary ng-binding">Create</a></div>' +
        '<div class="cancel"><a class="btn ng-binding" href="#">Cancel</a></div>' +
        '</div>' +
        '<div style="margin-bottom: 5%;">' +
        '<select class="btn" style="height: 20%;">' +
        '<option>Choose custom field</option>' +
        '<option class="ng-scope">' +
        '<a href="#">' +
        '<span class="title ng-binding">Gender</span>' +
        '</a>' +
        '</option>' +
        '<option class="ng-scope">' +
        '<a href="#">' +
        '<span class="title ng-binding">Work address</span>' +
        '</a>' +
        '</option>' +
        '<option class="ng-scope">' +
        '<a href="#">' +
        '<span class="title ng-binding">State of origin</span>' +
        '</a>' +
        '</option>' +
        '<option class="ng-scope">' +
        '<a href="#">' +
        '<span class="title ng-binding">Fax</span>' +
        '</a>' +
        '</option>' +
        '</select>' +
        '</div>' +
        '<input class="btn" placeholder="Enter new custom field">' + 
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>');
}
//end

function specificLInk(value) {
    var _str = value.toLowerCase();
    var str = _str.replace(" ", "_");
    $(".validate-check-attribute-value").html(value).attr("data-check-value", str);

    $(".action-sidebar").find(".row").remove();
    $(".action-sidebar").append('<div class="row ng-scope actionSidebarCustomField">' +
        '<div class="col-xs-12">' +
        '<div class="form-group next-row ng-hide">' +
        '<div class="email-content-loader trigger-error-on-visible">' +
        '<div class="sk-spinner sk-spinner-three-bounce">' +
        '<div class="sk-bounce1"></div>' +
        '<div class="sk-bounce2"></div>' +
        '<div class="sk-bounce3"></div>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '<div>' +
        '<select style="height: 20%; width: 100%; margin-bottom: 3%;">' +
        '<option>Choose specific link</option>' +
        '<option class="ng-scope">' +
        '<a href="#">' +
        '<span class="title ng-binding">www.google.com</span>' +
        '</a>' +
        '</option>' +
        '<option class="ng-scope">' +
        '<a href="#">' +
        '<span class="title ng-binding">www.sendmunk.com</span>' +
        '</a>' +
        '</option>' +
        '</select>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>');
}
//end

function specificLInkClicked(value) {
    var _str = value.toLowerCase();
    var str = _str.replace(" ", "_");
    $(".validate-check-attribute-value").html(value).attr("data-check-value", str);

    $(".action-sidebar").find(".row").remove();
    $(".action-sidebar").append('<div class="row ng-scope actionSidebarCustomField">' +
        '<div class="col-xs-12">' +
        '<div class="form-group next-row ng-hide">' +
        '<div class="email-content-loader trigger-error-on-visible">' +
        '<div class="sk-spinner sk-spinner-three-bounce">' +
        '<div class="sk-bounce1"></div>' +
        '<div class="sk-bounce2"></div>' +
        '<div class="sk-bounce3"></div>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '<div>' +
        '<select style="height: 20%; width: 100%; margin-bottom: 3%;">' +
        '<option>Choose specific link</option>' +
        '<option class="ng-scope">' +
        '<a href="#">' +
        '<span class="title ng-binding">www.google.com</span>' +
        '</a>' +
        '</option>' +
        '<option class="ng-scope">' +
        '<a href="#">' +
        '<span class="title ng-binding">www.sendmunk.com</span>' +
        '</a>' +
        '</option>' +
        '</select>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>');
}
//end

/*******************************************************************
Remove Tag.
********************************************************************/
function removeTag(value) {
    var _str = value.toLowerCase();
    var str = _str.replace(" ", "_");
    $(".validate-check-attribute-value").html(value).attr("data-check-value", str);
    $("h3.action-header").addClass("ng-hide");
    $("h3.addtag-header").addClass("ng-hide");
    $("h3.delay-header").addClass("ng-hide");
    $("h3.sms-header").addClass("ng-hide");
    $("h3.unsubscribe-header").addClass("ng-hide");
    $("h3.email-header").addClass("ng-hide");
    $("h3.removetag-header").removeClass("ng-hide");

    $(".action-sidebar").find(".row").remove();
    $(".action-sidebar").append('<div class="row ng-scope actionSidebarRemoveFromGroup">' +
        '<div class="col-xs-12">' +
        '<div class="form-group next-row ng-hide">' +
        '<div class="email-content-loader trigger-error-on-visible">' +
        '<div class="sk-spinner sk-spinner-three-bounce">' +
        '<div class="sk-bounce1"></div>' +
        '<div class="sk-bounce2"></div>' +
        '<div class="sk-bounce3"></div>' +
        '</div>' +
        '</div>' +
        '</div>' +

        '<div class="dropdown ng-scope" ng-if="!groupsLoading">' +
        '<label style="margin-top:5%; font-size:16px;">Remove one or more existing tags with the ctrl key</label>' +
        '<select class="custom-select" style="width: 350px; height:150px;" multiple>' +
        '<option value="" disabled selected>Choose tag</option>' +
        '<option value="1">Tag 1</option>' +
        '<option value="2">Tag 2</option>' +
        '<option value="3">Tag 3</option>' +
        '<option value="4">Tag 4</option>' +
        '<option value="5">Tag 5</option>' +
        '<option value="5">Tag 6</option>' +
        '<option value="5">Tag 7</option>' +
        '<option value="5">Tag 8</option>' +
        '<option value="5">Tag 9</option>' +
        '<option value="5">Tag 10</option>' +
        '</select>' +
        '</div>' +
        '</div>' +
        '</div>');
}

/***********************************************************************
Copy subscriber to another group while remaining in the original group.
************************************************************************/
function update(value) {
    var _str = value.toLowerCase();
    var str = _str.replace(" ", "_");
    $(".validate-check-attribute-value").html(value).attr("data-check-value", str);

    $(".action-sidebar").find(".row").remove();
    $(".action-sidebar").append('<div class="row ng-scope actionSidebarSubscriberGroup">' +
        '<div class="col-xs-12">' +
        '<div class="form-group next-row ng-hide">' +
        '<div class="email-content-loader trigger-error-on-visible">' +
        '<div class="sk-spinner sk-spinner-three-bounce">' +
        '<div class="sk-bounce1"></div>' +
        '<div class="sk-bounce2"></div>' +
        '<div class="sk-bounce3"></div>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>');
}
//end

/***********************************************************************
Move a subscriber from the selected group to a new group.
************************************************************************/
function moveToGroup(value) {
    var _str = value.toLowerCase();
    var str = _str.replace(" ", "_");
    $(".validate-check-attribute-value").html(value).attr("data-check-value", str);

    $(".action-sidebar").find(".row").remove();
    $(".action-sidebar").append('<div class="row ng-scope actionSidebarMoveToGroup">' +
        '<div class="col-xs-12">' +
        '<div class="form-group next-row ng-hide">' +
        '<div class="email-content-loader trigger-error-on-visible">' +
        '<div class="sk-spinner sk-spinner-three-bounce">' +
        '<div class="sk-bounce1"></div>' +
        '<div class="sk-bounce2"></div>' +
        '<div class="sk-bounce3"></div>' +
        '</div>' +
        '</div>' +
        '</div>' +

        '<div class="dropdown ng-scope">' +
        '<label class="ng-binding">Move from</label>' +
        '<button class="btn validate-check-attribute-value ng-binding" type="button" data-toggle="dropdown" data-check-value="{&quot;id&quot;:&quot;1379&quot;,&quot;name&quot;:&quot;sss List&quot;,&quot;total&quot;:&quot;1&quot;,&quot;date&quot;:&quot;2010-11-13 00:00:00&quot;,&quot;excluded&quot;:&quot;&quot;}"> sss List<span class="caret"></span></button>' +

        '<ul class="dropdown-menu mb30 with-search with-description ng-isolate-scope">' +
        '<div class="search-bar ng-hide">' +
        '<span class="glyphicon glyphicon-search ng-scope" aria-hidden="true" style="cursor: pointer"></span>' +
        '<input class="form-control ng-pristine ng-valid" type="text" placeholder="Search..">' +
        '</div>' +
        '<div style="max-height: 300px;overflow:auto;">' +
        '<li class="ng-scope selected">' +
        '<a href="#" class="ng-binding"><div class="title">sss List</div><div class="description small">1 subscribers • Created on 2010-11-13 00:00:00</div></a>' +
        '</li>' +
        '<li class="ng-scope">' +
        '<a href="#" class="ng-binding"><div class="title">sddd</div><div class="description small">0 subscribers • Created on 2019-09-13 16:54:21</div></a>' +
        '</li>' +
        '<li class="ng-scope">' +
        '<a href="#" class="ng-binding"><div class="title">sddd ddfdfd</div><div class="description small">0 subscribers • Created on 2019-09-13 16:54:35</div></a>' +
        '</li>' +
        '</div>' +
        '</ul>' +
        '</div>' +

        '<div class="dropdown ng-scope">' +
        '<label class="ng-binding">Move to<a class="create-new pull-right" href="javascript:;">Create a new group</a>' +
        '</label>' +

        '<div class="display-flex create-new-group-holder ng-hide" style="">' +
        '<div class="input">' +
        '<input class="form-control ng-pristine ng-valid" type="text" placeholder="Group name" style="">' +
        '</div>' +

        '<div class="create">' +
        '<a href="#" class="btn btn-primary ng-binding">Create</a>' +
        '</div>' +

        '<div class="cancel">' +
        '<a class="btn ng-binding" href="#">Cancel</a>' +
        '</div>' +
        '</div>' +

        '<button class="btn validate-check-attribute-value ng-binding" type="button" data-toggle="dropdown" data-check-value="">Select one of your subscriber groups<span class="caret"></span>' +
        '</button>' +

        '<ul class="dropdown-menu mb30 with-search with-description ng-isolate-scope">' +
        '<div class="search-bar ng-hide">' +
        '<span class="glyphicon glyphicon-search ng-scope" aria-hidden="true" style="cursor: pointer"></span>' +
        '<input class="form-control ng-pristine ng-valid" type="text" placeholder="Search..">' +
        '</div>' +
        '<div style="max-height: 300px;overflow:auto;">' +
        '<li class="ng-scope">' +
        '<a href="#" class="ng-binding"><div class="title">sddd</div><div class="description small">0 subscribers • Created on 2019-09-13 16:54:21</div></a>' +
        '</li>' +
        '<li class="ng-scope">' +
        '<a href="#" class="ng-binding"><div class="title">sddd ddfdfd</div><div class="description small">0 subscribers • Created on 2019-09-13 16:54:35</div></a>' +
        '</li>' +
        '</div>' +
        '</ul>' +
        '</div>' +
        '</div>' +
        '</div>');
}
//end

/***********************************************************************
Remove a subscriber from a selected group.
************************************************************************/
function showSMSsidebar(value) {
    var _str = value.toLowerCase();
    var str = _str.replace(" ", "_");
    $(".validate-check-attribute-value").html(value).attr("data-check-value", str);
    $("h3.action-header").addClass("ng-hide");
    $("h3.removetag-header").addClass("ng-hide");
    $("h3.addtag-header").addClass("ng-hide");
    $("h3.delay-header").addClass("ng-hide");
    $("h3.unsubscribe-header").addClass("ng-hide");
    $("h3.email-header").addClass("ng-hide");
    $("h3.sms-header").removeClass("ng-hide");

    $(".action-sidebar").find(".row").remove();
    $(".action-sidebar").append('<div class="row ng-scope actionSidebarRemoveFromGroup">' +
        '<div class="col-xs-12">' +
        '<div class="form-group next-row ng-hide">' +
        '<div class="email-content-loader trigger-error-on-visible">' +
        '<div class="sk-spinner sk-spinner-three-bounce">' +
        '<div class="sk-bounce1"></div>' +
        '<div class="sk-bounce2"></div>' +
        '<div class="sk-bounce3"></div>' +
        '</div>' +
        '</div>' +
        '</div>' +

        '<div class="dropdown ng-scope" ng-if="!groupsLoading">' +
        '<label class="ng-binding">SMS</label>' +
        '<textarea class="btn validate-check-attribute-value" placeholder="Type SMS here" rows="7" style="margin-bottom: 30px;"></textarea>' +
        '<button class="btn validate-check-attribute-value ng-binding" type="button" data-toggle="dropdown" data-check-value="">Select a sending server<span class="caret"></span></button>' +
        '<ul class="dropdown-menu mb30 with-search with-description ng-isolate-scope">'+
            '<div class="search-bar ng-hide">'+
                '<span class="glyphicon glyphicon-search ng-scope" aria-hidden="true" style="cursor: pointer"></span>'+
                '<input class="form-control ng-pristine ng-valid" type="text" placeholder="Search..">'+
            '</div>'+
            '<div style="max-height: 300px;overflow:auto;">'+
                '<li>'+
                    '<a href="#">'+
                        '<div class="title">Mailgun</div>'+
                    '</a>'+
                '</li>'+
                '<li>'+
                    '<a href="#">'+
                        '<div class="title">Mailchimp</div>'+
                    '</a>'+
                '</li>'+
                '<li>'+
                    '<a href="#">'+
                        '<div class="title">Sendingblue</div>'+
                    '</a>'+
                '</li>'+
            '</div>'+
        '</ul>'+
        '</div>' +
        '</div>' +
        '</div>');
}
//end

/***********************************************************************
Subscriber becomes inactive and is no longer part of the workflow.
************************************************************************/
function markAsUnsubscribed(value) {
    var _str = value.toLowerCase();
    var str = _str.replace(" ", "_");
    $("h3.action-header").addClass("ng-hide");
    $("h3.removetag-header").addClass("ng-hide");
    $("h3.delay-header").addClass("ng-hide");
    $("h3.sms-header").addClass("ng-hide");
    $("h3.addtag-header").addClass("ng-hide");
    $("h3.email-header").addClass("ng-hide");
    $("h3.unsubscribe-header").removeClass("ng-hide");
    $(".validate-check-attribute-value").html(value).attr("data-check-value", str);
    $(".action-sidebar").find(".row").remove();
    $(".action-sidebar").append('<div class="row ng-scope actionSidebarRemoveFromGroup">' +
    '<a href="#" class="btn ng-binding btn-default" style="border-radius: none; margin-top: 5%; margin-left: 5%;">Click to unsubscribe</a>' +
    '</div>');
}
//end

/***********************************************************************
Move subscriber to a different step in the workflow.
************************************************************************/
function moveToAnotherStep(value) {
    var _str = value.toLowerCase();
    var str = _str.replace(" ", "_");
    $(".validate-check-attribute-value").html(value).attr("data-check-value", str);

    $(".action-sidebar").find(".row").remove();
    $(".action-sidebar").append('<div class="row ng-scope actionSidebarFilterByStep">' +
        '<div class="col-xs-12">' +
        '<label class="ng-binding">Filter by step</label>' +
        '<div class="dropdown">' +
        '<button class="btn validate-check-attribute-value ng-binding" type="button" data-toggle="dropdown" data-check-value="" aria-expanded="false">Select<span class="caret"></span></button>' +
        '<ul class="dropdown-menu">' +
        '<li class="ng-scope"><a href="#" class="ng-binding">Action</a></li>' +
        '<li class="ng-scope"><a href="#" class="ng-binding">Condition</a></li>' +
        '</ul>' +
        '</div>' +
        '</div>' +
        '</div>');
}
//end

function showDefaultSidebar(){
	$(".incomplete-steps").removeClass("ng-hide");
	$("h3.ng-binding").addClass("ng-hide");

	$("form.ng-pristine").find(".sidebar-content-bottom").remove();
	$("form.ng-pristine .sidebar-content").css("bottom","0px");
	$("form.ng-pristine .sidebar-content .sidebar-include").html('<div class="ng-scope">'+
	    '<div class="form-group workflow-report separator">'+
            
                '<div class="col-xs-12">' +
                '<h4 class="ng-binding">Workflow Name</h4>' +
                '</div>' +
                '<div class="col-xs-12">' +
                '<h4 class="ng-binding">Date created</h4>' +
                '</div>' +
                '<div class="row mb30">'+
	            '<div class="col-xs-4" style="margin-top:10%;">'+
	                '<h4 class="ng-binding">Completed</h4>'+
	                '<span class="number ng-binding">0</span>'+
	            '</div>'+

	            '<div class="col-xs-4" style="margin-top:10%;">'+
	                '<h4 class="ng-binding">Queue</h4>'+
	                '<span class="number ng-binding">0</span>'+
                '</div>'+
                
                '<div class="col-xs-4" style="margin-top:10%;">' +
                    '<h4 class="ng-binding" style="font-size:11px;">Number of people in workflow</h4>' +
                    '<span class="number ng-binding">0</span>' +
                '</div>' +
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
	    '</div>'+
	'</div>');
	
}

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

// Emoji Picker 
$(function () {
    $('.emojiPickerIconWrap').emoji();
})

$(function () {
    $('.emojiPickerIconWrap').emoji({
      button: '&#x1F642;'
    });
})

$(function () {
    $('.emojiPickerIconWrap').emoji({
      place: 'after'
    });
})

$(function () {
    $('.emojiPickerIconWrap').emoji({
      fontSize: '20px'
    });
})

$(function () {
    $('.emojiPickerIconWrap').emoji({
      emojis: ['&#x1F642;', '&#x1F641;', '&#x1f600;', '&#x1f601;', '&#x1f602;', '&#x1f603;', '&#x1f604;', '&#x1f605;', '&#x1f606;', '&#x1f607;', '&#x1f608;', '&#x1f609;', '&#x1f60a;', '&#x1f60b;', '&#x1f60c;', '&#x1f60d;', '&#x1f60e;', '&#x1f60f;', '&#x1f610;', '&#x1f611;', '&#x1f612;', '&#x1f613;', '&#x1f614;', '&#x1f615;', '&#x1f616;', '&#x1f617;', '&#x1f618;', '&#x1f619;', '&#x1f61a;', '&#x1f61b;', '&#x1f61c;', '&#x1f61d;', '&#x1f61e;', '&#x1f61f;', '&#x1f620;', '&#x1f621;', '&#x1f622;', '&#x1f623;', '&#x1f624;', '&#x1f625;', '&#x1f626;', '&#x1f627;', '&#x1f628;', '&#x1f629;', '&#x1f62a;', '&#x1f62b;', '&#x1f62c;', '&#x1f62d;', '&#x1f62e;', '&#x1f62f;', '&#x1f630;', '&#x1f631;', '&#x1f632;', '&#x1f633;', '&#x1f634;', '&#x1f635;', '&#x1f636;', '&#x1f637;', '&#x1f638;', '&#x1f639;', '&#x1f63a;', '&#x1f63b;', '&#x1f63c;', '&#x1f63d;', '&#x1f63e;', '&#x1f63f;', '&#x1f640;', '&#x1f643;', '&#x1f4a9;', '&#x1f644;', '&#x2620;', '&#x1F44C;','&#x1F44D;', '&#x1F44E;', '&#x1F648;', '&#x1F649;', '&#x1F64A;']
    });
})

$(function () {
    $('.emojiPickerIconWrap').emoji({
      listCSS: {
        position: 'absolute', 
        border: '1px solid gray', 
        backgroundColor: '#fff', 
        display: 'none'
      }
    });
})

$(function () {
    $('.emojiPickerIconWrap').emoji({
      rowSize: 10
    });
})