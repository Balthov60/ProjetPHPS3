<?php
session_start();
    include_once("../classes/SQLServices.php");
    include('../includes/variables.inc.php');
    $sqlService = new SQLServices($host, $dbName, $user, $password);

    $username = $_SESSION['user']['username'];

    if(is_null($username))
        header('Location:../index.php');

    $cartData = $sqlService->getData('cart', '*', array("where" => "username = '$username'"));
    var_dump($cartData);
    insertCartDataIntoUserImage($cartData);
    $sqlService->removeData('cart',"username = '$username'");
    header('Location: ../index.php');

    function insertCartDataIntoUserImage($cartData)
    {
        $host = "localhost";
        $user = "root";
        $password = '';
        $dbName = "projetphps3";

        $sqlService = new SQLServices($host, $dbName, $user, $password);

        foreach ($cartData as $image)
        {
            $sqlService->insertData('user_image', array(array("username" => $_SESSION['user']['username'], "image_name" => $image['image_name'])));
        }
    }
?>