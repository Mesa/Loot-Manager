<div style="float:left;">
    <?php if ($rights->hasRight("admin_access_dashboard")): ?>
    <a class="ui_button" href="<?php echo $web_root?>admin/">Home</a>
    <?php endif; ?>
    <a class="ui_button" href="<?php echo $web_root?>admin/edit_chars/">Charakter Editieren</a>
</div>

<div style="float:right">
    <a class="ui_button" href="<?php echo $web_root?>">Startseite</a>
</div>
<p style="clear: both">&nbsp;</p>