<?php

class UserPanel
{
    function __construct()
    {
        new HeaderBar(true, false, "Panel");

        /* Implement main content */

        new FooterBar();
    }

}