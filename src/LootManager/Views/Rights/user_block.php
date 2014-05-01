<div class="user" id="user_id_<?php echo $user_data["Id"] ?>">
    <input type="hidden" value="<?php echo htmlentities(base64_decode($user_data["UserName"]), ENT_QUOTES, "UTF-8") ?>" class="secret_name">
    <div class="user_name">
        <?php echo ($is_guest)?$lang->translate("GUEST_ACCOUNT"):$user_data["Name"]; ?>
    </div>
    <div class="email"><?php echo $user_data["Email"] ?></div>
    <div class="options">
        <?php if ( $rights->hasRight("admin_edit_user") and $is_guest === false ): ?>
            <button class="admin_edit_user" value="<?php echo $user_data["Id"]; ?>"><?php echo $lang->translate("EDIT_USER"); ?></button>
        <?php endif; ?>
        <?php if ( $rights->hasRight("rights_edit_user_rights") ): ?>
            <button class="user_rights_btn"><?php echo $lang->translate("RIGHTS"); ?></button>
        <?php endif; ?>
        <?php if ( $rights->hasRight("admin_add_remove_user_to_group") and $is_guest === false ): ?>
            <button class="user_groups_btn"><?php echo $lang->translate("GROUPS"); ?></button>
        <?php endif ?>
        <?php if ( $rights->hasRight("admin_delete_user") and $is_guest === false ): ?>
            <button class="user_delete_btn"><?php echo $lang->translate("DELETE"); ?></button>
        <?php endif ?>
    </div>
    <div class="last_login"><?php echo ($user_data["lastLogin"] > 1) ? date("d.m.y H:i", $user_data["lastLogin"]) : "" ?></div>
</div>