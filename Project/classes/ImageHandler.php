<?php

// Define padding with css value for dynamic implementation.
include_once($_SERVER['DOCUMENT_ROOT'] . "/ProjetPHPS3/Project/includes/variables.inc.php");
define("PADDING", $padding);
define("ROW_WIDTH", $containerWidth - $containerPadding * 2 - 10); // we remove 10 more to avoid float error.
class ImageHandler
{
    private $sqlService;
    private static $rowWidth = ROW_WIDTH;

    /**
     * ImageHandler constructor.
     *
     * @param SQLServices $sqlService
     */
    function __construct(SQLServices $sqlService)
    {
        $this->sqlService = $sqlService;
    }

    /******************/
    /* Upload Methods */
    /******************/

    /**
     * Upload an image.
     *
     * @param $fileName
     * @param $targetFile
     * @param $imageFileType
     */
    public function uploadImage($fileName, $targetFile, $imageFileType)
    {
        if (!(move_uploaded_file($fileName, $targetFile)))
        {
            header("Location:../index.php?page=panel&error=unableToMoveFile");
        }
        else
        {
            $this->checkDataValidity();
            $this->sqlService->insertData('image',
                array(
                    array(
                        'name_image' => $_FILES['pictureToUpload']["name"],
                        'price' => round($_POST["price"], 2),
                        'description' => htmlspecialchars($_POST["description"])
                    )
                )
            );

            $this->linkKeywordsToImage();
            $this->addCopyright($_FILES['pictureToUpload']['name'], $imageFileType);
            header("Location:../index.php?page=panel&success=true");
        }
    }

    /**
     * Check Data validity and correct it if there is issues.
     */
    private function checkDataValidity() {
        if(empty($_POST['description']))
        {
            $_POST['description'] = 'none';
        }
        if(empty($_POST['price']))
        {
            $_POST['price'] = 0;
        }
        if ($_POST["price"] > 999999999) {
            $_POST["price"] = 999999999;
        }
        else if ($_POST["price"] < 0) {
            $_POST["price"] = 0;
        }
    }

    /**
     * Link Keywords with image and create keyword if not exist
     */
    private function linkKeywordsToImage()
    {
        // Get ImageID & Keywords
        $imageID = $this->sqlService->extractValueFromArray(
            $this->sqlService->getData('image', 'id_image',
                array(
                    "where" => "name_image = '" . $_FILES["pictureToUpload"]["name"] . "'"
                )
            ));
        $tagArray = $_POST['tags'];

        // Link All KeyWord with Image
        foreach ($tagArray as $tag)
        {
            $keyword = $this->sqlService->getData('keyword', 'name_keyword',
                array("where" => "name_keyword = '$tag'")
            );
            $this->linkKeywordToPicture($keyword[0]['name_keyword'], $imageID);
        }
    }

    /**
     * Link keyword to Image in DB.
     *
     * @param $keyword
     * @param $imageID
     */
    private function linkKeywordToPicture($keyword, $imageID) {
        $this->sqlService->insertData('image_keyword',
            array(
                array(
                    'id_image' => $imageID,
                    'keyword_name' => $keyword,
                )
            )
        );
    }

    /**
     * Add copyright to picture.
     *
     * @param $fileName
     * @param $imageFileType
     */
    private function addCopyright($fileName, $imageFileType)
    {
        // Create New Image
        if ($imageFileType == "png")
        {
            $photo = imagecreatefrompng("../library/images/$fileName");
        }
        else
        {
            $photo = imagecreatefromjpeg("../library/images/$fileName");
        }
        $width_photo = imagesx($photo);
        $height_photo = imagesy($photo);
        $color = imagecolorallocate($photo, 255,255,255);

        // Apply Copyright
        imageline($photo,0,0,$width_photo,$height_photo,$color);
        imageline($photo,$width_photo,0,0,$height_photo,$color);

        // Save Image
        if($imageFileType == "png")
            imagepng($photo, "../library/images_copyright/$fileName");
        else
            imagejpeg($photo, "../library/images_copyright/$fileName");

        imagedestroy($photo);
    }

    /**********************************/
    /* Advanced Display Image Methods */
    /**********************************/

