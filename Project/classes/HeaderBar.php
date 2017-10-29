<?php

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
        echo "
            <div class=\"bg-dark collapse\" id=\"advanced-menu\">
                <form action=\"\" method=\"post\" class=\"container d-flex justify-content-between\">
                    <label class=\"text-white\">
                        TAG 1
                        <input type=\"checkbox\"/>
                    </label>
                    <!-- TODO: PHP for tag imp -->
                    <input type=\"submit\" class=\"btn\">
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
                    <a class=\"nav-link\" href=\"#\">Se connecter</a>
                </li>
                <li class=\"nav-item\">
                    <a class=\"nav-link\" href=\"#\">S'inscrire</a>
                </li>
            ";
    }
    private function displayAdminHomepageNavItems() {
        echo "
                <li class=\"nav-item\">
                    <a class=\"nav-link\" href=\"#\">Zone Administrateur</a>
                </li>
                <li class=\"nav-item\">
                    <a class=\"nav-link\" href=\"#\">Deconnexion</a>
                </li>
            ";
    }
    private function displayBasicHomepageNavItems() {
        echo "
                <li class=\"nav-item\">
                    <a class=\"nav-link\" href=\"#\">Mon espace</a>
                </li>
                <li class=\"nav-item\">
                    <a class=\"nav-link\" href=\"#\">Mon panier</a>
                </li>
                <li class=\"nav-item\">
                    <a class=\"nav-link\" href=\"#\">Deconnexion</a>
                </li>
            ";
    }
    private function displayAdminPanelNavItems() {
        echo "
                <li class=\"nav-item\">
                    <a class=\"nav-link\" href=\"#\">Accueil</a>
                </li>
                <li class=\"nav-item\">
                    <a class=\"nav-link\" href=\"#\">Deconnexion</a>
                </li>
            ";
    }
    private function displayBasicPanelNavItems() {
        echo "
                <li class=\"nav-item\">
                    <a class=\"nav-link\" href=\"#\">Accueil</a>
                </li>
                <li class=\"nav-item\">
                    <a class=\"nav-link\" href=\"#\">Mon panier</a>
                </li>
                <li class=\"nav-item\">
                    <a class=\"nav-link\" href=\"#\">Deconnexion</a>
                </li>
            ";
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
                <li class=\"nav-item\">
                    <a class=\"nav-link\" href=\"#\">Deconnexion</a>
                </li>
            ";
    }
}