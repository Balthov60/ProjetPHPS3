<?php

include_once("HeaderBar.php");

class HomePage
{

    function __construct($isConnected = false, $isAdmin = false) {
        new HeaderBar($isConnected, $isAdmin, 'HomePage');
    }

}