<?php
session_start();

require_once 'db_connect.php';

$sql = "SELECT orders.order_id AS orderId, first_name, orders.total_order_amount AS order_total, orders.order_date
FROM orders
INNER JOIN customer ON orders.customer_id = customer.id
INNER JOIN order_details ON orders.order_id = order_details.order_id
GROUP BY orders.order_id"; 

$stmt = $conn->prepare($sql);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);


$sql2 = "SELECT * FROM customer";
$stmt2 = $conn->prepare($sql2);
$stmt2->execute();


?>


<!DOCTYPE html>
<html>
<head>
    <title>Login Form</title>
    <!-- jsPDF CDN -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script> -->
<!-- jsPDF CDN (uncompressed version) -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.min.js"></script>
<!-- Include jsPDF library -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script> -->

<!-- Include jsPDF-AutoTable plugin -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.15/jspdf.plugin.autotable.min.js"></script>



    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <style>

/* Set global styles */
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
}

/* Style the sidebar */
.sidebar {
    width: 250px;
    height: 100%;
    background-color: #1d1f26;
    position: fixed;
    left: 0;
    top: 0;
    overflow-y: auto;
}

.sidebar .logo {
    padding: 20px;
    color: #fff;
    text-align: center;
    border-bottom: 1px solid #444;
}

.sidebar ul {
    list-style: none;
    padding: 20px;
}

.sidebar ul li a {
    color: #fff;
    display: block;
    padding: 10px 20px;
    text-decoration: none;
    border-radius: 5px;
    margin-bottom: 10px;
    transition: background-color 0.3s ease;
}

.sidebar ul li a:hover {
    background-color: #2d2f36;
}

.sidebar ul li a i {
    margin-right: 10px;
}

/* Style the main content area */
.main-content {
    margin-left: 250px;
    padding: 20px;
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #eee;
    padding-bottom: 20px;
    margin-bottom: 20px;
}

.user {
    display: flex;
    align-items: center;
}

.user img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    margin-right: 10px;
}

.logout-btn {
    background-color: #dc3545;
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    transition: background-color 0.3s ease;
}

.logout-btn:hover {
    background-color: #c82333;
}

.content h2 {
    margin-bottom: 20px;
    font-size: 24px;
}

.content p {
    font-size: 18px;
    line-height: 1.5;
    color: #444;
}



/*---------------------------------------*/


.order-section {
  padding: 20px;
}

.order-buttons {
  margin-bottom: 20px;
}

.order-buttons button {
  background-color: #4CAF50;
  color: #fff;
  border: none;
  padding: 10px 20px;
  font-size: 14px;
  border-radius: 5px;
  cursor: pointer;
  margin-right: 10px;
  transition: background-color 0.3s ease;
}

.order-buttons button:hover {
  background-color: #45a049;
}

.order-products-table {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 20px;
}

.order-products-table th,
.order-products-table td {
  border: 1px solid #ddd;
  padding: 8px;
}

.order-products-table th {
  background-color: #f2f2f2;
}

.order-products-table td:last-child {
  text-align: center;
}

.order-total {
  margin-top: 20px;
  font-size: 18px;

  display:flex;
}

.order-total #total-value {
  font-weight: bold;
  color: #4CAF50;
}

.modal {
  display: none;
  position: fixed;
  z-index: 9999;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgba(0, 0, 0, 0.5);
}

.modal-content {
  background-color: #fefefe;
  margin: 10% auto;
  padding: 20px;
  border: 1px solid #888;
  width: 80%;
  max-width: 600px;
  border-radius: 5px;
}

.modal h3 {
  margin-bottom: 10px;
}

.customer-table,
.product-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 10px;
}

.customer-table th,
.customer-table td,
.product-table th,
.product-table td {
  border: 1px solid #ddd;
  padding: 8px;
}

.customer-table th {
  background-color: #f2f2f2;
}

.modal-content .close-btn {
  color: #888;
  float: right;
  font-size: 24px;
  font-weight: bold;
  cursor: pointer;
}

.modal-content .close-btn:hover,
.modal-content .close-btn:focus {
  color: #000;
  text-decoration: none;
}



/*-----------------------------------*/

