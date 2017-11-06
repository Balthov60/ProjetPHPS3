<?php

class ModalHandler
{
    /**
     * Display image details modal.
     */
    public static function displayDetailsModal()
    { ?>
        <div id="modal-image-details" class='modal'>
            <div class="modal-content horizontal-layout">
                <div id='modal-image-container'>
                    <!-- Image displayed by Javascript -->
                </div>
                <div class="vertical-layout modal-details-content">
                    <div id='details-container'>
                        <!-- Details displayed by Javascript -->
                    </div>

                    <div id='action-container'>
                        <!-- Action displayed by Javascript -->
                    </div>
                </div>
            </div>
        </div>
    <?php
    }

    /**
     * Display New tag form modal.
     */
    public static function displayNewTagModalForm()
    { ?>
        <div id='new-tag-modal' class='modal'>
            <div id='new-tag-modal-content' class='modal-content horizontal-layout'>
                <input type='text' id='new-tag-input' class='form-control' placeholder='Nouveau mot-clé'>
                <input type='button' id='new-tag-submit' class='btn btn-primary' value='Ajouter'>
            </div>
        </div>
    <?php
    }
}