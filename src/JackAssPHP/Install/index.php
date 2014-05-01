<html>
    <head>
        <title>Install JackAssPHP</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script src="../Library/jquery.js" type="text/javascript"></script>
        <link rel="stylesheet" href="../Library/jquery.css" type="text/css"></link>
        <script type="text/javascript" >
            $(document).ready( function (){
                var mysql_con_test = $("<div>");
                var console_output = $("<div>");
                $('body').append(mysql_con_test);
                $('body').append(console_output);
                mysql_con_test.dialog({ width: 600, autoOpen: false, title : "MySQL connection Test"});
                console_output.dialog({ width: 600, autoOpen: false});
                $.post("rewrite_test.php", {action: "rewrite_test"}, function (data){
                    var target = $("#apache_rewrite");
                    if ( data == "true" ) {
                        target.addClass("ready").find(".status").html("ok");
                    } else {
                        target.addClass("error").find(".status").html(data);
                    }
                })
                $.post("install.php", {action: "file_permission", path : "<?php echo dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR ?>" },
                function ( data ) {
                    var target = $("#root_file_permissions");
                    if ( data == "true" ) {
                        target.addClass("ready").find(".status").html("ok");
                    } else {
                        target.addClass("error").find(".status").html(data);
                    }
                });
                $.post("install.php", {action: "file_permission", path : "<?php echo dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . "JackAssPHP" . DIRECTORY_SEPARATOR . "Install" . DIRECTORY_SEPARATOR ?>" },
                function ( data ) {
                    var target = $("#inst_file_permissions");
                    if ( data == "true" ) {
                        target.addClass("ready").find(".status").html("ok");
                    } else {
                        target.addClass("error").find(".status").html(data);
                    }
                });
                $.post("install.php", {action: "folder_permission", path : "Application" },
                function ( data ) {
                    var target = $("#folder_permissions");
                    if ( data == "true" ) {
                        target.addClass("ready").find(".status").html("ok");
                    } else {
                        target.addClass("error").find(".status").html(data);
                    }
                });
                $.post("install.php", {action: "php_version" },
                function ( data ) {
                    var target = $("#php_version");
                    if ( data != "false" ) {
                        target.addClass("ready").find(".status").html(data);
                    } else {
                        target.addClass("error").find(".status").html(data);
                    }
                });
                $.post("install.php", {action: "pdo_version" },
                function ( data ) {
                    var target = $("#pdo_version");
                    if ( data != "false" ) {
                        target.addClass("ready").find(".status").html(data);
                    } else {
                        target.addClass("error").find(".status").html("PDO extension is missing");
                    }
                });

                $("#admin_password input").keyup( function (){
                    var password1 = $("#password_1");
                    var password2 = $("#password_2");
                    checkPasswords(password1, password2);
                });

                $("#check_db_connection").click( function() {
                    var mysql_host = $("#mysql_host_inpt").val();
                    var mysql_shema = $("#mysql_shema_inpt").val();
                    var mysql_port = $("#mysql_port_inpt").val();
                    var mysql_username = $("#mysql_username_inpt").val()
                    var mysql_password = $("#mysql_password_inpt").val();

                    var password1 = $("#password_1");
                    var password2 = $("#password_2");
                    var admin_username = $("#user_name");

                    if ( checkUsername(admin_username) && checkPasswords(password1, password2) ) {

                    var admin_name = admin_username;
                    var admin_password = password1;
                        $.post("install.php",
                        {   action: "check_db_connection",
                            host: mysql_host,
                            shema: mysql_shema,
                            port: mysql_port,
                            username: mysql_username,
                            password: mysql_password
                        },
                        function ( data ) {
                            if ( data == "" ) {
                                mysql_con_test.html("<div class='ready'>Ok, Database connection established. <br />Please go ahead....</div>").dialog("open");
                                $("#check_db_connection").parent().slideUp();
                                $("#submit_btn").show();
                            } else {
                                mysql_con_test.html(data).dialog("open");
                            }
                        })
                    }

                    return false;
                });

                $("#submit_btn").click( function() {
                    var mysql_host = $("#mysql_host_inpt").val();
                    var mysql_shema = $("#mysql_shema_inpt").val();
                    var mysql_port = $("#mysql_port_inpt").val();
                    var mysql_username = $("#mysql_username_inpt").val()
                    var mysql_password = $("#mysql_password_inpt").val();
                    var account_username = $("#user_name").val();
                    var account_password = $("#password_1").val();

                    $(this).hide();
                        $("body").append("<iframe id=\"console\" src=\"install.php?action=run&acc_username="+account_username+"&acc_password="+account_password+"&host="+mysql_host+"&shema="+mysql_shema+"&port="+mysql_port+"&username="+mysql_username+"&password="+mysql_password+"\">test</iframe><h1><a href=\"../../\">Zur Startseite</a></h1>");
                    $("#console").css({
                        width: "100%",
                        "height": "500px"
                    })
                    return false;
                });

                $("#mysql>div, #check>div, #account>div").hover(
                function ( ){
                    showDescription($(this));
                }, function () {
                    hideDescription($(this));
                });
            })
            function checkUsername ( username ) {

                if ( username.val() == "" ) {
                    username.addClass("error");
                    return false;
                } else {
                    username.removeClass("error");
                    username.addClass("ready");
                    return true;
                }
            }
            function checkPasswords (password1, password2)
            {
                if( password1.val() == "" ) {
                    password1.addClass("error");
                    password2.addClass("error");
                } else if (password1.val() != password2.val() ) {
                    password1.addClass("error");
                    password2.addClass("error");
                    return false;
                } else if ( password1.val() != "" && password1.val() == password2.val() ) {
                    password1.removeClass("error");
                    password1.addClass("ready");
                    password2.removeClass("error");
                    password2.addClass("ready");
                    return true;
                }
            }
            function showDescription ( target )
            {
                $("#" + target.attr("id") + "_desc").stop(true, true).slideDown("slow");
            }

            function hideDescription ( target ) {

                $("#" + target.attr("id") + "_desc").stop(true, true).slideUp("slow")
            }
        </script>

        <style type="text/css">
            .ui-dialog {
                font-size: 0.8em;
            }

            .ready {
                color: green;
            }

            .error {
                color: black;
                background-color: red;
            }

            fieldset>div,#description>div {
                position: relative;
                border-bottom: 1px solid lightgray;
            }

            div div.status, div input {
                margin-left: 50%;
                width: 50%;
            }

            fieldset div span {
                position: absolute;
                top: 15px;
                left: 0px;
                width: 45%;
                text-align: right;
                font-size: 0.7em;
            }

            body {
                background-color: white;
                font-family: Tahoma, Verdana, Sans-serif;
                font-size: 1.0em;
                color: #444444;
            }

            h1 {
                color: steelblue;
                border-bottom: 2px dotted #CCCCCC;
                text-align: right;
                font-size: 1.6em;
            }

            fieldset, #description {
                font-size: 0.9em;
                background-color: #f9f9f9;
                border: 1px solid #D0D0D0;
                color: steelblue;
                margin: 0px 3px;
                padding: 12px 0px;
                float: left;
                width: 32%;
                overflow: hidden;
            }

            #description {
                position: absolute;
                top: 50px;
                right: 0px;
                width: 33%;
            }

            fieldset>div {
                padding-top: 10px;
                padding-bottom: 10px;
            }

            .description h3 {
                color: steelblue;
                text-decoration: underline;
                font-size: 1.2em;
                font-weight: 900;
                margin-right: 5px;
                padding-top: 5px;
                text-align: center;
            }

            .description {
                color: #444444;
                font-size: 0.8em;
                padding-top: 15px;
                padding-bottom: 5px;
            }

            #page_wrapper {
                min-width: 1000px;
                margin: auto;
                margin-top: 100px;
                position: relative;
            }

            .hidden {
                display: none;
            }
        </style>
    </head>
    <body>
        <div id="page_wrapper">
            <h1>Installation</h1>
            <div class="info">

            </div>
            <div style="width: 66%;">
                <fieldset id="check">
                    <legend>System Rechte und Erweiterungen</legend>
                    <div id="apache_rewrite">
                        <span>Apache Rewrite</span>
                        <div class="status">
                            Bitte überprüfe dies vor der Installation
                        </div>
                    </div>
                    <div id="root_file_permissions">
                        <span>Root Folder</span>
                        <div class="status">&nbsp;</div>
                    </div>
                    <div id="inst_file_permissions">
                        <span>./JackAssPHP/Install/</span>
                        <div class="status">&nbsp;</div>
                    </div>
                    <div id="php_version">
                        <span>PHP Version</span>
                        <div class="status">&nbsp;</div>
                    </div>
                    <div id="pdo_version">
                        <span>
                            PDO Version<br />
                        (<a href="http://de2.php.net/manual/de/pdo.installation.php">weitere Infos</a>)
                        </span>
                        <div class="status">&nbsp;</div>
                    </div>
                </fieldset>
                <fieldset id="account">
                    <legend>Administrator Account</legend>
                    <div id="admin_name">
                        <span>Benutzername</span>
                        <input
                            type="text"
                            name="user_name"
                            id="user_name"
                            />
                    </div>
                    <div id="admin_password">
                        <span>Passwort</span>
                        <input
                            type="password"
                            name="user_name"
                            id="password_1"
                            /><br />
                        <input
                            type="password"
                            name="user_name"
                            id="password_2"
                            />
                    </div>
                </fieldset>
                <fieldset id="mysql">
                    <legend>MySQL Information</legend>
                    <div id="mysql_host">
                        <span>Host (Ip-Adresse/DNS-Name)</span>
                        <input
                            type="text"
                            name="mysql_host"
                            id="mysql_host_inpt"
                            value="<?php echo (isset($_POST["mysql_host"]) && strlen($_POST["mysql_host"]) > 0) ? $_POST["mysql_host"] : "127.0.0.1" ?>"
                            />
                    </div>
                    <div id="mysql_port">
                        <span>Port (3306)</span>
                        <input
                            type="text"
                            name="mysql_port"
                            id="mysql_port_inpt"
                            size="2"
                            value="<?php echo ( isset($_POST["mysql_port"]) && strlen($_POST["mysql_port"]) > 0 ) ? $_POST["mysql_port"] : "3306" ?>"
                            />
                    </div>
                    <div id="mysql_shema">
                        <span>Name der Datenbank:</span>
                        <input
                            type="text"
                            name="mysql_shema"
                            id="mysql_shema_inpt"
                            value="<?php echo ( isset($_POST["mysql_shema"]) && strlen($_POST["mysql_shema"]) > 0 ) ? $_POST["mysql_shema"] : "lootmanager"; ?>"
                            />
                    </div>
                    <div id="mysql_username">
                        <span>Benutzername:</span>
                        <input
                            type="text"
                            name="mysql_username"
                            id="mysql_username_inpt"
                            value="<?php echo ( isset($_POST["mysql_username"]) && strlen($_POST["mysql_username"]) > 0 ) ? $_POST["mysql_username"] : "" ?>"
                            />
                    </div>
                    <div id="mysql_password">
                        <span>Passwort</span>
                        <input
                            type="text"
                            name="mysql_password"
                            id="mysql_password_inpt"
                            value="<?php echo ( isset($_POST["mysql_password"]) && strlen($_POST["mysql_password"]) > 0 ) ? $_POST["mysql_password"] : "" ?>"
                            />
                    </div>
                    <div id="check_db" style="text-align: center;">
                        <button id="check_db_connection">Datenbank Verbinung prüfen</button>
                    </div>
                </fieldset>
            </div>
            <div id="description">
                <legend>Beschreibung</legend>
                <div id="apache_rewrite_desc" class="description hidden">
                    <h3>Apache Rewrite Modul</h3>
                    Für den Betrieb der Webseite benötigst du das Apache Rewrite
                    Modul. <br /><br />Außerdem muss für den Ordner dieser Seite die Option
                    "AllowOverride" auf ALL gesetzt werden.
                    <a href="http://httpd.apache.org/docs/2.0/mod/core.html#allowoverride">weitere Infos</a>
                </div>
                <div id="root_file_permissions_desc" class="description hidden">
                    <h3>Schreibrechte</h3>
                    Für den Betrieb der Seite werden im Root Ordner (also zwei
                    Ordner höher als dieses Script) die <b>.htaccess, index.php und
                        mysql.ini</b> Datei erstellt. Damit du diese Dateien nicht von
                    Hand erstellen musst, gib dem Webserver vorübergehen Schreibrechte.
                    Außerdem wird der Install Ordner nach erfolgreicher Installation
                    gelöscht, damit nicht zufällig deine Einstellungen überschrieben
                    werden.
                </div>
                <div id="php_version_desc" class="description hidden">
                    <h3>PHP Version</h3>
                    Du solltest mindestens PHP 5.1 installiert haben. Außer dass
                    es immer sicherer ist, die neuste Version installiert zu haben
                    verwendet dieses Projekt Erweiterungen die noch nicht so lange
                    in PHP enthalten sind.
                </div>
                <div id="pdo_version_desc" class="description hidden">
                    <h3>PDO Version</h3>
                    Je nach Betriebssystem hängt es von der PHP Version ab ob du
                    die PDO Erweiterung aktivieren musst.<br />
                </div>
                <div id="mysql_host_desc" class="description hidden">
                    <h3>MySQL Host</h3>
                    Du kannst entweder eine Ip Adresse oder den DNS-Namen angeben.
                    Oft ist es so das der MySQL Server auf der gleichen Maschine
                    läuft und somit nur "127.0.0.1" oder "localhost" als Adresse
                    eingetragen werden muss.
                </div>
                <div id="mysql_port_desc" class="description hidden">
                    <h3>MySQL Port</h3>
                    Der standard Port ist 3306, sollte der Datenbank-Server auf
                    einem anderen Port lauschen, dann gib hier den ensprechenden
                    Port ein.
                </div>
                <div id="mysql_shema_desc" class="description hidden">
                    <h3>Name der Datenbank</h3>
                    Hier musst du den Namen der Datenbank eingeben.
                </div>
                <div id="mysql_username_desc" class="description hidden">
                    <h3>MySQL Benutzername</h3>
                    Der Benutzername für die Verbindung mit dem MySQL Server.
                </div>
                <div id="mysql_password_desc" class="description hidden">
                    <h3>MySQL Passwort</h3>
                    Das Passwort für die Verbindung mit dem MySQL Server.
                </div>
                <div id="check_db_desc" class="description hidden">
                    <h3>Datenbank Verbindung prüfen</h3>
                    Du musst erst die Datenbank Verbindung prüfen bevor du mit
                    der Installation fortfahren kannst.
                </div>
                <div id="admin_name_desc" class="description hidden">
                    <h3>Benutzername</h3>
                    Dein Benutzername für deinen Administrator Zugang zum System.
                </div>
                <div id="admin_password_desc" class="description hidden">
                    <h3>Passwort</h3>
                    Das Passwort für deinen Administrator Zugang zum System.
                </div>
            </div>
            <div style="clear: both; text-align: center">
                <button id="submit_btn" class="hidden">Installieren</button>
            </div>
        </div>
    </body>
</html>
