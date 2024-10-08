<?php 
include_once('dbconnect.php'); 
session_start();

if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    handlePostRequest($conn);
}

function handlePostRequest($conn) {
    if (isset($_POST['add_stock'])) {
        addStock($conn);
    } elseif (isset($_POST['update_stock'])) {
        updateStock($conn);
    } elseif (isset($_POST['use_stock'])) {
        useStock($conn);
    } elseif (isset($_POST['delete_food'])) {
        deleteFood($conn);
    }
}

function addStock($conn) {
    $item_name = $_POST['item_name'];
    $amount = $_POST['amount'];
    $expiration_date = $_POST['expiration_date'];
    $category = $_POST['category'];
    $target_file = uploadImage();

    if ($target_file) {
        $sql = "INSERT INTO inventory (item_name, amount, expiration_date, category, image_url) VALUES ('$item_name', '$amount', '$expiration_date', '$category', '$target_file')";
        mysqli_query($conn, $sql);
        echo "<script>alert('Stock added successfully!');</script>";
    }
}

function uploadImage() {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["item_image"]["name"]);
    $upload_ok = 1;
    $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if (getimagesize($_FILES["item_image"]["tmp_name"]) === false || $_FILES["item_image"]["size"] > 2000000 || !in_array($image_file_type, ['jpg', 'png', 'jpeg', 'gif'])) {
        echo "<script>alert('Invalid file upload.');</script>";
        return false;
    }

    if (move_uploaded_file($_FILES["item_image"]["tmp_name"], $target_file)) {
        return $target_file;
    } else {
        echo "<script>alert('Error uploading file.');</script>";
        return false;
    }
}

function updateStock($conn) {
    if (isset($_POST['item_id']) && isset($_POST['updated_amount'])) {
        $item_id = $_POST['item_id'];
        $updated_amount = $_POST['updated_amount'];

        $sql = "UPDATE inventory SET amount = amount + '$updated_amount' WHERE id = '$item_id'";
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Stock updated successfully!');</script>";
        } else {
            echo "<script>alert('Error updating stock: " . mysqli_error($conn) . "');</script>";
        }
    }
}

function useStock($conn) {
    if (isset($_POST['item_id']) && isset($_POST['used_amount'])) {
        $item_id = $_POST['item_id'];
        $used_amount = $_POST['used_amount'];

        $check_sql = "SELECT amount FROM inventory WHERE id = '$item_id'";
        $result = mysqli_query($conn, $check_sql);
        $row = mysqli_fetch_assoc($result);
        $current_amount = $row['amount'];

        if ($current_amount >= $used_amount) {
            $sql = "UPDATE inventory SET amount = amount - '$used_amount' WHERE id = '$item_id'";
            if (mysqli_query($conn, $sql)) {
                echo "<script>alert('Stock used successfully!');</script>";
            } else {
                echo "<script>alert('Error using stock: " . mysqli_error($conn) . "');</script>";
            }
        } else {
            echo "<script>alert('Not enough stock to use!');</script>";
        }
    }
}

function deleteFood($conn) {
    if (isset($_POST['food_id'])) {
        $food_id = $_POST['food_id'];
        $sql = "DELETE FROM food_items WHERE id = '$food_id'";
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Food item deleted successfully!');</script>";
        } else {
            echo "<script>alert('Error deleting food item: " . mysqli_error($conn) . "');</script>";
        }
    }
}

