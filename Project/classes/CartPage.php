<?php
include_once("HeaderBar.php");
include_once("AdminPanel.php");
include_once("FooterBar.php");
class CartPage
{
    function __construct()
    {
        new HeaderBar(true, false, "Cart");

        /* Implement main content */

        new FooterBar();
    }
}