<?php
// Include the file for redirecting
include 'redirect_to.php';

// Check the user's role
$user_role = $_SESSION['user_role'];

// If the user is not an admin, redirect to the login page
if($user_role != 'admin'){
  header('Location: login.php');
}

// Include the database connection file
require_once 'db_connect.php';

// Select all users from the database
$sql = "SELECT * FROM user";
$stmt = $conn->prepare($sql);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>


<!DOCTYPE html>
<html>
<head>
    <title>Users</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/common-style.css">
    <link rel="stylesheet" href="css/user-style.css">
</head>
<body>

<?php include 'common-section.php'; ?>

<h2>Users</h2>

<div class="user-section">

<?php include 'alert-file.php'; ?>

  <div class="user-table">
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Picture</th>
          <th>Full Name</th>
          <th>Email</th>
          <th>Password</th>
          <th>Telephone</th>
          <th>User Role</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <!-- More user rows here -->
        <?php
            // Generate table rows
            foreach($results as $row)
            {
                echo "<tr>";
                echo "<td>".$row['id']."</td>";
                echo "<td style='width:75px; height:75px;'><img src='".$row['user_image']."' style='width:100%; height:100%;'></td>";
                echo "<td>".$row['fullname']."</td>";
                echo "<td>".$row['email']."</td>";
                echo "<td>********</td>";
                echo "<td>".$row['tel']."</td>";
                echo "<td>".$row['user_role']."</td>";
                echo "<td>
                        <button class='edit-btn' onclick='editUser(this)'>Edit / Remove</button>
                      </td>";
                echo "</tr>";
            }
        ?>
      </tbody>
    </table>
  </div>

  <div class="user-form">
    <h3>Manage</h3>
    <form action="user.php" method="post" enctype="multipart/form-data">
      <input type="hidden" name="id" id="user-id">
      <input type="text" name="fullname" id="fullname" placeholder="Full Name" required>
      <input type="email" name="email" id="email" placeholder="Email" required>
      <input type="password" name="password" id="password" placeholder="Password">
      <input type="tel" name="tel" id="tel" placeholder="Telephone" required>

      <!-- Add the input file element for selecting an image -->
      <label for="product-image">Profile Picture:</label>
      <input type="text" id="user-image-delete" name="user-image-delete" value="1">
      <input type="file" id="user-image" name="user-image" value="">

      <select name="user_role" id="user-role">
        <option value="admin">Admin</option>
        <option value="user">User</option>
      </select>
      <div class="form-buttons">
        <button name="add" class="add-btn">Add</button>
        <button name="edit" class="edit-btn">Edit</button>
        <button name="remove" class="remove-btn">Remove</button>
      </div>
    </form>
  </div>
</div>

<script>
  function editUser(button) {
    // Get the table row
    var row = button.parentNode.parentNode;

    // Get the user data from the row
    var id = row.cells[0].textContent;
    var picture = row.cells[1].querySelector('img').getAttribute('src');
    var fullname = row.cells[2].textContent;
    var email = row.cells[3].textContent;
    var tel = row.cells[5].textContent;
    var userRole = row.cells[6].textContent;

    // Populate the form fields with the user data
    document.getElementById('user-id').value = id;
    document.getElementById("user-image-delete").value = picture;
    document.getElementById('fullname').value = fullname;
    document.getElementById('email').value = email;
    document.getElementById('tel').value = tel;
    
    
    // Set the selected option in the user role select element
    var userRoleSelect = document.getElementById('user-role');
    for (var i = 0; i < userRoleSelect.options.length; i++) {
    if (userRoleSelect.options[i].value === userRole) {
        userRoleSelect.selectedIndex = i;
        break;
    }
    }

    // Scroll to the form
    document.querySelector('.user-form').scrollIntoView({
      behavior: 'smooth'
    });
  

  }
</script>



<script src="js/close-msg.js"></script>




        </div>
    </div>



</body>
</html>
