<?php

$keywordsSelected = "";

if (isset($_POST)) {
    foreach ($_POST as $keyword => $value)
    {
        if ($keyword == "submit")
            continue;

        $keywordsSelected .= "$keyword&";
    }
    $keywordsSelected = substr($keywordsSelected, 0, -1);
}

if (!empty($keywordsSelected))
    header("Location:../index.php?keywords=$keywordsSelected");
else
    header("Location:../index.php");