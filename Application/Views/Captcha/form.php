<script type="text/javascript">
 var RecaptchaOptions = {
    theme : 'white'
 };
 </script>
 <div class="block">
    <h2>Captcha</h2>
    <p>
        <?php echo $lang->translate("CAPTCHA_PRE_TEXT"); ?>
        <a href="http://www.google.de/url?sa=t&rct=j&q=captcha&source=web&cd=1&ved=0CGMQFjAA&url=http%3A%2F%2Fde.wikipedia.org%2Fwiki%2FCAPTCHA&ei=wUHYT-X2CMi_0QWznvGvBA&usg=AFQjCNGb6oqCiSYxdsEhfeX7HEwUm07j-A">&gt;&gt;</a>
    </p>
    <form method="POST" action="<?php echo $form_action ?>">
        <?php echo $captcha_html ?>
        <input type="submit" value="<?php echo $lang->translate("submit") ?>"></input>
    </form>
</div>