.select-customer-btn{ border: none; background-color: #555; color:#fff; padding: 10px 20px; 
                      border-radius: 5px; font-size: 16px; cursor: pointer; 
                      transition: all 0.2s ease-in-out; width:100%; margin-top:10px;
                    }

.select-customer-btn:hover{ background-color: #333; }

.remove-btn {
/*   background-color: transparent;
  border: none;
  color: #007bff;
  cursor: pointer;
  margin-right: 10px;
  font-size: 14px;
  transition: color 0.2s ease-in-out; */
  background-color: #e74c3c;
      color: #fff;
      border: none;
      cursor: pointer;
      padding: 5px 10px;
      font-size: 14px;
      transition: background-color 0.3s ease;

}

    .quantity-container {
      display: flex;
      align-items: center;
    }
    
    .quantity-buttons {
      display: flex;
    }
    
    .minus-button,
    .plus-button {
      background-color: #eee;
      color: #666;
      border: none;
      cursor: pointer;
      padding: 5px 8px;
      font-size: 14px;
    }

    .minus-button:hover,
    .plus-button:hover {
      background-color: #ddd;
    }
    
    .quantity {
      width: 60px;
      margin: 0 10px;
      padding: 5px 8px;
      border: 1px solid #ddd;
      background-color: #f9f9f9;
      color: #333;
      text-align: center;
    }




.order-total {
  background-color: #f2f2f2;
  border: 1px solid #ddd;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  padding: 10px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-family: Arial, sans-serif;
}

.order-total span {
  font-weight: bold;
  color: #333;
}

.order-total form {
  margin-left: 10px;
}

.order-total button {
  padding: 8px 16px;
  background-color: #007bff;
  color: #fff;
  border: none;
  border-radius: 4px;
  font-size: 14px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.order-total button:hover {
  background-color: #0056b3;
}





#customerIdInput {
  background: none;
  border: none;
  outline: none;
  padding: 0;
  margin: 0;
  font-size: inherit;
  color: inherit;
}




.generate-btn {
    background-color: #e74c3c;
    color: #fff;
    border: none;
    cursor: pointer;
    padding: 5px 10px;
    font-size: 14px;
    transition: background-color 0.3s ease;
    width:100%;
}







     
    #orderDetailsTable {
      border-collapse: collapse;
      width: 100%;
    }

#orderDetailsTable th, #orderDetailsTable td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
  }

  #orderDetailsTable th {
    background-color: #f2f2f2;
    color: #333;
    font-weight: bold;
  }

  .details-btn {
    background-color: #777;
  }




    </style>
</head>
<body>

    <div class="sidebar">
        <div class="logo">
            <h2>Inventory Management System</h2>
        </div>
        <ul>
            <li><a href="#"><i class="fas fa-box"></i>Products</a></li>
            <li><a href="#"><i class="fas fa-tags"></i>Categories</a></li>
            <li><a href="#"><i class="fas fa-users"></i>Customers</a></li>
            <li><a href="#"><i class="fas fa-shopping-cart"></i>Orders</a></li>
            <li><a href="#"><i class="fas fa-user"></i>Users</a></li>
        </ul>
    </div>
    <div class="main-content">
        <div class="header">
            <div class="user">
                <img src="https://via.placeholder.com/50" alt="User Image">
                <span>Welcome, John Doe</span>
            </div>
            <a href="#" class="logout-btn"><i class="fas fa-sign-out-alt"></i>Logout</a>
        </div>
        <div class="content">
        <h2>Orders List</h2>
        <hr>





<!-- modal customers -->

<div class="order-section">
  <div class="order-buttons">
    <button id="customer-modal-btn">Select Customer</button>
    <button id="product-modal-btn">Add Product</button>
    <button id="generate-pdf">Generate PDF</button>
    <button id="orders-list">Orders List</button>
  </div>


  <form>
  <div class="order-buttons">
  <input type="text" id="customerIdInput" name="customer_id" value="1" readonly>
    <p id="">Customer: <span id="customerId">1</span> - <span id="customerName">abcd efg</span></p>
  </div>
  <table class="order-products-table" id="orderTable" name="tableData">
    <thead>
      <tr>
        <th>Order ID</th>
        <th>Customer</th>
        <th>Total</th>
        <th>Date</th>
        <th>Details</th>
        <th>Generate PDF</th>
      </tr>
    </thead>
    <tbody>
      <!-- Order rows here -->
                   <!-- Customer rows here -->
                   <?php

