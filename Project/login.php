<?php
session_start();

// Handle Cookie values in order to restore it.
$usernameCookie="";
$isChecked="";
if (isset($_COOKIE["username"])) {
    $usernameCookie = $_COOKIE["username"];
    $isChecked = "checked";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Catalogue - Log In</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css"/>
    <link rel="stylesheet" href="./css/form-page.css"/>
</head>
<body class="bg-dark">

<div>

    <form method="post" action="scripts/login.php" class="form-container bg-light">

        <h2 class="form-heading">Se Connecter</h2>

        <?php handleSignUp() ?>

        <input type="text" name="username" class="form-control" placeholder="Username" required autofocus
               <?php displayCookieOrSessionValueIfExist($usernameCookie, $isChecked); ?>>
        <input type="password" name="password" class="form-control" placeholder="Password" required>

        <?php handleError() ?>

        <div class="checkbox">
            <label>
                <input type="checkbox" name="remember-me" <?php echo($isChecked) ?>> Se souvenir de moi
            </label>
        </div>

        <button class="btn btn-lg btn-primary btn-block" type="submit" name="submit">Connexion</button>
        <a href="signup.php" id="signInLink">Pas encore inscrit ?</a>
    </form>

</div>

</body>
</html>

<?php

/**
 * Check if the previous submission of this form get error and display it.
 */
function handleError()
{
    if (isset($_GET['error']) && $_GET['error'] == "notValidID") {
        echo "<p class='text-danger'>Mot de passe ou nom d'utilisateur erroné.</p>";
    }
}

/**
 * User who Sign Up are derirected here while signup success, display a sucess message in this case.
 *
 * @return void (Display method)
 */
function handleSignUp()
{
    if (isset($_GET['signup']) && $_GET['signup'] == "success") {
        echo "<p class='text-info'>Votre compte a bien été créé.</p>";
    }
}

/**
 * Check If There is a value for username in Session or in Cookie
 * Session have priority on Cookie because it come from a previous sign up
 *
 * @param $usernameCookie
 * @param $isChecked (Security uncheck while session value is used)
 */
function displayCookieOrSessionValueIfExist($usernameCookie, &$isChecked) {
    if (isset($_SESSION["form"]["username"])) {
        echo "value='" . $_SESSION["form"]["username"] . "'";
        $isChecked = "";
    }
    else {
        if (!empty($usernameCookie))
            echo "value='$usernameCookie'";
    }
}