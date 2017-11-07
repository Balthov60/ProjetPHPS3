<?php
header("Content-type: text/css; charset: UTF-8");
include_once ("../../../ProjetPHPS3/Project/includes/variables.inc.php");
?>

.image-display
{
    margin: <?php echo $padding; ?>px;
}

.basic-image-display
{
    width: auto;
    height: 200px;

    margin: <?php echo $padding; ?>px;
}

.my-container
{
    width: <?php echo $containerWidth; ?>px;
    margin-right: auto;
    margin-left: auto;
    padding-right: <?php echo $containerPadding; ?>px;
    padding-left: <?php echo $containerPadding; ?>px;
}