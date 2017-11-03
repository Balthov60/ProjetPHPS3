<?php

include_once("HeaderBar.php");
include_once("AdminPanel.php");
include_once("FooterBar.php");
include_once("ImageHandler.php");

class HomePage
{
    private $sqlService;

    const TABLE_JOIN = "image i JOIN image_keyword ik ON i.id_image = ik.id_image";

    function __construct($isConnected = false, $isAdmin = false, SQLServices $sqlService) {
        $this->imageHandler = new ImageHandler($sqlService);
        $this->sqlService = $sqlService;

        new HeaderBar($isConnected, $isAdmin, 'HomePage', $sqlService);

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
            $this->displayCopyrightedImages($images);
            /* foreach ($images as $key => $value) {
                $this->imageHandler->displayCopyrightedImage($value[0]);
            } */
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

        $imagesName = $this->sqlService->getData(self::TABLE_JOIN, 'name_image',
            array("where" => $whereClause)
        );
        $this->displayCopyrightedImages($imagesName);
    }

    private function createWhereClauseForMultipleKeywords($keywords) {
        $whereClause = "";
        foreach ($keywords as $keyword) {
            $whereClause .= "OR ik.keyword_name = '$keyword' ";
        }
        return substr($whereClause, 3);
    }

    private function displayCopyrightedImages($imagesName) {
        if(sizeof($imagesName) > 0)
        {
            $currentX = 0;
            $minY = 150;
            $row = array();
            foreach ($imagesName as $imageName)
            {
                list($width, $height) = getimagesize($_SERVER['DOCUMENT_ROOT'] .
                    "/ProjetPHPS3/Project/library/images_copyright/$imageName[0]");
                $ratio = $width / $height;
                $weight = $minY * $ratio;

                $totalWidth = 0;
                if ($currentX + $weight > 1110) {

                   $finalRatio = 1110 / $currentX;
                   $finalHeight = $minY * $finalRatio;

                   foreach ($row as $image) {
                       list($width, $height) = getimagesize($_SERVER['DOCUMENT_ROOT'] .
                           "/ProjetPHPS3/Project/library/images_copyright/$image");
                       $finalWidth = $width / $height * $minY * $finalRatio;
                       $totalWidth += $finalWidth;

                       ImageHandler::displayCopyrightedImageWithSize($image, $finalHeight, $finalWidth);
                   }
                   $currentX = 0;
                   $row = array();
                }
                $currentX += $weight;
                array_push($row, $imageName[0]);


                // ImageHandler::displayCopyrightedImage($imageName[0]);
            }
        }
        else
        {
            echo "Pas d'image correspondante.";
        }
    }

}