<?php
// Include the database connection file
require_once "db_connect.php";

// Check if the login form was submitted
if(isset($_POST['login']))
{
    // Start the session
    session_start();

    // Get the user input from the form
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the email exists in the database
    $email_check_query = "SELECT * FROM `user` WHERE `email`=:email";
    $stmt = $conn->prepare($email_check_query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // If the email exists, verify the password
    if($result)
    {  
        if(password_verify($password, $result['password']))
        {
            // Retrieve the user info from the database
            $profilePicture = $result['user_image'];
            $_SESSION['fullname'] = $result['fullname'];
            $_SESSION['email'] = $result['email'];
            $_SESSION['user_role'] = $result['user_role'];
            $_SESSION['profile_picture'] = $profilePicture;
            
            header("Location: dashboard.php");
            exit();
        }
        else
        {
            $_SESSION['error'] = 'Incorrect Password';
            header("Location: login.php");
            exit();
        }
    }
    else
    {
        $_SESSION['error'] = 'Incorrect Email or Password';
        header("Location: login.php");
        exit();
    }
    
    // Clear any existing error messages
    unset($_SESSION['error']);
}
?>

