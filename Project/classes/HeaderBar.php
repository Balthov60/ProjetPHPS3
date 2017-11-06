<?php

class HeaderBar
{
    private $sqlServices;

    /**
     * HeaderBar constructor. Display header bar.
     *
     * @param $isConnected
     * @param $isAdmin
     * @param $currentPage (HomePage, Cart or Panel)
     * @param SQLServices $sqlServices
     */
    function __construct($isConnected, $isAdmin, $currentPage, SQLServices $sqlServices)
    {
        $this->sqlServices = $sqlServices;

        echo "<header class='sticky-top'>";

        $this->displayNavBar($isConnected, $isAdmin, $currentPage);
        if ($currentPage == "HomePage") {
            $this->displayExtendedHeaderBar();
        }

        echo "</header>";
    }

    /***************************************/
    /* Display Navigation Header Bar Methods */
    /***************************************/

    /**
     * Display main website navigation bar.
     *
     * @param $isConnected
     * @param $isAdmin
     * @param $currentPage
     */
    private function displayNavBar($isConnected, $isAdmin, $currentPage)
    { ?>
        <nav class="navbar navbar-dark bg-dark">
            <div class="container d-flex justify-content-between">
                <div class="d-flex title">
                    <img src="../../../ProjetPHPS3/Project/images/logo.png" id="logo" alt="websiteLogo">
                    <h1 class="text-white align-self-center">Photos'Shop -</h1>
                    <?php $this->displaySubTitle($isAdmin, $currentPage); ?>
                </div>

                <ul class="navbar-nav d-flex">
                    <?php $this->displayNavItems($isConnected, $isAdmin, $currentPage); ?>
                </ul>
            </div>
        </nav>
    <?php
    }

    /**
     * Display subtitle for the current page.
     *
     * @param $isAdmin
     * @param $currentPage
     */
    private function displaySubTitle($isAdmin, $currentPage) {
        if ($currentPage == "HomePage")
        {
            echo "<h2 class='text-white align-self-center'>Catalogue</h2>";
        }
        else if ($currentPage == "Cart")
        {
            echo "<h2 class='text-white align-self-center'>Mon Panier</h2>";
        }
        else if ($currentPage == "Panel" && !$isAdmin)
        {
            echo "<h2 class='text-white align-self-center'>Mes Photos</h2>";
        }
        else
        {
            echo "<h2 class='text-white align-self-center'>Zone Administrateur</h2>";
        }
    }

    /**
     * Display Nav Buttons for current user and page.
     *
     * @param $isConnected
     * @param $isAdmin
     * @param $currentPage
     */
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


    /**
     * Display button for disconnected nav bar.
     */
    private function displayDisconnectedNavItems()
    { ?>
        <li class="nav-item">
            <a class="nav-link" href="../../../ProjetPHPS3/Project/login.php">Se connecter</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="../../../ProjetPHPS3/Project/signup.php">S'inscrire</a>
        </li>
    <?php
    }

    /**
     * Display button for admin homepage nav bar.
     */
    private function displayAdminHomepageNavItems()
    { ?>
        <li class="nav-item">
            <a class="nav-link" href="../../../ProjetPHPS3/Project/index.php?page=panel">Zone Administrateur</a>
        </li>
        <?php $this->displayLogoutNavItem();
    }

    /**
     * Display button for classic homepage nav bar.
     */
    private function displayBasicHomepageNavItems()
    {
        $this->displayUserPanelNavItem();
        $this->displayCartNavItem();
        $this->displayLogoutNavItem();
    }

    /**
     * Display button for admin panel nav bar.
     */
    private function displayAdminPanelNavItems() 
    {
        $this->displayHomePageNavItem();
        $this->displayLogoutNavItem();
    }

    /**
     * Display button for classic panel nav bar.
     */
    private function displayBasicPanelNavItems()
    {
        $this->displayHomePageNavItem();
        $this->displayCartNavItem();
        $this->displayLogoutNavItem();
    }

    /**
     * Display button for cart nav bar.
     */
    private function displayCartNavItems()
    {
        $this->displayHomePageNavItem();
        $this->displayUserPanelNavItem();
        $this->displayLogoutNavItem();
    }


    /**
     * Display button to reach user panel in nav bar.
     */
    private function displayUserPanelNavItem()
    { ?>
        <li class="nav-item">
            <a class="nav-link" href="../../../ProjetPHPS3/Project/index.php?page=panel">Mon Espace</a>
        </li>
    <?php
    }

    /**
     * Display button to reach homepage in nav bar.
     */
    private function displayHomePageNavItem()
    { ?>
        <li class="nav-item">
            <a class="nav-link" href="../../../ProjetPHPS3/Project/index.php">Accueil</a>
        </li>
    <?php
    }

    /**
     * Display button to reach cart page in nav bar.
     */
    private function displayCartNavItem()
    { ?>
        <li class="nav-item">
            <a class="nav-link" href="../../../ProjetPHPS3/Project/index.php?page=cart">Mon Panier</a>
        </li>
    <?php
    }

    /**
     * Display button to logout.
     */
    private function displayLogoutNavItem() {
        echo "
            <li class=\"nav-item\">
                <a class=\"nav-link\" href=\"../../../ProjetPHPS3/Project/scripts/logout.php\">Deconnexion</a>
            </li>";
    }

    /***************************************/
    /* Display Extended Header Bar Methods */
    /***************************************/

    /**
     * Display extended header bar for keywords selection.
     */
    private function displayExtendedHeaderBar()
    { ?>
        <div class="bg-dark collapse" id="advanced-menu">
            <form action="../../../ProjetPHPS3/Project/scripts/displayImagesWithKeywords.php"
                  method="post" class="container d-flex">
                <div class="container-fluid">
                    <?php $this->displayKeywords(); ?>
                </div>
                <div class="centered">
                    <input type="submit" name="submit" value="Afficher" class="btn">
                </div>
            </form>
        </div>

        <a data-toggle="collapse" href="#advanced-menu" aria-expanded="false" aria-controls="collapseExample">
            <img src="../../../ProjetPHPS3/Project/images/advanced_menu.png" class="center-horizontally"
                 id="advanced-menu-button" alt="Activer Menu Avancé">
        </a>
    <?php
    }

    /**
     * Display keywords.
     */
    private function displayKeywords() {
        $result = $this->sqlServices->getData("keyword", "name_keyword");

        foreach($result as $value) {
            $this->displayTag($value[0]);
        }
    }

    /**
     * Display tag name checkbox item.
     *
     * @param $tagName
     */
    private function displayTag($tagName)
    { ?>
        <label class="text-white tags">
            <?php echo $tagName ?>
            <input type="checkbox" name="<?php echo $tagName ?>" class="custom-checkbox"/>
        </label>
    <?php
    }
}