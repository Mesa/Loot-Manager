<?php if ($rights->hasRight("PROGRESS_EDIT")): ?>
<div id="submenu">
    <button id="change_state"><?php echo $lang->translate("CHANGE_STATE")?></button>
    <div class="clear"></div>
</div>
<?php endif; ?>

<?php foreach ($data as $dungeon):?>
    <div class="dungeon block">
        <div class="progress_bar" data-max="<?php echo $dungeon["boss_count"]?>"></div>
        <h3><?php echo $lang->translate($dungeon["name"]); ?></h3>
        <div class="info"><?php echo $dungeon["clear"] ."/".$dungeon["boss_count"]?></div>
        <?php foreach($dungeon["boss"] as $boss):?>
            <div data-name="<?php echo $boss["name"]?>" class="encounter <?php echo ($boss["status"]===true)?"boss_down":""?>"><?php echo $lang->translate($boss["name"]); ?>
            <?php if ($boss["status"]=="clear"): ?>
                <div class="clear-date"><?php echo (isset($boss["date"]))?$boss["date"]:"" ?></div>
            <?php endif; ?>
            </div>
        <?php endforeach;?>
    </div>
<?php endforeach; ?>
<div class="clear"></div>

<script type="text/javascript">
    $(document).ready( function (){

        $(".progress_bar").each( function (){
            showProgressBar( $(this) );
        });
<?php if ($rights->hasRight("PROGRESS_EDIT")): ?>

        $("#change_state").click( function (){

            if ($(this).hasClass("btn_down")) {
                $(this).removeClass("btn_down");
                $(".encounter").unbind();
                $(".encounter").css({
                    cursor : "auto"
                })
            } else {
                $(this).addClass("btn_down");
                $(".encounter").hover( function (){
                    $(this).addClass("highlight");
                    $(this).css({
                        cursor : "pointer"
                    })
                }, function () {
                    $(this).removeClass("highlight");
                });

                $(".encounter").click( function (){
                    var target = $(this);
                    var name = target.data("name");
                    
                    if ( $(this).hasClass("boss_down") ) {
                        $.get("switch_status/" + name + "/", {}, function ( data ) {
                            if(data.executed == true) {
                                target.removeClass("boss_down");
                                showProgressBar(target.parent().find(".progress_bar"));
                            }
                        }, "json");
                    } else {
                        $.get("switch_status/" + name + "/", {}, function ( data ) {
                            if (data.executed == true) {
                                target.addClass("boss_down");
                                showProgressBar(target.parent().find(".progress_bar"));
                            }
                        }, "json");                         
                    }
                })
            }
        });
<?php endif; ?>
    });
    
function showProgressBar( item ) {
//    var clear = item.data("clear");
    var clear = item.parent().find(".boss_down").length;
    var max   = item.data("max");
    var itemWidth = item.parent().width();
    var info  = item.parent().find(".info");
    
    info.text(clear + "/" + max);
    if ( clear > 0 ) {
        item.animate({
            width : (itemWidth / max) * clear
        })
    } else {
        item.animate({
            width : 10
        })
    }
}    
</script>