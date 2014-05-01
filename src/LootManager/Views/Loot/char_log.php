<div class="block border_light">
    <div class="float_right"><?php echo $lang->translate("ADDED_AGO"); ?>: <b><?php echo $join_date ?></b></div>
    <div class="float_left"><?php echo $lang->translate("ITEMS_RECIEVED"); ?>: <b><?php echo $item_count ?></b></div>
    <div class="clear"></div>
</div>
<?php foreach ($list as $step): ?>
    <div class="log_event">
        <div class="action">
            <image class="action_icon" src="<?php echo $web_root . "/" . $icon[$step["Type"]]; ?>" >
            <h3><?php echo $action_names[$step["Type"]]; ?></h3>
        </div>
        <div class="event_name">
            &lt; <?php echo $event->getEventName($step["EventId"]) ?> &gt;
        </div>
        <div class="date">
                <?php echo date("d.m.y H:i", $step["Time"]) ?>
        </div>
        <div class="extra_log_data hidden">
            <div class="admin_name">
                <?php echo $user->getUserNameById($step["AdminId"]); ?>
            </div>
            <?php if (!empty($step["FromPosition"])): ?>
                <div class="fromPosition">Von: <span><?php echo $step["FromPosition"] ?></span></div>
            <?php endif; ?>
            <?php if (!empty($step["ToPosition"])): ?>
                <div class="toPosition">Auf: <span><?php echo $step["ToPosition"] ?></span></div>
            <?php endif; ?>
            <?php if (!empty($step["Desc"])): ?>
                <div class="description"><?php echo $step["Desc"] ?></div>
            <?php endif; ?>
        </div>
<div class="clear"></div>
    </div>
<?php endforeach; ?>
