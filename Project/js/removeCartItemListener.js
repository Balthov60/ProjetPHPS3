$(document).ready(function()
{
    $(".remove-cart-span").click(function() //Detect click on suppression span
    {
        deleteCartItem($(this).attr("id"));
    });
});

/**
 * Call php script to remove cart item.
 *
 * @param button_id
 */
function deleteCartItem(button_id)
{
    window.location.href = "scripts/removeCartItem.php?buttonID=" + button_id;
}