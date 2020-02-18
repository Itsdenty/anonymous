@extends('layouts.user')
@section('title', 'Sendmunk | Site Code')

@section('content')
    <div class="ui vertical segment">
        {{-- <a href="{{ url('createsite') }}" class="right floated ui primary button"><i class="plus icon"></i>Add New Site</a> --}}
        <h2>Site Code</h2>    
    </div>
    <br/>
    <div class="ui vertically stackable grid">
        <div class="three column row">
            <div class="column">
                @if($form->form_type == 'popover' || $form->form_type == 'scrollbox' || $form->form_type == 'welcome_mat' || $form->form_type == 'poll' || $form->form_type == 'calculator' || $form->form_type == 'quiz' || $form->form_type == 'action' || $form->form_type == 'facebook' || $form->form_type == 'twitter' || $form->form_type == 'pinterest')
                <div class="ui form">
                    <h4>Hosted Form Link</h4>
                    <div class="field">
                        <input id="link_url" name="url" class="ui fluid" id="url" type="text" value="{{ url('hostedform'). '/' . $form->id }}" disabled />			
                    </div>
                    <div class="ui fluid buttons">                    
                        <button  id="copy_url" class="ui icon button">
                            <i class="copy icon"></i>
                        </button>                        
                        <a id="fb-share" target="blank" href="https://www.facebook.com/dialog/share?app_id=780423745670639&display=popup&href={{ url('hostedform'). '/' . $form->id }}" class="ui facebook icon button">
                            <i class="facebook icon"></i>
                        </a>                        
                        <a id="twt-share" target="blank" href="https://twitter.com/intent/tweet?text={{ strip_tags($form->headline) }}  {{ url('hostedform'). '/' . $form->id }}" class="ui twitter icon button">
                            <i class="twitter icon"></i>
                        </a>                       
                        <a id="lkd-share" target="blank" href="https://www.linkedin.com/shareArticle?mini=true&url={{ url('hostedform'). '/' . $form->id }}" class="ui linkedin icon button">
                            <i class="linkedin icon"></i>
                        </a>
                        <a id="pin-share" target="blank" href="http://pinterest.com/pin/create/button/?url={{ url('hostedform'). '/' . $form->id }}&media=https://Sendmunkapp.com/logo/Sendmunk_icon.png" class="ui pinterest icon button">
                            <i class="pinterest icon"></i>
                        </a>                  
                    </div>
                </div>
                @endif
            </div>
            <div class="column">
                @if($form->form_type != 'embedded')
                <h4>Paste the following code right before the &lt;/head&gt; tag on every page of your site:</h4>
                @else
                <h4>Paste the following code right before the &lt;/head&gt; tag on every page of your site:</h4>
                <h4>Also place <strong style="color: #F47921"><em>{{ '<div class="optin_block_page"></div>' }}</em></strong> where you need the embedded form</h4>
                @endif

                <div class="ui form">
                    <div class="field">

                        {{-- @if (env('APP_ENV') ==='production') --}}
                        <textarea id="code" readonly><!-- Jquery Plugin -->&#13;&#10;<script id="jquery_script" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>&#13;&#10;&#13;&#10;<!-- Opt-In Code -->&#13;&#10;<script id="optin_script" src="{{url('/')}}/js/optinplugin.js" data-form-id="{{$form->id}}"  data-redirect_url="{{$form->redirect_url}}" async></script>
                        </textarea>
                        {{-- @else
                        <textarea id="code" readonly><!-- Jquery Plugin -->&#13;&#10;<script id="jquery_script"  src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js" async></script>&#13;&#10;&#13;&#10;<!-- semantic js --><script src="{{url('/')}}/Semantic-UI-CSS-master/semantic.min.js" type='text/javascript' async></script>&#13;&#10;&#13;&#10;<!-- Opt-In Code -->&#13;&#10;<script id="optin_script" src="{{url('/')}}/js/optinplugin_local.js" data-form-id="{{$form->id}}" data-redirect_url="{{$form->redirect_url}}" async></script>
                        </textarea>
                        @endif --}}
                    </div>
                    
                    <button id="copy_code" type="button" class="ui fluid button publish">Copy to Clipboard</button>
                </div>
                <br>

            </div>
            <div class="column"></div>    
        </div>
    </div>
@endsection

@section('footerscripts')
    <script type="text/javascript" src="{{ url("js/notify.min.js") }}"></script>
    <script>
        $('.message .close').on('click', function() {
            $(this).closest('.message').transition('fade');
        });

        $('#copy_code').click(function(){
            var code = $('#code').text();
            var $temp = $('<input>');
            $("body").append($temp);
            $temp.val(code).select();
            document.execCommand("copy");
            $temp.remove();
            $.notify('Opt-In Code copied to clipboard', 'success');
        });

        $('#copy_url').click(function(){
            var link_url = $('#link_url').val();
            console.log(link_url);
            var $temp = $('<input>');
            $("body").append($temp);
            $temp.val(link_url).select();
            document.execCommand("copy");
            $temp.remove();
            $.notify('Hosted Form Link copied to clipboard', 'success');
        });
    </script>
@endsection