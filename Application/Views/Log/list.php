<div class="block border_light">
    <h3 class="headline">Log Nachrichten</h3>
    <?php foreach ( $log_list as $entry ): ?>
        <div class="log_entry">
            <div class="log_message"><?php echo $entry["message"]?></div>
            <div class="log_time"><?php echo date("d.m.y H:i", $entry["date"])?></div>
        </div>
    <?php endforeach; ?>
</div>