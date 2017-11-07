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
            <?php $this->handleError() ?>
            <?php $this->handleSuccess() ?>
            <form action="../../../ProjetPHPS3/Project/scripts/uploadImage.php"
                  method="post" enctype="multipart/form-data">

                <input type="file" class="form-control col-xs-2" name="pictureToUpload" value="Choisissez une image">
                <div id="keyword-list-container">
                    <input type="text" name="keyword_input" id="keyword-search"
                           placeholder="Cochez les mots-clé qui seront attribué à l'image">

                    <ul id="keywords-list">
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
                            <input type="number" step="0.01" class="form-control" name="price" title="price">
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

    /**
     * Check if the previous submission of this form get error and display it.
     */
    function handleError() {
        if (isset($_GET['error'])) {
            if ($_GET['error'] == "invalidPrice")
            {
                echo "<p class='text-danger'>Le prix doit être une valeur numérique.</p>";
            }
            else if ($_GET['error'] == "descriptionTooLong")
            {
                echo "<p class='text-danger'>La description ne peut mesurer plus de 256 caractères.</p>";
            }
            else if ($_GET['error'] == "invalidImage")
            {
                echo "<p class='text-danger'>L'image n'est pas au bon format.</p>";
            }
            else if ($_GET['error'] == "fileAlreadyExist")
            {
                echo "<p class='text-danger'>Un fichier du même nom existe déjà.</p>";
            }
            else if ($_GET['error'] == "imageTooBig")
            {
                echo "<p class='text-danger'>La taille de l'image est trop grande.</p>";
            }
            else if ($_GET['error'] == "invalidExtension")
            {
                echo "<p class='text-danger'>
                        L'extension de l'image n'est pas valide les formats possibles sont jpg/jpeg/png.
                      </p>";
            }
            else if ($_GET['error'] == "unableToMoveFile")
            {
                echo "<p class='text-danger'>Problème lors du transfert de fichier.</p>";
            }
        }
    }

    /**
     * Check if the previous submission of this form get success and display it.
     */
    function handleSuccess() {
        if (isset($_GET['success'])) {
            if ($_GET['success'] == true)
            {
                echo "<p class='text-info'>La photo à bien été ajouté.</p>";
            }
        }
    }
}