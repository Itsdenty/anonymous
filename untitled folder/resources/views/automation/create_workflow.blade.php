<!DOCTYPE html>
<html class="">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <style type="text/css">@charset "UTF-8";[ng\:cloak],[ng-cloak],[data-ng-cloak],[x-ng-cloak],.ng-cloak,.x-ng-cloak,.ng-hide{display:none !important;}ng\:form{display:block;}.ng-animate-block-transitions{transition:0s all!important;-webkit-transition:0s all!important;}.ng-hide-add-active,.ng-hide-remove{display:block!important;}</style>
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    
    <link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.no-icons.min.css" rel="stylesheet">
    <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
    <link rel="icon" href='{{ asset("favicon.png") }}'/>
    <link rel="stylesheet" href='{{ url("css/bootstrap.min.css") }}'>
    <link rel="stylesheet" href='{{ url("css/jquery.fancybox.css") }}'>
    <link rel="stylesheet" href='{{ url("css/slider.css") }}'>
    <link rel="stylesheet" href='{{ url("css/green.css") }}'>

    <link href='{{ url("css/datepicker.css") }}' rel="stylesheet" type="text/css">
    <link href='{{ url("css/fonts.css") }}' rel="stylesheet" type="text/css">

    <link rel="stylesheet" href='{{ url("css/bootstrap-switch.css") }}'>
    <link rel="stylesheet" href='{{ url("css/universal.css") }}'>
    <link href='{{ url("css/style.css") }}' rel="stylesheet" type="text/css" media="all">

    <script src='{{ url("js/bootstrap-slider.min.js") }}'></script>

    <link rel="stylesheet" href='{{ url("css/bootstrap-slider.min.css") }}'>

    <script src='{{ url("js/jquery.min.js") }}'></script>
    <script src='{{ url("js/jquery-ui.min.js") }}'></script>

    <!-- <script src="https://unpkg.com/popper.js@1.15.0/dist/umd/popper.min.js"></script> -->
    <script src='{{ url("js/bootstrap.min.js") }}'></script>

    <link rel="stylesheet" href='{{ url("css/photoeditor.css") }}'>
    <link rel="stylesheet" href='{{ url("css/productpicker.css") }}'>

<!-- 
<script src='{{ url("js/components.js") }}'></script>
<script src='{{ url("js/filemanager.js") }}'></script>
<script src='{{ url("js/components(1).js") }}'></script><style data-adonis="true"></style>
<script src='{{ url("js/photoeditor.js") }}'></script>
<script src='{{ url("js/components(2).js") }}'></script>
<script src='{{ url("js/productpicker.js") }}'></script>
<script src='{{ url("js/translate.js") }}'></script>
<script src='{{ url("js/statistics.js") }}'></script>
<script src='{{ url("js/bootstrap-datetimepicker.js") }}'></script>
<script src='{{ url("js/bootstrap-switch.js") }}'></script>
<script src='{{ url("js/moment-with-locales.min.js") }}'></script>
<script type="text/javascript" src="js/socket.io.min.js"></script> -->

<script src='{{ url("js/jquery.validate.min.js") }}'></script>
<script type="text/javascript" src='{{ url("js/bootstrap-switch.min(1).js") }}'></script>
<script src='{{ url("js/clipboard.min.js") }}'></script>
<script src='{{ url("js/selectize.min.js") }}'></script>
<link rel="stylesheet" href='{{ url("css/selectize.default.css") }}'>
    
<title>SendMunk | Create Workflow</title>

<link rel="stylesheet" type="text/css" href='{{ url("css/universal.css") }}' media="all">
<style type="text/css">.fancybox-margin{margin-right:0px;}</style>
<script>
    window.onload = function() {
        $('#loader').addClass("ng-hide");
        $('.short-link').removeClass("ng-hide");
    }
    // var updatedContent = document.querySelectorAll("#actionDropdown li span");
    // console.log(updatedContent);
    // for (var i = 0; i < updatedContent.length; i++) {
    //   console.log(updatedContent.length);
    //     updatedContent[i].onClick = function() {
    //         document.getElementById('defineAction').value = this.innerHTML;
    //     }
    // }
</script>
</head>


<body>

<script src='{{ url("js/svg4everybody.min.js") }}'></script>
<script>svg4everybody();</script>




<link rel="stylesheet" type="text/css" href='{{ url("css/emoji.css") }}'>
<link rel="stylesheet" type="text/css" href='{{ url("css/emoji-sprite-20.css") }}'>
<script type="text/javascript" src='{{ url("js/jquery.emojipicker.js") }}'></script>
<script type="text/javascript" src='{{ url("js/jquery.emojipicker.a.js") }}'></script>

