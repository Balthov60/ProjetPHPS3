<?php
include_once("HeaderBar.php");
include_once("AdminPanel.php");
include_once("FooterBar.php");
class CartPage
{
    function __construct(SQLServices $sqlService)
    {
        new HeaderBar(true, false, "Cart", $sqlService);

        $this->displayCart();

        new FooterBar();
    }



    /*** Display method ***/

    function displayCart()
    {
        $username = $_SESSION['user']['username'];
        $cart = $this->getCartOf($username);

        if(empty($cart))
            echo "<div id='cart-container'><p>You don't selected photos, you cart is empty!</p></div>";
        else
            $this->display($cart);
    }

    /**
     * @param $userID
     * @return data
     */
    function getCartOf($username)
    {
        $result = $this->sqlService->getData('cart', 'image_name', array("where" => "username = '$username'"));
        return $result;
    }


    function display($cartArray)
    {
        echo "<div id='cart-container'>";

        foreach($cartArray as $cartElement)
        {

            echo "<div class='cart-element'>";

            $this->displayImage($cartElement);

            echo "<div class='details-container'>";

            $this->displayDescription($cartElement);
            $this->displayPrice($cartElement);

            echo "</div>";

            $this->displayRemoveButton($cartElement);

            echo "</div>";

            echo "<div class='divider-horizontal'></div>";
        }

        echo "</div>";
        $this->displayTotalOf($cartArray);
    }


    function displayImage($cartElement)
    {
        $imageName = $cartElement['image_name'];
        ImageHandler::displayCopyrightedImage($imageName);
    }

    function displayDescription($cartElement)
    {
        $description = $this->getDescriptionOf($cartElement);
        echo "<p>$description</p>";
    }

    function displayPrice($cartElement)
    {
        $price = $this->getPriceOf($cartElement);
        echo "<p>$price</p>";
    }

    function displayRemoveButton($cartElement)
    {
        $imageName = $cartElement['image_name'];
        echo "<span class='remove-cart-span' id='remove-$imageName'>&times;</span>";
    }

    function displayTotalOf($cart)
    {
        $price = $this->getTotalPriceOf($cart);
        $nbPicture = $this->getPictureNumberIn($cart);

        echo "<p id='nb-picture-cart'>Number of picture selected :  $nbPicture</p></br><p>Total : $price € </p>";
    }

    function getPriceOf($cartElement)
    {
        $imageName = $cartElement['image_name'];
        $result = $this->sqlService->getData('image', 'price', array("where" => "name_image = '$imageName'"));
        return $result[0]['price'];
    }

    function getDescriptionOf($cartElement)
    {
        $imageName = $cartElement['image_name'];
        $result = $this->sqlService->getData('image', 'description', array("where" => "name_image = '$imageName'"));
        return $result[0]['description'];
    }

    function getTotalPriceOf($cart)
    {
        $priceCounter = 0;
        foreach ($cart as $cartElement)
            $priceCounter += $this->getPriceOf($cartElement);

        return $priceCounter;
    }

    function getPictureNumberIn($cart)
    {
        $pictureCounter = 0;
        foreach ($cart as $cartElement)
            $pictureCounter ++;

        return $pictureCounter;
    }
}






