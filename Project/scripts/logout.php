<?php

session_start();

$_SESSION["user"]["isConnected"] = false;
$_SESSION["user"]["isAdmin"] = false;

header('location: ../index.php');