<div id="right_block">
    <div id="dungeons">
        <?php foreach ( $activeDungeons as $dungeon ): ?>
            <div class="dungeon block border_light">
                <h3 id="dungeon_<?php echo $dungeon["Id"] ?>" class="headline"><?php echo $dungeon["Name"] ?></h3>
                <div class="dropper">
                    <?php if (isset($encounter[$dungeon["Id"]])): ?>
                    <?php foreach ( $encounter[$dungeon["Id"]] as $boss ): ?>
                        <div class="block encounter border_light <?php echo ($boss["Status"] == 1) ? "enc_down" : "" ?>">
                            <div class="name" id="encId_<?php echo $boss["Id"] ?>"><?php echo $boss["Name"] ?></div>
                            <div class="status">
                                <image src="<?php echo $web_root;
                echo ($boss["Status"] == 1 ) ? "clear.png" : "not_clear.png" ?>" />
                            </div>
                            <?php if ( $this->rights->hasRight("progress_edit") ): ?>
                                <div class="options">
                                    <button value="encId_<?php echo $boss["Id"] ?>"><?php echo $lang->translate("EDIT"); ?></button>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <?php if ( $this->rights->hasRight("progress_edit") ): ?>
                    <div class="options">
                        <button value="dungeon_<?php echo $dungeon["Id"] ?>"><?php echo $lang->translate("EDIT"); ?></button>
                    </div>
                <?php endif; ?>
                <div class="clear"></div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<div id="left_block">
    <?php if ( $this->rights->hasRight("progress_edit") ): ?>
        <div id="trash" class="dungeon block border_light">
            <div class="clear"><button id="clear_trash"><?php echo $lang->translate("CLEAR") ?></button></div>
            <h3 id="dungeon_0" class="headline"><?php echo $lang->translate("TRASH") ?></h3>
            <div class="information">
                <img src="<?php echo $web_root?>information.png">
                Instanzen und Boss können hier abgelegt werden.
            </div>
            <div class="dropper">
                <?php if(isset($encounter) and isset($encounter[0])):?>
                <?php foreach ( $encounter[0] as $boss ): ?>
                    <div class="block encounter border_light <?php echo ($boss["Status"] == 1) ? "enc_down" : "" ?>">
                        <div class="name" id="encId_<?php echo $boss["Id"] ?>"><?php echo $boss["Name"] ?></div>
                        <div class="status">
                            <image src="<?php echo $web_root;
            echo ($boss["Status"] == 1 ) ? "clear.png" : "not_clear.png" ?>" />
                        </div>
                        <div class="options">
                            <button value="encId_<?php echo $boss["Id"] ?>"><?php echo $lang->translate("EDIT"); ?></button>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php endif; ?>
        <?php foreach ( $disabledDungeons as $dungeon ): ?>
            <div class="dungeon block border_light">
                <h3 id="dungeon_<?php echo $dungeon["Id"] ?>" class="headline"><?php echo $dungeon["Name"] ?></h3>
                <div class="clear"></div>
            </div>
        <?php endforeach; ?>
            </div>
        </div>

        <div class="block">
            <h3 class="headline"><?php echo $lang->translate("CREATE_DUNGEON"); ?></h3>
            <input type="text" id="create_dungeon">
        </div>
        <div class="block">
            <h3 class="headline"><?php echo $lang->translate("CREATE_ENCOUNTER"); ?></h3>
            <input type="text" id="create_encounter">
        </div>
    <?php endif; ?>
</div>
<div class="clear"></div>
