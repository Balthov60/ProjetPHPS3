<?php
session_start();
include("./scripts/utils.php");

// Handle Cookie
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
               <?php displayValueIfExist($usernameCookie, $isChecked); ?>>
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
function handleError()
{
    if (isset($_GET['error']) && $_GET['error'] == "notValidID") {
        echo "<p class='text-danger'>Mot de passe ou nom d'utilisateur erroné.</p>";
    }
}

/**
 * If user previously Sign Up
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
 * If Session is used then we must unchecked remember-me
 *
 * @param $usernameCookie
 * @param $isChecked
 */
function displayValueIfExist($usernameCookie, &$isChecked) {
    if (isset($_SESSION["form"]["username"])) {
        echo "value='" . $_SESSION["form"]["username"] . "'";
        $isChecked = "";
    }
    else {
        if (!empty($usernameCookie))
            echo "value='$usernameCookie'";
    }
}