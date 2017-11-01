<?php
/**
 * Created by PhpStorm.
 * User: sntri
 * Date: 01/11/2017
 * Time: 16:57
 */

class AdminPanel
{
    private $sqlService;
    function __construct()
    {
        $hostnameDB = "localhost";
        $userDB = "root";
        $passwordDB = '';
        $dbName = "projetphps3";

        $this->sqlService = new SQLServices($hostnameDB,$dbName,$userDB, $passwordDB);

        displayUploadForm();
        displayModifForm();
    }

    function displayUploadForm()
    {
        echo "<div id='upload-form-container'>
                <form action=\"../scripts/uploadImage.php\" method=\"post\" enctype=\"multipart/form-data\">
                    <input type=\"file\" class=\"form-control col-xs-2\" name=\"pictureToUpload\" value=\"Choose Image..\">
                    <div class=\"modal-checkbox-container\">
                        <input type=\"text\" onkeyup=\"filterKeyword()\" name=\"keyword_input\" id=\"keyword-search\" placeholder=\"Keyword1,Keyword2,Keyword3..\">
                        <!--  Search List Keyword -->
                        <ul id=\"keywordList\">";
            $this->sqlService->displayKeywordList();
            echo        "</ul>
                    </div>
                    <label for=\"description\">Description</label>
                    <textarea class=\"form-control\" rows=\"4\" name=\"description\"></textarea>
                    <div class=\"submit-price-container \">
                        <div class=\"col-xs-2 d-flex flex-row\">
                            <label for=\"price\">Price</label>
                            <input type=\"text\" class=\"form-control\" name=\"price\">
                        </div>
                        <input type=\"submit\" id=\"submit-upload\" name=\"submit\" value=\"Upload\">
                    </div>
                </form>
              </div>";
    }

    function displayModifForm()
    {

    }
}