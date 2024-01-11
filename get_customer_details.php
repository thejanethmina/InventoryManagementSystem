<?php
// Connect to the database
require_once 'db_connect.php';

// Get the customer ID from the AJAX request
$customerId = $_POST['customer_id'];

// Prepare the query to retrieve the customer's orders information
$query = "SELECT COUNT(*) AS ordersCount, SUM(total_order_amount) AS totalOrdersAmount, MAX(order_date) AS latestOrderDate
          FROM orders
          WHERE customer_id = :customerId";

// Prepare the statement
$stmt = $conn->prepare($query);
$stmt->bindParam(':customerId', $customerId);

// Execute the query
$stmt->execute();

// Fetch the customer's details
$customerDetails = $stmt->fetch(PDO::FETCH_ASSOC);

// Convert the customer details to JSON and return the response
echo json_encode($customerDetails);
?>
