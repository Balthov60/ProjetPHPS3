<?php
include_once("HeaderBar.php");
include_once("AdminPanel.php");
include_once("FooterBar.php");
class UserPanel
{
    function __construct()
    {
        new HeaderBar(true, false, "Panel");

        /* Implement main content */

        new FooterBar();
    }

}