function filterKeyword()
{
    var input, filterList, filter, ul, keywordArray, a, i;
    input = document.getElementById('keyword-search');

    filter = input.value.toUpperCase();

    ul = document.getElementById("keywordList");
    keywordArray = ul.getElementsByTagName('li');
    var nbTagAvailable = keywordArray.length;


    // Loop through all list items, and hide those who don't match the search query
    for (i = 0; i < keywordArray.length; i++) {

        if (keywordArray[i].innerHTML.toUpperCase().indexOf(filter) > -1)
        {
            keywordArray[i].style.display = "";
        }
        else
        {
            keywordArray[i].style.display = "none";
            nbTagAvailable--;
        }


    }

    document.getElementById('new-tag-link').style.display = ""; //Always display "New keyword"

}


$(document).ready(function(){

    getTagList(displayTagList);


    $("#keyword-search").focus(function()
    {
        $("#keywordList").show(150);
    });

    $("#keywordList").click(function(event)
    {
        if(event.target.tagName === "INPUT")
        {
            if(event.target.checked)
                addTagToTotalTagList(event);

            else
                deleteTagFromTotalTagList(event);
        }

        if(event.target.tagName === "A")
        {
            $("#new-tag-modal").show(0);
            $("#new-tag-input").val($("#keyword-search").val());
        }
    });


    $("#new-tag-submit").click(function()
    {
        $("#new-tag-modal").hide(100);
        addNewTag($("#new-tag-input").val(), displayTagList);
    });


});

function addTagToTotalTagList(event)
{
   var tagName = getRealTagNameFrom(event);

   var tagAlreadyChosenList = getChosenTagsArrayFrom($("#recap-tags").html());

    appendTagNameToTotal(tagName, tagAlreadyChosenList);
}

function getRealTagNameFrom(event)
{
    var name_checkbox = event.target.id;
    var checkboxID_length = "_tag".length;
    var name_tag = name_checkbox.substr(0, name_checkbox.length - checkboxID_length);
    return name_tag;
}


function getChosenTagsArrayFrom(totalTagString)
{
    var totalTag = totalTagString;
    var indexUselessThing = totalTag.search(":");
    var tagListString = totalTag.substr(indexUselessThing+1, totalTag.length);


    return tagListString;
}

function appendTagNameToTotal(tagName, tagListString)
{

    if(tagListString.length  <= 2)
        $("#recap-tags").append(" " + tagName);

    else
        $("#recap-tags").append(', ' + tagName);

}



function deleteTagFromTotalTagList(event)
{
    var tagName = getRealTagNameFrom(event);

    var tagAlreadyChosenList = getChosenTagsArrayFrom($("#recap-tags").html());

    deleteTag(tagName, tagAlreadyChosenList);
}

function deleteTag(tagName, tagAlreadyChosenList)
{

    if(tagAlreadyChosenList.search(tagName + ", ") > 0 )
        var newTagList = tagAlreadyChosenList.replace(tagName + ", ", "");

    else if(tagAlreadyChosenList.search(", "  + tagName) > 0)
        var newTagList = tagAlreadyChosenList.replace(", " + tagName, "");

    else
        var newTagList = tagAlreadyChosenList.replace(" " + tagName, "");


    $('#recap-tags').html("Total des mots-clés ajoutés :" + newTagList);

}


function addNewTag(tagName, callback)
{
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState === 4 && (xmlhttp.status === 200 || xmlhttp.status === 0)) {
            callback(xmlhttp.responseText);
        }
    };
    xmlhttp.open("GET", "scripts/displayTagList.php?newTagName=" + tagName, true);
    xmlhttp.send(null);
}


function getTagList(callback)
{
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState === 4 && (xmlhttp.status === 200 || xmlhttp.status === 0)) {
            callback(xmlhttp.responseText);
        }
    };
    xmlhttp.open("GET", "scripts/displayTagList.php", true);
    xmlhttp.send(null);
}

function displayTagList(responseText)
{
    $("#keywordList").html(responseText);
    $("#keywordList").append("<div><a id='new-tag-link'>Nouveau Mot-Clé</a></div>");
}