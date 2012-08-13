<div class="block border_light">
    <h1 class="headline"><?php echo $lang->translate("REGISTER") ?></h1>
    <div class="form">
        <div class="item">
            <div class="label"><?php echo $lang->translate("USERNAME") ?>:</div>
            <input type="text" id="username" class="user_input" />
            <div class="message"></div>
        </div>
        <div class="item">
            <div class="label">E-Mail:</div>
            <input type="text" id="email" class="user_input" />
            <div class="message"></div>
        </div>
        <div class="item">
            <div class="label"><?php echo $lang->translate("PASSWORD"); ?>:</div>
            <input type="password" id="password_1" class="user_input" />
            <input type="password" id="password_2" class="user_input" />
        </div>
        <div class="item hidden">
            <input id="submit-btn" type="submit" value="<?php echo $lang->translate("SUBMIT"); ?>" />
        </div>
    </div>
    <div>
        <ul id="error-list">
            <li id="username-empty">
                <?php echo $lang->translate("USERNAME_MIN_LENGTH"); ?>
            </li>
            <li id="email-empty">
                <?php echo $lang->translate("EMAIL_NOT_VALID"); ?>
            </li>
            <li id="password_error">
                Das Passwort sollte mindestens
                <?php echo $this->registry->get("MIN_PASSWORD_LENGTH") ?>
                Zeichen lang sein.
            </li>
            <li id="password_error_special_char">
                <?php echo $lang->translate("PASSWORD_NEEDS_SPECIAL_CHAR"); ?>
            </li>
            <li id="password_error_number">
                <?php echo $lang->translate("PASSWORD_REQUIRES_NUMBER"); ?>
            </li>
            <li id="email_not_free">
                <?php echo $lang->translate("EMAIL_NOT_FREE"); ?>
            </li>
            <li id="username_not_free">
                <?php echo $lang->translate("USERNAME_NOT_FREE"); ?>
            </li>
            <li id="passwords_not_equal">
                <?php echo $lang->translate("PASSWORDS_NOT_EQUAL"); ?>
            </li>
        </ul>
    </div>
    <div id="server-response">
    </div>
</div>

<script type="text/javascript">
    var timeoutObj = "";
    var username_val = false;
    var email_val = false;
    var password_val_special = false;
    var password_val_number = false;
    var password_val = false;
    var password_check = false;

    $(document).ready( function () {

        $("#submit-btn").click( function (){
            addPreloader($(".form"), "Übertrage die Daten");
            $.post("save/<?php echo $token; ?>/",{
                name: $("#username").val(),
                password: $("#password_1").val(),
                email: $("#email").val()
            }, function ( data ){
                var response = $.parseJSON(data);
                if ( response.status == true) {
                    stopPreLoaderAnimation(response.html, function (){});
                } else {

                    window.clearInterval(preLoadInterval);
                    $(".pre-loader").slideUp("fast", function (){
                        $(this).remove();
                    });

                    $("#server-response").html(response.error_msg[0]);
                }
            });
        });

        var emailRegEx = <?php echo $this->registry->get("VALIDATE_EMAIL_REGEX"); ?>;
        var usernameRegEx = <?php echo $this->registry->get("VALIDATE_USERNAME_REGEX"); ?>;
        var specialCharRegex = <?php echo $this->registry->get("VALIDATE_SPECIAL_CHAR_REGEX");?>;
        $("#username").blur( function (){
            username_val = validateInput(usernameRegEx, $("#username"), $("#username-empty"));
            timer("usernameIsUnused($(\"#username\"))", 0);
            checkForm();
        }).keyup( function (){
            username_val = validateInput(usernameRegEx, $("#username"), $("#username-empty"));
            timer("usernameIsUnused($(\"#username\"))", 1000);
            checkForm();
        });

        $("#email").blur( function (){
            email_val = validateInput(emailRegEx, $("#email"),$("#email-empty"));
            timer("emailIsUnused($(\"#email\"))", 0);
            checkForm();
        }).keyup( function (){
            email_val = validateInput(emailRegEx, $("#email"),$("#email-empty"));
            timer("emailIsUnused($(\"#email\"))", 1000);
            checkForm();
        });

        $("#password_1, #password_2").keyup( function (){
            password_val_special = validateInput(specialCharRegex, $("#password_1"), $("#password_error_special_char"));
            password_val_number = validateInput(/[0-9]/, $("#password_1"), $("#password_error_number"));
            password_val = validateInput(/.{<?php echo (int)$this->registry->get("MIN_PASSWORD_LENGTH"); ?>,}/, $("#password_1"), $("#password_error"));
            password_check = passwordsAreEqual($("#password_1"), $("#password_2"), $("#passwords_not_equal"));
            checkForm();
        });
    });

    function checkForm ( )
    {
        if ( $("#username").data("status") == undefined ) {
            usernameIsUnused($("#username"));
        }

        if (
            username_val
            && email_val
            && password_val
            && password_val_special
            && password_val_number
            && password_check
            && $("#username").data("status")
            && $("#email").data("status")
        ) {
            $("#submit-btn").parent().slideDown("slow");
        } else {
            $("#submit-btn").parent().slideUp("slow");
        }
    }

    function validateInput ( regex,  input, error_msg_id )
    {
        if ( input.val() != "") {
            var result = input.val().search(regex)

            if( result != -1 ) {
                error_msg_id.slideUp("fast");
                return true;
            } else {
                error_msg_id.slideDown("fast");
                return false;
            }
        }
    }

    function passwordsAreEqual ( target1, target2 , error_msg_id)
    {
        if ( target1.val() == target2.val()) {
            error_msg_id.slideUp("fast");
            return true;
        } else {
            error_msg_id.slideDown("fast");
            return false;
        }
    }

    function timer ( callback, time)
    {
        if ( timeoutObj != "" ) {
            window.clearTimeout(timeoutObj);
        }
            timeoutObj = window.setTimeout(callback,time);
    }

    function emailIsUnused ( target )
    {
        if ( email_val && target.data("lastVal") != target.val() ) {
            addPreloader(
                target.parent().find(".message"),
                "<?php echo $lang->translate("VERIFY_EMAIL") ?>"
            );

            $.post("check/email/", {email: target.val()}, function ( data ){
                stopPreLoaderAnimation("",function(){});
                var response = $.parseJSON(data);
                if ( response == true ) {
                    $("#email_not_free").slideUp("fast");
                    target.data("status", true);
                } else {
                    $("#email_not_free").slideDown("fast");
                    target.data("status", false);
                }
                target.data("lastVal", target.val());
                checkForm();
            })
        }
        checkForm();
    }

    function usernameIsUnused ( target )
    {
        var inputLength = 0;
        inputLength = target.val().length;
        var response = false;

        if ( inputLength >= 2 && target.data("lastVal") != target.val()) {
            addPreloader(
                target.parent().find(".message"),
                "<?php echo $lang->translate("VERIFY_USERNAME") ?>"
            );
            $.post("check/uname/", {username: target.val()}, function ( data ){
                stopPreLoaderAnimation("",function(){});
                var response = $.parseJSON(data);
                if (response == true) {
                    $("#username_not_free").slideUp("fast");
                    target.data("status", true);
                } else {
                    $("#username_not_free").slideDown("fast");
                    target.data("status", false);
                }
                target.data("lastVal", target.val());
                checkForm();
            });
        }
    }
</script>