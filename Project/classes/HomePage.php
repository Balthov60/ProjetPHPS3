<?php

include_once("HeaderBar.php");
include_once("AdminPanel.php");
include_once("FooterBar.php");
include_once("ImageHandler.php");
include_once("ModalHandler.php");

class HomePage
{
    private $sqlService;
    private $rowWidth; // width of a row of photos

    const TABLE_JOIN = "image i JOIN image_keyword ik ON i.id_image = ik.id_image";

    function __construct($isConnected = false, $isAdmin = false, SQLServices $sqlService) {
        $this->imageHandler = new ImageHandler($sqlService);
        $this->sqlService = $sqlService;
        $this->rowWidth = 1100;

        new HeaderBar($isConnected, $isAdmin, 'HomePage', $sqlService);
        new ModalHandler();

        echo "<div class=\"container bg-secondary\">";

        if (!isset($_GET["keywords"])) {
            $this->displayAllImages();
        }
        else
        {
            $this->displayImagesMatchingKeywords($_GET["keywords"]);
        }

        echo "</div>";

        new FooterBar();
    }

    /**
     * Generic Method for Images Displaying (Handle Keywords in $_GET)
     */
    private function displayAllImages() {
        $images = $this->sqlService->getData('image', 'name_image');

        if (!is_null($images)) {
            ImageHandler::displayCopyrightedImagesWithAutomaticResizing($images, $this->rowWidth);
        }
    }
    private function displayImagesMatchingKeywords($keywords)
    {
        if(strpos($keywords, " ") !== false) // Check if there are multiple keywords
        {
            $keywords = explode(' ', $keywords);
            $whereClause = $this->createWhereClauseForMultipleKeywords($keywords);
        }
        else
        {
            $whereClause = "ik.keyword_name = '$keywords'";
        }

        $images = $this->sqlService->getData(self::TABLE_JOIN, 'name_image',
            array("where" => $whereClause)
        );
        ImageHandler::displayCopyrightedImagesWithAutomaticResizing($images, $this->rowWidth);
    }

    private function createWhereClauseForMultipleKeywords($keywords) {
        $whereClause = "";
        foreach ($keywords as $keyword) {
            $whereClause .= "OR ik.keyword_name = '$keyword' ";
        }
        return substr($whereClause, 3);
    }

}