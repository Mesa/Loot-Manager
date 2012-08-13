<script type="text/javascript">
    $(document).ready(function(){

        $(".topnav li").hover( function ( ){
            $(this).addClass("menu-hover");
        }, function (){
            $(this).removeClass("menu-hover");
        });

        $(".subnav .subnav").css({
            left: $(".subnav").outerWidth() - 1,
            top: "0px"
        });

        $(".subnav").parent().append("<span></span>");

        $(".subnav span, .topnav span").click(function() {
            var subMenu = $(this).parent().children("ul.subnav");
            var status = subMenu.css("display");

            if (status == "none") {
                $(this).addClass("subnav-btn-hover");
                subMenu.slideDown("fast").show();
            } else {
                $(this).removeClass("subnav-btn-hover");
                subMenu.slideUp().show();
            }

        });

//        $(".more-block").each( function (){
//            $(this).parent().append("<span class=\"more-btn\"></span>");
//            $(this).hide();
//        });

//        $(".more-btn").click(function (){
//            if ( $(this).parent().find(".more-block").is(":visible") == false ) {
//                $(this).addClass("subnav-btn-hover");
//                $(this).parent().find(".more-block").slideDown("fast");
//            } else {
//                $(this).removeClass("subnav-btn-hover");
//                $(this).parent().find(".more-block").slideUp("fast");
//            }
//        })
    });
</script>