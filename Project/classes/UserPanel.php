<?php
include_once("HeaderBar.php");
include_once("AdminPanel.php");
include_once("FooterBar.php");
include_once("ImageHandler.php");
include_once("ModalHandler.php");

class UserPanel
{
    private $sqlService;
    private static $tableJoin = "user_image ui JOIN image i ON ui.image_name = i.name_image";

    function __construct(SQLServices $sqlService)
    {
        $this->sqlService = $sqlService;

        new HeaderBar(true, false, "Panel", $sqlService);
        new ModalHandler();

        $this->displayUserPhotos();

        new FooterBar();
    }

    private function displayUserPhotos() {
        $images = $this->sqlService->getData(self::$tableJoin, "ui.image_name",
            array(
                "where" => "username = '" . $_SESSION["user"]["username"] . "'"
            )
        );

        if (!empty($images))
        {
            echo "<div class=\"container bg-secondary images-container\">";
            ImageHandler::displayImagesWithAutomaticResizing($images, false);
            echo "</div>";
        }
        else
        {
            echo "<h2 class='text-center text-dark empty-content'>Vous n'avez pas encore acheté de photos.</h2>";
        }
    }

}