<?php
session_start();

if(isset($_POST['mail']) && isset($_POST['username']) &&
    isset($_POST['password']) && isset($_POST['password-confirmation']))
{
    initSession();

    /* Test if data format is valid */

    if (strlen($_POST['password']) < 8) {
        header('Location:../signup.php?error=passwordTooSmall');
        return;
    }
    if (strlen($_POST['username']) < 6) {
        header('Location:../signup.php?error=usernameTooSmall');
        return;
    }
    if (strlen($_POST['username']) > 16) {
        header('Location:../signup.php?error=usernameTooBig');
        return;
    }
    if (strcmp($_POST['password'], $_POST['password-confirmation']) != 0) {
        header('Location:../signup.php?error=confirmationPassword');
        return;
    }

    /* Test if IDs are available */

    include('../classes/SQLServices.php');
    include('../includes/variables.inc.php');
    $dbHandler = new SQLServices($host, $dbName, $user, $password);

    if ($dbHandler->mailExist($_POST['mail'])) {
        header('Location:../signup.php?error=mailAlreadyExist');
        return;
    }
    if ($dbHandler->usernameExist($_POST['username'])) {
        header('Location:../signup.php?error=usernameAlreadyExist');
        return;
    }

    /* If all test succeed, create a new user */

    $dbHandler->insertData('user', array(
        array(
            'mail' => $_POST['mail'],
            'username' => $_POST['username'],
            'password' => md5($_POST['password']),
        )
    ));

    header('Location:../login.php?signup=success');
}
else
{
    session_destroy();
    session_start();
    header('Location:../index.php');
}

function initSession() {
    $_SESSION["form"]["mail"] = $_POST["mail"];
    $_SESSION["form"]["username"] = $_POST["username"];
}