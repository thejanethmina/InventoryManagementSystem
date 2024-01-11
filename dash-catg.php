<?php

include 'redirect_to.php';

require_once 'db_connect.php';

// Select all rows from the "category" table
$sql = "SELECT * FROM category";
$stmt = $conn->prepare($sql);
$stmt->execute();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Category</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/common-style.css">
    <link rel="stylesheet" href="css/catg-style.css">
</head>
<body>

<?php include 'common-section.php'; ?>

        <h2>Categories</h2>

<div class="category-section">

<?php include 'alert-file.php'; ?>

<div class="category-form">
  <h3>Manage</h3>
  <form action="category.php" method="post">
    <input type="number" name="id" id="category_id" placeholder="Category ID">
    <input type="text" name="name" id="category_name" placeholder="Category Name">
    <div class="form-buttons">
      <button type="submit" name="add" class="add-btn">Add</button>
      <button type="submit" name="edit" class="edit-btn">Edit</button>
      <button type="submit" name="remove" class="remove-btn">Remove</button>
    </div>
  </form>
</div>





<div class="category-table">
  <h3>Category List</h3>
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <!-- more category rows here -->
      <?php
        // Generate table rows
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          echo "<tr>";
          echo "<td>".$row['id']."</td>";
          echo "<td>".$row['name']."</td>";
          echo "<td>";
          echo "<button class='view-products-btn' data-category-id='".$row['id']."' data-category-name='".$row['name']."'>View Products</button>";
          echo "</td>";
          echo "</tr>";
        }
      ?>
    </tbody>
  </table>
</div>

<div class="product-table">
  <h3>Products in - <span id="categoryName" style="color:#555">[Category Name]</span></h3>
  <table id="product-table">
    <!-- Table content will be loaded dynamically When a Category is Selected -->
  </table>
</div>
</div>


<script>

document.addEventListener('DOMContentLoaded', function() {
  // Handler for "View Products" button click
  var viewProductsBtns = document.querySelectorAll('.view-products-btn');
  viewProductsBtns.forEach(function(btn) {
    btn.addEventListener('click', function() {
      var categoryId = this.dataset.categoryId;
      var categoryName = this.dataset.categoryName;

      document.getElementById('category_id').value = categoryId;
      document.getElementById('category_name').value = categoryName;
      document.getElementById('categoryName').textContent = categoryName;

      // AJAX request to fetch products for the selected category
      fetch('get_products.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'category_id=' + categoryId
      })
        .then(function(response) {
          if (response.ok) {
            return response.text();
          } else {
            throw new Error('Request failed with status ' + response.status);
          }
        })
        .then(function(data) {
          // Update the product table with the fetched data
          document.getElementById('product-table').innerHTML = data;
        })
        .catch(function(error) {
          console.log(error);
        });

        // Scroll to the form
        window.scrollTo({ top: 0, behavior: 'smooth' });

    });

  });

});

</script>

<script src="js/close-msg.js"></script>

        </div>
    </div>

</body>
</html>
