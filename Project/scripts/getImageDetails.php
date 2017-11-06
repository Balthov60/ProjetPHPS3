<?php

session_start();

if (isset($_GET["imageName"])) {
    include_once("../classes/SQLServices.php");
    include_once("../includes/variables.inc.php");
    $sqlService = new SQLServices($host, $dbName, $user, $password);

    $imageName = $_GET["imageName"];
    $description = getDescription($imageName, $sqlService);
    $price = getPrice($imageName, $sqlService);
    if ($_SESSION['user']['isConnected'])
        $status = getStatus($imageName, $sqlService);
    else
        $status = "disconnected";

    if (empty($price) && empty($description)) {
        header("Location: ../../../ProjetPHPS3/Project/index.php");
    }
    else
    {
        echo $description . "/" . $price . "/" . $status;
    }
}
else {
    header("Location: ../../../ProjetPHPS3/Project/index.php");
}

function getDescription($imageName, SQLServices $sqlService)
{
    $result = $sqlService->getData("image", "description", array("where" => "name_image = '$imageName'"));
    if ($result[0]['description'] != "none")
        return $result[0]['description'];
    else
        return '-';
}

function getPrice($imageName, SQLServices $sqlService)
{
    $result = $sqlService->getData("image", "price", array("where" => "name_image = '$imageName'"));
    return $result[0]['price'];
}

/**
 * Return if image is in cart/owned or not
 *
 * @param $imageName
 * @param SQLServices $sqlService
 * @return string cart / owned / normal
 */
function getStatus($imageName, SQLServices $sqlService) {

    if(isAdmin()) {
        return "admin";
    }
    else if(isOwned($imageName, $sqlService)) {
        return "owned";
    }
    else if(isInCart($imageName, $sqlService)) {
        return "cart";
    }
    else {
        return "normal";
    }
}
function isInCart($imageName, SQLServices $sqlService) {
    $result = $sqlService->getData('cart', '*',
        array( "where" => "image_name = '$imageName' && username = '{$_SESSION['user']['username']}'" )
    );
    return (!empty($result));
}
function isOwned($imageName, SQLServices $sqlService) {
    $result = $sqlService->getData('user_image', '*',
        array( "where" => "image_name = '$imageName' && username = '{$_SESSION['user']['username']}'" )
    );
    return (!empty($result));
}

function isAdmin()
{
    return ($_SESSION['user']['isAdmin']);
}