<?php if ( $rights->hasRight("edit_news")) : ?>
<div id="submenu">
    <div class="description">Neuigkeiten:</div>
    <button id="create_news">
        <?php echo $lang->translate("create") ?><br>
        <img src="<?php echo $web_root ?>big_add.png">
    </button>
    <button id="delete_news">
        <?php echo $lang->translate("delete"); ?><br>
        <img src="<?php echo $web_root ?>big_delete.png">
    </button>
    <div class="clear"></div>
</div>
<?php endif; ?>
<?php if (! empty($list)) : ?>
    <?php foreach ($list as $news): ?>
        <?php if ($news["Id"] > 0): ?>
        <div class="news block border_light" data-url="<?php echo $web_root ?>news/edit/<?php echo $news["Id"] ?>/">
        <?php else: ?>
        <div class="news block border_light" data-url="<?php echo $web_root ?>news/create/1/">
        <?php endif; ?>
            <h3
                class="headline"
                data-name="headline"><?php echo $news["headline"] ?></h3>

            <?php if ( $rights->hasRight("EDIT_NEWS")) : ?>
            <div class="display_options"><?php echo $lang->translate("DISPLAY_CONTENT_FROM_UNTIL")?>:
                <div class="display_to" data-name="to"><?php echo ($news["show_until"]) ? date("d.m.Y", $news["show_until"]) : " " ?></div>
                <div class="display_from" data-name="from"><?php echo ($news["show_from"]) ? date("d.m.Y", $news["show_from"]) : " "; ?></div>
            </div>
            <?php endif; ?>

            <div class="date"><?php echo date("d.m.y", $news["date"]) ?></div>
            <div class="author"
                 data-name="author"><?php echo $user_model->getUserNameById($news["author"]) ?></div>
            <div class="content" data-htmleditor data-name="content"><?php echo $news["content"] ?></div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
<?php endif; ?>