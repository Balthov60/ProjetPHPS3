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
}
$(document).ready(function(){

    $("#keyword-search").focus(function()
    {
        $("#keywordList").show(150);
    });

    $("li input[type='checkbox']").click(function(event)
    {
        if(event.target.checked)
            addTagToTotalTagList(event);

        else
            deleteTagFromTotalTagList(event);

    });

    $("#keywordList li a").click(function()
    {
        $("#new-tag-modal").show(0);
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
    var name_checkbox = event.target.name;
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
