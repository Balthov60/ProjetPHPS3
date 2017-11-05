<?php
include_once("HeaderBar.php");
include_once("AdminPanel.php");
include_once("ModalHandler.php");
include_once("FooterBar.php");
class AdminPanel
{
    private $sqlService;

    // TODO : Enhance Graphics
    // TODO : Fix TOP & BOTTOM Space Issues

    function __construct()
    {
        // TODO : Fix Include problem
        $hostnameDB = "localhost";
        $userDB = "root";
        $passwordDB = '';
        $dbName = "projetphps3";
        $this->sqlService = new SQLServices($hostnameDB,$dbName,$userDB, $passwordDB);

        new HeaderBar(true, true, "Panel", $this->sqlService);

        echo "<div class=\"container bg-secondary\"/>";
        ModalHandler::displayNewTagForm();
        $this->displayUploadForm();
        $this->displayModifForm();
        echo "</div>";

        new FooterBar();
    }

    function displayUploadForm()
    { ?>
        <div id='upload-form-container'>
            <form action="../../../ProjetPHPS3/Project/scripts/uploadImage.php"
                  method="post" enctype="multipart/form-data">

                <input type="file" class="form-control col-xs-2" name="pictureToUpload" value="Choose Image...">
                <div id="keyword-list-container">
                    <input type="text" onkeyup="filterKeyword()" name="keyword_input" id="keyword-search"
                           placeholder="Check tags you want to add to your picture">

                    <ul id="keywordList">
                        <!-- Displayed by ajax -->
                    </ul>
                    <p id="recap-tags">Total des mots-clés ajoutés :</p>
                </div>

                <label for="description">
                    Description
                    <textarea class="form-control" rows="1" name="description"></textarea>
                </label>

                <div class="submit-price-container ">
                    <div class="col-xs-2 d-flex flex-row">
                        <label for="price">
                            Price
                            <input type="text" class="form-control" name="price" title="price">
                        </label>
                    </div>
                    <input type="submit" id="submit-upload" name="submit" value="Upload">
                </div>
            </form>
        </div>
    <?php
    }

    function displayModifForm()
    {

    }
}