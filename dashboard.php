<?php

include 'redirect_to.php';

// Include the db_connect.php file to connect to the database
require_once 'db_connect.php';

// Define an array of table names and corresponding variables
$tables = [
    'product' => 'totalProducts',
    'category' => 'totalCategories',
    'customer' => 'totalCustomers',
    'orders' => 'totalOrders'
];

// Fetch data for each table and assign the values to variables
foreach ($tables as $table => $variable) {
    $query = "SELECT COUNT(*) AS total FROM $table";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $$variable = $result['total'];
}

// Close the database connection
$conn = null;

?>


<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/common-style.css">
    <link rel="stylesheet" href="css/data-style.css">
</head>

<body>

<?php include 'common-section.php'; ?>

<h2>Dashboard</h2>

<div class="analytics-section">
  <div class="analytics-cards">
    <div class="analytics-card">
      <i class="fas fa-box-open"></i>
      <h3>Total Products</h3>
      <span><?php echo $totalProducts; ?></span>
    </div>
    <div class="analytics-card">
      <i class="fas fa-tags"></i>
      <h3>Total Categories</h3>
      <span><?php echo $totalCategories; ?></span>
    </div>
    <div class="analytics-card">
      <i class="fas fa-user-friends"></i>
      <h3>Total Customers</h3>
      <span><?php echo $totalCustomers; ?></span>
    </div>
    <div class="analytics-card">
      <i class="fas fa-shopping-cart"></i>
      <h3>Total Orders</h3>
      <span><?php echo $totalOrders; ?></span>
    </div>
  </div>
</div>

</div>
</div>

</body>
</html>
