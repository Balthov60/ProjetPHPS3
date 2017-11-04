$(document).ready(function()
{
    $(".remove-cart-span").click(function() //Detect click on suppression span
    {
        deleteCartItem($(this).attr("id"));
    });
});

function deleteCartItem(button_id)
{
    window.location.href = "scripts/removeCartItem.php?buttonID=" + button_id;
}