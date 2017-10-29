<?php

session_start();
// TODO : Handle Admin In $_SESSION

/***********/
/* Include */
/***********/

include('../includes/variables.inc.php');
include('../classes/SQLServices.php');

//Déclaration des variables
$dbHandler = new SQLServices($hostnameDB, $dbName, $userDB, $passwordDB);

if(isset($_POST['username']) && isset($_POST['password'])) {
    $_SESSION['user']['username'] = $_POST['username'];
    $_SESSION['user']['password'] = $_POST['password'];

    if ($dbHandler->isRegistered($_SESSION['user']['username'], $_SESSION['user']['password']))
    {
        $_SESSION['user']['isConnected'] = true;
        $_SESSION['user']['isAdmin'] = false;
        header('Location:../index.php');
    }
    elseif ($dbHandler->isAdmin($_SESSION['user']['username'], $_SESSION['user']['password']))
    {
        $_SESSION['user']['isConnected'] = true;
        $_SESSION['user']['isAdmin'] = true;
        header('Location:../index.php');
    }
    else
    {
        $_SESSION['user']['isConnected'] = false;
        header('Location:../login.html?error_connexion=noIdentified');
    }
}

else
{
    header('Location: ../login.html');
}

?>
