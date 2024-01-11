<?php

include 'redirect_to.php';

require_once 'db_connect.php';

$sql = "SELECT * FROM product";
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
    <title>Order</title>
    <!-- jsPDF CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.min.js"></script>

    <!-- Include jsPDF-AutoTable plugin -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.15/jspdf.plugin.autotable.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/common-style.css">
    <link rel="stylesheet" href="css/order-style.css">    
</head>
<body>

<?php include 'common-section.php'; ?>

<h2>Order</h2>

<div class="order-section">

<?php include 'alert-file.php'; ?>

  <div class="order-buttons">
    <button id="customer-modal-btn">Select Customer</button>
    <button id="product-modal-btn">Add Product</button>
    <button id="clear">Clear</button>
    <a href="dash-print-order.php" id="orders-list-a">Orders List</a>
  </div>


  <form action="_order_.php" method="post">
  <div class="order-buttons">
  <input type="hidden" id="customerIdInput" name="customer_id" value="0" readonly>
    <p>Customer: 
    <span id="customerId">00</span> 
    - <span id="customerName" style="display: inline-block;margin-left:10px">### ###</span></p>
  </div>
  <table class="order-products-table" id="orderTable" name="tableData">
    <thead>
      <tr>
        <th>ID</th>
        <th>Product</th>
        <th>Quantity</th>
        <th>Price</th>
        <th>Total</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody id="orderTableBody">
      <!-- Order products rows here -->
    </tbody>
  </table>

  <div class="order-total">
        Total Order Value: <span id="total-value">$0.00</span>
        <input type="hidden" name="orderTotalAmount" id="total-value-2" value="0.00">
        <button type="submit" name="add_order" id="create-order-btn">Create Order</button>          
  </div>
</form>
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

    <button type="button" class="close-modal-btn" id="close-customer">Close</button>

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
    echo "</tr>";
}

?>

      </tbody>
    </table>
    
    <button type="button" class="close-modal-btn" id="close-product">Close</button>

  </div>
</div>


<!-- end modal customer -->

        </div>
    </div>


<script>

// show the customers model

// Get the modal
var modal = document.getElementById("customer-modal");

// Get the button that opens the modal
var selectCustomerButton = document.querySelector("#customer-modal-btn");

// Get the button that close the modal
var closeCustomerButton = document.getElementById("close-customer");

// Get the <span> (x) element that closes the modal
var closeBtn = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal
selectCustomerButton.onclick = function() {
  modal.style.display = "block";
}

// When the user clicks on close button, close the modal
closeCustomerButton.onclick = function() {
  modal.style.display = "none";
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

// show the products model

// Get the modal
var modal2 = document.getElementById("product-modal");

// Get the button that opens the modal
var selectProductButton = document.querySelector("#product-modal-btn");


// Get the button that close the modal
var closeProductButton = document.getElementById("close-product");

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

// When the user clicks on <span> (x), close the modal
closeProductButton.onclick = function() {
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
    var orderRows = orderTable.getElementsByTagName("tbody")[0].getElementsByTagName("tr");

    for (var i = 0; i < orderRows.length; i++) {
      var row = orderRows[i];
      var productNameInOrder = row.cells[0].querySelector("input").value;

      if (productNameInOrder === productId) {
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
    var newRow = orderTable.getElementsByTagName("tbody")[0].insertRow();
    
    // Create cells for the new row
    var productIdCell = newRow.insertCell();
    var productNameCell = newRow.insertCell();
    var quantityCell = newRow.insertCell();
    var priceCell = newRow.insertCell();
    var totalAmountCell = newRow.insertCell();
    var removeRowCell = newRow.insertCell();
    
    // Set the values in the new row cells
    productIdCell.innerHTML = '<input type="text" name="id[]" value="'+productId+'" readonly>';
    productNameCell.innerHTML = '<input type="text" name="name[]" value="'+productName+'" readonly>';
    quantityCell.innerHTML = `
      <div class="quantity-container">
        <div class="quantity-buttons">
          <button type="button" class="minus-button">-</button>
          <button type="button" class="plus-button">+</button>
        </div>
        <input name="quantity[]" class="quantity" type="number" value="${quantity}" readonly>
      </div>
    `;
    priceCell.innerHTML = '<input type="number" name="price[]" value="'+numericPrice+'" step="0.01" readonly>';
    totalAmountCell.innerHTML = '<input type="number" name="totalAmount[]" value="'+totalAmount+'" step="0.01" readonly>';
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
      totalAmountCell.innerHTML = '<input type="number" name="totalAmount[]" value="'+totalAmount.toFixed(2)+'" step="0.01">'

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


// Function to update the total order value
function updateTotalOrderValue() {
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

  totalValueSpan.textContent ="$" + orderTotal.toFixed(2);
  totalValueInput.value = orderTotal.toFixed(2); 
}

</script>


<script>

// Get the button element
var clearButton = document.getElementById('clear');
var totalValueSpan = document.getElementById("total-value");
var totalValueInput = document.getElementById("total-value-2");
var customerIdInput = document.getElementById("customerIdInput");
var customerId = document.getElementById("customerId");
var customerName = document.getElementById("customerName");
// Select the table rows and remove them
var tableBody = document.getElementById('orderTableBody');
// Add click event listener to the clear button
clearButton.addEventListener('click', function() {
  // Clear the table body by setting innerHTML to an empty string
  tableBody.innerHTML = '';
  totalValueSpan.textContent = "$0.00";
  totalValueInput.value = "0.00";
  customerIdInput.value = "00";
  customerId.textContent = "00";
  customerName.textContent = "### ###";
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

<script src="js/pagination.js"></script>

<script>
  // Call handlePagination() for "productTable" and "customerTable"
  handlePagination('productTable', 3);
  handlePagination('customerTable', 5);
</script>


<script src="js/close-msg.js"></script>


</body>

</html>
