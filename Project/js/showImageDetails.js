$(document).ready(function () {
    $("img").click(function()
    {
        insertModalContent($(this).attr("id"));
    });

    $(".modal").click(function(){
        hideModal()
    })
});


/* Modal Content Insertion */

/**
 *
 * @param imageSelectedID
 */
function insertModalContent(imageSelectedID)
{

    insertSelectedImage(imageSelectedID);
    insertSelectedImageDetails(imageSelectedID);
}

/**
 *
 * @param imageSelectedID
 */
function insertSelectedImage(imageSelectedID)
{
    var imageName = getSelectedImageName(imageSelectedID);
    writeHTMLImageCode(imageName);

}

/**
 *
 * @param imageSelectedID
 * @returns {Array.<T>|string|Blob|ArrayBuffer|*}
 */
function getSelectedImageName(imageSelectedID)
{
    var imageIDPos = imageSelectedID.search("._image");
    var realImageName = imageSelectedID.slice(0, imageIDPos);
    return realImageName;
}

/**
 *
 * @param imageName
 */
function writeHTMLImageCode(imageName)
{
    var htmlCodeImage = "<img src=\"library/images_copyright/" + imageName + "\">";
    $(".modal #image-container").html(htmlCodeImage);

}

/**
 *
 * @param imageSelectedID
 */
function insertSelectedImageDetails(imageSelectedID)
{
    var imageName = getSelectedImageName(imageSelectedID);
    writeHTMLImageDetailsCode(imageName);
}

/**
 *
 * @param imageName
 */
function writeHTMLImageDetailsCode(imageName)
{
    getImageDetailsWithAJAX(imageName, displayData);
}


/**
 *
 * @param imageName
 * @param callback
 */
function getImageDetailsWithAJAX(imageName, callback) //AJAX Method
{
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && (xmlhttp.status == 200 || xmlhttp.status == 0)) {
            var responseText = xmlhttp.responseText;
            callback(responseText);
        }
    };
    xmlhttp.open("GET", "scripts/getImageDetails.php?imageName=" + imageName, true);
    xmlhttp.send(null);
}

/**
 *
 * @param detailsString
 */
function displayData(detailsString)
{
    var detailsArray = extractDetailsFromString(detailsString);
    var description = detailsArray[0];
    var price = detailsArray[1];

    var codeHtmlDetails = "<p>Description</p>" + description + "<p>Price</p>" + price;
    $(".modal #details-container").html(codeHtmlDetails);

    openModal();
}

/**
 *
 * @param responseString
 * @returns {Array}
 */
function extractDetailsFromString(responseString)
{
    var responseArray = responseString.split("/");
    return responseArray;
}


/* Modal Handling */

function openModal()
{
    $(".modal").show(150);
}

function hideModal()
{
    $(".modal").hide(150);
}