function displayInventory($category, $conn) {
    $sql = "SELECT * FROM inventory WHERE category='$category'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        echo "<div class='inventory-container'>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='inventory-item'>
                    <img src='{$row['image_url']}' alt='{$row['item_name']}' class='inventory-image'>
                    <h3>{$row['item_name']}</h3>
                    <p>Amount: <span class='item-amount'>{$row['amount']}</span></p>
                    <p>Expiry: {$row['expiration_date']}</p>
                    <form method='POST' action='' class='action-form'>
                        <input type='hidden' name='item_id' value='{$row['id']}'>
                        <input type='number' name='updated_amount' placeholder='Add amount' required>
                        <button type='submit' name='update_stock'>Update</button> 
                        
                    </form>
                    <br>
                    <form method='POST' action='' class='action-form'>
                        <input type='hidden' name='item_id' value='{$row['id']}'>
                        <input type='number' name='used_amount' placeholder='Use amount' required>
                        <button type='submit' name='use_stock'>Use</button>
                    </form>
                </div>";
        }
        echo "</div>";
    } else {
        echo "<p>No items in $category.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PizzaBelle Inventory Management</title>
    <link rel="stylesheet" href="stylesinventory.css">
</head>
<style>
nav a {
    color: white;
    text-decoration: none;
    transition: color 0.3s;
    font-size: 1.0rem;
}
    nav {
    background-color: #a25932e7;
    padding: 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
}
.dropdown-content a {
    color: #ff6347; 
    padding: 12px 16px;
    text-decoration: none;
    display: block;
    transition: background-color 0.3s;
    text-align: center;
}
nav img {
    height: 40px; 
}
h1, h2 {
    margin: 20px 0;
    color: #ff6347; ; 
    text-align: center;
}
.inventory-item {
    background-color: #fff; 
    border: 1px solid #ddd; 
    border-radius: 8px; 
    padding: 15px;
    width: calc(22% - 10px); 
    text-align: center; 
    margin: 10px 0; 
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); 
    transition: transform 0.3s;
}

</style>
<body>
    <nav class="navbar">
        <div class="logo">
            <img src="logo.png" alt="pizza belle">
        </div>
        <div class="navbar-toggle" id="navbar-toggle">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </div>
        <ul class="navbar-menu" id="navbar">
            <?php if (isset($_SESSION['user_id'])) { ?>
                <?php if ($_SESSION['role'] === 'manager') { ?>
                    <li><a href="add_food.php">Add New Food</a></li>
                    <li><a href="index.php">View Menu</a></li>
                    <li><a href="inventory.php">Manage Inventory</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle">Inventory</a>
                        <div class="dropdown-content">
                            <a href="#freezer">Freezer</a>
                            <a href="#chiller">Chiller</a>
                            <a href="#stockroom">Stockroom</a>
                        </div>
                    </li>
                <?php } ?>
                <li><a href="index.php?action=logout">Logout</a></li>
            <?php } else { ?>
                <li><a href="login.php">Login</a></li>
            <?php } ?>
        </ul>
    </nav>

    <div class="content">
        <h1>Pizza Belle's Inventory Management</h1>

        <h2>Add Stock</h2>
        <form method="POST" action="" class="add-stock-form" enctype="multipart/form-data">
            <input type="text" name="item_name" placeholder="Item Name" required>
            <input type="number" name="amount" placeholder="Amount" required>
            <input type="date" name="expiration_date" required>
            <input type="file" name="item_image" accept="image/*" required>
            <select name="category" required>
                <option value="Freezer">Freezer</option>
                <option value="Chiller">Chiller</option>
                <option value="Stockroom">Stockroom</option>
            </select>
            <button type="submit" name="add_stock">Add Stock</button>
        </form>

        <h2>Available Food Items</h2>
        <div class="available-food-container">
            <?php
            $query = "SELECT * FROM food_items"; 
            $result = $conn->query($query);
            
            if ($result) {
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        ?>
                        <div class="available-food-item">
                            <img src="<?php echo $row['image_url']; ?>" alt="<?php echo $row['name']; ?>" class="available-food-image">
                            <h3><?php echo $row['name']; ?></h3>
                            <p>Price: <?php echo $row['price']; ?> PHP</p>
                            <form method="POST" action="">
                                <input type="hidden" name="food_id" value="<?php echo $row['id']; ?>"> <br>
                                <button type="submit" name="delete_food">Delete Food</button>
                            </form>
                        </div>
                        <?php
                    }
                } else {
                    echo "<p>No food items available.</p>";
                }
            } else {
                echo "<p>Error retrieving food items: " . $conn->error . "</p>";
            }
            ?>
        </div>

        <h2>Inventory by Category</h2>
        <h3 id="freezer">Freezer</h3>
        <?php displayInventory('Freezer', $conn); ?>
        <h3 id="chiller">Chiller</h3>
        <?php displayInventory('Chiller', $conn); ?>
        <h3 id="stockroom">Stockroom</h3>
        <?php displayInventory('Stockroom', $conn); ?>
    </div>

    <script>
        document.getElementById("navbar-toggle").onclick = function() {
            var navbar = document.getElementById("navbar");
            navbar.classList.toggle("active");
        };
    </script>
</body>
</html>