// Generate table rows
// foreach($results as $row)
// {
//     //orders.id, customers.first_name, orders.total_order_amount AS order_total, orders.order_date
//     echo "<tr>";
//     echo "<td>".$row['orderId']."</td>";
//     echo "<td>".$row['first_name']."</td>";
//     echo "<td>".$row['order_total']."</td>";
//     echo "<td>".$row['order_date']."</td>";
//     echo "<td><button class='details-btn generate-btn'>View Datails</button></td>";
//     echo "<td><button class='pdf-btn generate-btn'>Generate</button></td>";
//     echo "</tr>";
// }

?>





<?php
// Generate table rows
foreach ($results as $row) {
    //orders.id, customers.first_name, orders.total_order_amount AS order_total, orders.order_date
    echo "<tr>";
    echo "<td>".$row['orderId']."</td>";
    echo "<td>".$row['first_name']."</td>";
    echo "<td>".$row['order_total']."</td>";
    echo "<td>".$row['order_date']."</td>";
    echo "<td><button class='details-btn generate-btn' data-order-id='".$row['orderId']."'>View Details</button></td>";
    echo "<td><button class='pdf-btn generate-btn'>Generate</button></td>";
    echo "</tr>";
}
?>

      </tbody>
    </table>
</form>


 <h3>Order Details</h3>
 <hr>

<table id="orderDetailsTable">
  <thead>
    <tr>
      <th>Product ID</th>
      <th>Product Name</th>
      <th>Quantity</th>
      <th>Price</th>
      <th>Quantity x Price</th>
    </tr>
  </thead>
  <tbody></tbody>
</table>

<script>
  // JavaScript code

  // Function to fetch and display order details
  function fetchOrderDetails(orderID) {
    // Create an AJAX request
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "fetch_order_details.php?order_id=" + orderID, true);

    // Set the response type to JSON
    xhr.responseType = "json";

    // Handle the AJAX response
    xhr.onreadystatechange = function() {
      if (xhr.readyState === XMLHttpRequest.DONE) {
        if (xhr.status === 200) {
          // Request was successful
          var orderDetails = xhr.response;
          displayOrderDetails(orderDetails);
        } else {
          // Request failed
          console.log("Error: " + xhr.status);
        }
      }
    };

    // Send the AJAX request
    xhr.send();
  }

  // Function to display order details in an HTML table
  function displayOrderDetails(orderDetails) {
    // Get the table element
    var table = document.getElementById("orderDetailsTable");

    // Clear the table body
    table.tBodies[0].innerHTML = "";


    // Loop through the order details and generate table rows
    for (var i = 0; i < orderDetails.length; i++) {
      var row = table.tBodies[0].insertRow();
      row.insertCell().textContent = orderDetails[i].product_id;
      row.insertCell().textContent = orderDetails[i].name;
      row.insertCell().textContent = orderDetails[i].quantity;
      row.insertCell().textContent = orderDetails[i].price;
      row.insertCell().textContent = orderDetails[i].price_x_quantity;
    }
  }

  // Attach a click event listener to the parent element of the "View Details" buttons
  var table = document.querySelector("table");
  table.addEventListener("click", function(event) {
    if (event.target.classList.contains("details-btn")) {
      event.preventDefault();
      var orderID = event.target.dataset.orderId;
      fetchOrderDetails(orderID);
    }
  });
</script>










</div>

<!-- Customer Modal -->
<div class="modal" id="customer-modal">
  <div class="modal-content">
  <span class="close">&times;</span>
    <h3>Select Customer</h3>
    <table class="customer-table" id="customerTable">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Tel</th>
          <th>Email</th>
          <!-- <th>Actions</th> -->
        </tr>
      </thead>
      <tbody>
        <!-- Customer rows here -->
        <?php

