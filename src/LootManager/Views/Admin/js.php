<script type="text/javascript">

<?php if ( $rights->hasRight("edit_news")) : ?>
    $(document).ready( function (){
        $(".news").editMe({
            edit_btn_txt  : "<?php echo $lang->translate("EDIT");?>",
            cancel_btn_txt: "<?php echo $lang->translate("CANCEL");?>",
            save_btn_txt  : "<?php echo $lang->translate("SAVE"); ?>",
            img_root_path : "<?php echo $web_root?>",
            tiny_mce_path : "<?php echo $web_root?>tiny_mce/tiny_mce.js"
        });

        $("#create_news").click( function () {
            var copy_element = $(".news");
            if ( copy_element.length > 0) {
                var newItem = $(".news").eq(0).clone();
                var createUrl = "<?php echo $web_root ?>news/create/1/";
                newItem.data("url", createUrl);
                $(".news").eq(0).before(newItem);
                $(".news").editMe("reload");

                newItem.find(".content").html(" ");
                newItem.find(".headline").html(" ");
                newItem.find(".display_from").html("");
                newItem.find(".display_to").html("");
            } else {
                window.location.reload();
            }
        });
        var modalDiv;
        modalDiv = $("<div id=\"modal_delete_news\"></div>");
        $("body").append(modalDiv);
        modalDiv.dialog({
                resizable: false,
                autoOpen : false,
                height   : 140,
                modal    : true,
                title    : "<?php echo $lang->translate("DELETE_NEWS"); ?>",
                buttons: {
                        "<?php echo $lang->translate("DELETE") ?>": function() {
                                var modalDiv = $("#modal_delete_news");
                                if (typeof modalDiv.data("target") == "object") {
                                    var target = modalDiv.data("target");
                                    var newsId = target.data("url").replace(/.+\/(\d+)\/$/,"$1");
                                    $.post("<?php echo $web_root?>news/delete/", {id: newsId}, function ( response ){
                                        if( response.executed ) {
                                            target.slideUp("slow", function () {
                                                target.remove();
                                            });
                                        }
                                    }, "json")
                                }
                                $( this ).dialog( "close" );
                        },
                        <?php echo $lang->translate("cancel") ?>: function() {
                                var modalDiv = $("#modal_delete_news");
                                modalDiv.data("target", "");
                                $(this).dialog( "close" );
                        }
                }
        });
        $("#delete_news").click( function (){

            if ( $(this).hasClass("btn_down")) {
                $(this).removeClass("btn_down");
                $(".news").unbind();
            } else {
                $(this).addClass("btn_down");
                $(".news").click( function () {
                    var modalDiv = $("#modal_delete_news");
                    modalDiv.html("<b>"+$(this).find(".headline").html() + "</b><div class=\"information\"><img src=\"<?php echo $web_root;?>\information.png\"><?php echo $lang->translate("delete_news_question");?></div>");
                    modalDiv.data("target", $(this));
                    modalDiv.dialog("open");
                });

                $(".news").hover( function () {
                    $(this).addClass("highlight");
                }, function () {
                    $(this).removeClass("highlight");
                });
            }
        });
    });
<?php endif; ?>

    function positionDialog ( button, dialog ) {
        var padding = 10;
        var button_offset = button.offset();
        dialog.dialog("open");

        var dialog_width = dialog.width();
        var dialog_height = dialog.height();

        dialog.dialog("option",{
            position: [button_offset.left - dialog_width - padding, button_offset.top - ( dialog_height / 2)]
        });
    }
</script>