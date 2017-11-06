<?php
session_start();

if (isset($_GET['imageID']))
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

/**
 * Get image name associated to his ID.
 *
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