// Generate table rows
while($row2=$stmt2->fetch(PDO::FETCH_ASSOC))
{
    echo "<tr>";
    echo "<td>".$row2['id']."</td>";
    echo "<td>".$row2['first_name']." ". $row2['last_name'] ."</td>";
    echo "<td>".$row2['tel']."</td>";
    echo "<td>".$row2['email']."</td>";
    //echo "<td>";
    // echo "<button class='edit-btn'>Edit</button>";
    //echo "<button class='edit-btn'>Edit</button>";
    //echo "<button class='remove-btn'>Remove</button>";
    //echo "</td>";
    echo "</tr>";
}

?>

      </tbody>
    </table>

    <button type="button" class="select-customer-btn" id="select-customer">Cancel</button>

  </div>
</div>

<!-- Product Modal -->
<div class="modal" id="product-modal">
  <div class="modal-content">
  <span class="close">&times;</span>
    <h3>Add Product</h3>
    <table class="product-table" id="productTable">
      <thead>
        <tr>
     <!-- `id`, `name`, `category`, `quantity`, `price`, `description`, `picture` -->
          <th>ID</th>
          <th>Picture</th>
          <th>Name</th>
          <th>Category</th>
          <th>Quantity</th>
          <th>Price</th>
          <th>Description</th>
        </tr>
      </thead>
      <tbody>
        <!-- Product rows here -->
                <!-- Customer rows here -->
                <?php

// Generate table rows
foreach($results as $row)
{
    echo "<tr>";
    echo "<td>".$row['id']."</td>";
    echo "<td style='width:75px; height:75px;'><img src='".$row['picture']."' style='width:100%; height:100%;'></td>";
    echo "<td>".$row['name']."</td>";
    echo "<td>".$row['category']."</td>";
    echo "<td>".$row['quantity']."</td>";
    echo "<td>".$row['price']."</td>";
    echo "<td>".$row['description']."</td>";
    
    //echo "<td>";
    // echo "<button class='edit-btn'>Edit</button>";
    //echo "<button class='edit-btn'>Edit</button>";
    //echo "<button class='remove-btn'>Remove</button>";
    //echo "</td>";
    echo "</tr>";
}

?>

      </tbody>
    </table>
    
    <button type="button" class="select-customer-btn" id="select-product">Cancel</button>

  </div>
</div>


<!-- end modal customer -->




















        </div>
    </div>


<script>

// Get the modal
var modal = document.getElementById("customer-modal");

// Get the button that opens the modal
var selectCustomerButton = document.querySelector("#customer-modal-btn");

