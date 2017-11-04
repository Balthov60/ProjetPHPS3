<?php

include_once("../classes/SQLServices.php");

$imageName = $_GET['imageName'];
if(isInCart($imageName))
    echo 'true';
else
    echo 'false';

function initSQLService()
{
    include("../includes/variables.inc.php");
    $sqlService = new SQLServices($host, $dbName, $user, $password);
    return $sqlService;
}

function isInCart($imageName)
{
    $sqlService = initSQLService();
    $result = $sqlService->getData('cart', '*', array("where"=>"image_name = '$imageName'"));
    if(empty($result))
        return false;

    return true;
}
?>