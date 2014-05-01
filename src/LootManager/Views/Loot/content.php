<div id="left_block">
    <div class="block border_light sliding-block">
        <h3 class="headline">Filter:</h3>
        <div class="block_data hidden">
            <?php
            if (isset($filter_list) and count($filter_list) > 0):
                foreach ($filter_list as $filter):
                    ?>
                    <div class="class_filter">
                        <?php echo $filter ?>
                        <div class="button">
                            <input type="checkbox" class="select_class" id="slct_<?php echo $filter ?>">
                            <label for="slct_<?php echo $filter ?>">&nbsp;</label>
                        </div>
                    </div>

                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="block border_light sliding-block" id="event_list">
        <h3 class="headline"><?php echo $lang->translate("EVENT") ?></h3>
        <div class="block_data hidden">
            <?php if (isset($event_list) and count($event_list) > 0): ?>
                <?php foreach ($event_list as $event): ?>
                    <a href="<?php echo $web_root . "loot/" . $event["Id"] ?>" class="event_link"><?php echo $event["Name"] ?></a>
                <?php endforeach; ?>
            <?php endif; ?>
            <?php if ($rights->hasRight("loot_create_event")): ?>
                <div id="create_new_event">
                    <input type="text" id="create_event_input" class="replace_value" title="" value="<?php echo $lang->translate("CREATE_NEW_EVENT") ?>">
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php if (( $rights->hasRight("loot_add_char_to_event") or $rights->hasRight("loot_delete_char")) and count($missing_player) > 0): ?>
        <div class="block border_light sliding-block">
            <h3 class="headline"><?php echo $lang->translate("MISSING_PLAYER"); ?>:</h3>
            <div class="block_data hidden">
                <?php foreach ($missing_player as $player): ?>
                    <div class="missing_player">
                        <?php echo $player["Name"] ?>
                        <?php if ($rights->hasRight("loot_add_char_to_event")): ?>
                            <div class="options_left">
                                <button class="add_missing_player" value="<?php echo $player["Id"]; ?>"><?php echo $lang->translate("ADD_CHAR"); ?></button>
                            </div>
                        <?php endif; ?>
                        <?php if ($rights->hasRight("loot_delete_char")): ?>
                            <div class="options_right">
                                <button class="delete_missing_player" value="<?php echo $player["Id"]; ?>"><?php echo $lang->translate("DELETE_CHAR"); ?></button>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($rights->hasRight("loot_create_char")): ?>
        <div class="block border_light sliding-block">
            <h3 class="headline"><?php echo $lang->translate("CREATE_NEW_CHAR"); ?>:</h3>
            <div class="block_data hidden">
                <button id="create_player_btn"><?php echo $lang->translate("CREATE"); ?></button>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php echo $player_list ?>
<div id="delete_event_dialog" class="hidden information">
    <div class="warning">
        <?php echo $lang->translate("DELETE_EVENT_QUESTION") ?>
    </div>
</div>
<div id="player_loot_dialog" class="hidden">
    <div class="block border_light">
        <div class="log_filter">
            <?php echo $lang->translate("ITEM_RECIEVED") ?>
            <div class="button">
                <input class="char_log_filter_btn" type="checkbox" id="char_log_filter_suicide" checked>
                <label for="char_log_filter_suicide">&nbsp;</label>
            </div>
        </div>
        <div class="log_filter">
            <?php echo $lang->translate("CHAR_MOVED") ?>
            <div class="button">
                <input class="char_log_filter_btn" type="checkbox" id="char_log_filter_moved">
                <label for="char_log_filter_moved">&nbsp;</label>
            </div>
        </div>
        <div class="log_filter">
            <?php echo $lang->translate("CHAR_ADDED") ?>
            <div class="button">
                <input class="char_log_filter_btn" type="checkbox" id="char_log_filter_added">
                <label for="char_log_filter_added">&nbsp;</label>
            </div>
        </div>
        <div class="log_filter">
            <?php echo $lang->translate("CHAR_REMOVED") ?>
            <div class="button">
                <input class="char_log_filter_btn" type="checkbox" id="char_log_filter_removed">
                <label for="char_log_filter_removed">&nbsp;</label>
            </div>
        </div>
        <div class="log_filter_bottom">
            <?php echo $lang->translate("COUNT") ?>
            <input type="text" id="char_log_filter_count" value="25">
        </div>
        <div class="log_filter_bottom">
            <button id="charLog-refresh-btn"><?php echo $lang->translate("RELOAD") ?></button>
            <input class="char_log_filter_btn" type="checkbox" id="char_log_filter_event" checked>
            <label for="char_log_filter_event"><?php echo $lang->translate("All")?></label>
        </div>
        <div class="clear"></div>
    </div>
    <div class="content"></div>
</div>
<div id="event_loot_dialog" class="hidden">
    <div class="block border_light">
        <div class="log_filter">
            <?php echo $lang->translate("ITEM_RECIEVED") ?>
            <div class="button">
                <input class="event_log_filter_btn" type="checkbox" id="event_log_filter_suicide" checked>
                <label for="event_log_filter_suicide">&nbsp;</label>
            </div>
        </div>
        <div class="log_filter">
            <?php echo $lang->translate("CHAR_MOVED") ?>
            <div class="button">
                <input class="event_log_filter_btn" type="checkbox" id="event_log_filter_moved">
                <label for="event_log_filter_moved">&nbsp;</label>
            </div>
        </div>
        <div class="log_filter">
            <?php echo $lang->translate("CHAR_ADDED") ?>
            <div class="button">
                <input class="event_log_filter_btn" type="checkbox" id="event_log_filter_added">
                <label for="event_log_filter_added">&nbsp;</label>
            </div>
        </div>
        <div class="log_filter">
            <?php echo $lang->translate("CHAR_REMOVED") ?>
            <div class="button">
                <input class="event_log_filter_btn" type="checkbox" id="event_log_filter_removed">
                <label for="event_log_filter_removed">&nbsp;</label>
            </div>
        </div>
        <div class="log_filter_bottom">
            <?php echo $lang->translate("COUNT") ?>
            <input type="text" id="event_log_filter_count" value="25">
        </div>
        <div class="log_filter_bottom">
            <button id="log-refresh-btn"><?php echo $lang->translate("RELOAD") ?></button>
        </div>
        <div class="clear"></div>
    </div>
    <div class="content"></div>
</div>
<div class="clear">&nbsp;</div>
