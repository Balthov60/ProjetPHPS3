<?php

include_once("../classes/SQLServices.php");
include_once("../classes/CartPanel.php");

include("../includes/variables.inc.php");
$sqlService = new SQLServices($host, $dbName, $user, $password);

$imageName = getImageNameFrom($_GET['buttonID']);


$sqlService->removeData('cart', "image_name = '$imageName'");

header("Location: ../index.php?page=cart");

function getImageNameFrom($buttonID)
{
    $removeID = "remove-";
    $removeIDlength = strlen($removeID);
    $imageName = substr($buttonID, $removeIDlength);
    return $imageName;
}

?>