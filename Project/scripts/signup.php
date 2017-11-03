<?php //TODO: Evolve Sign UP

include('../classes/SQLServices.php');
include('../includes/variables.inc.php');
$dbHandler = new SQLServices($host, $dbName, $user, $password);

if(isset($_POST['mail']) && isset($_POST['username']) &&
    isset($_POST['password']) && isset($_POST['password-confirmation']))
{
    $username = $_POST['username'];
    $password = $_POST['password'];

    if(!($dbHandler->isUser($username, $password)))
    {
        $dbHandler->insertData('user', array(
            array(
                'username' => $username,
                'password' => md5($password),
                'admin' => 0
            )
        ));
        header('Location:../login.php?error_signUp=no_error');
    }
    else
    {
        session_destroy();
        header('Location:../login.php?error_signUp=existingUsername');
    }
}
else
{
    session_destroy();
    header('Location:../login.php?error_signUp=fieldEmpty');
}