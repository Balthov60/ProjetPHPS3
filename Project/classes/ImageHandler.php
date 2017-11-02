<?php
class ImageHandler
{
    private $sqlService;
    // TODO: Make it constant
    private static $tableJoin = "image i JOIN image_keyword ik ON i.id_image = ik.id_image";

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

    /* Display Image Methods */

    public function displayImages($keywords = null) {
        if (empty($keywords)) {
            $this->displayAllImages();
        }
        else
        {
            $this->displayImagesWithKeywords($keywords);
        }
    }

    private function displayAllImages() {
        $images = $this->sqlService->getData('image', 'name_image');
        if (!is_null($images)) {
            foreach ($images as $key => $value) {
                $this->displayCopyrightedImage($value[0]);
            }
        }
    }
    private function displayImagesWithKeywords($keywords)
    {
        if(strpos($keywords, " ") !== false) // Check if there are multiple keywords
        {
            $keywordsArray = explode(' ', $keywords);
            $this->displayImagesWithMultipleKeywords($keywordsArray);
        }
        else
        {
            $this->displayImagesWithSingleKeyword($keywords);
        }
    }

    private function displayImagesWithSingleKeyword($keyword) {
        $imagesName = $this->sqlService->getData(self::$tableJoin, 'name_image',
            array("where" => "ik.keyword_name = '$keyword'")
        );
        $this->displayCopyrightedImages($imagesName);
    }
    private function displayImagesWithMultipleKeywords($keywords) {
        $whereClause = "";
        foreach ($keywords as $keyword) {
            $whereClause .= "OR ik.keyword_name = '$keyword' ";
        }
        $whereClause = substr($whereClause, 3);

        $imagesName = $this->sqlService->getData(self::$tableJoin, 'distinct name_image',
            array("where" => $whereClause)
        );
        $this->displayCopyrightedImages($imagesName);
    }

    public static function displayCopyrightedImages($imagesName) {
        if(sizeof($imagesName) > 0)
        {
            foreach ($imagesName as $key => $value)
            {
                self::displayCopyrightedImage($value[0]);
            }
        }
        else
        {
            echo "Pas d'image correspondante.";
        }
    }
    public static function displayCopyrightedImage($imageName) {
        echo "<img src=\"../../../ProjetPHPS3/Project/library/images_copyright/$imageName\" 
                           alt=\"$imageName\" id=\"$imageName._image\" class=\"image-display\">";
    }

    public static function displayClearImage($imageName) {
        echo "<img src=\"../../../ProjetPHPS3/Project/library/images/$imageName\" 
                           alt=\"$imageName\" id=\"$imageName._image\" class=\"image-display\">";
    }
    
}