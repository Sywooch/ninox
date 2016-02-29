<?php
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false); // required for certain browsers
header("Content-Transfer-Encoding: binary");
//header("Content-Disposition: attachment; filename=\"" . ($_GET['category'] ? 'category-'.$_GET['category'].'.xml' : 'ymlexport.xml') . "\"");
header('Content-Type: text/xml');
?>
<?='<?xml version="1.0" encoding="UTF-8"?>'?><!DOCTYPE yml_catalog SYSTEM "shops.dtd">
<yml_catalog date="<?=date('Y-m-d H:i')?>">
    <shop>
    <?php echo $content; ?>

    </shop>
</yml_catalog>