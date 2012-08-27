<div id="submenu">
    <div class="description">&nbsp;</div>
    <div class="clear"></div>
</div>
<div class="block border-light">
    <h3 class="headline"><?php echo $lang->translate("GUILD_NAME"); ?></h3>
        <div class="item">
            <div class="setting-name">
                <?php echo $lang->translate("GUILD_NAME"); ?>
            </div>
            <div class="setting-value"
                 data-name="value"
                 data-url="<?php echo $web_root?>config/edit/GUILD_NAME/">
                <?php echo $config->getValue("GUILD_NAME");?>
            </div>
        </div>
</div>
<div class="block border-light">
    <h3 class="headline"><?php echo $lang->translate("CONFIGURATION"); ?></h3>
        <div class="item">
            <div class="setting-name">
                <?php echo $lang->translate("SMTP_HOST"); ?>
            </div>
            <div class="setting-value"
                 data-name="value"
                 data-url="edit/SMTP_HOST/">
                <?php echo $config->getValue("SMTP_HOST");?>
            </div>
        </div>
        <div class="item">
            <div class="setting-name">
                <?php echo $lang->translate("SMTP_USERNAME"); ?>
            </div>
            <div class="setting-value"
                 data-name="value"
                 data-url="edit/SMTP_USERNAME/">
                <?php echo $config->getValue("SMTP_USERNAME");?>
            </div>
        </div>
        <div class="item">
            <div class="setting-name">
                <?php echo $lang->translate("SMTP_PASSWORD"); ?>
            </div>
            <div class="setting-value"
                 data-name="value"
                 data-url="edit/SMTP_PASSWORD/">
                <?php echo $config->getValue("SMTP_PASSWORD");?>
            </div>
        </div>
        <div class="item">
            <div class="setting-name">
                <?php echo $lang->translate("SMTP_FROMNAME"); ?>
            </div>
            <div class="setting-value"
                 data-name="value"
                 data-url="edit/SMTP_FROMNAME/">
                <?php echo $config->getValue("SMTP_FROMNAME");?>
            </div>
        </div>
        <div class="item">
            <div class="setting-name">
                <?php echo $lang->translate("SMTP_FROMMAIL"); ?>
            </div>
            <div class="setting-value"
                 data-name="value"
                 data-url="edit/SMTP_FROMMAIL/">
                <?php echo $config->getValue("SMTP_FROMMAIL");?>
            </div>
        </div>
</div>
<div class="block border-light">
    <h3 class="headline">ReCaptcha</h3>
        <div class="item">
            <div class="setting-name">
                <?php echo $lang->translate("RECAPTCHA_PUB_KEY"); ?>
            </div>
            <div class="setting-value"
                 data-name="value"
                 data-url="edit/RECAPTCHA_PUB_KEY/">
                <?php echo $config->getValue("RECAPTCHA_PUB_KEY");?>
            </div>
        </div>
        <div class="item">
            <div class="setting-name">
                <?php echo $lang->translate("recaptcha_priv_key"); ?>
            </div>
            <div class="setting-value"
                 data-name="value"
                 data-url="edit/RECAPTCHA_PRIV_KEY/">
                <?php echo $config->getValue("RECAPTCHA_PRIV_KEY");?>
            </div>
        </div>
</div>
<script type="text/javascript">
    $(document).ready( function (){
        $(".setting-value").editMe({
            target_url : "edit/",
            edit_btn_txt : "<?php echo $lang->translate("EDIT");?>",
            cancel_btn_txt : "<?php echo $lang->translate("CANCEL");?>",
            save_btn_txt   : "<?php echo $lang->translate("SAVE"); ?>",
            img_root_path : "<?php echo $web_root?>"
        });
    });
</script>