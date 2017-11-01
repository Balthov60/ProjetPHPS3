<?php

include('SQLServices.php');

class HeaderBar
{

    /**
     * HeaderBar constructor.
     * @param $isConnected
     * @param $isAdmin
     * @param $currentPage (HomePage, Shop or Panel)
     */
    function __construct($isConnected, $isAdmin, $currentPage)
    {
        echo " <header class=\"sticky-top\"> ";

        $this->displayNavBar($isConnected, $isAdmin, $currentPage);
        if ($currentPage == "HomePage") {
            $this->displayExtendedHeaderBar();
        }

        echo "</header>";
    }

    private function displayNavBar($isConnected, $isAdmin, $currentPage) {
        echo "
            <nav class=\"navbar navbar-dark bg-dark\">
            <div class=\"container d-flex justify-content-between\">

                <div class=\"navbar-brand d-flex\">
                    <img src=\"./images/logo.png\" id=\"logo\">
                    <h1 class=\"text-white align-self-center\">Catalogue</h1>
                </div>
            ";

        echo "<ul class=\"navbar-nav d-flex\">";
        $this->displayNavItems($isConnected, $isAdmin, $currentPage);
        echo "</ul></div></nav>";
    }
    private function displayExtendedHeaderBar() {
        echo "<div class=\"bg-dark collapse\" id=\"advanced-menu\">
                <form action=\"\" method=\"post\" class=\"container d-flex\">
            ";
        $this->displayTags();
        echo "<input type=\"submit\" class=\"btn\">
              </form>
            </div>

            <a data-toggle=\"collapse\" href=\"#advanced-menu\" aria-expanded=\"false\" aria-controls=\"collapseExample\">
                <img src=\"images/advanced_menu.png\" class=\"center-horizontaly\" id=\"advanced-menu-button\" >
            </a>";
    }

    /* Display Nav Items Methods */

    private function displayNavItems($isConnected, $isAdmin, $currentPage) {
        if (!$isConnected)
        {
            $this->displayDisconnectedNavItems();
        }
        else if ($isAdmin && $currentPage == "HomePage") {
            $this->displayAdminHomepageNavItems();
        }
        else if ($currentPage == "HomePage") {
            $this->displayBasicHomepageNavItems();
        }
        else if ($isAdmin && $currentPage == "Panel") {
            $this->displayAdminPanelNavItems();
        }
        else if ($currentPage == "Panel") {
            $this->displayBasicPanelNavItems();
        }
        else if ($currentPage == "Shop") {
            $this->displayShopNavItems();
        }
    }

    private function displayDisconnectedNavItems() {
        echo "
                <li class=\"nav-item\">
                    <a class=\"nav-link\" href=\"../../../ProjetPHPS3/Project/login.html\">Se connecter</a>
                </li>
                <li class=\"nav-item\">
                    <a class=\"nav-link\" href=\"../../../ProjetPHPS3/Project/signup.html\">S'inscrire</a>
                </li>
            ";
    }
    private function displayAdminHomepageNavItems() {
        echo "
                <li class=\"nav-item\">
                    <a class=\"nav-link\" href=\"admin/admin_panel.php\">Zone Administrateur</a>
                </li>
            ";
        $this->displayLogoutNavItem();
    }
    private function displayBasicHomepageNavItems() {
        echo "
                <li class=\"nav-item\">
                    <a class=\"nav-link\" href=\"#\">Mon espace</a>
                </li>
                <li class=\"nav-item\">
                    <a class=\"nav-link\" href=\"#\">Mon panier</a>
                </li>
            ";
        $this->displayLogoutNavItem();
    }
    private function displayAdminPanelNavItems() {
        echo "
                <li class=\"nav-item\">
                    <a class=\"nav-link\" href=\"#\">Accueil</a>
                </li>
             ";
        $this->displayLogoutNavItem();
    }
    private function displayBasicPanelNavItems() {
        echo "
                <li class=\"nav-item\">
                    <a class=\"nav-link\" href=\"#\">Accueil</a>
                </li>
                <li class=\"nav-item\">
                    <a class=\"nav-link\" href=\"#\">Mon panier</a>
                </li>
             ";
        $this->displayLogoutNavItem();
    }
    // TODO : Find Better name for "panier"
    private function displayShopNavItems() {
        echo "
                <li class=\"nav-item\">
                    <a class=\"nav-link\" href=\"#\">Accueil</a>
                </li>
                <li class=\"nav-item\">
                    <a class=\"nav-link\" href=\"#\">Mon Espace</a>
                </li>
            ";
        $this->displayLogoutNavItem();
    }

    private function displayLogoutNavItem() {
        echo "
            <li class=\"nav-item\">
                <a class=\"nav-link\" href=\"../../../ProjetPHPS3/Project/scripts/logout.php\">Deconnexion</a>
            </li>";
    }

    /* Display Extended Header Bar Methods */

    // TODO: Fix Include variables.inc.php
    // TODO: Improve graphics
    private function displayTags() {
        $hostnameDB = "localhost";
        $userDB = "root";
        $passwordDB = '';
        $dbName = "projetphps3";

        $db = new SQLServices($hostnameDB, $dbName, $userDB, $passwordDB);
        $result = $db->getData("keyword", "name_keyword");

        foreach($result as $value) {
            $this->displayTag($value[0]);
        }
    }
    private function displayTag($tagName) {
        echo "
            <label class=\"text-white\">
                $tagName
                <input type=\"checkbox\" name=$tagName/>
            </label>";
    }
}