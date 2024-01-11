<!DOCTYPE html>
<html>
<head>
    <title>Invoice</title>
    <style>
 /*        body {
            font-family: Arial, sans-serif;
        }

        .invoice-header {
            margin-bottom: 20px;
        }

        .invoice-header h1 {
            color: #333;
            margin-bottom: 5px;
        }

        .customer-info {
            margin-bottom: 20px;
        }

        .customer-info p {
            margin: 5px 0;
        }

        .order-details {
            border-collapse: collapse;
            width: 100%;
        }

        .order-details th,
        .order-details td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .order-details th {
            background-color: #f2f2f2;
        }

        .total-amount {
            margin-top: 20px;
            text-align: right;
            font-weight: bold;
            background-color: #ddd;
            padding:10px;
            text-align:center;
            font-size:18px;
        } */


 /* Reset default styles */
 * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        /* Page background and font styles */
        body {
            background-color: #f5f5f5;
            color: #333;
            font-family: Arial, sans-serif;
        }
        
        /* Invoice header styles */
        .invoice-header {
            background-color: #333;
            padding: 20px;
            text-align: center;
        }
        
        .invoice-header h1 {
            color: #fff;
            font-size: 24px;
        }
        
        /* Customer info styles */
        .customer-info {
            background-color: #fff;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .customer-info p {
            margin: 5px 0;
            font-size: 16px;
        }
        
        /* Order details table styles */
        .order-details {
            background-color: #fff;
            width: 100%;
            border-collapse: collapse;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .order-details th,
        .order-details td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        .order-details thead {
            background-color: #333;
            color: #fff;
        }
        
        .order-details th {
            font-weight: bold;
        }
        
        .order-details tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        
        /* Total amount styles */
        .total-amount {
            background-color: #fff;
            padding: 20px;
            text-align: right;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .total-amount p {
            margin: 5px 0;
            font-size: 18px;
            font-weight: bold;
        }


    </style>
</head>
<body>
    <div class="invoice-header">
        <h1>Invoice</h1>
    </div>

    <div class="customer-info">
    <p>Order ID: <span id="order-id"></span></p>
    <p>Order Date: <span id="order-date"></span></p>
    <p>Customer Name: <span id="customer-name"></span></p>
    </div>

    <table class="order-details" id="order-details">
        <thead>
            <tr>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total Amount</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>

    <div class="total-amount">
        <p>Total Order Value: <span id="order-total-value"></span></p>
    </div>

<script>

// Retrieve the data from URL parameters
var urlParams = new URLSearchParams(window.location.search);
var orderId = urlParams.get("orderId");
var orderDate = urlParams.get("orderDate");
var customerName = urlParams.get("customerName");
var totalOrderValue = urlParams.get("totalOrderValue");

// Set the retrieved data to HTML elements
document.getElementById('order-id').textContent = orderId;
document.getElementById('order-date').textContent = orderDate;
document.getElementById('customer-name').textContent = customerName;
document.getElementById('order-total-value').textContent = totalOrderValue;

// Retrieve the array data from URL parameters
var rowsParam = urlParams.get("rows");
var rows = JSON.parse(decodeURIComponent(rowsParam));

// Get the table element
var table = document.getElementById("order-details");

// Loop through the rows array and populate the table
for (var i = 0; i < rows.length; i++) {
  var row = rows[i];
  var tableRow = document.createElement("tr");

  for (var j = 0; j < row.length; j++) {
    var cell = document.createElement("td");
    cell.textContent = row[j];
    tableRow.appendChild(cell);
  }

  table.appendChild(tableRow);
}

</script>


</body>
</html>
