<?php
// Include the db_connect.php file to connect to the database
require_once 'db_connect.php';

session_start();

// Add a new product
if (isset($_POST['add'])) {
    $name = $_POST['product-name'];
    $category = $_POST['product-category'];
    $quantity = $_POST['product-quantity'];
    $description = $_POST['product-description'];
    $price = $_POST['product-price'];

    // Get uploaded file info
    $file = $_FILES['product-image'];
    $filename = $file['name'];
    $tmp_name = $file['tmp_name'];
    $size = $file['size'];
    $error = $file['error'];
    $extension = pathinfo($filename, PATHINFO_EXTENSION);
    $upload_dir = 'images/products/';

    // Generate a unique filename using a prefix and the current timestamp
    $unique_filename = 'pic_' . uniqid() . '.' . $extension;
    $upload_path = $upload_dir . $unique_filename;

    // Validate the uploaded file
    if (isset($tmp_name)) {
        if ($size > 100000) {
            $_SESSION['error'] = 'Error - File Size too Large';
            header('Location: dash-prod.php');
            exit();
        }

        if (!in_array($extension, ['jpg', 'png', 'jpeg', 'gif'])) {
            $_SESSION['error'] = 'Error - Wrong File Type';
            header('Location: dash-prod.php');
            exit();
        }
    }
    
    if (!empty(trim($name))){
    // Move the uploaded file to the designated directory
    if (move_uploaded_file($tmp_name, $upload_path)) {
        $stmt = $conn->prepare("INSERT INTO `product`(`name`, `category`, `quantity`, `price`, `description`, `picture`) VALUES (:name, :category, :quantity, :price, :description, :picture)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':picture', $upload_path);

        if ($stmt->execute()) {
            $_SESSION['success'] = "New Product added";
        } else {
            $_SESSION['error'] = "Product not added";
        }
    }
}

else{
    $_SESSION['error'] = "Enter the Product name";
}
    header('Location: dash-prod.php');
}

// Edit an existing product
if (isset($_POST['edit'])) {
    // Get the product ID and updated product information from the form
    $product_id = $_POST['product-id'];
    $new_name = $_POST['product-name'];
    $new_category = $_POST['product-category'];
    $new_quantity = $_POST['product-quantity'];
    $new_description = $_POST['product-description'];
    $new_price = $_POST['product-price'];

    // Check if the product with the given ID exists in the database
    $stmt = $conn->prepare("SELECT * FROM product WHERE id = :id");
    $stmt->bindParam(':id', $product_id);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    // If the product is not found, display an error message and redirect to the dashboard page
    if (!$product) {
        $_SESSION['error'] = 'Error - Product Not Found';
        header('Location: dash-prod.php');
        exit();
    }

    // Check if a new product image file is uploaded
    if ($_FILES['product-image']['tmp_name']) {
        $file = $_FILES['product-image'];
        $filename = $file['name'];
        $tmp_name = $file['tmp_name'];
        $size = $file['size'];
        $error = $file['error'];
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $upload_dir = 'images/products/';

        // Generate a unique filename for the uploaded image
        $unique_filename = 'pic_' . uniqid() . '.' . $extension;
        $upload_path = $upload_dir . $unique_filename;

        // Validate the uploaded file
        if ($size > 100000) {
            $_SESSION['error'] = 'Error - File Size too Large';
            header('Location: dash-prod.php');
            exit();
        } elseif (!in_array($extension, ['jpg', 'png', 'jpeg', 'gif'])) {
            $_SESSION['error'] = 'Error - Wrong File Type';
            header('Location: dash-prod.php');
            exit();
        } else {
            // Move the uploaded file to the designated directory
            move_uploaded_file($tmp_name, $upload_path);
            
            // Delete the previous product image
            unlink($product['picture']);
            
            // Update the product image path in the database
            $stmt = $conn->prepare("UPDATE `product` SET `picture`=? WHERE `id`=?");
            $stmt->execute([$upload_path, $product_id]);
        }
    }

    // Update the product information in the database
    $stmt = $conn->prepare("UPDATE `product` SET `name`=?, `category`=?, `quantity`=?, `price`=?, `description`=? WHERE `id`=?");
    $stmt->execute([$new_name, $new_category, $new_quantity, $new_price, $new_description, $product_id]);
    
    // Set a success message and redirect to the dashboard page
    $_SESSION['success'] = 'Product Updated Successfully';
    header('Location: dash-prod.php');
}



// Remove a product
// Check if the 'remove' button is clicked
if (isset($_POST['remove'])) {
    // Get the product ID and product image from the form
    $id = $_POST['product-id-delete'];
    $product_image = $_POST['product-image-delete'];

    // Prepare the SQL statement to delete the product from the database
    $stmt = $conn->prepare('DELETE FROM `product` WHERE `id`=:id');

    try {
        // Execute the SQL statement with the product ID as a parameter
        if ($stmt->execute(array(':id' => $id))) {
            // Check if the product image file exists and delete it from the server
            if (file_exists($product_image)) {
                unlink($product_image);
            }

            // Set a success message to be displayed
            $_SESSION['success'] = "Product deleted";
        } else {
            // Set an error message to be displayed
            $_SESSION['error'] = "Product not deleted";
        }
    } catch (PDOException $ex) {
        // Set an error message with the exception details
        $_SESSION['error'] = "Product not deleted - " . $ex->getMessage();
    }

    // Redirect the user to the dashboard page
    header('Location: dash-prod.php');
    exit();
}

?>
