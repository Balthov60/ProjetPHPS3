<?php
/**
 * Created by PhpStorm.
 * User: sntri
 * Date: 02/11/2017
 * Time: 19:20
 */
session_start();
include_once("../classes/SQLServices.php");


$imageID = $_GET['imageID'];
$imageName = giveRealNameOf($imageID);
$username = $_SESSION['user']['username'];

$sqlService = initSQLService();
$sqlService->insertData('cart', array(array("username" => $username, "image_name" => $imageName)));


/**
 * @param $imageID
 * @return bool|string
 */
function giveRealNameOf($imageID)
{
    $IDpos = strpos($imageID,'._image');
    $imageName = substr($imageID, 0, $IDpos);
    return $imageName;
}


function initSQLService()
{
    include("../includes/variables.inc.php");
    $sqlService = new SQLServices($host, $dbName, $user, $password);
    return $sqlService;
}
?>