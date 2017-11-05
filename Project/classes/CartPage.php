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

        echo "<div class='container bg-secondary vertical-layout'>";
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
            $this->displayTotalOf($cart);
            $this->displayValidationButton();

        }   
        echo "</div>";
    }

    /* Cart Item Methods */

    private function displayCartItem($cartItem)
    { ?>
        <div class='horizontal-layout'>
            <?php ImageHandler::displayCopyrightedImage($cartItem['image_name']); ?>
            <div class='details-container vertical-layout container-fluid'>
                <h2 class="text-white"><?php echo $cartItem['image_name'] ?></h2>
                <p><?php $this->displayDescriptionOf($cartItem); ?></p>
                <p><?php $this->displayPriceOf($cartItem); ?></p>
            </div>
            <?php $this->displayRemoveButton($cartItem) ?>
        </div>
        <div class='divider-horizontal bg-dark'></div>
    <?php
    }


    /* Display Method */
    private function displayDescriptionOf($cartItem)
    {
        $imageName = $cartItem['image_name'];
        $result = $this->sqlService->getData('image', 'description', array("where" => "name_image = '$imageName'"));
        echo "Description : {$result[0]['description']}";
    }
    private function displayPriceOf($cartItem)
    {
        echo "Prix : {$this->getPriceOf($cartItem)} €";
    }
    private function displayRemoveButton($cartItem)
    {
        echo "<span class='remove-cart-span text-danger' id='remove-{$cartItem['image_name']}'>&times;</span>";
    }

    private function displayValidationButton()
    {
        echo "<a onclick='validateCart()' id='validate-button'>Valider le paiement</a>";
    }




    /* Global Cart Methods */

    private function displayTotalOf($cart)
    {
        ?>
        <div id='total-container' class="horizontal-layout justify-content-between">
            <h3 id='nb-picture-cart'>
                <?php $this->displayPicturesQuantityIn($cart); ?>
            </h3>
            <h3>
                <?php $this->displayTotalPriceOf($cart); ?>
            </h3>
        </div>
    <?php
    }

    private function displayPicturesQuantityIn($cart)
    {
        echo "Nombre de photos dans le panier : " . sizeof($cart);
    }
    private function displayTotalPriceOf($cart)
    {
        $totalPrice = 0;
        foreach ($cart as $cartElement)
            $totalPrice += $this->getPriceOf($cartElement);

        echo "Prix Total : " . $totalPrice . " €";
    }

    /**
     * @param $username
     * @return array of images
     */
    private function getCartOf($username)
    {
        return $this->sqlService->getData('cart', 'image_name', array("where" => "username = '$username'"));
    }

    private function getPriceOf($cartItem) {
        $imageName = $cartItem['image_name'];
        $result = $this->sqlService->getData('image', 'price',
            array("where" => "name_image = '$imageName'")
        );

        return $result[0]['price'];
    }

}






