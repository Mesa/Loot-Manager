<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
    "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <meta name="author" content="">
        <meta name="robots" content="">
        <meta http-equiv="Content-Style-Type" content="text/css">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <?php foreach($meta_data as $meta) {
            $data = "<meta ";
                foreach($meta as $key => $value){
                    $data .= "$key=\"$value\"";
                }
            $data .= ">\n\t";
            echo $data;
        }?>
        <title><?php echo (isset($title))? $title: "" ?></title>

        <link rel="stylesheet" type="text/css" href="<?php echo $web_root ?>jquery-ui-1.8.15.custom.css">
        <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Knewave|Patrick+Hand">
        <?php foreach ($css_link as $link):?>
        <link rel="stylesheet" type="text/css" href="<?php echo $link ?>">
        <?php endforeach; ?>

        <?php echo $this->load("layout.css") ?>
        <?php echo $this->load("DropDownMenu/css"); ?>
        <?php echo (isset($css))? $css:"" ?>

        <script type="text/javascript" src="<?php echo $web_root ?>jquery.js"></script>
        <script type="text/javascript" src="<?php echo $web_root ?>default.js"></script>
        <script type="text/javascript" src="<?php echo $web_root ?>admin.js"></script>
        <script type="text/javascript" src="<?php echo $web_root ?>submenu.js"></script>
        <?php echo $this->load("DropDownMenu/js");?>

        <?php foreach ($js_link as $link): ?>
        <script type="text/javascript" src="<?php echo $link?>"></script>
        <?php endforeach;?>

        <?php echo (isset($javascript))? $javascript:"" ?>

    </head>
    <body>
        <div id="wrapper">
            <div id="header" class="block border_light">
                <?php echo $header ?>
                <?php
                    $menu = new JackAssPHP\Helper\DropDownMenu();
                    $menu->loadXml(__DIR__ . DS . "../../Config/AdminMenu.xml");
                    echo $menu->getHtml();
                ?>
            </div>
            <div id="content">
                <?php echo $content ?>
            </div>
            <div id="footer">
                <?php echo $this->load("FrontPage/footer"); ?>
                <?php echo $footer ?>
            </div>
        </div>
        <div id="copy">
            <a href="http://www.xebro.de" >&copy; by Mesa</a>
        </div>
        <div id="frame_pool">
        </div>
        <div id="javascript-messages">
        </div>
    </body>
</html>