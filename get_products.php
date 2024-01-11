<?php
// Connect to the database
require_once 'db_connect.php';

// Get the category ID from the AJAX request
$categoryID = $_POST['category_id'];

// Prepare the query to retrieve the products based on the category ID or fetch all products
if ($categoryID === 'all') {
  $query = "SELECT * FROM products";
} else {
  $query = "SELECT * FROM product WHERE category = :categoryID";
}

// Prepare the statement
$stmt = $conn->prepare($query);

// Bind the category ID parameter if it's not 'all'
if ($categoryID !== 'all') {
  $stmt->bindParam(':categoryID', $categoryID);
}

// Execute the query
$stmt->execute();

// Fetch the products
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Generate the HTML for the table header
$headerHtml = "<tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Description</th>
                  <th>Price</th>
                </tr>";

// Generate the HTML for the product rows
$rowHtml = '';
foreach ($products as $product) {
  $rowHtml .= "<tr>";
  $rowHtml .= "<td>".$product['id']."</td>";
  $rowHtml .= "<td>".$product['name']."</td>";
  $rowHtml .= "<td>".$product['description']."</td>";
  $rowHtml .= "<td>".$product['price']."</td>";
  $rowHtml .= "</tr>";
}

// Combine the header and row HTML
$html = $headerHtml . $rowHtml;

// Return the generated HTML
echo $html;
?>
