<?php
include_once("HeaderBar.php");
include_once("FooterBar.php");
include_once("ImageHandler.php");

class CartPage
{
    private $sqlService;

    /**
     * CartPage constructor. Display cart page.
     *
     * @param SQLServices $sqlService
     */
    function __construct(SQLServices $sqlService)
    {
        $this->sqlService = $sqlService;

        new HeaderBar(true, false, "Cart", $sqlService);

        $this->displayCartContent();

        new FooterBar();
    }

    /*******************/
    /* Display Methods */
    /*******************/

    /**
     * Display user cart content. list of item, quantity of item and total price.
     */
    private function displayCartContent()
    {
        $cart = $this->getCartForCurrentUser();

        echo "<div class='container bg-secondary vertical-layout main-page-content'>";
        if(empty($cart))
        {
            echo "<h2 class='text-center text-dark empty-content'>Votre panier est vide!</h2>";
        }
        else
        {
            foreach($cart as $cartItem)
            {
                $this->displayCartItem($cartItem);
            }
            echo "<div id='total-container' class='horizontal-layout justify-content-between'>";
                $this->displayTotalPriceAndQuantityOf($cart);
                $this->displayValidationButton();
            echo "</div>";

        }   
        echo "</div>";
    }


    /**
     * Display image, title, description and price of a cart item and display a remove button.
     *
     * @param $cartItem
     */
    private function displayCartItem($cartItem)
    { ?>
        <div class='horizontal-layout'>
            <?php ImageHandler::displayCopyrightedImage($cartItem['image_name']); ?>
            <div class='details-container vertical-layout container-fluid'>
                <h2 class="text-white">
                    <?php echo $this->sqlService->removeExtensionFromImageName($cartItem['image_name']) ?>
                </h2>
                <p>
                    <?php $this->displayDescriptionOf($cartItem); ?>
                </p>
                <p>
                    <?php $this->displayPriceOf($cartItem); ?>
                </p>
            </div>
            <?php $this->displayRemoveButton($cartItem) ?>
        </div>
        <div class='divider-horizontal bg-dark'></div>
    <?php
    }

    /**
     * Display description of a cart item.
     *
     * @param $cartItem
     */
    private function displayDescriptionOf($cartItem)
    {
        $imageName = $cartItem['image_name'];
        $result = $this->sqlService->getData('image', 'description', array("where" => "image_name = '$imageName'"));
        echo "Description : {$result[0]['description']}";
    }

    /**
     * Display price of a cart item.
     *
     * @param $cartItem
     */
    private function displayPriceOf($cartItem)
    {
        echo "Prix : {$this->getPriceOf($cartItem)} €";
    }

    /**
     * Display remove button for a cart item.
     *
     * @param $cartItem
     */
    private function displayRemoveButton($cartItem)
    {
        echo "<span class='remove-cart-span text-danger' id='remove-{$cartItem['image_name']}'>&times;</span>";
    }


    /**
     * Display total price and quantity for cart.
     *
     * @param $cart
     */
    private function displayTotalPriceAndQuantityOf($cart)
    {
        ?>
        <h3 id='nb-picture-cart'>
            <?php $this->displayPicturesQuantityIn($cart); ?>
        </h3>
        <h3>
            <?php $this->displayTotalPriceOf($cart); ?>
        </h3>
    <?php
    }

    /**
     * Display pictures quantity for cart.
     *
     * @param $cart
     */
    private function displayPicturesQuantityIn($cart)
    {
        echo "Nombre de photos dans le panier : " . sizeof($cart);
    }

    /**
     * Display total price for cart.
     *
     * @param $cart
     */
    private function displayTotalPriceOf($cart)
    {
        $totalPrice = 0;
        foreach ($cart as $cartElement)
            $totalPrice += $this->getPriceOf($cartElement);

        echo "Prix Total : " . $totalPrice . " €";
    }

    /**
     * Display Cart validation Button.
     */
    private function displayValidationButton()
    { ?>
        <a href='../../../ProjetPHPS3/Project/scripts/validateCart.php' class='btn btn-danger'>
            Valider le paiement
        </a>
    <?php
    }

    /****************/
    /* Data Methods */
    /****************/

    /**
     * Get cart for current user.
     *
     * @return array of images
     */
    private function getCartForCurrentUser()
    {
        $username = $_SESSION['user']['username'];
        return $this->sqlService->getData('cart', 'image_name', array("where" => "username = '$username'"));
    }

    /**
     * Get price for current $cartItem.
     *
     * @param $cartItem
     * @return float price of cart item
     */
    private function getPriceOf($cartItem) {
        $imageName = $cartItem['image_name'];

        $result = $this->sqlService->getData('image', 'price',
            array("where" => "image_name = '$imageName'")
        );

        return $result[0]['price'];
    }

}