// Get the <span> element that closes the modal
var closeBtn = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal
selectCustomerButton.onclick = function() {
  modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
closeBtn.onclick = function() {
  modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}


</script>


<script>

// Get the modal
var modal2 = document.getElementById("product-modal");

// Get the button that opens the modal
var selectProductButton = document.querySelector("#product-modal-btn");

// Get the <span> element that closes the modal
var closeBtn2 = document.getElementsByClassName("close")[1];

// When the user clicks the button, open the modal
selectProductButton.onclick = function() {
  modal2.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
closeBtn2.onclick = function() {
  modal2.style.display = "none";
}

// Close the delete modal and main modal when clicking outside of them
window.onclick = function(event){
if(event.target == modal || event.target == modal2 ){
    modal2.style.display = "none"; 
    modal.style.display = "none"; 
}
}


</script>


<script>

// Get the table element
var productTable = document.getElementById('productTable');
// Get the target table element
var orderTable = document.getElementById('orderTable');

// get the total order value
var totalValueSpan = document.getElementById("total-value");
var totalValueInput = document.getElementById("total-value-2");

// Attach a click event listener to the source table
productTable.addEventListener('click', function(event) {
  // Check if a row was clicked
  if (event.target.tagName === 'TD') {
    // Get the selected row
    var selectedRow = event.target.parentNode;
    
    // Get the data from the cells of the selected row
    var productId = selectedRow.cells[0].textContent;
    var productName = selectedRow.cells[2].textContent;
    var quantity = 1;
    var price = selectedRow.cells[5].textContent;
    // Parse the price as a number (assuming it's a valid numeric value)
    var numericPrice = parseFloat(price);
    // Calculate the total amount
    var totalAmount = quantity * numericPrice;

    // Check if the product is already added to the order table
    var isProductAdded = false;
    //var orderRows = orderTable.getElementsByTagName("tr");
    var orderRows = orderTable.getElementsByTagName("tbody")[0].getElementsByTagName("tr");

    for (var i = 0; i < orderRows.length; i++) {
      var row = orderRows[i];
      var productNameInOrder = row.cells[0].textContent;

      if (productNameInOrder === productName) {
        isProductAdded = true;
        break;
      }
    }

    if (isProductAdded) {
      // Product already added, display a message or perform any other action
      alert("Product is already added to the order table.");
      return;
    }

    // Create a new row in the target table
    //var newRow = orderTable.insertRow();
    var newRow = orderTable.getElementsByTagName("tbody")[0].insertRow();
    
    // Create cells for the new row
    var productIdCell = newRow.insertCell();
    var productNameCell = newRow.insertCell();
    var quantityCell = newRow.insertCell();
    var priceCell = newRow.insertCell();
    var totalAmountCell = newRow.insertCell();
    var removeRowCell = newRow.insertCell();
    
    // Set the values in the new row cells
    //productIdCell.textContent = productId;
    productIdCell.innerHTML = '<input type="text" name="id[]" value="'+productId+'">';
    //productNameCell.textContent = productName;
    productNameCell.innerHTML = '<input type="text" name="name[]" value="'+productName+'">';
    quantityCell.innerHTML = `
      <div class="quantity-container">
        <div class="quantity-buttons">
          <button type="button" class="minus-button">-</button>
          <button type="button" class="plus-button">+</button>
        </div>
        <input name="quantity[]" class="quantity" type="number" value="${quantity}" readonly>
      </div>
    `;
    //priceCell.textContent = price;
    priceCell.innerHTML = '<input type="number" name="price[]" value="'+numericPrice+'" step="0.01">';
    //totalAmountCell.textContent = totalAmount;
    totalAmountCell.innerHTML = '<input type="number" name="totalAmount[]" value="'+totalAmount+'" step="0.01">';
    removeRowCell.innerHTML = "<button class='remove-btn'>Remove</button>";

    // update the total order value
    updateTotalOrderValue();

    // Update quantity when plus or minus buttons are clicked
    var plusButtons = newRow.querySelectorAll(".plus-button");
    var minusButtons = newRow.querySelectorAll(".minus-button");

    plusButtons.forEach(function(plusBtn) {
      plusBtn.addEventListener('click', function() {
        quantity++;
        updateQuantity();
      });
    });

    minusButtons.forEach(function(minusBtn) {
      minusBtn.addEventListener('click', function() {
        if (quantity > 1) {
          quantity--;
          updateQuantity();
        }
      });
    });

    // Function to update the quantity cell
    function updateQuantity() {
      // Update the quantity cell content
      var quantityInput = newRow.querySelector(".quantity");
      quantityInput.value = quantity;

      // Calculate the total amount
      totalAmount = quantity * numericPrice;

      // Display the total amount
      //totalAmountCell.textContent = totalAmount;
      //totalAmountCell.innerHTML = "";
      totalAmountCell.innerHTML = '<input type="number" name="totalAmount[]" value="'+totalAmount+'" step="0.01">'

      updateTotalOrderValue();
    }
  }
});

// Attach a click event listener to the order table for the remove button
orderTable.addEventListener('click', function(event) {
  if (event.target.classList.contains('remove-btn')) {
    // Get the parent row and remove it from the order table
    var selectedRow = event.target.parentNode.parentNode;
    orderTable.deleteRow(selectedRow.rowIndex);
    
    // Update the total order value
    updateTotalOrderValue();
  }
});

/* // Function to update the total order value
function updateTotalOrderValue() {
  var orderRows = orderTable.getElementsByTagName("tr");
  var orderTotal = 0;

  for (var i = 0; i < orderRows.length; i++) {
    var row = orderRows[i];
    var rowTotal = parseFloat(row.cells[3].textContent);
    
    if (!isNaN(rowTotal)) {
      orderTotal += rowTotal;
    }
  }

  totalValueSpan.textContent = orderTotal.toFixed(2); // Display the total order value with 2 decimal places
  totalValueInput.textContent = orderTotal.toFixed(2); // Display the total order value with 2 decimal places
}
 */



// Function to update the total order value
function updateTotalOrderValue() {
  //var orderRows = orderTable.getElementsByTagName("tr");
  var orderRows = orderTable.getElementsByTagName("tbody")[0].getElementsByTagName("tr");
  var orderTotal = 0;

  for (var i = 0; i < orderRows.length; i++) {
    var row = orderRows[i];
    var inputValue = parseFloat(row.cells[4].querySelector("input").value);
    console.log(inputValue);

   if (!isNaN(inputValue)) {
      orderTotal += inputValue;
    }
  }

  totalValueSpan.textContent = orderTotal.toFixed(2);
  totalValueInput.value = orderTotal.toFixed(2); 
}












</script>


<script>

// Get the button element
var generatePdfButton = document.getElementById('generate-pdf');

// Add a click event listener to the button
generatePdfButton.addEventListener('click', function() {
  // Create a new jsPDF instance
  var doc = new jsPDF();

// window.jsPDF = window.jspdf.jsPDF;



  // Get the table element
  var orderTable = document.getElementById('orderTable');

  // Define the columns and rows for the table
  var columns = ['Product Name', 'Quantity', 'Price', 'Total Amount'];
  var rows = [];

  // Iterate through the table rows and extract the data
  var tableRows = orderTable.getElementsByTagName('tr');
  for (var i = 0; i < tableRows.length; i++) {
    var row = tableRows[i];
    var rowData = [];

    for (var j = 0; j < row.cells.length; j++) {
      rowData.push(row.cells[j].textContent);
    }

    rows.push(rowData);
  }

  // Set the table column styles
  var columnStyles = {
    0: { columnWidth: 80 }, // Product Name
    1: { columnWidth: 40 }, // Quantity
     2: { columnWidth: 40 }, // Price
    3: { columnWidth: 50 } // Total Amount
  };

  // Add the table to the PDF document
  doc.autoTable({
    head: [columns],
    body: rows,
    startY: 20,
    styles: { overflow: 'linebreak', columnStyles: columnStyles }
  });

  // Save the PDF file
  doc.save('order.pdf');
});


</script>




<script>

// get the selected customer
// Get the table element
var customerTable = document.getElementById('customerTable');
var customerId = document.getElementById('customerId');
var customerName = document.getElementById('customerName');

// Attach a click event listener to the source table
customerTable.addEventListener('click', function(event) {
  // Check if a row was clicked
  if (event.target.tagName === 'TD') {
    // Get the selected row
    var selectedRow = event.target.parentNode;
    
    // Get the data from the cells of the selected row
    var customer_id = selectedRow.cells[0].textContent;
    var customer_name = selectedRow.cells[1].textContent;
    
    customerId.textContent = customer_id;
    document.getElementById('customerIdInput').value = customer_id;
    customerName.textContent = customer_name;

  }
});


</script>



<script>

// Get the button element
var generatePdfButton = document.getElementById('generate-pdf');

// Add a click event listener to the button
generatePdfButton.addEventListener('click', function() {
  // Create a new jsPDF instance
  var doc = new jsPDF();

// window.jsPDF = window.jspdf.jsPDF;



  // Get the table element
  var orderTable = document.getElementById('orderTable');

  // Define the columns and rows for the table
  var columns = ['Product ID', 'Product Name', 'Quantity', 'Price', 'Total Amount'];
  var rows = [];

  // Iterate through the table rows and extract the data
  var tableRows = orderTable.getElementsByTagName('tr');
  for (var i = 1; i < tableRows.length; i++) {
    var row = tableRows[i];
    var rowData = [];

    for (var j = 0; j < row.cells.length; j++) {
      var inputElement = row.cells[j].querySelector("input");
      var value = inputElement ? inputElement.value : '';
      rowData.push(value);
    }

    rows.push(rowData);
  }

  // Set the table column styles
  var columnStyles = {
    0: { columnWidth: 80 }, // Product Name
    1: { columnWidth: 80 }, // Product Name
    2: { columnWidth: 40 }, // Quantity
     3: { columnWidth: 40 }, // Price
    4: { columnWidth: 50 } // Total Amount
  };

  // Add the table to the PDF document
  doc.autoTable({
    head: [columns],
    body: rows,
    startY: 20,
    styles: { overflow: 'linebreak', columnStyles: columnStyles }
  });

  // Save the PDF file
  doc.save('order.pdf');
});


</script>
















</body>
</html>
