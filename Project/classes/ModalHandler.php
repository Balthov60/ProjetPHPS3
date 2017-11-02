<?php
/**
 * Created by PhpStorm.
 * User: sntri
 * Date: 02/11/2017
 * Time: 13:22
 */

class ModalHandler
{
    public function __construct()
    {

    }

    public function displayImageDetailsModal()
    {
        echo "<div id=\"modal-image-details\" class='modal'>
                <div id=\"modal-image-details-content\">
                    <div id='image-container'>
                        <!-- Image displayed by Javascript -->
                    </div>
                    <div id='details-container'>
                        <!-- Details displayed by Javascript -->
                    </div>
                    <input type='submit' name='submit-add-cart' id='add-cart-submit'>
                </div>
              </div>";
    }
}