<?php

function checkSessionFormFor($value) {
    if (isset($_SESSION["form"][$value]))
        echo "value='" . $_SESSION["form"][$value] . "'";
}
