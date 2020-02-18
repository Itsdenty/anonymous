<script src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
<script src="{{ asset('summernote/summernote-lite.js') }}"></script>
<script src='{{ asset("Semantic-UI-CSS-master/semantic.min.js") }}'></script>
<script>
    $('.ui.dropdown').dropdown();

    function checkWidth(init)
    {
        if($(window).width() < 767)
        {
            $('#sidebar').removeClass('visible');
            $('.pusher').removeClass('dimmed');
        }
        else
        {
            if(!init){
                $('#sidebar').addClass('visible');
                $('.pusher').removeClass('dimmed');
            }
        }
    }

    $(document).ready(function(){
        $('#toggle_menu').on('click', function(){
            $('.ui.sidebar').sidebar('toggle');
        });

        checkWidth(true);

        $(window).resize(function(){
            checkWidth(false);
        });

        $('#subaccount-select').dropdown();

        $('#subaccount-select').on('change', function() {
            var current_account_id = $(this).val();
            $.ajax({
                url: "{{ url('setaccount') }}",
                method: "POST",
                data: {"current_account_id":current_account_id, _token: '{{ csrf_token() }}'},
                success: function (data){
                    location.reload(true);
                }
            });
        });


        $('.ui.accordion').accordion({
            selector: {

            }
        });

        $('.summernote').summernote({
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['fontname', ['fontname']]
            ],
            tabsize: 1,
            disableDragAndDrop: true,
        });

        summernoteLoad();

        $("a.setting-item").each(function(){
            if($(this).hasClass("active"))
            {
                $(this).parent('.content').addClass('active');
            }
        });
    });

    function summernoteLoad() {
        var $summernoteTextAreas = $('[data-control="summernote"]')
        var $summernotes = $('[data-control="summernote-container"]');
        $summernotes.find('.note-toolbar').hide();
        $summernotes.each(function(idx, element) {
            var $snote = $(element);
            $snote.on('focus', '.note-editable', function(e) {
                if($snote.find('.note-toolbar').hasClass('active')) {}
                else
                {
                    $summernotes.find('.note-toolbar')
                        .removeClass('active')
                        .slideUp();
                    $snote.find('.note-toolbar')
                        .addClass('active')
                        .slideDown();
                }
            });
            $snote.on('blur', '.note-editable', function(e) {
                if($snote.find('.note-toolbar').hasClass('active')) {}
                else
                {
                    $snote.find('.note-toolbar')
                        .removeClass('active')
                        .slideUp();
                }
            });
        });
    }
</script>
@yield('footerscripts')
