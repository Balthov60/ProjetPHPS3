<?php
session_start();

include('../includes/variables.inc.php');
include('../classes/SQLServices.php');
$dbHandler = new SQLServices($host, $dbName, $user, $password);

if(isset($_POST['username']) && isset($_POST['password'])) {
    if ($dbHandler->isUser($_POST['username'], $_POST['password']))
    {
        session_destroy();
        session_start();
        $_SESSION['user']['isConnected'] = true;
        $_SESSION['user']['isAdmin'] = false;
        $_SESSION['user']['username'] = $_POST['username'];

        checkRememberMe();
        header('Location:../index.php');
    }
    elseif ($dbHandler->isAdmin($_POST['username'], $_POST['password']))
    {
        session_destroy();
        session_start();
        $_SESSION['user']['isConnected'] = true;
        $_SESSION['user']['isAdmin'] = true;

        checkRememberMe();
        header('Location:../index.php');
    }
    else
    {
        $_SESSION['user']['isConnected'] = false;
        header('Location:../login.php?error=notValidID');
    }
}
else
{
    header('Location: ../login.php');
}

function checkRememberMe() {
    if (isset($_POST['remember-me'])) {
        setcookie("username", $_POST['username'], time() + (86400 * 30), '/');
    }
    else
    {
        setcookie("username", "", time() - 3600, '/');
    }
}
