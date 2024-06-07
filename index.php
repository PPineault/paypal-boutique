<?php
//enlever lignes 3 et 4 quand c'est en ligne
error_reporting(E_ALL);
ini_set("display_error", 1);

$message = '';

if (isset($_POST['add_to_cart'])) {
    if (isset($_COOKIE['shopping_cart'])) {
        $cookie_data = $_COOKIE['shopping_cart'];

        $cart_data = json_decode($cookie_data, true);
    } else {

        $cart_data = array();
    }

    $item_list = array_column($cart_data, 'hidden_id');

    if (in_array($_POST["hidden_id"], $item_list)) {
        foreach ($cart_data as $k => $v) {
            if ($cart_data[$k]["hidden_id"] == $_POST["hidden_id"]) {
                $cart_data[$k]["quantity"] = $cart_data[$k]["quantity"] + $_POST["quantity"];
            }
        }
    } else {
        $item_array = array(
            'hidden_id' => $_POST['hidden_id'],
            'hidden_name' => $_POST['hidden_name'],
            'hidden_price' => $_POST['hidden_price'],
            'quantity' => $_POST['quantity']
        );

        $cart_data[] = $item_array;
    }

    $item_data = json_encode($cart_data);
    setcookie('shopping_cart', $item_data, time() + (86400 * 30));
    //redirection
    header("location:index.php?success=1");
}
//vider le panier
if (isset($_GET["action"]) == "clear") {
    setcookie("shopping_cart", "", time() - 3600);
    header("location:index.php?clearAll=1");
}
// [element2]
if (isset($_GET["action"]) == "delete") {
    $cookie_data = stripslashes($_COOKIE['shopping_cart']);
    $cart_data = json_decode($cookie_data, true);
    foreach ($cart_data as $k => $v) {
        if ($cart_data[$k]["hidden_id"] == $_GET['id']) {
            unset($cart_data[$k]);
            $item_data = json_encode($cart_data);
            setcookie('shopping_cart', $item_data, time() + (86400 * 30));
            header("location:index.php?remove=1");
        }
    }
}

if (isset($_GET['success'])) {
    $message = '
    <div  class="unpeuplusgros">
        le produit a été ajouté avec succès
    </div>
    ';
}

if (isset($_GET['remove'])) {
    $message = '
    <div  class="unpeuplusgros">
        le produit a été enlevé avec succès
    </div>
    ';
}

