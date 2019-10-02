<?php
require 'classes/Product.php';
require 'classes/ProductCatalogue.php';
require 'classes/ShoppingCart.php';

session_start();

/**
 * Als er nog geen winkelwagen is opgeslagen in de sessie
 * dan wordt hij hier aangemaakt en in de sessie opgeslagen
 */
if (empty($_SESSION['cart'])) {
    $_SESSION['cart'] = new ShoppingCart();
}

$productCatalogue = new ProductCatalogue('products.json');
$shoppingCart = $_SESSION['cart'];

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'add_product':
            $product_code = $_GET['code'];
            $product = $productCatalogue->getProduct($product_code);
            $shoppingCart->addProduct($product);
            header('Location: cart.php');
            break;
        case 'remove_item':
            $item_index = $_GET['item_index'];
            $shoppingCart->removeItem($item_index);
            header('Location: cart.php');
            break;

            case 'remove_all_items':
            $shoppingCart->removeAllItems();
            header('Location: cart.php');
            break;
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cart</title>
    <link href="https://fonts.googleapis.com/css?family=Oswald:400,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<section class="webshop">
    <h2 class="webshop__title">My first webshop <a href="cart.php" class="cart-icon">Cart</a></h2>
    <div class="webshop__content">
        <div class="shopping-cart">
            <h2>Shopping Cart</h2>

            <?php if ($shoppingCart->hasProducts()): ?>

                <p>This is now in your shopping cart:</p>

                <?php foreach ($shoppingCart->getProducts() as $index => $product): ?>
                    <div class="shopping-cart__item">
                        <h4><?php echo $product->getTitle() ?> </h4>
                        <span class="price">&nbsp; &euro; <?php echo $product->getPrice() ?></span>
                        <img src="<?php echo $product->getImage() ?>">
                        <a href="cart.php?action=remove_item&item_index=<?php echo $index?>">Remove</a>
                    </div>
                <?php endforeach; ?>
                <strong>Total amount: &euro; <?php echo $shoppingCart->getFullPrice() ?> </strong>

            <?php else: ?>

                <p>You have no items in your shopping cart</p>

            <?php endif; ?>
        </div>
    </div>
    <footer>
        <a href="index.php">To the products</a> &nbsp;
        <a href="cart.php?action=remove_all_items">Remove all items</a>
    </footer>
</section>
</body>
</html>
