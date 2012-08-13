<div class="block border_light">
    <div id="list-head">
        <div class="name">
            <?php echo $lang->translate("NAME") ?>
        </div>
        <div class="path">
            <?php echo $lang->translate("PATH") ?>
        </div>
        <div class="options">
            <button id="add-btn">&nbsp;<?php echo $lang->translate("ADD"); ?></button>
        </div>
    </div>
    <ul id="link-list">
        <?php foreach ($link_list as $link): ?>
            <li>
                <div class="link block border_light">
                    <input type="hidden" value="<?php echo $link["Id"]; ?>" />
                    <div class="name">
                        <input type="text" value="<?php echo $link["Name"]; ?>" />
                    </div>
                    <div class="path">
                        <input type="text" value="<?php echo $link["Path"]; ?>"/>
                    </div>
                    <div class="rights">
                        <select class="right-select">
                            <option value="0"><?php echo $lang->translate("NO_RIGHTS_NEEDED"); ?></option>
                            <?php foreach ($right_list as $key => $right_group) : ?>
                                <optgroup label="<?php echo $lang->translate($key) ?>">
                                    <?php foreach ($right_group as $right) : ?>
                                        <option value="<?php echo $right["Id"]; ?>" <?php echo ($right["name"] == $link["Right"])?"selected":"" ?>><?php echo $lang->translate($right["name"]); ?></option>
                                    <?php endforeach; ?>
                                </optgroup>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="options">
                        <button class="delete-btn">&nbsp;<?php echo $lang->translate("DELETE"); ?></button>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
        <li>
            <div class="new-item hidden block border_light" id="new-item">
                <input type="hidden" value="0" />
                <div class="name">
                    <input type="text" value="" />
                </div>
                <div class="path">
                    <input type="text" value=""/>
                </div>
                <div class="options">
                    <button class="save-btn"><?php echo $lang->translate("CREATE") ?></button>
                </div>
            </div>
        </li>
    </ul>
    <div class="clear"></div>
</div>
<div class="clear"></div>