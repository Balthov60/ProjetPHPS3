function filterKeyword()
{
    var input, filterList, filter, ul, keywordArray, a, i, nb_keyword_available;
    input = document.getElementById('keyword-search');

    filterList = input.value.toUpperCase().split(",");
    filter = filterList[filterList.length-1];

    ul = document.getElementById("keywordList");
    keywordArray = ul.getElementsByTagName('li');
    nb_keyword_available = keywordArray.length;

    // Loop through all list items, and hide those who don't match the search query
    for (i = 0; i < keywordArray.length; i++) {
        a = keywordArray[i].getElementsByTagName("a")[0];
        if (a.innerHTML.toUpperCase().indexOf(filter) > -1)
        {
            keywordArray[i].style.display = "";
        }
        else
        {
            keywordArray[i].style.display = "none";
            nb_keyword_available--;
        }
    }

}

$("li").click(function() {
    filterList = $("#keyword-search").val().split(",");
    if (filterList.length > 1)
    {
        filterList = filterList.splice(0, filterList.length - 1);
        $("#keyword-search").val(filterList.concat() + "," + $(this).text());
    }
    else
    {
        $("#keyword-search").val($(this).text());
    }
});