
<?php


// HIDE THE USER LINK FROM THE MENU IF THE USER ROLE IS 'USER'

$user_fullname = $_SESSION['fullname'];
$user_email = $_SESSION['email'];
$user_role = $_SESSION['user_role'];
$profilePicture = $_SESSION['profile_picture'];

$manage_users = "";

if($user_role == 'admin'){
    $manage_users = '<li><a href="dash-user.php"><i class="fas fa-user"></i>Users</a></li>';
}

echo '<div class="sidebar">
        <div class="logo">
            <h2><a href="dashboard.php">Inventory Management System</a></h2>
        </div>
        <ul>
            <li><a href="dash-prod.php"><i class="fas fa-box"></i>Products</a></li>
            <li><a href="dash-catg.php"><i class="fas fa-tags"></i>Categories</a></li>
            <li><a href="dash-cust.php"><i class="fas fa-users"></i>Customers</a></li>
            <li><a href="dash-order.php"><i class="fas fa-shopping-cart"></i>Orders</a></li>
            '. $manage_users .'
        </ul>
    </div>
    <div class="main-content">
        <div class="header">
            <div class="user">
                <img src="'. $profilePicture .'" alt="User Image">
                <span>Welcome, '. $user_fullname .'</span>
            </div>
            <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i>Logout</a>
        </div>
        <div class="content">';
        
