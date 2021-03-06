<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
    "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <meta name="author" content="<?php echo $this->registry->get("META_AUTHOR_NAME") ?>">
        <meta name="robots" content="<?php echo $this->registry->get("META_ROBOTS") ?>">
        <meta http-equiv="Content-Style-Type" content="text/css">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <title><?php echo $page_title ?></title>

        <link rel="stylesheet" type="text/css" href="<?php echo $web_root ?>jquery-ui-1.8.15.custom.css">
        <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Knewave|Patrick+Hand">
        <?php include  __DIR__ . DS .'../layout.css.php' ?>
        <?php echo ( isset($page_css) ) ? $page_css : ""; ?>

        <script type="text/javascript" src="<?php echo $web_root ?>jquery.js" ></script>
        <?php echo ( isset($extra_js) ) ? $extra_js : ""; ?>
    </head>
    <body>
        <div id="wrapper">
            <div id="content">
                <?php echo $content ?>
            </div>
        </div>
        <div id="frame_pool">
        </div>
        <div id="javascript-messages">

        </div>
    </body>
</html>