<?php
include_once("HeaderBar.php");
include_once("AdminPanel.php");
include_once("ModalHandler.php");
include_once("FooterBar.php");

class AdminPanel
{
    private $sqlService;

    /**
     * AdminPanel constructor. Display Admin Panel Page.
     *
     * @param SQLServices $sqlService
     */
    function __construct(SQLServices $sqlService)
    {
        $this->sqlService = $sqlService;

        new HeaderBar(true, true, "Panel", $this->sqlService);

        echo "<div class=\"container bg-secondary\"/>";
        ModalHandler::displayNewTagModalForm();
        $this->displayUploadForm();
        $this->displayModifForm();
        echo "</div>";
    }

    /**
     * Display image upload form.
     */
    function displayUploadForm()
    { ?>
        <div id='upload-form-container' class="main-page-content">
            <form action="../../../ProjetPHPS3/Project/scripts/uploadImage.php"
                  method="post" enctype="multipart/form-data">

                <input type="file" class="form-control col-xs-2" name="pictureToUpload" value="Choisissez une image">
                <div id="keyword-list-container">
                    <input type="text" onkeyup="filterKeyword()" name="keyword_input" id="keyword-search"
                           placeholder="Cochez les mots-clé qui seront attribué à l'image">

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
                    <div class="flex-row">
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

    /**
     * NOT IMPLEMENTED YET.
     */
    function displayModifForm()
    {
        //TODO: Implement Modif Form (possible improvement)
    }
}