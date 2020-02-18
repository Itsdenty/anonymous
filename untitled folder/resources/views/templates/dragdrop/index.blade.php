<!doctype html>
<html>
    <head>
        <title>Sendmunk | View Template</title>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">

        <link href="{{ asset('assets/css/demo.css') }}" rel="stylesheet" />
        <link href="{{ asset('assets/css/email-editor.bundle.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('assets/css/colorpicker.css') }}" rel="stylesheet" />

        <link href="{{ asset('assets/css/editor-color.css') }}" rel="stylesheet" />
        <!--for bootstrap-tour  -->
        <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-tour/build/css/bootstrap-tour.min.css') }}">
         <link rel="stylesheet" href="{{ asset('assets/vendor/sweetalert2/dist/sweetalert2.min.css') }}">
    </head>
    <body>
        <div class="bal-header">
            <div class="bal-user-info">

                <div class="bal-header-controls">
                    <a id="bal-button-save-template" class="bal-button-exit" href="#">Save Template</a>
                    <a id="bal-button-save-and-exit" class="bal-button-exit" href="#">Save And Exit</a>
                    <a id="bal-button-exit" class="bal-button-exit" href="#">Exit</a>
                </div>
            </div>
        </div>
        <div class="bal-editor-demo">

        </div>
        <div id="previewModal" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Preview</h4>
                </div>
                <div class="modal-body">
                    <div class="">
                        <label for="">URL : </label> <span class="preview_url"></span>
                    </div>
                    <iframe id="previewModalFrame" width="100%" height="400px"></iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

            </div>
        </div>

        <script src="{{ asset('assets/vendor/jquery/dist/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/jquery-ui/jquery-ui.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/jquery-nicescroll/dist/jquery.nicescroll.min.js') }}"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        
        <!--for ace editor  -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.1.01/ace.js" type="text/javascript"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.1.01/theme-monokai.js" type="text/javascript"></script>

        <!--for tinymce  -->

        <script src="{{ asset('assets/vendor/tinymce/js/tinymce/tinymce.min.js') }}"></script>
        {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.0.11/tinymce.min.js" type="text/javascript"></script> --}}

        <script src="{{ asset('assets/vendor/sweetalert2/dist/sweetalert2.min.js') }}"></script>


        <script src="{{ asset('assets/js/colorpicker.js') }}"></script>
        <script src="{{ asset('assets/js/bal-email-editor-plugin.js?v=23') }}"></script>

        <!--for bootstrap-tour  -->
        <script src="{{ asset('assets/vendor/bootstrap-tour/build/js/bootstrap-tour.min.js') }}"></script>

        <script type="text/javascript" src="{{ url('js/notify.min.js') }}"></script>

        <script>
            var _is_demo = false;

            function loadImages() {
                $.ajax({
                    url: 'get-files.php',
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        if (data.code == 0) {
                            _output = '';
                            for (var k in data.files) {
                                if (typeof data.files[k] !== 'function') {
                                    _output += "<div class='col-sm-3'>" +
                                        "<img class='upload-image-item' src='" + data.directory + data.files[k] + "' alt='" + data.files[k] + "' data-url='" + data.directory + data.files[k] + "'>" +
                                        "</div>";
                                    // console.log("Key is " + k + ", value is" + data.files[k]);
                                }
                            }
                            $('.upload-images').html(_output);
                        }
                    },
                    error: function() {}
                });
            }

            var _templateListItems;

            var  _emailBuilder=  $('.bal-editor-demo').emailBuilder({
                //new features begin
                showMobileView:true,
                onTemplateDeleteButtonClick:function (e,dataId,parent) {

                    $.ajax({
                            url: 'delete_template.php',
                            type: 'POST',
                            data: {
                                    templateId: dataId
                            },
                        //	dataType: 'json',
                            success: function(data) {
                                        parent.remove();
                            },
                            error: function() {}
                    });
                },
                //new features end

                lang: 'en',
                elementJsonUrl: '{{ url("assets/elements-1.json") }}',
                langJsonUrl: '{{ url("assets/lang-1.json") }}',
                loading_color1: 'red',
                loading_color2: 'green',
                showLoading: true,

                blankPageHtmlUrl: '{{ url("template-blank-page") }}',
                loadPageHtmlUrl: '{{ url("template-load-page") }}',

                //left menu
                showElementsTab: true,
                showPropertyTab: true,
                showCollapseMenu: true,
                showBlankPageButton: true,
                showSettingsLoadTemplate: true,
                showCollapseMenuinBottom: true,

                //setting items
                showSettingsBar: true,
                showSettingsPreview: true,
                showSettingsExport: true,
                showSettingsSendMail: false,
                showSettingsSave: false,

                //show context menu
                showContextMenu: true,
                showContextMenu_FontFamily: true,
                showContextMenu_FontSize: true,
                showContextMenu_Bold: true,
                showContextMenu_Italic: true,
                showContextMenu_Underline: true,
                showContextMenu_Strikethrough: true,
                showContextMenu_Hyperlink: true,

                //show or hide elements actions
                showRowMoveButton: true,
                showRowRemoveButton: true,
                showRowDuplicateButton: true,
                showRowCodeEditorButton: true,
                onElementDragStart: function(e) {
                    console.log('onElementDragStart html');
                },
                onElementDragFinished: function(e,contentHtml) {
                    console.log('onElementDragFinished html');
                    //console.log(contentHtml);

                },

                onBeforeRowRemoveButtonClick: function(e) {
                    console.log('onBeforeRemoveButtonClick html');

                    /*
                        if you want do not work code in plugin ,
                        you must use e.preventDefault();
                    */
                    //e.preventDefault();
                },
                onAfterRowRemoveButtonClick: function(e) {
                    console.log('onAfterRemoveButtonClick html');
                },
                onBeforeRowDuplicateButtonClick: function(e) {
                    console.log('onBeforeRowDuplicateButtonClick html');
                    //e.preventDefault();
                },
                onAfterRowDuplicateButtonClick: function(e) {
                    console.log('onAfterRowDuplicateButtonClick html');
                },
                onBeforeRowEditorButtonClick: function(e) {
                    console.log('onBeforeRowEditorButtonClick html');
                    //e.preventDefault();
                },
                onAfterRowEditorButtonClick: function(e) {
                    console.log('onAfterRowDuplicateButtonClick html');
                },
                onBeforeShowingEditorPopup: function(e) {
                    console.log('onBeforeShowingEditorPopup html');
                    //e.preventDefault();
                },
                onBeforeSettingsSaveButtonClick: function(e) {
                    console.log('onBeforeSaveButtonClick html');
                    //e.preventDefault();

                    //  if (_is_demo) {
                    //      $('#popup_demo').modal('show');
                    //      e.preventDefault();//return false
                    //  }
                },
                onPopupUploadImageButtonClick: function() {
                    console.log('onPopupUploadImageButtonClick html');
                    var file_data = $('.input-file').prop('files')[0];
                    var form_data = new FormData();
                    form_data.append('file', file_data);
                    $.ajax({
                        url: 'upload.php', // point to server-side PHP script
                        dataType: 'text', // what to expect back from the PHP script, if anything
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: form_data,
                        type: 'post',
                        success: function(php_script_response) {
                            loadImages();
                        }
                    });
                },
                onSettingsPreviewButtonClick: function(e, getHtml) {
                    console.log('onPreviewButtonClick html');
                    $.ajax({
                        url: 'export',
                        type: 'POST',
                        headers: {'X-CSRF-Token': '{{ csrf_token() }}'},
                        data: {
                            html: getHtml
                        },
                        dataType: 'json',
                        success: function(data) {
                            if (data.code == -5) {
                                $('#popup_demo').modal('show');
                                return;
                            } else if (data.code == 0) {
                                $('#previewModalFrame').attr('src',data.preview_url);
                                $('.preview_url').html('<a href="'+data.preview_url+'" target="_blank">'+data.preview_url+'</a>');
                                $('#previewModal').modal('show');
                                // var win = window.open(data.preview_url, '_blank');
                                // if (win) {
                                //     //Browser has allowed it to be opened
                                //     win.focus();
                                // } else {
                                //     //Browser has blocked it
                                //     alert('Please allow popups for this website');
                                // }
                            }
                        },
                        error: function(response) {
                        }
                    });
                    //e.preventDefault();
                },

                onSettingsExportButtonClick: function(e, getHtml) {
                    console.log('onSettingsExportButtonClick html');
                    $.ajax({
                        url: 'export',
                        type: 'POST',
                        headers: {'X-CSRF-Token': '{{ csrf_token() }}'},
                        data: {
                            html: getHtml
                        },
                        dataType: 'json',
                        success: function(data) {
                            if (data.code == -5) {
                                $('#popup_demo').modal('show');
                            } else if (data.code == 0) {
                                window.location.href = data.url;
                            }
                        },
                        error: function() {}
                    });
                    //e.preventDefault();
                },
                onBeforeSettingsLoadTemplateButtonClick: function(e) {
                    // To do - Emegbue Chukwudi John
                    $.ajax({
                        url: 'load_user_templates',
                        headers: {'X-CSRF-Token': '{{ csrf_token() }}'},
                        type: 'POST',
                        data: {templateId: '{{ $template->id }}'},
                        success: function(data) {
                            $('.template-list').html('<ul class="nav nav-tabs"><li class="active"><a data-toggle="tab" href="#user">My Templates</a></li><li><a data-toggle="tab" href="#gallery">Templates Gallery</a></li></ul> <div class="tab-content"><div id="user" class="tab-pane fade in active row"><div style="text-align:center">No items</div></div><div id="gallery" class="tab-pane row"><div style="text-align:center">No items</div></div></div>');
                            
                            let user_templates = JSON.parse(data.user_templates);
                            let custom_templates = JSON.parse(data.custom_templates);
                            if(user_templates.length != 0)
                            {
                                let user_template_content="<br/>";
                                $.each(user_templates, function(index, value){
                                    let template = '<div class="col-sm-6 col-md-4">';
                                    template += '<div class="thumbnail">';
                                    if(value["screenshot"] == null)
                                    {
                                        template += '<img src="' + '{{ url("/") }}' + '/' + 'img/default.jpg' + '">';
                                    }
                                    else
                                    {
                                        template += '<img src="' + '{{ url("/") }}' + '/' + value["screenshot"] + '">';
                                    }
                                    template += '<div class="caption">';
                                    template += '<h6 style="text-align: center;">'+ value["name"] +'</h6>'
                                    template += '<a href="#" class="apply_template_btn btn btn-block btn-primary" role="button" data-id="' + value['id'] + '" data-loading-text="<i class=' + "'fa fa-circle-o-notch fa-spin'" + '></i> Applying">Apply</a>';
                                    template += "</div></div></div>";
                                    user_template_content += template;
                                });

                                $('.template-list').find('#user').empty();
                                $('.template-list').find('#user').append(user_template_content);
                            }
                            if(custom_templates.length != 0)
                            {
                                let custom_template_content="<br/>";
                                $.each(custom_templates, function(index, value){
                                    let template = '<div class="col-sm-6 col-md-4">';
                                    template += '<div class="thumbnail">';
                                    if(value["screenshot"] == null)
                                    {
                                        template += '<img src="' + '{{ url("/") }}' + '/' + 'img/default.jpg' + '">';
                                    }
                                    else
                                    {
                                        template += '<img src="' + '{{ url("/") }}' + '/' + value["screenshot"] + '">';
                                    }
                                    template += '<div class="caption">';
                                    template += '<h6 style="text-align: center;">'+ value["name"] +'</h6>'
                                    template += '<a href="#" class="apply_template_btn btn btn-block btn-primary" role="button" data-id="' + value['id'] + '" data-loading-text="<i class=' + "'fa fa-circle-o-notch fa-spin'" + '></i> Applying">Apply</a>';
                                    template += "</div></div></div>";
                                    custom_template_content += template;
                                });

                                $('.template-list').find('#gallery').empty();
                                $('.template-list').find('#gallery').append(custom_template_content);
                            }                            
                        },
                        error: function(data) {
                            console.log("error");
                            $('.template-list').html('<div style="text-align:center">No items</div>');
                        }
                    });
                },
                onSettingsSendMailButtonClick: function(e) {
                    console.log('onSettingsSendMailButtonClick html');
                    //e.preventDefault();
                },
                onPopupSendMailButtonClick: function(e, _html) {
                    console.log('onPopupSendMailButtonClick html');
                    _email = $('.recipient-email').val();
                    _element = $('.btn-send-email-template');

                    output = $('.popup_send_email_output');
                    var file_data = $('#send_attachments').prop('files');
                    var form_data = new FormData();
                    //form_data.append('attachments', file_data);
                    $.each(file_data,function (i,file) {
                        form_data.append('attachments['+i+']', file);
                    });
                    form_data.append('html', _html);
                    form_data.append('mail', _email);

                    $.ajax({
                        url: 'send.php', // point to server-side PHP script
                        dataType: 'json', // what to expect back from the PHP script, if anything
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: form_data,
                        type: 'post',
                        success: function(data) {
                            if (data.code == 0) {
                                output.css('color', 'green');
                            } else {
                                output.css('color', 'red');
                            }

                            _element.removeClass('has-loading');
                            _element.text('Send Email');

                            output.text(data.message);
                        }
                    });

                },
                onBeforeChangeImageClick: function(e) {
                    console.log('onBeforeChangeImageClick html');
                    loadImages();
                },
                onBeforePopupSelectTemplateButtonClick: function(e) {
                    console.log('onBeforePopupSelectTemplateButtonClick html');

                },
                onBeforePopupSelectImageButtonClick: function(e) {
                    console.log('onBeforePopupSelectImageButtonClick html');

                },
                onPopupSaveButtonClick: function() {
                    console.log('onPopupSaveButtonClick html');
                    $.ajax({
                        url: 'save_template',
                        type: 'POST',
                        data: {
                            name: $('.template-name').val(),
                            content: $('.bal-content-wrapper').html()
                        },
                        success: function(data) {
                            //  console.log(data);
                            if (data === 'ok') {
                                $('#popup_save_template').modal('show');
                            } else {
                                $('.input-error').text('Problem in server');
                            }
                        },
                        error: function(error) {
                            $('.input-error').text('Internal error');
                        }
                    });
                },
                onUpdateButtonClick: function() {
                    console.log('onUpdateButtonClick html');
                    $.ajax({
                            url: 'upload_template.php',
                            type: 'POST',
                            //dataType: 'json',
                            data: {
                                    name: $('.bal-project-name').text(),
                                    content: $('.bal-content-wrapper').html(),
                                    id: $('.bal-project-name').attr('data-id')
                            },
                            success: function(data) {
                                    //  console.log(data);
                                    // if (data === 'ok') {
                                    // 		$('#popup_save_template').modal('hide');
                                    // } else {
                                    // 		$('.input-error').text('Problem in server');
                                    // }
                            },
                            error: function(error) {
                                    $('.input-error').text('Internal error');
                            }
                    });
                },
                onClickSaveAndExitButton: function(e, getHtml){
                    $.ajax({
                        url: 'save_and_exit_template',
                        headers: {'X-CSRF-Token': '{{ csrf_token() }}'},
                        type: 'POST',
                        data: {
                            editable_content: $('.bal-content-wrapper').html(),
                            preview_content: getHtml,
                            template_id : '{{ $template->id }}'
                        },
                        success: function(data){
                            window.location.href = "{{ url('templates') }}";
                            console.log("template saved successfully");
                        },
                        error: function(response){
                            $('.input-error').text('Internal error');
                        }
                    })
                },
                onClickSaveTemplateButton: function(e, getHtml){
                    $.ajax({
                        url: 'save_and_exit_template',
                        headers: {'X-CSRF-Token': '{{ csrf_token() }}'},
                        type: 'POST',
                        data: {
                            editable_content: $('.bal-content-wrapper').html(),
                            preview_content: getHtml,
                            template_id : '{{ $template->id }}'
                        },
                        success: function(data){
                            $.notify('Template Saved Successfully', 'success');
                            console.log("template saved successfully");
                        },
                        error: function(response){
                            $('.input-error').text('Internal error');
                        }
                    })
                },
                onClickExitButton: function(e){
                    window.location.href = "{{ url('templates') }}";
                }
            });

            _emailBuilder.setAfterLoad(function(e) {
                _emailBuilder.makeSortable();

                setTimeout(function(){
                    _emailBuilder.makeSortable();
                    _emailBuilder.makeRowElements();
                },1000);

            });

            $(document).ready(function(){
                var check_content = <?PHP echo json_encode($template->editable_content); ?>;
                if(check_content)
                {
                    setTimeout(function(){
                        $('.bal-content-wrapper').html('{!! str_replace(array("\n", "\r"), '', $template->editable_content) !!}');
                    }, 1000);
                }

                $(document).on('click', '.apply_template_btn', function(e){
                    e.preventDefault();
                    let template_id = $(this).data('id');
                    let applybtn = $(this);
                    applybtn.button('loading');
                    $.ajax({
                        url: 'get_template_content',
                        headers: {'X-CSRF-Token': '{{ csrf_token() }}'},
                        type: 'POST',
                        data: {templateId: template_id},
                        success: function(data){
                            applybtn.button('reset');
                            let template = JSON.parse(data.template);
                            if(template["editable_content"])
                            {
                                $.notify('Template Applied Successfully', 'success');
                                $('.bal-content-wrapper').html(template["editable_content"]);
                            }
                            else
                            {
                                $.notify('Template has no content', 'error');
                            }
                            $('#popup_load_template').modal('hide');
                        },
                        error: function(data){
                            $.notify('An error occurred', 'error');
                            applybtn.button('reset');
                        }
                    });
                });
            });
        </script>
    </body>
</html>
