<?php 
include_once('dbconnect.php'); 
session_start();
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    header("Location: index.php");
    exit();
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_POST['add_to_cart'])) {
    $food_id = $_POST['food_id'];
    if (!isset($_SESSION['cart'][$food_id])) {
        $_SESSION['cart'][$food_id] = 0;
    }
    $_SESSION['cart'][$food_id]++;
}

if (isset($_POST['update_cart'])) {
    $food_id = $_POST['food_id'];
    $quantity = $_POST['quantity'];
    if ($quantity <= 0) {
        unset($_SESSION['cart'][$food_id]);
    } else {
        $_SESSION['cart'][$food_id] = $quantity;
    }
}

if (isset($_POST['complete_order'])) {

}

if (isset($_POST['clear_cart'])) {
    unset($_SESSION['cart']);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pizzabelle Inventory System</title>
    <link rel="stylesheet" href="stylesindex.css">
    <script>
        function updateCartCount() {
            const cartCount = document.getElementById('cart-count');
            let totalItems = 0;

            <?php foreach ($_SESSION['cart'] as $count) : ?>
                totalItems += <?php echo $count; ?>;
            <?php endforeach; ?>

            cartCount.textContent = totalItems > 0 ? totalItems : '';
        }

        document.addEventListener('DOMContentLoaded', updateCartCount);
    </script>
</head>
<body>
<nav>
    <div class="logo">
        <img src="logopizza1.png" alt="Logo">
    </div>
    <ul>
        <?php if (isset($_SESSION['user_id'])) { ?>
            <?php if ($_SESSION['role'] === 'manager') { ?>
                <li><a href="add_food.php">Update Menu</a></li>
                <li><a href="inventory.php">Manage Inventory</a></li>
            <?php } ?>
            <li><a href="index.php?action=logout">Logout</a></li>
        <?php } else { ?>
            <li><a href="login.php">Login</a></li>
        <?php } ?>
        <li><a href="#ordernow" class="order-now">Order Now</a></li>
        <li class="search-bar">
            <input type="text" id="searchQuery" placeholder="Search food items...">
            <button onclick="searchFood()">Search</button>
        </li>
    </ul>
</nav>

<div class="cover">
    <img src="cover1.jpg" alt="">
</div>

    <section id="ordernow">
        <div class="content">
            <div class="menu">
                <div class="food-container">
                    <?php 
                    $sql = "SELECT * FROM food_items";
                    $result = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) { 
                    ?>
                        <div class="food-item">
                            <img src="<?php echo $row['image_url']; ?>" alt="<?php echo $row['name']; ?>" style="width: 100%; height: auto; border-radius: 4px;">
                            <h2><?php echo $row['name']; ?></h2>
                            <p>Price: ₱<?php echo number_format($row['price'], 2); ?></p>
                            <form method="POST" action="">
                                <input type="hidden" name="food_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="add_to_cart" class="add-to-cart-button">Add to Cart</button>
                            </form>
                        </div>
                    <?php 
                        }
                    } else {
                        echo "<p>No food items available.</p>";
                    }
                    ?>
                </div>
            </div>

            <div class="order-summary">
                <center><h2 style="color: black;">Your Order</h2></center>
                <hr class="new1">
                <div id="summary-content">
                    <?php 
                    if (empty($_SESSION['cart'])) {
                        echo "<p>No items in your cart.</p>";
                    } else {
                        $totalAmount = 0;
                        echo "<table style='width: 100%; border-collapse: collapse;'>";

                        foreach ($_SESSION['cart'] as $food_id => $quantity) {
                            $sql = "SELECT * FROM food_items WHERE id = $food_id";
                            $result = mysqli_query($conn, $sql);
                            $row = mysqli_fetch_assoc($result);

                            $food_name = $row['name'];
                            $food_price = $row['price'];

                            echo "<form method='POST' action=''>";
                            echo "<input type='hidden' name='food_id' value='$food_id'>";
                            echo "<tr>";
                            echo "<td>$food_name: <input type='number' name='quantity' value='$quantity' min='1' style='width: 50px;'></td>";
                            echo "<td><button type='submit' name='update_cart'>Update</button></td>";
                            echo "</tr>";
                            echo "</form>";
                        }

                        echo "<tr><td colspan='2'><hr></td></tr>";

                        foreach ($_SESSION['cart'] as $food_id => $quantity) {
                            $sql = "SELECT * FROM food_items WHERE id = $food_id";
                            $result = mysqli_query($conn, $sql);
                            $row = mysqli_fetch_assoc($result);

                            $food_name = $row['name'];
                            $food_price = $row['price'];
                            $subtotal = $food_price * $quantity;
                            $totalAmount += $subtotal;

                            echo "<tr>";
                            echo "<td>$food_name x $quantity</td>";
                            echo "<td style='text-align: right;'>₱" . number_format($subtotal, 2) . "</td>";
                            echo "</tr>";
                        }

                        echo "<tr><td><strong>Item Total</strong></td><td style='text-align: right;'><strong>₱" . number_format($totalAmount, 2) . "</strong></td></tr>";
                        echo "<tr><td colspan='2'><hr></td></tr>";
                        echo "<tr><td><strong>TOTAL</strong></td><td style='text-align: right;'><strong>₱" . number_format($totalAmount, 2) . "</strong></td></tr>";
                        echo "</table>";
                    }
                    ?>
                    <form method="POST" action="">
                        <button type="submit" name="clear_cart">Clear Order</button>
                    </form>

                    <form method="POST" action="">
                        <button type="submit" name="complete_order" style="background-color: #f0c030; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">Complete Order</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
