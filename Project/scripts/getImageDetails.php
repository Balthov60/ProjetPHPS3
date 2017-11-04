<?php

if (isset($_GET["imageName"])) {
    include_once("../classes/SQLServices.php");
    include_once("../includes/variables.inc.php");
    $sqlService = new SQLServices($host, $dbName, $user, $password);

    $imageName = $_GET["imageName"];
    $description = getDescription($imageName, $sqlService);
    $price = getPrice($imageName, $sqlService);
    if (empty($price) && empty($description)) {
        header("location: ../../../ProjetPHPS3/Project/index.php");
    }
    else {
        echo $description . "/" . $price;
    }
}
else {
    header("location: ../../../ProjetPHPS3/Project/index.php");
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