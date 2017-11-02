<?php
class ImageHandler
{
    private $sqlService;

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
            $keywordID = $this->sqlService->getData('keyword', 'id_keyword',
                array("where" => "name_keyword = '$keyword'")
            );
            // Create keyword if not exist
            if(empty($keywordID))
            {
                $this->addNewKeyword($keyword);
                $keywordID = $this->sqlService->getData('keyword', 'id_keyword',
                    array("where" => "name_keyword = '$keyword'")
                );
            }
            $keywordID = $this->sqlService->extractValueFromArray($keywordID);
            $this->linkKeywordToPicture($keywordID, $imageID);
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
    private function linkKeywordToPicture($keywordID, $imageID) {
        $this->sqlService->insertData('image_keyword',
            array(
                array(
                    'id_image' => $imageID,
                    'id_keyword' => $keywordID,
                )
            )
        );
    }

    function addCopyright($fileName, $imageFileType)
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

    function displayImageWithKeyword($idKeywords = null)
    {
        if(!empty($idKeywords))
        {
            $tableJoin = "image i JOIN image_keyword ik ON i.id_image = ik.id_image
                            JOIN keyword k ON ik.id_keyword = k.id_keyword";
            $idKeywords = substr($idKeywords, 1 ); //Delete the first ','
            $keywordsArray = explode(',', $idKeywords); //Delete the ',' between each id_keywords and stock them in array
            if(sizeof($keywordsArray) > 1) //If there are several keywords given in parameters
            {
                $cptKeywords = sizeof($keywordsArray);
                $whereClause = "";
                foreach ($keywordsArray as $key => $idKeyword)
                {
                    if($cptKeywords<1 || $cptKeywords == sizeof($keywordsArray))
                        $whereClause .= "ik.id_keyword = $idKeyword ";
                    else
                        $whereClause .= "OR ik.id_keyword = $idKeyword ";
                    $cptKeywords-- ;
                }
                $optionsArray = ["where" => $whereClause];
                $imagesName = $this->sqlService->getData($tableJoin, 'distinct name_image', $optionsArray);
                if(is_array($imagesName))
                {
                    foreach ($imagesName as $key => $line)
                    {
                        echo "<img src=\"library/images_copyright/$line[0]\" alt=\"$line[0]\" id=\"$line[0]._image\" >";
                    }
                }
            }
            else //If there is only one keyword given in parameters
            {
                $optionsArray = ["where" => "ik.id_keyword = $idKeywords"];
                $imagesName = $this->sqlService->getData($tableJoin, 'name_image', $optionsArray);
                if(is_array($imagesName)) //If there are several images returned by the query
                {
                    foreach ($imagesName as $key => $line)
                    {
                        echo "<img src=\"library/images_copyright/$line[0]\" alt=\"$line[0]\" id=\"$line[0]._image\" >";
                    }
                }
                else
                {
                    echo "<img src=\"library/images_copyright/$imagesName\" alt=\"$imagesName\" id=\"$imagesName._image\" >";
                }
            }
        }
        else //If no keywords in parameters
        {
            $imageName = $this->sqlService->getData('image', 'name_image');
            if (!is_null($imageName)) {
                foreach ($imageName as $key => $line) {
                    echo "<img class=\"image-display\" src=\"../../../ProjetPHPS3/Project/library/images_copyright/$line[0]\" 
                               alt=\"$line[0]\" id=\"$line[0]._image\" >";
                }
            }
        }
    }
    function displayCheckbox()
    {
        $checkBoxesName = $this->sqlService->getData('keyword', 'id_keyword, name_keyword');
        if(!is_null($checkBoxesName))
        {
            foreach ($checkBoxesName as $key => $line)
            {
                foreach ($line as $column => $value_column)
                {
                    if($column == 'id_keyword')
                        $id = $value_column;
                    else
                        $keyword = $value_column;
                }
                echo "<div class=\"p-2 checkbox-label-container\" ><input class=\"checkbox\" type=\"checkbox\" name=" . "\"$id" . "_checkbox\"><label class=\"checkbox-label\" for=" . "\"$id" . "_checkbox\" >$keyword</label></div>";
            }
        }
    }

    function deleteArrayOfImage($listOfSelectedImage)
    {
        foreach ($listOfSelectedImage as $key => $imageSelected)
        {
            $idStringLength = stripos($imageSelected, '._image'); //Find the 'id' string position in the image name
            $imageSelected = substr($imageSelected, 0,$idStringLength); //Delete the 'id' attribute string from the image name
            $this->sqlService->removeData('image',"name_image = '$imageSelected'", 1);
            unlink ("../library/images_copyright/$imageSelected");
            unlink("../library/images/$imageSelected");
        }
    }

    function keywordsSelected()
    {
        $checkBoxesName = $this->sqlService->getData('keyword', 'id_keyword');
        $keywordsSelected = "";
        foreach ($checkBoxesName as $key => $line)
        {
            foreach ($line as $column => $id_checkBox)
            {
                if(isset($_POST["$id_checkBox"."_checkbox"]))
                    $keywordsSelected .= ",$id_checkBox";
            }
        }
        $keywordsSelected = substr($keywordsSelected,1);
        $keywordsSelected = explode(',', $keywordsSelected);
        return $keywordsSelected;
    }

    function deleteKeywordAssociatedToImage($arrayImageToDelete)
    {
        foreach ($arrayImageToDelete as $key => $imageSelected)
        {
            $idStringLength = stripos($imageSelected, '._image'); //Find the 'id' string position in the image name
            $imageSelected = substr($imageSelected, 0,$idStringLength); //Delete the 'id' attribute string from the image name
            $idImage = $this->sqlService->getData('image', 'id_image', array("where" =>"name_image = '$imageSelected'"));
            $idImage = $this->sqlService->extractValueFromArray($idImage);
            $this->sqlService->removeData('image_keyword',"id_image = '$idImage' ", 1000);
        }
    }


}