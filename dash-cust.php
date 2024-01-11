<?php

include 'redirect_to.php';

require_once 'db_connect.php';

$sql = "SELECT * FROM customer";
$stmt = $conn->prepare($sql);
$stmt->execute();

?>


<!DOCTYPE html>
<html>
<head>
    <title>Customers</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/common-style.css">
    <link rel="stylesheet" href="css/cust-style.css">
</head>
<body>

<?php include 'common-section.php'; ?>

        <h2>Customers</h2>

   <div class="customer-section">

<?php include 'alert-file.php'; ?>

  <div class="customer-table">
    <h3>Customer List</h3>
    <table id="customerTable">
      <thead>
        <tr>
          <th>ID</th>
          <th>First Name</th>
          <th>Last Name</th>
          <th>Tel</th>
          <th>Email</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <!-- customer rows here -->
                <?php

// Generate table rows
while($row=$stmt->fetch(PDO::FETCH_ASSOC))
{
    echo "<tr>";
    echo "<td>".$row['id']."</td>";
    echo "<td>".$row['first_name']."</td>";
    echo "<td>".$row['last_name']."</td>";
    echo "<td>".$row['tel']."</td>";
    echo "<td>".$row['email']."</td>";
    echo "<td>";
    echo "<button class='view-details-btn'>Details</button>";
    echo "</td>";
    echo "</tr>";
}

?>
 
      </tbody>
    </table>
  </div>

  <div class="customer-form">
    <h3>Manage</h3>
    <form action="customer.php" method="post">
      <input type="number" name="id" id="id" placeholder="Customer ID" value="0" required>
      <input type="text" name="fname" id="fname" placeholder="First Name" required>
      <input type="text" name="lname" id="lname" placeholder="Last Name" required>
      <input type="tel" name="tel" id="tel" placeholder="Telephone" required>
      <input type="email" name="email" id="email" placeholder="Email" required>
      <div class="form-buttons">
        <button type="submit" name="add" class="add-btn">Add</button>
        <button type="submit" name="edit" class="edit-btn">Edit</button>
        <button type="submit" name="remove" class="cancel-btn" onclick="removeRequired()">Remove</button>
      </div>
    </form>
  </div>

  <div class="customer-details">
    <div class="orders-count">
      <h4>Orders Count</h4>
      <p>00</p>
    </div>
    <div class="total-orders-amount">
      <h4>Total Orders Amount</h4>
      <p>$00.00</p>
    </div>
    <div class="last-order-date">
      <h4>Last Order Date</h4>
      <p>0000-00-00</p>
    </div>
  </div>
</div>



<script>
  // Function to remove the "required" attribute from form inputs
  function removeRequired() {
    // Get all the form inputs
    var formInputs = document.querySelectorAll("form input:not([type='number'])");
    
    // Loop through each input and remove the "required" attribute
    formInputs.forEach(function(input) {
      input.removeAttribute("required");
    });
  }
</script>



        </div>
    </div>

<script>
// Function to fetch and update customer details
function updateCustomerDetails(customerId) {
  // Fetch customer details from the server
  fetch('get_customer_details.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: 'customer_id=' + customerId,
  })
    .then(response => response.json())
    .then(customerData => {
      // Update the customer details on the page
      const ordersCountElement = document.querySelector('.orders-count p');
      const totalOrdersAmountElement = document.querySelector('.total-orders-amount p');
      const lastOrderDateElement = document.querySelector('.last-order-date p');

      // Set the customer details based on the fetched data
      ordersCountElement.textContent = customerData.ordersCount || '0';
      totalOrdersAmountElement.textContent = customerData.totalOrdersAmount ? '$' + customerData.totalOrdersAmount : '$0.00';
      lastOrderDateElement.textContent = customerData.latestOrderDate || '0000-00-00';
    })
    .catch(error => {
      console.log('Error occurred while fetching customer details', error);
    });
}

// Event listener for view details button
document.querySelectorAll('.view-details-btn').forEach(function(button) {
  button.addEventListener('click', function() {
    // Retrieve the customer ID from the clicked row
    var customerId = this.closest('tr').querySelector('td:first-child').textContent;

    // Call the function to update the customer details
    updateCustomerDetails(customerId);

    // Set the customer details in the input fields
    document.getElementById('id').value = customerId;
    document.getElementById('fname').value = this.closest('tr').querySelector('td:nth-child(2)').textContent;
    document.getElementById('lname').value = this.closest('tr').querySelector('td:nth-child(3)').textContent;
    document.getElementById('tel').value = this.closest('tr').querySelector('td:nth-child(4)').textContent;
    document.getElementById('email').value = this.closest('tr').querySelector('td:nth-child(5)').textContent;
  });
});
</script>

<script src="js/pagination.js"></script>

<script>
  // Call handlePagination() for "customerTable"
  handlePagination('customerTable', 5);
</script>




<script src="js/close-msg.js"></script>



</body>
</html>
