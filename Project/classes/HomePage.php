<?php

include_once("HeaderBar.php");
include_once("AdminPanel.php");
class HomePage
{

    function __construct($isConnected = false, $isAdmin = false) {
        new HeaderBar($isConnected, $isAdmin, 'HomePage');
        ?>
        <div class="container bg-secondary" style="height: 1000px">
                <?php
                    $imageHandler = new ImageHandler();
                    $imageHandler->displayImageWithKeyword();
                ?>
        </div>
    <?php
    }

}