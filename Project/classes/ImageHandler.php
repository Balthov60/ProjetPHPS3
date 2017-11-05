<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/ProjetPHPS3/Project/includes/variables.inc.php");
define("PADDING", $padding);

class ImageHandler
{
    private $sqlService;
    private static $rowWidth = 1100;

    function __construct(SQLServices $sqlService)
    {
        $this->sqlService = $sqlService;
    }

    /* Upload Methods */

    public function uploadImage($fileName, $targetFile, $imageFileType)
    {
        if (!(move_uploaded_file($fileName, $targetFile)))
        {
            header("Location:../index.php?error=unableToMoveFile");
        }
        else
        {
            $this->checkDataValidity();
            $this->sqlService->insertData('image',
                array(
                    array(
                        'name_image' => $_FILES['pictureToUpload']["name"],
                        'extension' => $imageFileType,
                        'price' => $_POST["price"],
                        'description' => htmlspecialchars($_POST["description"])
                    )
                )
            );

            $this->linkKeywordToImage();
            $this->addCopyright($_FILES['pictureToUpload']['name'], $imageFileType);
            header("Location:../index?page=panel.php");
        }
    }
    //TODO: integer too big or description to long handling
    private function checkDataValidity() {
        if(empty($_POST['description']))
        {
            $_POST['description'] = 'none';
        }
        if(empty($_POST['price']) || !is_integer($_POST['price']))
        {
            $_POST['price'] = 0;
        }
    }

    /**
     * Link Keywords with image and create keyword if not exist
     */
    private function linkKeywordToImage()
    {
        // Get ImageID & Keywords
        $imageID = $this->sqlService->extractValueFromArray(
            $this->sqlService->getData('image', 'id_image',
                array(
                    "where" => "name_image = '" . $_FILES["pictureToUpload"]["name"] . "'"
                )
        ));
        $keywordArray = $this->getArrayOfKeywordFromString($_POST['keyword_input']);

        // Link All KeyWord with Image
        foreach ($keywordArray as $keyword)
        {
            $keywordExist = $this->sqlService->getData('keyword', 'name_keyword',
                array("where" => "name_keyword = '$keyword'")
            );
            // Create keyword if not exist
            if(empty($keywordExist))
            {
                $this->addNewKeyword($keyword);
            }
            $this->linkKeywordToPicture($keyword, $imageID);
        }
    }
    private function getArrayOfKeywordFromString($string) {
        $keywordArray = $string;
        $keywordArray = preg_replace('/\s+/', '', $keywordArray);
        $keywordArray = explode(",", $keywordArray);
        $keywordArray = array_unique($keywordArray);

        return $keywordArray;
    }
    private function addNewKeyword($keyword) {
        $this->sqlService->insertData('keyword',
            array(
                array(
                'name_keyword' => $keyword,
                )
            )
        );
    }
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

    /* Advanced Display Image Methods */

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

    /* Basic Display Methods */

    public static function displayCopyrightedImage($imageName) {
        echo "<img src=\"../../../ProjetPHPS3/Project/library/images_copyright/$imageName\" 
                           alt=\"$imageName\" id=\"$imageName._copyrighted-image\" class=\"basic-image-display\">";
    }
    public static function displayClearImage($imageName) {
        echo "<img src=\"../../../ProjetPHPS3/Project/library/images/$imageName\" 
                           alt=\"$imageName\" id=\"$imageName._image\" class=\"basic-image-display\">";
    }
    public static function displayCopyrightedImageWithSize($imageName, $height, $width) {
        echo "<img src=\"../../../ProjetPHPS3/Project/library/images_copyright/$imageName\" 
                           alt=\"$imageName\" id=\"$imageName._copyrighted-image\" class=\"image-display\"
                           style=\"width:{$width}px;height:{$height}px\">";
    }
    public static function displayClearImageWithSize($imageName, $height, $width) {
        echo "<img src=\"../../../ProjetPHPS3/Project/library/images/$imageName\" 
                           alt=\"$imageName\" id=\"$imageName._image\" class=\"image-display\"
                           style=\"width:{$width}px;height:{$height}px\">";
    }

    /* Utilities Methods */

    private static function getMinWidth($imageName, $minHeight) {
        list($width, $height) = self::getimagesize($imageName);
        $ratio = $width / $height;
        return $minHeight * $ratio;
    }
    private static function getImageSize($imageName) {
        return getimagesize($_SERVER['DOCUMENT_ROOT'] .
            "/ProjetPHPS3/Project/library/images_copyright/$imageName");
    }
    
}