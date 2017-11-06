<?php
include('../classes/SQLServices.php');
include('../classes/ImageHandler.php');
include('../includes/variables.inc.php');

$targetDirectory = "../../../ProjetPHPS3/Project/library/images/";
$targetFile = $targetDirectory . basename($_FILES['pictureToUpload']["name"]);
$imageFileType = pathinfo($targetFile,PATHINFO_EXTENSION);

/* Prevent access trough URL */

if (!isset($_POST["submit"]))
    header("Location:../index.php?error=emptyForm");

/* Test Values */

if (!is_float($_POST["price"]))
{
    header("Location:../index.php?error=invalidPrice");
}
if (strlen(htmlspecialchars($_POST['description'])) > 256)
{
    header("Location:../index.php?error=descriptionTooLong");
}

/* Test if image is valid */

if(!isValidImage())
{
    header("Location:../index.php?error=invalidImage");
}
else if(!fileAlreadyExist($targetFile))
{
    header("Location:../index.php?error=fileAlreadyExist");
}
else if(imageTooBig())
{
    header('Location:../index.php?error=imageTooBig');
}
else if(!validExtension($imageFileType)) // Allow only JPG, JPEG & PNG
{
    header('Location:../index.php?error=invalidExtension');
}
else
{
    $sqlService = new SQLServices($host,$dbName, $user, $password);
    uploadImage($sqlService, $targetFile, $imageFileType);
}

/* Image Validation Function */

function isValidImage()
{
    if(getimagesize($_FILES["pictureToUpload"]["tmp_name"]) == false)
        return false;

    return true;
}
function fileAlreadyExist($targetFile)
{
    if (file_exists($targetFile))
        return false;

    return true;
}
function imageTooBig()
{
    if ($_FILES["pictureToUpload"]["size"] > 5000000)
        return true;

    return false;
}
function validExtension($imageFileType)
{
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg")
        return false;

    return true;
}

/* Upload */

function uploadImage($sqlService, $targetFile, $imageFileType)
{
    $imageHandler = new ImageHandler($sqlService);
    $imageHandler->uploadImage($_FILES['pictureToUpload']["tmp_name"], $targetFile, $imageFileType);
}