    /**
     * Display Image with autoresizing with same height for each row.
     *
     * @param $imagesName
     * @param bool $isCopyrighted
     */
    public static function displayImagesWithAutomaticResizing($imagesName, $isCopyrighted = true) {
        if(sizeof($imagesName) > 0)
        {
            $imageRow = array();
            $totalMinWidth = 0;
            $minY = 200;
            $totalPadding = 0;

            foreach ($imagesName as $imageName)
            {
                $minWidth = self::getMinWidth($imageName[0], $minY);

                if (($totalMinWidth + $minWidth > self::$rowWidth - $totalPadding - 2*PADDING) && $totalMinWidth != 0) {
                    self::displayImageRowWithAutomaticResizing($imageRow, $totalMinWidth, $minY,
                                                        self::$rowWidth - $totalPadding, $isCopyrighted);
                    $totalMinWidth = 0;
                    $totalPadding = 0;
                    $imageRow = array();
                }

                $totalPadding += 2 * PADDING;
                $totalMinWidth += $minWidth;
                array_push($imageRow, $imageName[0]);
            }
            foreach($imageRow as $imageName) {
                if ($isCopyrighted) {
                    self::displayCopyrightedImage($imageName);
                }
                else {
                    self::displayClearImage($imageName);
                }
            }
        }
        else
        {
            echo "<h2 class='text-center text-dark empty-content'>Pas d'image correspondante.</h2>";
        }
    }

    /**
     * Get height and width for each photos on a row and display it.
     * Find the common height of photos and figure out the width associated with each.
     *
     * @param $imageRow array of photos for this row
     * @param $totalMinWidth Integer : Total width of photos with the lowest height resizing
     * @param $minHeight Integer
     * @param $rowWidth Integer
     * @param bool $isCopyrighted
     */
    private static function displayImageRowWithAutomaticResizing($imageRow, $totalMinWidth, $minHeight,
                                                                 $rowWidth, $isCopyrighted) {
        $finalResizingRatio = $rowWidth / $totalMinWidth;
        $finalHeight = $minHeight * $finalResizingRatio;

        foreach ($imageRow as $image) { // Get width for each photo and display it
            list($width, $height) = self::getimagesize($image);
            $finalWidth = $width / $height * $minHeight * $finalResizingRatio;

            if ($isCopyrighted)
            {
                ImageHandler::displayCopyrightedImageWithSize($image, $finalHeight, $finalWidth);
            }
            else {
                ImageHandler::displayClearImageWithSize($image, $finalHeight, $finalWidth);
            }
        }
    }

    /*************************/
    /* Basic Display Methods */
    /*************************/

    /**
     * display copyrighted image.
     *
     * @param $imageName
     */
    public static function displayCopyrightedImage($imageName) {
        echo "<img src=\"../../../ProjetPHPS3/Project/library/images_copyright/$imageName\" 
                           alt=\"$imageName\" id=\"$imageName._copyrighted-image\" class=\"basic-image-display\">";
    }

    /**
     * display clear image.
     *
     * @param $imageName
     */
    public static function displayClearImage($imageName) {
        echo "<img src=\"../../../ProjetPHPS3/Project/library/images/$imageName\" 
                           alt=\"$imageName\" id=\"$imageName._image\" class=\"basic-image-display\">";
    }

    /**
     * display copyrighted image for automatic resizing with height and width defined.
     *
     * @param $imageName
     * @param $height
     * @param $width
     */
    public static function displayCopyrightedImageWithSize($imageName, $height, $width) {
        echo "<img src=\"../../../ProjetPHPS3/Project/library/images_copyright/$imageName\" 
                           alt=\"$imageName\" id=\"$imageName._copyrighted-image\" class=\"image-display\"
                           style=\"width:{$width}px;height:{$height}px\">";
    }

    /**
     * display clear image for automatic resizing with height and width defined.
     *
     * @param $imageName
     * @param $height
     * @param $width
     */
    public static function displayClearImageWithSize($imageName, $height, $width) {
        echo "<img src=\"../../../ProjetPHPS3/Project/library/images/$imageName\" 
                           alt=\"$imageName\" id=\"$imageName._image\" class=\"image-display\"
                           style=\"width:{$width}px;height:{$height}px\">";
    }

    /*********************/
    /* Utilities Methods */
    /*********************/

    /**
     * Get minimum width for an image to respect ratio with minimum height.
     *
     * @param $imageName
     * @param $minHeight
     * @return int
     */
    private static function getMinWidth($imageName, $minHeight) {
        list($width, $height) = self::getimagesize($imageName);
        $ratio = $width / $height;
        return $minHeight * $ratio;
    }

    /**
     * Get image dimension.
     *
     * @param $imageName
     * @return array|bool
     */
    private static function getImageSize($imageName) {
        return getimagesize($_SERVER['DOCUMENT_ROOT'] .
            "/ProjetPHPS3/Project/library/images_copyright/$imageName");
    }
    
}