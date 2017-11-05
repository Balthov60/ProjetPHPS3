<?php
session_start();

include_once("../classes/SQLServices.php");
include('../includes/variables.inc.php');
$sqlService = new SQLServices($host, $dbName, $user, $password);

$username = $_SESSION['user']['username'];

$cartData = $sqlService->getData('cart', '*', array("where" => "username = '$username'"));
insertCartDataIntoUserImage($cartData, $sqlService);
$sqlService->removeData('cart',"username = '$username'");

header('Location: ../index.php?page=cart');

function insertCartDataIntoUserImage($cartData, SQLServices $sqlService)
{
    foreach ($cartData as $image)
    {
        $sqlService->insertData('user_image', array(array("username" => $_SESSION['user']['username'], "image_name" => $image['image_name'])));
    }
}
?>