<?php

include_once("HeaderBar.php");
include_once("AdminPanel.php");
include_once("FooterBar.php");
include_once("ImageHandler.php");
class HomePage
{

    function __construct($isConnected = false, $isAdmin = false, $sqlService) {
        new HeaderBar($isConnected, $isAdmin, 'HomePage');

        echo "<div class=\"container bg-secondary images-container\" style=\"height: 1000px\">";
        $imageHandler = new ImageHandler($sqlService);
        $imageHandler->displayImages();
        echo "</div>";

        new FooterBar();
    }

}