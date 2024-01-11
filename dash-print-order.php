<?php

// Include the necessary files
include 'redirect_to.php'; // Redirect helper function
require_once 'db_connect.php'; // Database connection

// Retrieve all orders with customer information
$sql = "SELECT orders.order_id AS orderId, customer.id, first_name, last_name, orders.total_order_amount AS order_total, orders.order_date
        FROM orders
        INNER JOIN customer ON orders.customer_id = customer.id
        INNER JOIN order_details ON orders.order_id = order_details.order_id
        GROUP BY orders.order_id  ORDER by orders.order_id desc"; 

$stmt = $conn->prepare($sql);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Retrieve all customers
$sql2 = "SELECT * FROM customer";
$stmt2 = $conn->prepare($sql2);
$stmt2->execute();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the search dates
    $startDate = $_POST['start-date'];
    $endDate = $_POST['end-date'];
    $customer_id = $_POST['customer-id'];

    // Prepare the SQL query
    $sql = "SELECT orders.order_id AS orderId, customer.id, first_name, last_name, orders.total_order_amount AS order_total, orders.order_date
            FROM orders
            INNER JOIN customer ON orders.customer_id = customer.id
            INNER JOIN order_details ON orders.order_id = order_details.order_id";

    // Check if a customer is selected
    if ($customer_id !== 'all') {
        $sql .= " WHERE orders.customer_id = :customer_id";
    }

    // Check if dates are provided
    if ($startDate && $endDate) {
        if ($customer_id !== 'all') {
            $sql .= " AND";
        } else {
            $sql .= " WHERE";
        }
        $sql .= " orders.order_date BETWEEN :start_date AND :end_date";
    }

    $sql .= " GROUP BY orders.order_id";

    // Execute the query with the provided start and end dates
    $stmt = $conn->prepare($sql);
    if ($customer_id !== 'all') {
        $stmt->bindParam(':customer_id', $customer_id);
    }
    if ($startDate && $endDate) {
        $stmt->bindParam(':start_date', $startDate);
        $stmt->bindParam(':end_date', $endDate);
    }
    $stmt->execute();

    // Fetch the results
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>



<!DOCTYPE html>
<html>
<head>
    <title>Orders List</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/common-style.css">
    <link rel="stylesheet" href="css/print-order-style.css">
</head>

<body>

<?php include 'common-section.php'; ?>

<h2>Orders List</h2>
        <hr>


<div class="order-section">


<form action="#" method="post" class="search-form">
    <div class="order-buttons">
    <button type="button" id="customer-modal-btn">Select Customer</button>
        <input type="hidden" id="customerIdInput" name="customer-id" value="all" readonly>
        <p id="">Customer: <span id="customerId">0</span> - <span id="customerName">###</span> <span id="separator">|</span></p>

        <div id="search-box">
            <label for="start-date">Start Date:</label>
            <input type="date" name="start-date" id="start-date">
            <label for="end-date">End Date:</label>
            <input type="date" name="end-date" id="end-date">
            <button id="search-button" name="search-button">Search</button>
        </div>
    </div>
</form>





  <table class="order-products-table" id="orderTable" name="tableData">
    <thead>
      <tr>
        <th>Order ID</th>
        <th>Customer ID</th>
        <th>Customer</th>
        <th>Total</th>
        <th>Date</th>
        <th>Details</th>
      </tr>
    </thead>
    <tbody>
      <!-- Order rows here -->
      <?php
      // Generate table rows
      foreach ($results as $row) {
          echo "<tr>";
          echo "<td>".$row['orderId']."</td>";
          echo "<td>".$row['id']."</td>";
          echo "<td>".$row['first_name']." ".$row['last_name']."</td>";
          echo "<td>".$row['order_total']."</td>";
          echo "<td>".$row['order_date']."</td>";
          echo "<td><button class='details-btn generate-btn' data-order-id='".$row['orderId']."' data-order-date='".$row['order_date']."' data-order-total='".$row['order_total']."' data-customer-id='" . $row['id'] . "' data-customer-name='" . $row['first_name'] . " " . $row['last_name'] . "'>View Details</button></td>";
          echo "</tr>";
      }
      ?>

      </tbody>
    </table>

 <h3 style="margin-top:20px;">Order #<span id="order-id"></span> | <span id="order-date"></span> Details | Customer:#<span id="customer-id"></span> - <span id="customer-name"></span></h3>
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

