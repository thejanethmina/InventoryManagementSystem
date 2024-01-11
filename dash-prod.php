<?php

include 'redirect_to.php';

// Include the database connection file
require_once 'db_connect.php';

// Fetch all products from the "product" table
$sql = "SELECT * FROM product";
$stmt = $conn->prepare($sql);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all categories from the "category" table
$sql2 = "SELECT * FROM category";
$stmt2 = $conn->prepare($sql2);
$stmt2->execute();

// Generate options for select element based on fetched categories
$options = '';
while($row2 = $stmt2->fetch(PDO::FETCH_ASSOC))
{
    $options .= '<option value="' . $row2['id'] . '" name="category_id">' . $row2['name'].'</option>';
}

// Check if a search operation is requested
if(isset($_POST['search']))  
{
    $searchValue = $_POST['search-value'];
    
    // Search products based on the search value using a LIKE query
    $search_sql = "SELECT * FROM product WHERE product.name LIKE '%" . $searchValue . "%'";
    
    $search_stmt = $conn->prepare($search_sql);
    $search_stmt->execute();
    $results = $search_stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Products</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/common-style.css">
    <link rel="stylesheet" href="css/prod-style.css">
    <style>

    </style>
</head>
<body>

<?php include 'common-section.php'; ?>

<h2>Products</h2>

<div class="product-section">

<?php include 'alert-file.php'; ?>

  <div class="product-header">
    <h3>Products</h3>
    <div class="search-bar">
      <form action="#" method="post" class="search-form">
        <input type="text" name="search-value" placeholder="Search...">
        <button type="submit" name="search">Search</button>
      </form>
    </div>
    <button class="add-btn">Add Product</button>
  </div>
  <table id="productTable">
    <thead>
        <tr>
            <th>ID</th>
            <th>Picture</th>
            <th>Name</th>
            <th>Category</th>
            <th>Description</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
      <!-- products rows here -->
      <?php

$i = 0;
foreach($results as $row)
{
    echo "<tr>";
    echo "<td>".$row['id']."</td>";
    echo "<td style='width:75px; height:75px;'><img src='".$row['picture']."' style='width:100%; height:100%;'></td>";
    echo "<td>".$row['name']."</td>";
    echo "<td>".$row['category']."</td>";
    echo "<td>".$row['description']."</td>";
    echo "<td>".$row['quantity']."</td>";
    echo "<td>".$row['price']."</td>";
    echo "<td>";
    echo"<button class='edit-btn' data-row-index=".$i.">Edit</button>";
    echo"<button class='remove-btn' data-row-index=".$i.">Remove</button>";
    echo "</td>";
    echo "</tr>";
    $i += 1;
}

?>
    </tbody>
  </table>

<!-- add product modal -->
<div class="modal" id="add-product-modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h3 id="modal-title">Add Product</h3>
      <form action="product.php" method="post" enctype="multipart/form-data">
        <input type="hidden" id="product-id" name="product-id" placeholder="id">
        <input type="text" id="product-name" name="product-name" placeholder="name" required>
        <input type="number" id="product-quantity" name="product-quantity" placeholder="quantity" required>
        <select id="product-category" name="product-category" placeholder="category" required>
          <option value="">Select Category</option>
          
          <!-- populate options dynamically from the database -->
          <?php echo $options; ?>

        </select>
        <textarea id="product-description" name="product-description" placeholder="description"></textarea>
        <input type="text" id="product-price" name="product-price" placeholder="price" required>
        
        <!-- Add the input file element for selecting an image -->
        <label for="product-image">Product Image:</label>
        <input type="file" id="product-image" name="product-image" value="">
        <!-- <button type="submit" name="edit">edit2</button> -->
        <button type="submit" name="add" class="add-btn" id="add-edit">Add Product</button>
        <button type="button" id="cancel-add" class="cancel-btn">Cancel</button>
      </form>
    </div>
  </div>
<!-- end add product modal -->

<!-- Start Delete modal -->

<div class="modal" id="delete-product-modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h3>Confirmation</h3>
        <p>Are you sure you want to delete this product?</p>
        <form action="product.php" method="post">
            <input type="hidden" id="product-id-delete" name="product-id-delete" value="1">
            <input type="hidden" id="product-image-delete" name="product-image-delete" value="1">
            <div class="modal-buttons">
                <button type="submit" name="remove" id="confirm-delete" class="delete-btn">
                    Delete
                </button>

                <button type="button" id="cancel-delete" class="cancel-btn">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- End Delete modal -->







</div>

        </div>
    </div>



<script>

// show the "add product modal"

// Get the modal
var modal = document.getElementById("add-product-modal");

// Get the button that opens the modal
var addButton = document.querySelector(".add-btn");

// Get the cancel add button
const cancelAddModal = document.getElementById("cancel-add");

// Get the <span> element that closes the modal
var closeBtn = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal
addButton.onclick = function() {
  modal.style.display = "block";

    // Clear the input fields
    document.getElementById("product-id").value = "";
    document.getElementById("product-name").value = "";
    document.getElementById("product-price").value = "";
    document.getElementById("product-description").value = "";
    document.getElementById("product-category").options[0].selected = true;

    // Update the modal title and button text
    document.getElementById("modal-title").innerText = "Add Product";
    document.getElementById("add-edit").innerText = "Add Product";
    document.getElementById("add-edit").name = "add";
}

// When the user clicks on <span> (x), close the modal
closeBtn.onclick = function() {
  modal.style.display = "none";
}

// Add click event listener to the cancel delete button
cancelAddModal.onclick = function(){
    // hide the delete modal
    modal.style.display = "none";
}


</script>

<script>

// show the "edit product modal"

// Get all the edit buttons
var editButtons = document.querySelectorAll(".edit-btn");

// Function to handle the click event on the edit buttons
editButtons.forEach((button)=>{

   button.addEventListener('click', (event)=>{
       // Get the row index of the clicked button
       const rowIndex = event.target.dataset.rowIndex;
       // Get the corresponding table row
       const tableRow = document.querySelector(`table tbody tr:nth-child(${parseInt(rowIndex)+1})`);
        // Get the values from the table cells
       const id = tableRow.cells[0].textContent;
       const picture = tableRow.cells[1].textContent;
       const name = tableRow.cells[2].textContent;
       const category = tableRow.cells[3].textContent;
       const description = tableRow.cells[4].textContent;
       const quantity = tableRow.cells[5].textContent;
       const price = tableRow.cells[6].textContent;
       //Update the input fields with the values;
       document.getElementById("product-id").value = id;
       document.getElementById("product-image").value = picture;
       document.getElementById("product-name").value = name;
       document.getElementById("product-description").value = description;
       document.getElementById("product-price").value = price;
       document.getElementById("product-quantity").value = quantity;
       
       // Select the corresponding option in the category dropdown
       const categoryDropDown = document.getElementById("product-category");
       for(let i=0; i < categoryDropDown.length; i++){
           if(categoryDropDown.options[i].value === category){
                 categoryDropDown.options[i].selected = true;
                 break;
           }
       }

        // Update the modal title and button text
       document.getElementById("modal-title").innerText = "Edit Product";
       document.getElementById("add-edit").innerText = "Edit Product";
       document.getElementById("add-edit").name = "edit";

        // Display the modal
        modal.style.display = "block";
   });

});

</script>


<script>

// Get all the delete buttons
const deleteButtons = document.querySelectorAll(".remove-btn");
// Get the delete modal
const deleteModal = document.getElementById("delete-product-modal");
// Get the cancel delete button
const cancelDeleteModal = document.getElementById("cancel-delete");

// Add click event listeners to the delete buttons
deleteButtons.forEach((button) => {
  button.addEventListener("click", (event) => {
    // Get the row index and table row of the clicked button
    const rowIndex = event.target.dataset.rowIndex;
    const tableRow = document.querySelector(`table tbody tr:nth-child(${parseInt(rowIndex) + 1})`);

    // Get the values from the table cells
    const id = tableRow.cells[0].textContent;
    const pictureCell = tableRow.cells[1];
    let picture;

    // Check if the picture is stored as an attribute or within an <img> tag
    if (pictureCell.hasAttribute("data-picture")) {
      picture = pictureCell.getAttribute("data-picture");
    } else if (pictureCell.querySelector("img")) {
      const img = pictureCell.querySelector("img");
      picture = img.getAttribute("src");
    } else {
      // Handle the case when the picture is not found
      picture = "";
    }

    console.log("---");
    console.log(picture);
    console.log("---");

    // Set the value of the delete form input
    document.getElementById("product-id-delete").value = id;
    document.getElementById("product-image-delete").value = picture;

    // Display the delete modal
    deleteModal.style.display = "block";
  });
});






// Add click event listener to the cancel delete button
cancelDeleteModal.onclick = function(){
    // hide the delete modal
    deleteModal.style.display = "none";
}

// Get the close button for the delete modal
var closeBtn = document.getElementsByClassName("close")[1];

// Add click event listener to the close button
closeBtn.onclick = function(){ 
    // hide the delete modal
    deleteModal.style.display = "none"; 
    }

// Close the delete modal and main modal when clicking outside of them
window.onclick = function(event){
if(event.target == deleteModal || event.target == modal ){
    deleteModal.style.display = "none"; 
    modal.style.display = "none"; 
}
}

</script>



<script src="js/pagination.js"></script>

<script>
  // Call handlePagination() for "productTable"
  handlePagination('productTable', 5);
</script>





<script src="js/close-msg.js"></script>




</body>
</html>
