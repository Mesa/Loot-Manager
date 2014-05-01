<div class="block" id="user_list">
    <div class="info">
        <div class="user_name"><?php echo $lang->translate("USERNAME");?></div>
        <div class="email">Email</div>
        <div class="last_login">Zuletzt Online</div>&nbsp;
    </div>
    <?php echo $user_block ?>
</div>
<div class="block" id="group_list">
    <div class="info">
        <div class="name"><?php echo $lang->translate("GROUP") ?>:</div>
        &nbsp;
    </div>
    <?php echo $group_block; ?>
</div>
<div class="clear"></div>
<div id="commit_delete_user" class="hidden">
    <input type="hidden" val="" id="commit_delete_user_id">
    <div class="information">
        <img src="<?php echo $web_root?>information.png" />
    <?php echo $lang->translate("DELETE_USER_QUESTION"); ?>
    </div>
</div>

<div id="commit_delete_group" class="hidden">
    <?php echo $lang->translate("DELETE_GROUP_QUESTION"); ?>
    <input type="hidden" val="" id="commit_delete_group_id">
</div>

<?php if($rights->hasRight("admin_edit_user")): ?>
<div id="admin_edit_user" class="hidden">
    <input type="hidden" val="" id="edit_user_id">
    <div class="block center">
        <h3 class="headline"><?php echo $lang->translate("USERNAME"); ?>:</h3>
        <input type="text" value="" id="edit_user_username">
    </div>
    <div class="block center">
        <h3 class="headline"><?php echo $lang->translate("PASSWORD"); ?>:</h3>
        <input type="text" value="" id="edit_user_password">
        <div class="error"></div>
    </div>
    <div class="block center">
        <h3 class="headline"><?php echo $lang->translate("SHOWN_NAME");?>:</h3>
        <input type="text" value="" id="edit_user_display_name">
    </div>

    <div class="block center">
        <h3 class="headline">E-Mail</h3>
        <input type="text" value="" id="edit_user_email">
    </div>
    <div class="block center">
        <button id="submit_btn_user_edit"><?php echo $lang->translate("EDIT_USER"); ?></button>
    </div>
</div>
<?php endif; ?>
<?php if($rights->hasRight("admin_create_group")): ?>
<div id="create_new_group_dialog" class="hidden">
    <div class="block border_light">
        <h3 class="headline"><?php echo $lang->translate("NAME"); ?></h3>
        <input type="text" value="" id="create_group_dialog_name">
    </div>
    <div class="block border_light">
        <button id="submit_btn_group_create"><?php echo $lang->translate("SAVE"); ?></button>
    </div>
</div>
<?php endif; ?>

<?php if($rights->hasRight("admin_create_user")): ?>
<div id="create_new_user_dialog">
    <div class="block border_light">
        <h3 class="headline"><?php echo $lang->translate("USERNAME"); ?></h3>
        <input type="text" value="" id="create_user_dialog_username">
    </div>
    <div class="block border_light">
        <h3 class="headline"><?php echo $lang->translate("PASSWORD"); ?></h3>
        <input type="text" value="" id="create_user_dialog_password">
        <div class="error"></div>
    </div>
    <div class="block border_light">
        <h3 class="headline">Angezeigter Name</h3>
        <input type="text" value="" id="create_user_dialog_display_name">
    </div>

    <div class="block border_light">
        <h3 class="headline">E-Mail</h3>
        <input type="text" value="" id="create_user_dialog_email">
    </div>
    <div class="block border_light">
        <button id="submit_btn_user_create"><?php echo $lang->translate("SAVE"); ?></button>
    </div>
</div>
<?php endif; ?>

<?php if($rights->hasRight("admin_add_remove_user_to_group")): ?>
<div id="group_member_manager" class="hidden">
<input type="hidden" id="group_management_group_id" value="">
    <div class="information clear">
        <img src="<?php echo $web_root?>information.png" >
        <?php echo $lang->translate("GROUP_MANAGEMENT_DESCRIPTION"); ?>
    </div>
    <div id="active_member" class="border_light">
         <h3 class="headline"><?php echo $lang->translate("GROUP_MEMBER"); ?></h3>
    </div>
    <div id="all_member" class="border_light">
        <h3 class="headline"><?php echo $lang->translate("ALL_USER"); ?></h3>
    </div>
</div>

<div id="user_group_manager">
    <input type="hidden" id="group_management_user_id" value="">
    <div class="information clear">
        <img src="<?php echo $web_root?>information.png" >
        <?php echo $lang->translate("USER_MANAGEMENT_DESCRIPTION"); ?>
    </div>
    <div id="deactive_groups" class="border_light">
        <h3 class="headline"><?php echo $lang->translate("ALL_GROUPS"); ?></h3>
    </div>

    <div id="active_groups" class="border_light">
        <h3 class="headline"><?php echo $lang->translate("GROUPS_OF_THE_USER") ?></h3>
    </div>
</div>
<?php endif;?>
