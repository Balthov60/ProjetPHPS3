<?php

class HomePage
{

    function addNavigationBar($isConnected = false, $isAdmin = false) {
        $navBar = new HeaderBar($isConnected, $isAdmin, 'HomePage');
    }

}