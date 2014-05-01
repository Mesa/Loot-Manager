<?php if ( $rights->hasRight("edit_news")) : ?>
<div id="submenu">
    <div class="description">Neuigkeiten:</div>
    <button id="create_news">
        <?php echo $lang->translate("create") ?><br>
        <img src="<?php echo $web_root ?>big_add.png">
    </button>
    <button id="delete_news">
        <?php echo $lang->translate("delete"); ?><br>
        <img src="<?php echo $web_root ?>big_delete.png">
    </button>
    <div class="clear"></div>
</div>
<?php endif; ?>