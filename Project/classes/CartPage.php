<?php
include_once("HeaderBar.php");
include_once("FooterBar.php");
include_once("ImageHandler.php");

class CartPage
{
    private $sqlService;

    function __construct(SQLServices $sqlService)
    {
        $this->sqlService = $sqlService;

        new HeaderBar(true, false, "Cart", $sqlService);

        $this->displayCartContent();

        new FooterBar();
    }

    private function displayCartContent()
    {
        $cart = $this->getCartOf($_SESSION['user']['username']);

        if(empty($cart))
        {
            echo "<div id='cart-container'><p>Your cart is empty!</p></div>";
        }
        else
        {
            echo "<div id='cart-container'>";
            foreach($cart as $cartItem)
            {
                $this->displayCartItem($cartItem);
            }
            echo "</div>";
            $this->displayTotalOf($cart);
        }
    }

    /* Cart Item Methods */

    private function displayCartItem($cartItem)
    { ?>
        <div class='cart-element'>
            <?php ImageHandler::displayCopyrightedImage($cartItem['image_name']); ?>
            <div class='details-container'>
                <p><?php $this->getDescriptionOf($cartItem); ?></p>
                <p><?php $this->getPriceOf($cartItem); ?></p>
            </div>
            <?php $this->displayRemoveButton($cartItem) ?>
        </div>
        <div class='divider-horizontal'></div>
    <?php
    }
    function displayRemoveButton($cartItem)
    {
        echo "<span class='remove-cart-span' id='remove-{$cartItem['image_name']}'>&times;</span>";
    }

    function getPriceOf($cartElement)
    {
        $imageName = $cartElement['image_name'];
        $result = $this->sqlService->getData('image', 'price',
            array("where" => "name_image = '$imageName'")
        );
        return $result[0]['price'];
    }
    function getDescriptionOf($cartElement)
    {
        $imageName = $cartElement['image_name'];
        $result = $this->sqlService->getData('image', 'description', array("where" => "name_image = '$imageName'"));
        return $result[0]['description'];
    }

    /* Global Cart Methods */

    function displayTotalOf($cart)
    {
        $price = $this->getTotalPriceOf($cart);
        $pictureQty = $this->getPictureQuantityIn($cart);

        echo "<p id='nb-picture-cart'>Number of picture selected : $pictureQty</p>
              </br><p>Total : $price €</p>";
    }

    function getTotalPriceOf($cart)
    {
        $totalPrice = 0;
        foreach ($cart as $cartElement)
            $totalPrice += $this->getPriceOf($cartElement);

        return $totalPrice;
    }
    function getPictureQuantityIn($cart)
    {
        return sizeof($cart);
    }

    /**
     * @param $username
     * @return array of images
     */
    private function getCartOf($username)
    {
        return $this->sqlService->getData('cart', 'image_name', array("where" => "username = '$username'"));
    }
}






