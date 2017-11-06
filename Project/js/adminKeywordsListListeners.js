$(document).ready(function()
{
    // init keywords for keywords selector.
    getTagList(displayKeywordsList);

    // Display Keywords selector on focus.
    var keywordSearch = $("#keyword-search");
    var keywordList = $("#keywords-list");
    keywordList.hide();

    // Register event for keywordSearch.
    keywordSearch.focus(
        function()
        {
            keywordList.show(150);
        }
    );

    // Register event for keywords list.
    keywordList.click(
        function(event)
        {
            // On click on keyword checkbox.
            if(event.target.tagName === "INPUT")
            {
                if(event.target.checked)
                {
                    addKeywordToTotalTagList(event);
                }
                else
                {
                    deleteTagFromTotalTagList(event);
                }
            }

            if(event.target.tagName === "A")
            {
                $("#new-tag-modal").show(100);
                $("#new-tag-input").val($("#keyword-search").val());
            }
        }
    );

    // Register event for add button in modal all keyword.
    $("#new-tag-submit").click(
        function()
        {
            $("#new-tag-modal").hide(100);
            addNewKeyword($("#new-tag-input").val(), displayKeywordsList);
        }
    );

});

/*********************/
/* Display Functions */
/*********************/

/**
 * Call displayKeywordsList php script to get keywords list.
 *
 * @param callback
 */
function getTagList(callback)
{
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState === 4 && (xmlhttp.status === 200 || xmlhttp.status === 0)) {
            callback(xmlhttp.responseText);
        }
    };
    xmlhttp.open("GET", "scripts/displayKeywordsList.php", true);
    xmlhttp.send(null);
}

/**
 * Display keywordsList & add keyword option.
 *
 * @param htmlKeywordsList
 */
function displayKeywordsList(htmlKeywordsList)
{
    $("#keywords-list").html("<div><a id='new-tag-link'>Ajout Mot-Clé</a></div>" + htmlKeywordsList);
}

/******************************/
/* Add/Delete keyword methods */
/******************************/

/**
 * Add keyword to list of all keywords selected
 *
 * @param event
 */
function addKeywordToTotalTagList(event)
{
    var tagName = getRealTagNameFrom(event);
    appendKeywordNameToTotalTagList(tagName);
}

/**
 * Add keyword to list of all keywords selected
 *
 * @param tagName
 */
function appendKeywordNameToTotalTagList(tagName) {
    var recapTags = $("#recap-tags");

    // if recap
    if(recapTagsIsEmpty(recapTags.html()))
    {
        recapTags.append(" " + tagName);
    }
    else
    {
        recapTags.append(', ' + tagName);
    }
}

/**
 * delete tag for current event in total tag list.
 *
 * @param event
 */
function deleteTagFromTotalTagList(event)
{
    var recapTags = $("#recap-tags");

    var tagName = getRealTagNameFrom(event);
    var tagAlreadyChosenList = getChosenTagsArrayFrom(recapTags.html());

    if(tagAlreadyChosenList.search(tagName + ", ") > 0 )
    {
        recapTags.html("Total des mots-clés ajoutés :" + tagAlreadyChosenList.replace(tagName + ", ", ""));
    }
    else if(tagAlreadyChosenList.search(", "  + tagName) > 0)
    {
        recapTags.html("Total des mots-clés ajoutés :" + tagAlreadyChosenList.replace(", " + tagName, ""));
    }
    else
    {
        recapTags.html("Total des mots-clés ajoutés :" + tagAlreadyChosenList.replace(" " + tagName, ""));
    }
}

/**
 * Get tag name for this event.
 *
 * @param event
 * @returns {string}
 */
function getRealTagNameFrom(event)
{
    var name_checkbox = event.target.id;
    var checkboxID_length = "_tag".length;

    return name_checkbox.substr(0, name_checkbox.length - checkboxID_length);
}

/**
 * Extract tags list from Chosen tags html code.
 *
 * @param totalTagHTML
 * @returns {string}
 */
function getChosenTagsArrayFrom(totalTagHTML) {
    var totalTag = totalTagHTML;
    var indexUselessThing = totalTag.search(":");
    return totalTag.substr(indexUselessThing+1, totalTag.length)
}

/**
 * Check if recaps tag is empty.
 *
 * @param totalTagHTML
 * @returns boolean
 */
function recapTagsIsEmpty(totalTagHTML)
{
    return getChosenTagsArrayFrom(totalTagHTML).length <= 2;
}

/*************************/
/* Create keyword method */
/*************************/

/**
 * Create a new Tag.
 *
 * @param keywordName
 * @param callback
 */
function addNewKeyword(keywordName, callback)
{
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState === 4 && (xmlhttp.status === 200 || xmlhttp.status === 0)) {
            callback(xmlhttp.responseText, keywordName);
            appendKeywordNameToTotalTagList(keywordName)
        }
    };
    xmlhttp.open("GET", "scripts/displayKeywordsList.php?newKeyword=" + keywordName, true);
    xmlhttp.send(null);
}