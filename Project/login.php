<!DOCTYPE html>
<html>
<head>
    <title>Catalogue - Log In</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css"/>
    <link rel="stylesheet" href="css/form-page.css"/>
</head>
<body class="bg-dark">

<div>

    <form method="post" action="scripts/login.php" class="form-container bg-light">

        <h2 class="form-heading">Se Connecter</h2>

        <input type="text" name="username" class="form-control" placeholder="Username" required autofocus>
        <input type="password" name="password" class="form-control" placeholder="Password" required>

        <?php handleError() ?>

        <div class="checkbox">
            <label>
                <input type="checkbox" value="remember-me"> Se souvenir de moi
            </label>
        </div>

        <button class="btn btn-lg btn-primary btn-block" type="submit" name="submit">Connexion</button>
        <a href="signup.html" id="signInLink">Pas encore inscrit ?</a>
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