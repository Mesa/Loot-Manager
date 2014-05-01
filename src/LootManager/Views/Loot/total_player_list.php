<div id="total_player_list" class="block border_light">
    <div class="filter"><input id="name_filter"></div>
    <div class="headline">
        <div id="event_name"><?php echo $event_name ?></div>
        <input type="text" class="hidden" id="event_name_edit" value="<?php echo $event_name ?>">
        <div class="options">
            <button id="event_log_btn">Event Log</button>
            <?php if ( $rights->hasRight("loot_edit_event") ): ?>
                <button value="<?php echo $event_id ?>" class="edit_event_btn">
                    <?php echo $lang->translate("EDIT_EVENT"); ?>
                </button>
            <?php endif; ?>
            <?php if ( $rights->hasRight("loot_delete_event") ): ?>
                <button value="<?php echo $event_id ?>" class="delete_event_btn">
                    <?php echo $lang->translate("DELETE_EVENT"); ?>
                </button>
            <?php endif; ?>
        </div>
    </div>
    <div id="list_desc" >
        <div class="position">
            <?php echo $lang->translate("POSITION"); ?>
        </div>
        <div class="name"><?php echo $lang->translate("NAME"); ?></div>
        <div class="class"><?php echo $lang->translate("CLASS"); ?></div>
        <div class="type"><?php echo $lang->translate("DESCRIPTION"); ?></div>
        <div class="last_loot"><?php echo $lang->translate("LAST_LOOT"); ?></div>
        <div class="clear"></div>
    </div>
    <div id="event_player_list">
        <?php
        if ( isset($list) and count($list) > 0 ):
            foreach ( $list as $player ):
                ?>
                <div class="total_list <?php echo $player["Class"] ?>" id="<?php echo $player["CharId"] ?>">
                    <input type="hidden" class="row_id" value="<?php echo $player["Id"] ?>">
                    <div class="position"><?php echo $player["Position"] ?></div>
                    <div class="name"><?php echo $player["Name"] ?></div>
                    <div class="class"><?php echo $player["Class"] ?></div>
                    <div class="type"><?php echo $player["Description"] ?></div>
                    <div class="last_loot">
                        <button class="player_loot_log">&nbsp;<?php echo $lang->translate("LOOT_LIST"); ?></button>
                    </div>
                    <div class="clear"></div>
                </div>
            <?php endforeach; elseif ( $rights->hasRight("loot_add_char_to_event") ) : ?>
            <div class="total_list" id="empty_slot">
                <div class="name"><?php echo $lang->translate("CREATE_NEW_CHAR"); ?></div>
                <div class="class"></div>
                <div class="type"></div>
                <div class="last_loot"></div>
            </div>
        <?php endif; ?>
    </div>
</div>
<input type="hidden" id="event_id" value="<?php echo $event_id ?>">