<?php

class ModalHandler
{
    public function __construct()
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
}