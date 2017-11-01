<?php

include_once("HeaderBar.php");
include_once("AdminPanel.php");
class HomePage
{

    function __construct($isConnected = false, $isAdmin = false) {
        new HeaderBar($isConnected, $isAdmin, 'HomePage');
        if($isAdmin)
        {
            new AdminPanel();
        }
    }

}