<?php

// Return tag list to Javascript.

include_once("../classes/SQLServices.php");
include("../includes/variables.inc.php");
$sqlService = new SQLServices($host, $dbName, $user, $password);

$keywordAdded = "";
if(isset($_GET['newKeyword'])) {
    $keyword = $_GET['newKeyword'];
    $keywordAdded = $keyword;
    if (!$sqlService->keywordExist($keyword))
    {
        $sqlService->insertData('keyword', array(array("keyword_name" => $keyword)));
    }
}

$keywordsList = $sqlService->getData('keyword', 'keyword_name');
if (isset($keywordsList))
{
    echo formatKeywordsListForHTMLInsertion($keywordsList, $keywordAdded);
}
else
{
    echo "<li>No Keyword Found</li>";
}

/**
 * Format keywords list for html insertion and check it if it has been add on this session.
 *
 * @param $keywordsList
 * @param $keywordAdded
 * @return string
 */
function formatKeywordsListForHTMLInsertion($keywordsList, $keywordAdded)
{
    $codeHtml = "";
    foreach ($keywordsList as $keyword) {
        $codeHtml.= "<li>
                        <input class='tags' type='checkbox' name='tags[]' value='$keyword[0]' 
                        id='$keyword[0]_tag' " . displayIfIsChecked($keyword[0], $keywordAdded) . ">
                        <p id='tag-text'>$keyword[0]</p>
                     </li>";
    }
    return $codeHtml;
}

//TODO: check previously ckecked keywords. NOT IMPLEMENTED YET
/**
 * return "checked" if it as been added on this session.
 *
 * @param $keywordName
 * @param $keywordAdded
 * @return string
 */
function displayIfIsChecked($keywordName, $keywordAdded) {
    if ($keywordAdded == $keywordName)
    {
        return "checked";
    }
    else
    {
        return "";
    }
}