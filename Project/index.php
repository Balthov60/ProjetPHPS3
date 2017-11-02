<?php
session_start();
initSession();
getKeywords();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Catalogue</title>

    <link href="./css/bootstrap.min.css" rel="stylesheet">
    <link href="./css/project-style.css" rel="stylesheet">
</head>
<body>
<div id="main-content">
    <?php
    /* Create SQLService */
    include_once("./includes/variables.inc.php");
    include_once("./classes/SQLServices.php");
    $sqlService = new SQLServices($host, $dbName, $user, $password);

    /* Create Valid Page */
    if (isset($_GET["page"]) && $_SESSION["user"]["isConnected"] == true) {
        if ($_GET["page"] == "panel")
        {
            if ($_SESSION["user"]["isAdmin"] == true)
            {
                include_once("./classes/AdminPanel.php");
                new AdminPanel();
            }
            else
            {
                include_once("./classes/UserPanel.php");
                new UserPanel();
            }
        }
        else if ($_GET["page"] == "cart")
        {
            include_once("./classes/CartPage.php");
            new CartPage();
        }
        else
        {
            include_once("./classes/HomePage.php");
            new HomePage($_SESSION["user"]["isConnected"], $_SESSION["user"]["isAdmin"], $sqlService);
        }
    }
    else
    {
        include_once("./classes/HomePage.php");
        new HomePage($_SESSION["user"]["isConnected"], $_SESSION["user"]["isAdmin"], $sqlService);
    }
    ?>
</div>

<!-- JavaScript -->
<script src="./js/jquery.min.3.1.2.js"></script>
<script src="./js/bootstrap.min.js"></script>
<script src="./js/filterListKeyword.js"></script>
<script src="./js/showImageDetails.js"></script>


</body>
</html>

<?php
function initSession() {
    if (!isset($_SESSION["user"]["isConnected"]) || !is_bool($_SESSION["user"]["isConnected"])) {
        $_SESSION["user"]["isConnected"] = false;
        $_SESSION["user"]["isAdmin"] = false;
    }
    else if (!isset($_SESSION["user"]["isAdmin"]) || !is_bool($_SESSION["user"]["isAdmin"])) {
        $_SESSION["user"]["isAdmin"] = false;
    }
}

function getKeywords()
{
    global $keywords;
    if(isset($_GET['keywords']))
        $keywords = $_GET['keywords'];
    else
        $keywords = null;
}
?>