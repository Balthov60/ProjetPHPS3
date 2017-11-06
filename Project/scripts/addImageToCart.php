<?php
session_start();
include_once("extractImageNameFromImageID.php");

if (isset($_GET['imageID']) && isset($_SESSION['user'])) // if $_SESSION['user'] is not empty then ['username']['isConnected']['isAdmin] are not too.
{
    include_once("../classes/SQLServices.php");
    include("../includes/variables.inc.php");
    $sqlService = new SQLServices($host, $dbName, $user, $password);

    $imageName = extractImageNameFrom($_GET['imageID']);
    $username = $_SESSION['user']['username'];

    // Insert a new entry in cart for user if not exist already.
    if (!$sqlService->cartEntryExist($imageName, $username)) {
        $sqlService->insertData('cart', array(array("username" => $username, "image_name" => $imageName)));
    }
}
else
{
    header("../../../ProjetPHPS3/Project/index.php");
}