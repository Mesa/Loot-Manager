<div class="information">
    <img src="<?php echo $web_root?>information.png">
    Klicke auf die Überschrift um das Menü zu öffnen.
</div>
<?php if ( $rights->hasRight("loot_move_char") ): ?>
    <div class="block border_light">
        <h3 class="headline">
            <img src="<?php echo $web_root?>coins.png">
            Kill King:
        </h3>
        <div class="hidden menu_data">
            <?php echo $lang->translate("DESCRIPTION"); ?>:
            <textarea type="text" id="suicide_desc"></textarea>
            <div class="clear"></div>
        <button id="kill_suicide_king"><?php echo $lang->translate("ITEM_RECIEVED"); ?></button>
        </div>
    </div>
<?php endif; ?>
<?php if ( $rights->hasRight("loot_move_char") ): ?>
    <div class="block border_light">
        <h3 class="headline">
            <img src="<?php echo $web_root?>arrow_switch.png">
            <?php echo $lang->translate("CHANGE_CHAR_POSITION"); ?>:</h3>
        <div class="hidden menu_data">
            <div class="data_block">
                Neue Position (Nr.): 
                <input type="text" id="edit_player_position" name="player_position">
            </div>
                <?php echo $lang->translate("DESCRIPTION"); ?>:
                <textarea type="text" id="move_player_desc"></textarea>            
            <button id="change_position"><?php echo $lang->translate("POSITION"); ?></button>
        </div>
    </div>
<?php endif; ?>
<?php if ( $rights->hasRight("loot_edit_char") ): ?>
    <div class="block border_light">
        <h3 class="headline">
            <img src="<?php echo $web_root?>user_edit.png">
            <?php echo $lang->translate("EDIT_CHAR"); ?>:
        </h3>
        <div class="hidden menu_data">
            <input type="hidden" name="char_id">
            <div class="data_block">
                <?php echo $lang->translate("NAME"); ?>:
                <input type="text" name="name" id="edit_char_name">
            </div>
            <div class="data_block">
                <?php echo $lang->translate("CLASS"); ?>:
                <input type="text" name="class" id="edit_char_class">
            </div>
                <?php echo $lang->translate("DESCRIPTION"); ?>:<br>
            <textarea id="edit_char_description"></textarea><br>
            <button id="edit_player"><?php echo $lang->translate("EDIT_CHAR"); ?></button>
        </div>
    </div>
<?php endif; ?>
<?php if ( $rights->hasRight("loot_remove_char_from_event") ): ?>
    <div class="block">
        <h3 id="head_remove_char" class="headline">
            <?php echo $lang->translate("REMOVE"); ?>?
        </h3>
        <div class="hidden menu_data">
            <img src="<?php echo $web_root?>group_delete.png">
            <button id="remove_player"><?php echo $lang->translate("REMOVE"); ?></button>
        </div>
    </div>
<?php endif; ?>
<input type="hidden" id="dialog_row_id">
<input type="hidden" id="dialog_player_id">