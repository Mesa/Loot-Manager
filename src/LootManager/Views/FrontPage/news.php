<?php if (! empty($list)) : ?>
    <?php foreach ($list as $news): ?>
        <?php if ($news["Id"] > 0): ?>
        <div class="news block border_light" data-url="<?php echo $web_root ?>news/edit/<?php echo $news["Id"] ?>/">
        <?php else: ?>
        <div class="news block border_light" data-url="<?php echo $web_root ?>news/create/2/">
        <?php endif; ?>
            <h3
                class="headline"
                data-name="headline"><?php echo $news["headline"] ?></h3>

            <?php if ( $rights->hasRight("EDIT_NEWS")) : ?>
            <div class="display_options"><?php echo $lang->translate("DISPLAY_CONTENT_FROM_UNTIL")?>:
                <div class="display_to" data-name="to"><?php echo ($news["show_until"]) ? date("d.m.Y", $news["show_until"]) : " " ?></div>
                <div class="display_from" data-name="from"><?php echo ($news["show_from"]) ? date("d.m.Y", $news["show_from"]) : " "; ?></div>
            </div>
            <?php endif; ?>

            <div class="date"><?php echo date("d.m.y", $news["date"]) ?></div>
            <div class="author"
                 data-name="author"><?php echo $user_model->getUserNameById($news["author"]) ?></div>
            <div class="content" data-htmleditor data-name="content"><?php echo $news["content"] ?></div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
<?php endif; ?>
<?php if (! empty($hidden_list)) : ?>
    <h3>Folgende Neuigkeiten werden nicht mehr oder noch nicht angezeigt</h3>
    <?php foreach ($hidden_list as $news): ?>
        <?php if ($news["Id"] > 0): ?>
        <div class="news block border_light" data-url="<?php echo $web_root ?>news/edit/<?php echo $news["Id"] ?>/">
        <?php else: ?>
        <div class="news block border_light" data-url="<?php echo $web_root ?>news/create/2/">
        <?php endif; ?>
            <h3
                class="headline"
                data-name="headline"><?php echo $news["headline"] ?></h3>

            <?php if ( $rights->hasRight("EDIT_NEWS")) : ?>
            <div class="display_options"><?php echo $lang->translate("DISPLAY_CONTENT_FROM_UNTIL")?>:
                <div class="display_to" data-name="to"><?php echo ($news["show_until"]) ? date("d.m.Y", $news["show_until"]) : " " ?></div>
                <div class="display_from" data-name="from"><?php echo ($news["show_from"]) ? date("d.m.Y", $news["show_from"]) : " "; ?></div>
            </div>
            <?php endif; ?>

            <div class="date"><?php echo date("d.m.y", $news["date"]) ?></div>
            <div class="author"
                 data-name="author"><?php echo $user_model->getUserNameById($news["author"]) ?></div>
            <div class="content" data-htmleditor data-name="content"><?php echo $news["content"] ?></div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
<?php endif; ?>

<?php if ( $rights->hasRight("edit_news")): ?>
<script>
    $(document).ready( function (){
        $(".news").editMe({
            edit_btn_txt : "<?php echo $lang->translate("EDIT");?>",
            cancel_btn_txt : "<?php echo $lang->translate("CANCEL");?>",
            save_btn_txt   : "<?php echo $lang->translate("SAVE"); ?>",
            img_root_path : "<?php echo $web_root?>",
            tiny_mce_path : '<?php echo $web_root;?>tiny_mce/tiny_mce.js'
        });

        $("#create_news").click( function () {
            var copy_element = $(".news");
            if ( copy_element.length > 0) {
                var newItem = $(".news").eq(0).clone();
                var createUrl = "<?php echo $web_root ?>news/create/2/";
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
</script>
<?php endif; ?>