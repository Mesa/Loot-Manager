<?php if (! $inline ):?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
    "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <title>Login</title>
        <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Patrick+Hand">
        <style type="text/css"><?php include $this->registry->get("VIEW_PATH")."FrontPage/layout.css"; ?></style>
        <script type="text/javascript" src="<?php echo $web_root ?>jquery.js"></script>
            <script type="text/javascript" >
            $(document).ready( function (){
                var loginObj = $("#login");
                var windowHeight = $(window).height();
                var loginHeight = loginObj.height();
                var marginTop = windowHeight/2-loginHeight/2;
                $("#login").css("margin-top", marginTop);
                $("#form").attr('action','<?php echo $web_root . "login/check/" . $form_token ?>');
            });
        </script>
    </head>
    <body>
<?php endif;?>
<?php if($inline): ?>
        <script type="text/javascript">
           $("#form").attr('action','<?php echo $web_root . "login/check/" . $form_token ?>');
        </script>
<?php endif; ?>
        <div id="login" class="block">
            <form id="form" method="POST" accept-charset="utf-8" >
                <div><?php echo $lang->translate("USERNAME"); ?>:<br />
                    <input id="one" class="block" type="text" name="<?php echo $username_input ?>" />
                </div>
                <div><?php echo $lang->translate("PASSWORD"); ?>: <br />
                    <input id="two" class="block" type="password" name="<?php echo $password_input ?>" />
                </div>
                <div>
                    <input id="login_button" class="block" type="submit" name="Anmelden" value="<?php echo $lang->translate("LOGIN"); ?>" /><br>
                    <span><?php echo $lang->translate("STAY_LOGGED_IN"); ?>
                        <input type="checkbox" name="remember_me"></span>
                </div>
            </form>
                <div class="error_msg block <?php echo ($login_try > 0)? "": "hidden" ?>">
                    <?php echo $lang->translate("WRONG_USERNAME_OR_PASSWORD") ;?>
                </div>
            <?php if ( $this->rights->hasRight("REGISTER")): ?>
                <div>
                    <a id="register" href="<?php echo $this->registry->get("WEB_ROOT")?>register/"><?php echo $lang->translate("register");?></a>&nbsp;&nbsp;&nbsp;
                    <a id="register" href="<?php echo $this->registry->get("WEB_ROOT")?>password_recovery/"><?php echo $lang->translate("LOST_PASSWORD");?></a>

                </div>
            <?php endif; ?>
        </div>
<?php if(! $inline ): ?>
    </body>
</html>
<?php endif; ?>
