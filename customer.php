<?php

require_once 'db_connect.php';

session_start();

// Add a new customer
if (isset($_POST['add'])) {
    $id = $_POST['id'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $tel = $_POST['tel'];
    $email = $_POST['email'];

    // Check if any field is empty
    if (!empty(trim($fname)) && !empty(trim($lname)) && !empty(trim($tel)) && !empty($email)) {
        $stmt = $conn->prepare('INSERT INTO `customer`(`first_name`,`last_name`,`tel`,`email`) VALUES (:fname,:lname,:tel,:email)');

        try {
            if ($stmt->execute(array(':fname' => $fname, ':lname' => $lname, ':tel' => $tel, ':email' => $email))) {
                $_SESSION['success'] = "New customer added";
            } else {
                $_SESSION['error'] = "Customer not added";
            }
        } catch (PDOException $ex) {
            $_SESSION['error'] = "Customer not added - " . $ex->getMessage();
        }
    } else {
        $_SESSION['error'] = "One or more fields are empty";
    }

    header('Location: dash-cust.php');
    exit();
}

// Edit an existing customer
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $tel = $_POST['tel'];
    $email = $_POST['email'];

    // Check if any field is empty
    if (!empty($id) && !empty(trim($fname)) && !empty(trim($lname)) && !empty(trim($tel)) && !empty($email)) {
        $stmt = $conn->prepare('UPDATE `customer` SET `first_name`=:fname, `last_name`=:lname, `tel`=:tel, `email`=:email WHERE `id`=:id');

        try {
            if ($stmt->execute(array(':fname' => $fname, ':lname' => $lname, ':tel' => $tel, ':email' => $email, ':id' => $id))) {
                
                if ($stmt->rowCount() > 0) {
                    // Row edited successfully
                    $_SESSION['success'] = "Customer info edited";
                }

                else {
                    $_SESSION['error'] = "Customer info not edited";
                }
                
            } else {
                $_SESSION['error'] = "Customer info not edited";
            }
        } catch (PDOException $ex) {
            $_SESSION['error'] = "Customer info not edited - " . $ex->getMessage();
        }
    } else {
        $_SESSION['error'] = "One or more fields are empty";
    }

    header('Location: dash-cust.php');
    exit();
}

// Remove a customer
if (isset($_POST['remove'])) {
    $id = $_POST['id'];

    $stmt = $conn->prepare('DELETE FROM `customer` WHERE `id`=:id');

    try {
        if ($stmt->execute(array(':id' => $id))) {
            
            if ($stmt->rowCount() > 0) {
                // Row deleted successfully
                $_SESSION['success'] = "Customer deleted";
            }

            else {
                $_SESSION['error'] = "Customer not deleted";
            }

        } else {
            $_SESSION['error'] = "Customer not deleted";
        }
    } catch (PDOException $ex) {
        $_SESSION['error'] = "Customer not deleted - " . $ex->getMessage();
    }

    header('Location: dash-cust.php');
    exit();
}

?>
