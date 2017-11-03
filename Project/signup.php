<?php
session_start();
include("./scripts/utils.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Catalogue - Sign Up</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css"/>
    <link rel="stylesheet" href="css/form-page.css"/>
</head>
<body class="bg-dark">

<div>

    <form method="post" action="scripts/signup.php" class="form-container bg-light">

        <h2 class="form-heading">S'inscrire</h2>

        <?php handleError() ?>

        <input type="email" name="mail" class="form-control" placeholder="E-mail" required autofocus
               <?php checkSessionFormFor('mail') ?>>
        <input type="text" name="username" class="form-control" placeholder="Username" required
               <?php checkSessionFormFor('username') ?>>

        <input type="password" name="password" class="form-control" placeholder="Password" required>
        <input type="password" name="password-confirmation" class="form-control" placeholder="Confirm Password" required>

        <input type="submit" name="submit" class="btn btn-lg btn-primary btn-block"  value="Sign Up">
        <a href="login.php" id="backMenuLink">Déjà inscrit ?</a>
    </form>

</div>

</body>
</html>

<?php

function handleError() {
    if (isset($_GET['error'])) {
        if ($_GET['error'] == "passwordTooSmall")
        {
            echo "<p class='text-danger'>Le Mot de passe doit contenir au minimum 8 caractères.</p>";
        }
        else if ($_GET['error'] == "usernameTooSmall" || $_GET['error'] == "usernameTooBig")
        {
            echo "<p class='text-danger'>Le nom d'utilisateur doit contenir entre 6 et 16 caractères.</p>";
        }
        else if ($_GET['error'] == "confirmationPassword")
        {
            echo "<p class='text-danger'>Les mots de passe doivent être identiques.</p>";
        }
        else if ($_GET['error'] == "mailAlreadyExist")
        {
            echo "<p class='text-danger'>Cette adresse mail est déjà utilisé.</p>";
        }
        else if ($_GET['error'] == "usernameAlreadyExist")
        {
            echo "<p class='text-danger'>Ce nom d'utilisateur est déjà utilisé.</p>";
        }
    }
}