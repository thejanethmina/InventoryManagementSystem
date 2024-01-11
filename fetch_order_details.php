<?php

require_once 'db_connect.php';

$orderID = $_GET['order_id']; // Get the 'order_id' parameter from the URL

try {
    // Prepare the SQL statement to retrieve order details based on the order ID
    $stmt = $conn->prepare("SELECT product_id, name, order_details.quantity, order_details.price, price_x_quantity 
                            FROM order_details 
                            INNER JOIN product 
                            ON order_details.product_id = product.id 
                            WHERE order_id = :orderID");

    $stmt->bindParam(':orderID', $orderID, PDO::PARAM_INT); // Bind the order ID parameter to the prepared statement

    $stmt->execute(); // Execute the SQL statement

    $orderDetails = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all the resulting rows as an associative array

    $orderDetailsJSON = json_encode($orderDetails); // Encode the order details array as JSON

    echo $orderDetailsJSON; // Output the JSON response

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage(); // Display an error message if an exception occurs during the database operation
}

?>
