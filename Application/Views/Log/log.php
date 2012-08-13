<div class="information">
    <img src="<?php echo $web_root ?>information.png" >
    Hier werden alle Aktionen für den einen Spieler gefiltert. Bitte beachte, 
    dass alle Ereignisse berücksichtigt werden.
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
        <div class="admin_name">
            <?php echo $user->getUserNameById($step["AdminId"]); ?>
        </div>        
                <?php echo date("d.m.y H:i", $step["Time"]) ?>
        </div>

        <?php if (!empty($step["FromPosition"])): ?>
            <div class="fromPosition">Position: <span><?php echo $step["FromPosition"] ?></span></div>   
        <?php endif; ?>            
        <?php if (!empty($step["Desc"])): ?>
            <div class="description"><?php echo $step["Desc"] ?></div>
        <?php endif; ?>        
<div class="clear"></div>
    </div>
<?php endforeach; ?>
