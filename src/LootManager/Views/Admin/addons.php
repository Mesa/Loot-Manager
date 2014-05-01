<?php foreach ($addon_list as $addon): ?>
<div class="addon block float_left border_light">
    <h3 class="headline"><?php echo $addon["name"] ?></h3>
    <?php echo $addon["html"] ?>
</div>
<?php endforeach; ?>

<div class="clear"></div>