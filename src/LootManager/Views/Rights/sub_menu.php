<div class="block submenu_left border_light">
    <h3 class="headline"><?php echo $lang->translate("USERNAME")?>:</h3>
    <input id="user_filter" type="text">
</div>
<?php if (  $rights->hasRight("admin_create_user") ):?>
<div class="block submenu_left border_light">
    <h3 class="headline"><?php echo $lang->translate("CREATE_NEW_USER")?>:</h3>
    <button id="create_new_user"><?php echo $lang->translate("CREATE")?></button>
</div>
<?php endif; ?>
<div class="block submenu_right border_light">
    <h3 class="headline"><?php echo $lang->translate("GROUP")?>:</h3>
    <input id="group_filter" type="text">
</div>

<?php if (  $rights->hasRight("admin_create_group") ):?>
<div class="block submenu_right border_light">
    <h3 class="headline"><?php echo $lang->translate("CREATE_NEW_GROUP")?>:</h3>
    <button id="create_new_group"><?php echo $lang->translate("CREATE")?></button>
</div>
<?php endif; ?>
<div class="clear"></div>