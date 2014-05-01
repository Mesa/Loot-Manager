<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
    "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <meta name="author" content="">
        <meta name="robots" content="">
        <meta http-equiv="Content-Style-Type" content="text/css">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <?php if(isset($meta_data)):?>
        <?php foreach($meta_data as $meta) {
            $data = "<meta ";
                foreach($meta as $key => $value){
                    $data .= "$key=\"$value\"";
                }
            $data .= ">\n\t";
            echo $data;
        }?>
        <?php endif;?>
        <title><?php echo (isset($title))? $title: "" ?></title>

        <link rel="stylesheet" type="text/css" href="<?php echo $web_root ?>jquery-ui-1.8.15.custom.css">
        <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Knewave|Patrick+Hand">
        <?php if ( isset($css_link) and count($css_link) > 0): ?>
        <?php foreach ($css_link as $link):?>
        <link rel="stylesheet" type="text/css" href="<?php echo $link ?>">
        <?php endforeach; ?>
        <?php endif; ?>

        <link rel="stylesheet" type="text/css" href="css/styles.css"/>
        <?php echo $this->load("DropDownMenu/css"); ?>
        <?php echo (isset($css))? $css:"" ?>

        <script type="text/javascript" src="<?php echo $web_root ?>jquery.js"></script>
        <script type="text/javascript" src="<?php echo $web_root ?>default.js"></script>
        <script type="text/javascript" src="<?php echo $web_root ?>admin.js"></script>
        <script type="text/javascript" src="<?php echo $web_root ?>submenu.js"></script>
        <?php echo $this->load("DropDownMenu/js");?>

        <?php if (isset($js_link)): ?>
        <?php foreach ($js_link as $link): ?>
        <script type="text/javascript" src="<?php echo $link?>"></script>
        <?php endforeach;?>
        <?php endif; ?>
        
        <?php echo (isset($javascript))? $javascript:"" ?>

    </head>
    <body>
        <div id="wrapper">
            <div id="header" class="block border_light">
                <?php echo $this->load("FrontPage/header")?>
                <?php echo (isset($header))?$header:""; ?>
            </div>
            <div class="block border_light">
                <?php
                    $menu = new JackAssPHP\Helper\DropDownMenu();
                    $menu->loadDb();
                    echo $menu->getHtml();
                ?>
            </div>
            <div id="content">
                <?php echo $content ?>
            </div>
            <div id="footer">
                <?php echo $this->load("FrontPage/footer"); ?>
                <?php echo (isset($footer))?$footer:"" ?>
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