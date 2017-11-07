<?php
include_once("HeaderBar.php");
include_once("AdminPanel.php");
include_once("FooterBar.php");
include_once("ImageHandler.php");
include_once("ModalHandler.php");

class UserPanel
{
    private $sqlService;

    /**
     * UserPanel constructor.
     *
     * @param SQLServices $sqlService
     */
    function __construct(SQLServices $sqlService)
    {
        $this->sqlService = $sqlService;

        new HeaderBar(true, false, "Panel", $sqlService);
        ModalHandler::displayDetailsModal();

        $this->displayUserImages();

        new FooterBar();
    }

    /**
     * Display Images that the user bought.
     */
    private function displayUserImages() {
        $images = $this->sqlService->getData("user_image ui JOIN image i ON ui.image_name = i.name_image",
                                             "ui.image_name",
            array(
                "where" => "username = '" . $_SESSION["user"]["username"] . "'"
            )
        );

        if (!empty($images))
        {
            echo "<div class='my-container bg-secondary main-page-content'>";
            ImageHandler::displayImagesWithAutomaticResizing($images, false);
            echo "</div>";
        }
        else
        {
            echo "<h2 class='text-center text-dark empty-content'>Vous n'avez pas encore acheté de photos.</h2>";
        }
    }

}