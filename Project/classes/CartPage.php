<?php

class CartPage
{
    function __construct()
    {
        new HeaderBar(true, false, "Cart");

        /* Implement main content */

        new FooterBar();
    }
}