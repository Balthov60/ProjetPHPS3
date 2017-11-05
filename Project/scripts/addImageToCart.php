<?php
session_start();

if (isset($_GET['imageID'])) {
    include_once("../classes/SQLServices.php");
    include("../includes/variables.inc.php");
    $sqlService = new SQLServices($host, $dbName, $user, $password);

    $imageID = $_GET['imageID'];
    $imageName = extractImageNameFrom($imageID);
    $username = $_SESSION['user']['username'];
    
    $sqlService->insertData('cart', array(array("username" => $username, "image_name" => $imageName)));
}
else {
    header("../../../ProjetPHPS3/Project/index.php");
}


/**
 * @param $imageID
 * @return bool|string
 */
function extractImageNameFrom($imageID)
{
    $idPos = strpos($imageID,'._copyrighted-image');
    if ($idPos == false) {
        $idPos = strpos($imageID,'._image');
    }
    return substr($imageID, 0, $idPos);
}