<span id="order-total-value"></span>

<button id="generate-invoice">Generate Invoice</button>

<script>

// Get the button element
var generateInvoiceButton = document.getElementById('generate-invoice');

// Add a click event listener to the button
generateInvoiceButton.addEventListener('click', function() {
  // Get the table element
  var orderTable = document.getElementById('orderTable');
  var orderDetailsTable = document.getElementById('orderDetailsTable');

  // Define the rows for the table
  var rows = [];

// Iterate through the table rows and extract the data
  var tableRows = orderDetailsTable.getElementsByTagName('tr');
  for (var i = 1; i < tableRows.length; i++) {
    var row = tableRows[i];
    var rowData = [];

    for (var j = 0; j < row.cells.length; j++) {
      rowData.push(row.cells[j].textContent);
    }

    rows.push(rowData);
  } 


  // Get the order details
  var orderId = document.getElementById('order-id').textContent;
  var orderDate = document.getElementById('order-date').textContent;
  var customerName = document.getElementById('customer-name').textContent;
  var totalOrderValue = document.getElementById('order-total-value').textContent;

  var url = "invoice.php" + "?orderId=" + encodeURIComponent(orderId) + "&orderDate=" + encodeURIComponent(orderDate) + "&customerName=" + encodeURIComponent(customerName) + "&totalOrderValue=" + encodeURIComponent(totalOrderValue) + "&rows=" + encodeURIComponent(JSON.stringify(rows));

  window.location.href = url;

});


</script>

<script>
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
      row.insertCell().textContent = "$"+orderDetails[i].price;
      row.insertCell().textContent = "$"+orderDetails[i].price_x_quantity;
    }

  }

  // Attach a click event listener to the parent element of the "View Details" buttons
  var table = document.querySelector("table");
  table.addEventListener("click", function(event) {
    if (event.target.classList.contains("details-btn")) {
      event.preventDefault();
      var orderID = event.target.dataset.orderId;
      var customerID = event.target.dataset.customerId;
      var orderTotal = event.target.dataset.orderTotal;
      var customerName = event.target.dataset.customerName;
      var orderDate = event.target.dataset.orderDate;
      fetchOrderDetails(orderID);
      var o_id = document.getElementById("order-id");
      var o_date = document.getElementById("order-date");
      var o_total = document.getElementById("order-total-value");
      var c_id = document.getElementById("customer-id");
      var c_name = document.getElementById("customer-name");
      o_id.textContent = orderID;
      o_date.textContent = orderDate;
      o_total.textContent = "$"+orderTotal;
      c_id.textContent = customerID;
      c_name.textContent = customerName;

      // Scroll to the products table
      document.querySelector('#orderDetailsTable').scrollIntoView({
        behavior: 'smooth'
      });

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
                echo "</tr>";
            }

        ?>

      </tbody>
    </table>

    <button type="button" class="select-customer-btn close-select-customer" id="select-customer">Close</button>

  </div>
</div>

      </tbody>
    </table>
    
    <!-- <button type="button" class="select-customer-btn" id="select-product">Cancel</button> -->

  </div>
</div>


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
var modal2 = document.getElementById("customer-modal");


// Get the <span> element that closes the modal
var closeBtn2 = document.getElementsByClassName("close-select-customer")[0];

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

<script src="js/pagination.js"></script>

<script>
  // Call handlePagination() for "customerTable" and "orderTable"
  handlePagination('customerTable', 5);
  handlePagination('orderTable', 5);
</script>




<script src="js/close-msg.js"></script>






</body>
</html>
