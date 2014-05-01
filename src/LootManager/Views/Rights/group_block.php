<div class="group" id="group_id_<?php echo $group_id ?>">
    <div class="name"><?php echo $group_name ?></div>
    <div class="options">
        <?php if ( $rights->hasRight("rights_edit_group_rights") ): ?>
            <button class="group_rights_btn"><?php echo $lang->translate("RIGHTS"); ?></button>
        <?php endif; ?>
        <?php if ( $rights->hasRight("admin_edit_group") ): ?>
            <button class="group_member_btn"><?php echo $lang->translate("MEMBERS"); ?></button>
        <?php endif; ?>
        <?php if ( $rights->hasRight("admin_delete_group") ): ?>
            <button class="group_delete_btn"><?php echo $lang->translate("DELETE"); ?></button>
        <?php endif; ?>
    </div>
</div>