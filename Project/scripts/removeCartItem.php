<?php
session_start();

if (isset($_GET['buttonID']) && isset($_SESSION['user'])) // if $_SESSION['user'] is not empty then ['username']['isConnected']['isAdmin] are not too.
{
    include_once("../includes/variables.inc.php");
    include_once("../classes/SQLServices.php");
    $sqlService = new SQLServices($host, $dbName, $user, $password);

    $imageName = getImageNameFrom($_GET['buttonID']);
    $username = $_SESSION['user']['username'];
    $sqlService->removeData('cart',"image_name = '$imageName' AND username = '$username'");
}
header("Location: ../index.php?page=cart");

/**
 * get Image Name associated with a button ID.
 *
 * @param $buttonID
 * @return string
 */
function getImageNameFrom($buttonID)
{
    return substr($buttonID, strlen("remove-"));
}