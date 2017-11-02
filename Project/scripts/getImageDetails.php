<?php
/**
 * Created by PhpStorm.
 * User: sntri
 * Date: 02/11/2017
 * Time: 14:20
 */

header("Content-Type: text/plain");
include_once("../classes/SQLServices.php");

$imageName = $_GET["imageName"];
$description = getDescription($imageName);
$price = getPrice($imageName);
$requestResult = $description."/".$price;
echo $requestResult;

function initSQLService()
{
    include("../includes/variables.inc.php");
    $sqlService = new SQLServices($host, $dbName, $user, $password);
    return $sqlService;
}


function getDescription($imageName)
{
    $sqlService = initSQLService();
    $result = $sqlService->getData("image", "description", array("where" => "name_image = '$imageName'"));
    $description = $result[0]['description'];
    if($description == "none")
        $description = "-";
    return $description;
}

function getPrice($imageName)
{
    $sqlService = initSQLService();
    $result = $sqlService->getData("image", "price", array("where" => "name_image = '$imageName'"));
    $price = $result[0]['price'];
    return $price;
}
?>