$(document).ready(function () {
    var imageID;
    $("img").click(function()
    {
        imageID = $(this).attr("id");
        if(!isWebSiteGraphicPicture(imageID)) {
            insertModalContent($(this).attr("id"));
            openModal();
        }
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

           $("#action-container")
               .html("<p id='photo-already-in-cart'>Cette photo est déjà dans votre panier</p>");
       }
    })
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
    var imageName = extractImageName(imageID);
    insertImage(imageName);
    insertDetails(imageName);
}

function extractImageName(imageID)
{

    var imageIDPos = imageID.search("._image");
    return imageID.slice(0, imageIDPos);
}
function insertImage(imageName)
{
    $(".modal #modal-image-container").html("<img src=\"library/images_copyright/" + imageName + "\">");

}
function insertDetails(imageName)
{
    getImageDetailsWithAJAX(imageName, displayContent);
}

/* PHP Linked */

function getImageDetailsWithAJAX(imageName, callback)
{
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState === 4 && (xmlhttp.status === 200 || xmlhttp.status === 0)) {
            callback(xmlhttp.responseText);
        }
    };
    xmlhttp.open("GET", "scripts/getImageDetails.php?imageName=" + imageName, true);
    xmlhttp.send(null);
}

/**
 * display price, description and status of the photo.
 *
 * @param detailsString string Format : description/price/status
 */
function displayContent(detailsString)
{
    var detailsArray = detailsString.split("/");

    displayDetails(detailsArray[0], detailsArray[1]);
    displayImageStatus(detailsArray[2]);
}

function displayDetails(description, price) {

    var codeHtmlDetails = "" +
        "<div id='desc-container'>" +
            "<p>Description : </p>" +
            "<p id='desc'>" + description + "</p>" +
        "</div>" +
        "<div id='price-container' class='horizontal-layout'>" +
            "<p>Prix : </p>" +
            "<p id='price'>" + price + '€' + "</p>" +
        "</div>";
    $(".modal #details-container").html(codeHtmlDetails);

}
function displayImageStatus(status) {
    if (status === 'cart') {
        $("#action-container")
            .html("<p>Cette photo est déjà dans votre panier.</p>");
    }
    else if (status === 'owned') {
        $("#action-container")
            .html("<p>Vous avez déjà acheter cette photo</p>");
    }
    else {
        $("#action-container")
            .html("<input type='submit' name='submit-add-cart' id='add-cart-submit' " +
                         "class='btn btn-primary' value='Add to cart'>");
    }
    //         "<div id='submit-add-cart-container'><input type='submit' name='submit-add-cart' id='add-cart-submit' value='Add to cart'></div>";
}

/* Modal Handling */

function openModal()
{
    $(".modal").show(0);
}
function hideModal()
{
    $(".modal").hide(250);
}
