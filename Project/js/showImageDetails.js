$(document).ready(function () {
    var imageID;

    $("img").click(function()
    {
        imageID = $(this).attr("id");
        if(!isWebSiteGraphicPicture(imageID))
            insertModalContent($(this).attr("id"));
    });

    $(document).click(function(event)
    {
       if(event.target.className === $(".modal").attr("class"))
       {
           hideModal();
       }
       else if(event.target.id === $("#add-cart-submit").attr("id"))
       {
           var xmlhttp = new XMLHttpRequest();
           xmlhttp.open("GET", "scripts/addImageToCart.php?imageID=" + imageID, true);
           xmlhttp.send(null);
           alert("added to cart");
       }
    });
});


/**
 * Check If ImageID is part of real image or if it is just a website graphical component.
 *
 * @param imageID
 * @returns {boolean}
 */
function isWebSiteGraphicPicture(imageID)
{
    return imageID === "advanced-menu-button" || imageID === "logo";
}

/* Modal Displaying functions */

function insertModalContent(imageID)
{
    writeHTMLImageCode(extractImageName(imageID));
    writeHTMLImageDetailsCode(extractImageName(imageID));
}

function writeHTMLImageCode(imageName)
{
    var htmlCodeImage = "<img src=\"library/images_copyright/" + imageName + "\">";
    $(".modal #image-container").html(htmlCodeImage);

}
function writeHTMLImageDetailsCode(imageName)
{
    getImageDetailsWithAJAX(imageName, displayData);
}

function extractImageName(imageID)
{
    var imageIDPos = imageID.search("._image");
    return imageID.slice(0, imageIDPos);
}

/* PHP Linked */

function getImageDetailsWithAJAX(imageName, callback) //AJAX Method
{
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState === 4 && (xmlhttp.status === 200 || xmlhttp.status === 0)) {
            var responseText = xmlhttp.responseText;
            callback(responseText);
        }
    };
    xmlhttp.open("GET", "scripts/getImageDetails.php?imageName=" + imageName, true);
    xmlhttp.send(null);
}
function displayData(detailsString)
{
    var detailsArray = extractDetailsFromString(detailsString);
    var description = detailsArray[0];
    var price = detailsArray[1];

    var codeHtmlDetails = "" +
        "<div id='desc-container'>" +
            "<p>Description : </p>" +
            "<p id='desc'>" + description + "</p>" +
        "</div>" +
        "<div id='price-container'>" +
            "<p>Price : </p>" +
            "<p id='price'>" + price + "</p>" +
        "</div>" +
        "<input type='submit' name='submit-add-cart' id='add-cart-submit' value='Add to cart'>";
    $(".modal #details-container").html(codeHtmlDetails);

    openModal();
}
function extractDetailsFromString(responseString)
{
    return responseString.split("/");
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
