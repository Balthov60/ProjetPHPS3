<?php

include_once("../classes/SQLServices.php");
include("../includes/variables.inc.php");
$sqlService = new SQLServices($host, $dbName, $user, $password);

if(isset($_GET['newTagName'])) {
    $tagName = $_GET['newTagName'];
    $sqlService->insertData('keyword', array(array("name_keyword" => $tagName)));
}

echo $sqlService->displayKeywordList();