<div id="user-info">
<?php if ( $user->isLoggedIn() === true ) :?>
    <?php echo $user->getName(); ?><br>
    <a href="<?php echo $web_root ?>logout" id="logout_btn">
        <?php echo $lang->translate("LOG_OUT"); ?></a>
<?php elseif ( $this->rights->hasRight("LOGIN")): ?>
    <a
    val="<?php echo $web_root . $this->registry->get("LOGIN_PATH") ?>"
    id="login_btn">
    <?php echo $lang->translate("LOGIN"); ?></a>
<?php endif; ?>
</div>
