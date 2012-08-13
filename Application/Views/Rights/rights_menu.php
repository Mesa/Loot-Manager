<div id="<?php echo $type ?>_rights_<?php echo $id ?>" class="<?php echo $type; ?>_right_menu">
    <h2><?php echo $name ?></h2>
    <div class="rights">
    <?php foreach ( $all_rights as $key => $right_group ): ?>
            <h3><a href="#"><?php echo $lang->translate($key) ?></a></h3>
            <div>
            <?php foreach ( $right_group as $right ): ?>
                <div class="right">
                    <input
                        id="<?php echo $type . "_" . $right["name"] . $id;?>"
                        type="checkbox"
                        class="right_select"
                        value="<?php echo $right["name"];?>"
                        <?php echo ( isset($right_list[$right["Id"]]) ) ? "checked" : ""; ?> />
                    <label for="<?php echo $type . "_" . $right["name"] . $id;?>">&nbsp;</label>
                    <?php echo $lang->translate($right["name"]) ?>
                </div>
            <?php endforeach; ?>
            </div>
    <?php endforeach; ?>
    </div>
</div>