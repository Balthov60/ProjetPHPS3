<?php
include_once("HeaderBar.php");
include_once("AdminPanel.php");
include_once("FooterBar.php");
class CartPage
{
    function __construct(SQLServices $sqlService)
    {
        new HeaderBar(true, false, "Cart", $sqlService);

        /* Implement main content */

        new FooterBar();
    }
}