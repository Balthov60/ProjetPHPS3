<?php

session_start();

if (isset($_GET["imageName"]) && isset($_SESSION['user'])) // if $_SESSION['user'] is not empty then ['username']['isConnected']['isAdmin] are not too.
{
    include_once("../classes/SQLServices.php");
    include_once("../includes/variables.inc.php");
    $sqlService = new SQLServices($host, $dbName, $user, $password);

    $imageName = $_GET["imageName"];
    $description = getDescription($imageName, $sqlService);
    $price = getPrice($imageName, $sqlService);
    $imageNameWithoutExtension = $sqlService->removeExtensionFromImageName($imageName);

    if ($_SESSION['user']['isConnected'])
    {
        $status = getStatus($imageName, $sqlService);
    }
    else
    {
        $status = "disconnected";
    }

    if (empty($price) && empty($description))
    {
        header("Location: ../../../ProjetPHPS3/Project/index.php");
    }
    else
    {
        echo $imageNameWithoutExtension . "/" . $description . "/" . $price . "/" . $status;
    }
}
else
{
    header("Location: ../../../ProjetPHPS3/Project/index.php");
}

/**
 * Get description for an image.
 *
 * @param $imageName
 * @param SQLServices $sqlService
 * @return string
 */
function getDescription($imageName, SQLServices $sqlService)
{
    $result = $sqlService->getData("image", "description", array("where" => "image_name = '$imageName'"));
    if ($result[0]['description'] != "none")
        return $result[0]['description'];
    else
        return '-';
}

/**
 * Get Price for an image.
 *
 * @param $imageName
 * @param SQLServices $sqlService
 * @return mixed
 */
function getPrice($imageName, SQLServices $sqlService)
{
    $result = $sqlService->getData("image", "price", array("where" => "image_name = '$imageName'"));
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

/**
 * Check if image is in cart.
 *
 * @param $imageName
 * @param SQLServices $sqlService
 * @return bool
 */
function isInCart($imageName, SQLServices $sqlService) {
    $result = $sqlService->getData('cart', '*',
        array( "where" => "image_name = '$imageName' && username = '{$_SESSION['user']['username']}'" )
    );
    return (!empty($result));
}

/**
 * Check if picture is owned.
 *
 * @param $imageName
 * @param SQLServices $sqlService
 * @return bool
 */
function isOwned($imageName, SQLServices $sqlService) {
    $result = $sqlService->getData('user_image', '*',
        array( "where" => "image_name = '$imageName' && username = '{$_SESSION['user']['username']}'" )
    );
    return (!empty($result));
}

/**
 * Check if user is an Admin
 *
 * @return boolean
 */
function isAdmin()
{
    return ($_SESSION['user']['isAdmin']);
}