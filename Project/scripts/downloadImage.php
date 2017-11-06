<?php

session_start();
include_once("extractImageNameFromImageID.php");

if(isset($_GET["imageID"]) && isset($_SESSION['user'])) // if $_SESSION['user'] is not empty then ['username']['isConnected']['isAdmin] are not too.
{
    $imageName = extractImageNameFrom($_GET["imageID"]);

    include_once("../../../ProjetPHPS3/Project/includes/variables.inc.php");
    include_once("../../../ProjetPHPS3/Project/classes/SQLServices.php");
    $sqlService = new SQLServices($host, $dbName, $user, $password);

    $result = $sqlService->getData("user_image", "username",
        array("where" => "image_name = '$imageName' AND username = '{$_SESSION['user']['username']}'")
    );
    if (empty($result)) {
        header("Location: ../../../ProjetPHPS3/Project/index.php");
    }
    else {
        $file = '../../../ProjetPHPS3/Project/library/images/' . $imageName;
        if(file_exists($file))
        {
            header('Content-Type: application/force-download');
            header("Content-Transfer-Encoding: binary");
            header('Content-Disposition: attachment; filename='.basename($file));
            header('Pragma: no-cache');
            header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
            header('Expires: 0');
            readfile($file);
        }
    }
}
else
{
    header("Location: ../../../ProjetPHPS3/Project/index.php");
}