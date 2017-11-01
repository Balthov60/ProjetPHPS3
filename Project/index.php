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
    <?php include_once("./classes/HomePage.php") ?>
    <?php include_once("classes/ImageHandler.php") ?>
</head>
<body>
<div id="main-content">

    <?php

    if (isset($_GET["page"]) && $_GET["page"] == "panel" && $_SESSION["user"]["isAdmin"] == true) {
        new AdminPanel();
    }
    else {
        new HomePage($_SESSION["user"]["isConnected"], $_SESSION["user"]["isAdmin"]);
    }

    ?>

    <footer>
        <div class="bg-dark">
            <div class="container d-flex justify-content-between text-white">
                mentions légales
                <!-- TODO: content imp -->
            </div>
        </div>
    </footer>
</div>


<!-- JavaScript -->
<script src="js/jquery.min.3.1.2.js"></script>
<script src="./js/bootstrap.min.js"></script>
<script src="js/filterListKeyword.js"></script>


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