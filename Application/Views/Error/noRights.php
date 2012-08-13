<?php
echo header("HTTP/1.0 403 NO ACCESS");
?>

<h1><?php echo $lang->translate("INSUFFICIENT_RIGHTS") ?></h1>
<a href="<?php echo $this->registry->get("WEB_ROOT"); ?>"><?php echo $lang->translate("HOME");?></a>