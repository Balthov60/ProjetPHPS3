<?php
include_once("HeaderBar.php");
include_once("AdminPanel.php");
include_once("FooterBar.php");
include_once("ImageHandler.php");

class UserPanel
{
    private $sqlService;
    private static $tableJoin = "user_image ui JOIN image i ON ui.id_image = i.id_image";

    function __construct(SQLServices $sqlService)
    {
        $this->sqlService = $sqlService;

        new HeaderBar(true, false, "Panel");

        $this->displayUserPhotos();

        new FooterBar();
    }

    private function displayUserPhotos() {
        $result = $this->sqlService->getData(self::$tableJoin, "i.name_image",
            array(
                "where" => "username = '" . $_SESSION["user"]["username"] . "'"
            )
        );

        echo "<div class=\"container bg-secondary images-container\">";
        foreach($result as $image) {
            ImageHandler::displayClearImage($image[0]);
        }
        echo "</div>";
    }

}