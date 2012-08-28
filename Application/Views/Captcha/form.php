<script type="text/javascript">
 var RecaptchaOptions = {
    theme : 'white'
 };
 </script>
 <div class="block">
    <h2><?php echo $lang->translate("RE_CAPTCHA_TITLE"); ?></h2>
    <form method="POST" action="<?php echo $form_action ?>">
        <p><?php echo $captcha_html ?></p>
        <input type="submit" value="<?php echo $lang->translate("submit") ?>"></input>
    </form>
    <p class="information">
        <?php echo $lang->translate("CAPTCHA_INFO_TEXT"); ?><br/>
        <a target="blank" href="http://www.google.de/url?sa=t&rct=j&q=captcha&source=web&cd=1&ved=0CGMQFjAA&url=http%3A%2F%2Fde.wikipedia.org%2Fwiki%2FCAPTCHA&ei=wUHYT-X2CMi_0QWznvGvBA&usg=AFQjCNGb6oqCiSYxdsEhfeX7HEwUm07j-A">info</a>
    </p>
</div>