if (isset($_GET['clearAll'])) {
    $message = '
    <div class="unpeuplusgros">
        le panier a été vidé !
    </div>
    ';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>boutique paypal cookie </title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js" defer></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" defer></script>
    <style>
        body {
            background: #333;
            color: white;
        }

        .product img {
            aspect-ratio: 1 / 1;
            width: 100%;
            object-fit: contain;
        }

        input[type=submit] {
            color: #222;
            background: #c1c1c1;
            border: none;
            padding: 2px;
        }

        input[type=text] {
            border: none;
            padding: 2px;
            color: #222;
            text-align: center;
        }

        .plusgros {
            font-size: 25px;
        }

        .unpeuplusgros {
            font-size: 22px;
        }
    </style>
</head>

<body>
    <?php
    if (isset($_GET['msg'])  && $_GET['msg'] === 'success') {
        echo '<p class="plusgros">Merci pour votre commande ! </p>';
    }
    if (isset($_GET['msg'])  && $_GET['msg'] === 'failed') {
        echo '<p class="plusgros">Votre transaction a été annulée! </p>';
    }

    ?>



    <div class="container">
        <h1>Ma super boutique</h1>
        <?php
        if (isset($_COOKIE['shopping_cart'])) {
            //    print_r($_COOKIE['shopping_cart']);
        }
        echo $message;
        ?>
        <div class="col-md-4">
            <form method="post">
                <div class="product">
                    <img src="images/1.jpg" alt="" width="250">
                    <h4>ourson en peluche</h4>
                    <h4>420.69$</h4>
                    <input type="text" name="quantity" value="1">
                    <input type="hidden" name="hidden_name" value="ourson en peluche">
                    <input type="hidden" name="hidden_price" value="420.69">
                    <input type="hidden" name="hidden_id" value="1">
                    <input type="submit" value="Ajouter au panier" name="add_to_cart">
                </div>
            </form>
        </div>

        <div class="col-md-4">
            <form action="" method="post">
                <div class="product">
                    <img src="images/2.jpg" alt="" width="250">
                    <h4>Gramophone</h4>
                    <h4>5789.27$</h4>
                    <input type="text" name="quantity" value="1">
                    <input type="hidden" name="hidden_name" value="Gramophone">
                    <input type="hidden" name="hidden_price" value="5789.27">
                    <input type="hidden" name="hidden_id" value="2">
                    <input type="submit" value="Ajouter au panier" name="add_to_cart">
                </div>
            </form>
        </div>
        <div class="col-md-4">
            <form action="" method="post">
                <div class="product">
                    <img src="images/3.jpg" alt="" width="250">
                    <h4>Livre coder proprement en français</h4>
                    <h4>0.10$</h4>
                    <input type="text" name="quantity" value="1">
                    <input type="hidden" name="hidden_name" value="livre coder proprement">
                    <input type="hidden" name="hidden_price" value="0.10">
                    <input type="hidden" name="hidden_id" value="3">
                    <input type="submit" value="Ajouter au panier" name="add_to_cart">
                </div>
            </form>
        </div>

        <div class="col-md-4">
            <form action="" method="post">
                <div class="product">
                    <img src="images/4.jpg" alt="" width="250">
                    <h4> Drapeau du Québec</h4>
                    <h4>2$</h4>
                    <input type="text" name="quantity" value="1">
                    <input type="hidden" name="hidden_name" value="Drapeau du Quebec">
                    <input type="hidden" name="hidden_price" value="2">
                    <input type="hidden" name="hidden_id" value="4">
                    <input type="submit" value="Ajouter au panier" name="add_to_cart">
                </div>
            </form>
        </div>
        <div class="col-md-4">
            <form action="" method="post">
                <div class="product">
                    <img src="images/5.jpg" alt="" width="250">
                    <h4> orange</h4>
                    <h4>27.27$</h4>
                    <input type="text" name="quantity" value="1">
                    <input type="hidden" name="hidden_name" value="orange">
                    <input type="hidden" name="hidden_price" value="27.27">
                    <input type="hidden" name="hidden_id" value="5">
                    <input type="submit" value="Ajouter au panier" name="add_to_cart">
                </div>
            </form>
        </div>
        <div class="col-md-4">
            <form action="" method="post">
                <div class="product">
                    <img src="images/6.jpg" alt="" width="250">
                    <h4> fourchette</h4>
                    <h4>0.5$</h4>
                    <input type="text" name="quantity" value="1">
                    <input type="hidden" name="hidden_name" value="fourchette">
                    <input type="hidden" name="hidden_price" value="0.5">
                    <input type="hidden" name="hidden_id" value="6">
                    <input type="submit" value="Ajouter au panier" name="add_to_cart">
                </div>
            </form>
        </div>

        <div style="clear:both">
            <br />
            <a href="index.php?action=clear">Vider le panier</a>
            <h3>Details de la commande</h3>
            <table>
                <tr>
                    <td width="40%">Nom</td>
                    <td width="20%">Quantite</td>
                    <td width="20%">Prix</td>
                    <td width="20%">total</td>
                    <td width="20%">Action</td>
                </tr>
                <?php
                if (isset($_COOKIE['shopping_cart'])) {
                    $total = 0;
                    $cookie_data = stripslashes($_COOKIE['shopping_cart']);
                    $cart_data = json_decode($cookie_data, true);
                    foreach ($cart_data as $k => $v) {
                        $total += $v["quantity"] * $v["hidden_price"];


                ?>
                        <tr>
                            <td><?php echo $v["hidden_name"]; ?></td>
                            <td><?php echo $v["quantity"]; ?></td>
                            <td><?php echo $v["hidden_price"]; ?></td>
                            <td><?php echo number_format($v["quantity"] * $v["hidden_price"], 2) ?>$</td>
                            <td><a href="index.php?action=delete&id=<?php echo $v["hidden_id"]; ?>">Effacer</a></td>
                        </tr>



                <?php
                    }
                } else {
                    echo "<tr>Ton panier est vide</tr>";
                }
                ?>

            </table>
            <hr>
            <strong> Total: </strong>

            <div id="total_price" style="margin-left:1100px;"> <?php echo number_format($total ?? 0, 2);
                                                                ?> </div>
            <div id="paypal-button" style="margin-top:10px;"></div>
        </div>
        <!-- clé api paypal à changer ici pour la votre-->
        <script src=""> </script>
        <script src="paypal.js"></script>

</body>

</html>