<script src='{{ url("js/emojione.min.js") }}'></script>
<link rel="stylesheet" href='{{ url("css/emojione.min.css") }}'>
<script>
    emojione.imagePathPNG = '/assets/emoji-data/img-apple-64/';
</script>

<script>
    window.replaceImgToAltAndRemoveHtml = function (element, plainText) {
        if (typeof plainText !== 'undefined' && plainText) {
            var text = element;
        } else {
            var text = $(element).html();
        }

        text = text.replace(/<img.*?alt=".*?".*?>/g, function (match) {
            return /alt="(.*?)"/.exec(match)[1];
        });
        text = $("<div>").html(text).text();

        return text;
    };
</script>

<script src='{{ url("js/jquery.caret.js") }}'></script>

<!-- <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>-->
<script src='{{ url("js/inputEmoji.js") }}'></script>

<div class="automation-controller ng-scope">

  <div id="loader">
		<span class="">
			<div class="sk-spinner sk-spinner-three-bounce">
				<div class="sk-bounce1"></div>
				<div class="sk-bounce2"></div>
				<div class="sk-bounce3"></div>
			</div>
			Loading...
		</span>
  </div>

  <div ng-show="!treeLoading" class="">
    <!-- Workflow builder -->
    <div class="ml-workflow-builder-template">
      <!-- Back button -->
      <div class="back-to-automation">
        <a class="back" href="{{ url('visual_automation').'/'}}">
          <div class="short-link ng-hide">
            <svg class="icon">
              <use xlink:href="assets/images/svg/workflow.svg#icon-arrow-left-thick"></use>
            </svg>
          </div>
          <div class="full-link hide">
            <svg class="icon mr5">
              <use xlink:href="assets/images/svg/workflow.svg#icon-arrow-left-thick"></use>
            </svg>
            <div class="pull-left">
              <ng-container class="  ng-scope">Go back</ng-container>
            </div>
          </div>
        </a>
      </div>
      <!-- End of Back button -->
      <!-- Zoom controls -->
      <div class="ml-workflow-zoom-controls hide">
        <div class="btn-group-vertical" role="group">
          <button type="button" class="btn btn-default">
            <span class="glyphicon glyphicon-zoom-in" aria-hidden="true"></span>
          </button>
          <button type="button" class="btn btn-default">
            <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
          </button>
          <button type="button" class="btn btn-default">
            <span class="glyphicon glyphicon-zoom-out" aria-hidden="true"></span>
          </button>
        </div>
      </div> 
      <!-- End of Zoom controls -->

      <!-- Workflow steps -->
      <div class="ml-workflow-builder-content">
        <div class="ml-workflow-builder-area">
          <ul class="wf-builder" style="width: 520px;" data-index="0">
            <li class="wf-builder-steps">

              <!-- ancestor step -->
              <div class="ng-scope ng-isolate-scope ancestor-step">

                <!-- all steps will be inside here-->
                <div class="ng-scope parent-step">

                  <!-- first child step (default) -->
                  <div class="step-container">
                    <div  class="step-content trigger step-complete" id="step-container1566666666">
                      <ng-container class="ng-scope">
                        <generate-automation-step-description step="data" class="ng-isolate-scope">
                          <ng-include src="&#39;/assetsjs/core/views/generate-automation-step-description/trigger.html&#39;" class="ng-scope">
                            <span class="ng-scope">
                            @if($automation->trigger_type=='contact')
                              <span class="  ng-scope">when contact is added</span>
                              @else
                              <span class="  ng-scope">when tag is added</span>
                            @endIf
                            </span>
                          </ng-include>
                          <ng-include src="&#39;/assetsjs/core/views/generate-automation-step-description/email.html&#39;" class="ng-scope"></ng-include>
                          <ng-include src="&#39;/assetsjs/core/views/generate-automation-step-description/action.html&#39;" class="ng-scope"></ng-include>
                          <ng-include src="&#39;/assetsjs/core/views/generate-automation-step-description/delay.html&#39;" class="ng-scope"></ng-include>
                          <ng-include src="&#39;/assetsjs/core/views/generate-automation-step-description/condition.html&#39;" class="ng-scope"></ng-include>
                        </generate-automation-step-description>
                      </ng-container>
                    </div>

                    <div class="add-step ng-scope add-step1566666666">
                      <a href="javascript:;" type="button" class="btn btn-add ng-hide">
                        <svg class="icon icon-action">
                          <use xlink:href='{{ url("assets/images/svg/workflow.svg#icon-action") }}'></use>
                        </svg>
                      </a>

                      <a href="javascript:;" type="button" class="btn btn-add show-steps" id="addstep1566666666" onclick="showSteps(this);">
                        <svg class="icon icon-action show-steps">
                          <use xlink:href='{{ url("assets/images/svg/workflow.svg#icon-action") }}'></use>
                        </svg>
                      </a>
                    </div>

                    <div class="ng-scope"></div>
                  </div>
                  <!--end -->

                </div>
                <!-- end-->

                <!-- explanation step -->
                <div class="first-step-explanation   ng-scope">Add steps to the workflow by clicking the plus icon</div>
                <!-- end-->

              </div>
              <!-- end-->

            </li>
          </ul>
        </div>
      </div>
      <!-- End of Workflow Steps -->

      <!-- steps side bar -->
      <div class="ml-workflow-builder-sidebar">
        <div class="sidebar-content-top">
          <h3 class="  ng-hide delay-header">Delay</h3>
          <h3 class="  ng-hide email-header">Email</h3>
          <h3 class="  ng-hide condition-header">Condition</h3>
          <h3 class="  ng-hide action-header">Action</h3>
          <h3 class="  ng-hide addtag-header">Add Tag</h3>
          <h3 class="  ng-hide removetag-header">Remove Tag</h3>
          <h3 class="  ng-hide sms-header">SMS</h3>
          <h3 class="  ng-hide unsubscribe-header">Unsubscribe</h3>

          <div class="workflow-details pull-left ng-hide">
            <h3 class=" ">Workflow</h3>
          </div>

          <div class="incomplete-steps">
            <a href="javascript:;" >
              <span class="  ng-scope">Workflow is not completed</span>
            </a>
          </div>
        </div>

        <form class="ng-pristine ng-valid">
          <div class="sidebar-content" style="bottom: 0px;">
            <div class="sidebar-include ng-scope">
              <div class="ng-scope">
                <div class="form-group workflow-report separator">
                  <div class="col-xs-12">
                    <h4 class=" ">{{$automation->name}}</h4>
                  </div>

                  <div class="col-xs-12">
                    <h4 class=" ">{{$automation->com_create_guid}}</h4>
                  </div>
                  <div class="row mb30">
                    <div class="col-xs-4" style="margin-top:10%;">
                      <h4 class=" ">Completed</h4>
                      <span class="number  ">0</span>
                    </div>

                    <div class="col-xs-4" style="margin-top:10%;">
                      <h4 class=" ">Queue</h4>
                      <span class="number  ">0</span>
                    </div>

                    <div class="col-xs-4" style="margin-top:10%;">
                      <h4 class=" " style="font-size:11px;">Number of people in workflow</h4>
                      <span class="number  ">0</span>
                    </div>
                  </div>

                  <div class="row mb30">
                    <div class="col-xs-12 ng-scope">
                      <a class="btn btn-block btn-default  ">View workflow activity</a>
                    </div>
                  </div>
                </div>

                <div class="form-group workflow-report">
                  <div class="row mb30 ng-scope">
                    <div class="col-xs-12">
                      <div class="notification">
                        <p class=" ">This workflow does not have any sent emails yet.</p>
                      </div>
                    </div>
                  </div>
                  
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
      <!-- end -->
    </div>


  </div>
