<?php

class HeaderBar
{
    /**
     * HeaderBar constructor.
     * @param $isConnected
     * @param $isAdmin
     * @param $currentPage (HomePage, Cart or Panel)
     */
    function __construct($isConnected, $isAdmin, $currentPage)
    {
        echo "<header class=\"sticky-top\">";

        $this->displayNavBar($isConnected, $isAdmin, $currentPage);
        if ($currentPage == "HomePage") {
            $this->displayExtendedHeaderBar();
        }

        echo "</header>";
    }

    /*****************************/
    /* Display Nav Items Methods */
    /*****************************/
    
    private function displayNavBar($isConnected, $isAdmin, $currentPage)
    { ?>
        <nav class="navbar navbar-dark bg-dark">
            <div class="container d-flex justify-content-between">
                <div class="navbar-brand d-flex">
                    <img src="../../../ProjetPHPS3/Project/images/logo.png" id="logo" alt="websiteLogo">
                    <h1 class="text-white align-self-center">Catalogue</h1>
                </div>

                <ul class="navbar-nav d-flex">
                    <?php $this->displayNavItems($isConnected, $isAdmin, $currentPage); ?>
                </ul>
            </div>
        </nav>
    <?php
    }

    private function displayNavItems($isConnected, $isAdmin, $currentPage) {
        if (!$isConnected)
        {
            $this->displayDisconnectedNavItems();
        }
        else if ($isAdmin && $currentPage == "HomePage") 
        {
            $this->displayAdminHomepageNavItems();
        }
        else if ($currentPage == "HomePage") 
        {
            $this->displayBasicHomepageNavItems();
        }
        else if ($isAdmin && $currentPage == "Panel")
        {
            $this->displayAdminPanelNavItems();
        }
        else if ($currentPage == "Panel") 
        {
            $this->displayBasicPanelNavItems();
        }
        else if ($currentPage == "Cart")
        {
            $this->displayCartNavItems();
        }
    }

    private function displayDisconnectedNavItems()
    { ?>
        <li class="nav-item">
            <a class="nav-link" href="../../../ProjetPHPS3/Project/login.html">Se connecter</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="../../../ProjetPHPS3/Project/signup.html">S'inscrire</a>
        </li>
    <?php
    }
    private function displayAdminHomepageNavItems()
    { ?>
        <li class="nav-item">
            <a class="nav-link" href="../../../ProjetPHPS3/Project/index.php?page=panel">Zone Administrateur</a>
        </li>
        <?php $this->displayLogoutNavItem();
    }
    private function displayBasicHomepageNavItems()
    {
        $this->displayUserPanelNavItem();
        $this->displayCartNavItem();
        $this->displayLogoutNavItem();
    }
    private function displayAdminPanelNavItems() 
    {
        $this->displayHomePageNavItem();
        $this->displayLogoutNavItem();
    }
    private function displayBasicPanelNavItems()
    {
        $this->displayHomePageNavItem();
        $this->displayCartNavItem();
        $this->displayLogoutNavItem();
    }
    private function displayCartNavItems()
    {
        $this->displayHomePageNavItem();
        $this->displayUserPanelNavItem();
        $this->displayLogoutNavItem();
    }

    private function displayUserPanelNavItem()
    { ?>
        <li class="nav-item">
            <a class="nav-link" href="../../../ProjetPHPS3/Project/index.php?page=panel">Mon Espace</a>
        </li>
    <?php
    }
    private function displayHomePageNavItem()
    { ?>
        <li class="nav-item">
            <a class="nav-link" href="../../../ProjetPHPS3/Project/index.php">Accueil</a>
        </li>
    <?php
    }
    private function displayCartNavItem()
    { ?>
        <li class="nav-item">
            <a class="nav-link" href="../../../ProjetPHPS3/Project/index.php?page=cart">Mon Panier</a>
        </li>
    <?php
    }
    private function displayLogoutNavItem() {
        echo "
            <li class=\"nav-item\">
                <a class=\"nav-link\" href=\"../../../ProjetPHPS3/Project/scripts/logout.php\">Deconnexion</a>
            </li>";
    }

    /* Display Extended Header Bar Methods */

    private function displayExtendedHeaderBar()
    { ?>
        <div class="bg-dark collapse" id="advanced-menu">
            <form action="" method="post" class="container d-flex">
                <?php $this->displayTags(); ?>
                <input type="submit" class="btn">
            </form>
        </div>

        <a data-toggle="collapse" href="#advanced-menu" aria-expanded="false" aria-controls="collapseExample">
            <img src="../../../ProjetPHPS3/Project/images/advanced_menu.png" class="center-horizontally"
                 id="advanced-menu-button" alt="Activer Menu Avancé">
        </a>
    <?php
    }

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
    private function displayTag($tagName)
    { ?>
        <label class="text-white">
            <?php echo $tagName ?>
            <input type="checkbox" name=$tagName/>
        </label>";
    <?php
    }
}