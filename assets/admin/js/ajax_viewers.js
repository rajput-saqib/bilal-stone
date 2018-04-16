    var ajax_container = '.ajax_container';
    var ajax_opacityLayer = '.opacity_layer';
    var ajax_close = '.ajax_close';
    var content_container = '.content_container';

    function closeAjax() {
        $(ajax_opacityLayer).click();
    }


    function callAjax(id, URL)
    {
        var DATA = {'id' : id};
        $.ajax({
            url : URL,
            type : 'POST',
            data : DATA,
            cache : false,
            success : function (resp)
            {
                $(ajax_opacityLayer).fadeIn('fast', function ()
                {
                    $(ajax_container).html('<div class="content_container">' + resp + '</div>').show();
                    //$(ajax_container).prepend('<div class="ajax_close"><img src="../../assets/admin/img/close.png" /></div>');
                    $(window).resize();
                    setTimeout("$(window).resize();", 10);
                    //$(resp).find('.box').find('.box-content').find('.datepicker').datepicker({dateFormat: "dd-mm-yy"});
                });
            },
            error : function (e)
            {
                console.log(e.message);
                console.log(e);
            }
        });
    }

    jQuery(window).resize(function ()
    {
        var WW = $(window).width();
        var WH = $(window).height();


        $(ajax_container).css({
            position : 'fixed',
            width : (WW - 200) + 'px',
            height : (WH - 200) + 'px'
        });

        if ($(ajax_container).height() > $(content_container).children('div').height()) {
            $(ajax_container).height($(content_container).children('div').height() + 30)
        }

        $(ajax_container).css({
            position : 'fixed',
            left : ($(window).width() - $(ajax_container).outerWidth()) / 2,
            top : ($(window).height() - $(ajax_container).outerHeight()) / 2
        });


        $('.child_popup').css({
            position : 'fixed',
            left : ($(window).width() - $('.child_popup').outerWidth()) / 2,
            top : ($(window).height() - $('.child_popup').outerHeight()) / 2
        });

        $(content_container).css({height : $(ajax_container).height() + 'px'});


    });

    jQuery(window).load(function ()
    {
        $(ajax_close).live('click', function ()
        {
            $(ajax_opacityLayer).click();
        });

        $(ajax_opacityLayer).live('click', function ()
        {
            $(this).hide();
            $(ajax_container).hide().children('*').remove();
        });

        $('body').prepend('<div class="opacity_layer"></div>');
        $('body').append('<div class="ajax_container"></div>');
        jQuery(window).resize();
    });