</div>

<input type="hidden" name="step_postion">

<script type="text/javascript" src='{{ url("js/jqueryMailPlugin.js") }}'></script>
<script>
         jQuery(document).ready(function(){
            jQuery('div.save-btn').click(function(e){
               e.preventDefault();
               $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                  }
              });
               jQuery.ajax({
                  url: "{{ route('workflow') }}",
                  method: 'post',
                  data: {

                          action: jQuery('div.sidebar-content-top h3:visible').html(),
                          addTag: jQuery('div[style] select[class=custom-select]:visible').val(),
                          removeTag: jQuery('div[ng-if=!groupsLoading] select:visible').val(),
                          emailSubject: jQuery('div.emoji-container div[class=ng-valid]:visible').text(),
                          senderName: jQuery('div.open button:visible').text(),
                          senderEmail: jQuery('div.dropdown button:visible').text(),
                          wait: jQuery('div.display-flex input[type=text]:visible').val(),
                          time: jQuery('div.number-width select:visible').val(),
                          smsContent: jQuery('textarea[placeholder=Type SMS here]').val(),
                          smsServer: jQuery('div[ng-if=!groupsLoading] button').text()
                  },
                  success: function(result){
                     jQuery('.alert').show();
                     jQuery('.alert').html(result.success);
                  }});
               });
            });
            </script>
</body>
</html>