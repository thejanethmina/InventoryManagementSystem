<?php

// Include the db_connect.php file to connect to the database
require_once 'db_connect.php';

// Start the session
session_start();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Retrieve the table data from the form
    if(isset($_POST['id'])){
    $ids = $_POST['id'];
    $names = $_POST['name'];
    $quantities = $_POST['quantity'];
    $prices = $_POST['price'];
    $totalAmounts = $_POST['totalAmount'];
    $customerId = $_POST['customer_id'];
    $orderTotalAmount = $_POST['orderTotalAmount'];

    // Start a transaction
    $conn->beginTransaction();

    if($customerId == 0){ 
        $_SESSION['error'] = "You need to select the customer first";
        header('Location: dash-order.php');
        exit();
     }

    try {
        // Insert the order into the "orders" table
        $orderData = [
            'customer_id' => $customerId,
            'total_order_amount' => $orderTotalAmount
        ];
        $orderInsertStmt = $conn->prepare("INSERT INTO `orders`(`customer_id`, `order_date`, `total_order_amount`) VALUES (:customer_id, NOW(), :total_order_amount)");
        $orderInsertStmt->execute($orderData);

        // Retrieve the last inserted order ID
        $orderId = $conn->lastInsertId();

        // Insert the order details into the "order_details" table
        // Process the table data
        $rowCount = count($ids);
        for ($i = 0; $i < $rowCount; $i++) {
            $productId = $ids[$i];
            $name = $names[$i];
            $quantity = $quantities[$i];
            $price = $prices[$i];
            $totalAmount = $totalAmounts[$i];

            // Check if the product quantity in the order is valid
            $checkQuantityStmt = $conn->prepare("SELECT `quantity` FROM `product` WHERE `id` = :product_id");
            $checkQuantityStmt->bindParam(':product_id', $productId);
            $checkQuantityStmt->execute();
            $product = $checkQuantityStmt->fetch(PDO::FETCH_ASSOC);

            if ($product && $product['quantity'] >= $quantity) {
                $orderDetailData = [
                    'order_id' => $orderId,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'price' => $price,
                    'price_x_quantity' => $totalAmount
                ];

                $orderDetailInsertStmt = $conn->prepare("INSERT INTO `order_details`(`order_id`, `product_id`, `quantity`, `price`, `price_x_quantity`) VALUES (:order_id, :product_id, :quantity, :price, :price_x_quantity)");
                $orderDetailInsertStmt->execute($orderDetailData);

                // Update the product quantity
                $updateProductQuantityStmt = $conn->prepare("UPDATE `product` SET `quantity` = `quantity` - :quantity WHERE `id` = :product_id");
                $updateProductQuantityStmt->bindParam(':quantity', $quantity);
                $updateProductQuantityStmt->bindParam(':product_id', $productId);
                $updateProductQuantityStmt->execute();
            } else {
                // Rollback the transaction if the product quantity is not valid
                $conn->rollBack();

                // Display an error message
                $_SESSION['error'] = "Invalid product quantity for product with ID: $productId";
                header('Location: dash-order.php');
                exit();
            }
        }

        // Commit the transaction if all queries succeed
        $conn->commit();

        // Success message or further processing
        $_SESSION['success'] = "Order placed successfully!";
        header('Location: dash-order.php');
        exit();
    } catch (PDOException $e) {
        // Rollback the transaction if any query fails
        $conn->rollBack();

        // Error handling
        $_SESSION['error'] = "Error: " . $e->getMessage();
        header('Location: dash-order.php');
        exit();
    }
}
    else{
        $_SESSION['error'] = "You need to select order's products";
        header('Location: dash-order.php');
    }

    header('Location: dash-order.php');

}

?>
