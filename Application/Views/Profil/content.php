<div id="submenu">
    <h3 class="description">Profil</h3>
    <div class="clear"></div>
</div>
<div class="block">
    <h3 class="headline">Chars</h3>
    <?php foreach ($char_list as $char ): ?>

    <?php endforeach;?>
</div>
<div class="block form">
        <h3 class="headline"><?php echo $lang->translate("GENERAL_INFORMATION"); ?></h3>
        <div class="item" data-url="change/name/">
            <div class="label"><?php echo $lang->translate("SHOWN_NAME") ?>:</div>
            <div class="value" data-name="name"><?php echo $user->getName(); ?></div>
        </div>
        <div class="item" data-url="change/email/">
            <div class="label">E-Mail:</div>
            <div class="value" data-name="email"><?php echo $user->getEmail(); ?></div>
        </div>
        <div class="item" data-url="change/login/">
            <div class="label">
                <?php echo $lang->translate("USERNAME") ?>:<br>
                <?php echo $lang->translate("NEW_PASSWORD") ?>:<br>
                <?php echo $lang->translate("RETYPE_PASSWORD") ?><br><br><br>
                <?php echo $lang->translate("OLD_PASSWORD") ?>:
            </div>
            <div class="value" data-name="login"><?php echo $user->getLoginName();?></div>
            <div class="text" data-name="new_password1"></div>
            <div class="text" data-name="new_password2"></div><br>
            <div class="text" data-name="old_password"></div>
        </div>
</div>

<script type="text/javascript">
    $(document).ready( function ( ) {
        $(".item").editMe({
            edit_btn_txt : "<?php echo $lang->translate("EDIT");?>",
            cancel_btn_txt : "<?php echo $lang->translate("CANCEL");?>",
            save_btn_txt   : "<?php echo $lang->translate("SAVE"); ?>",
            img_root_path : "<?php echo $web_root?>"
        });
    });
</script>
