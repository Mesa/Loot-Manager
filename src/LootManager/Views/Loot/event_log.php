<div id="event_loot_log">
<?php foreach ($list as $step): ?>
    <div class="log_event">
        <div class="action">
            <image class="action_icon" src="<?php echo $web_root . "/" . $icon[$step["Type"]]; ?>" >
            <h3><?php echo $action_names[$step["Type"]]; ?></h3>
        </div>
        <div class="date">
            <?php echo date("d.m.y H:i", $step["Time"]) ?>
        </div>
        <div class="char_name">&lt;
            <?php echo $charDao->getCharName($step["CharId"]) ?>
            &gt;
        </div>
        <div class="extra_log_data hidden">
            <div class="admin_name">
                <?php echo $user->getUserNameById( $step["AdminId"] ); ?>
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
</div>