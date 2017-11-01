<?php

session_start();

if($_SESSION['connected'] != 1)
{
    session_destroy();
    header('Location:../login.html?error_connexion=NoConnected');
}

echo 'Bonjour '.$_SESSION['username'].', vous etes admin c\'est cool';
?>

<!DOCTYPE html>
<html>
<head>
</head>
<body>
    <div id="main-content">
        <?php
            new HomePage($_SESSION["user"]["isConnected"], $_SESSION["user"]["isAdmin"]);

            new AdminPanel();
            
        ?>
    </div>
</body>
</html>
