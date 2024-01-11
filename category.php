<?php

// Include the database connection file
require_once 'db_connect.php';

// Start the session
session_start();

// Add a new category
if (isset($_POST['add'])) {

    $name = $_POST['name'];

    // Check if the category name is not empty
    if (!empty(trim($name))) {
        $stmt = $conn->prepare('INSERT INTO `category`(`name`) VALUES (:name)');

        try {
            // Execute the SQL query to insert the category
            if ($stmt->execute(array(':name' => $name))) {
                $_SESSION['success'] = "New category added";
            } else {
                $_SESSION['error'] = "Category not added";
            }
        } catch (PDOException $ex) {
            $_SESSION['error'] = "Category not added - " . $ex->getMessage();
        }
    } else {
        $_SESSION['error'] = "Category name cannot be empty";
    }

    // Redirect back to the dashboard
    header('Location: dash-catg.php');
    exit();
}

// Edit category
if (isset($_POST['edit'])) {

    $id = $_POST['id'];
    $name = $_POST['name'];

    // Check if both the category ID and name are not empty
    if (!empty($id) && !empty(trim($name))) {
        $stmt = $conn->prepare('UPDATE `category` SET `name`=:name WHERE `id`=:id');

        try {
            // Execute the SQL query to update the category name
            if ($stmt->execute(array(':name' => $name, ':id' => $id))) {
                
                if ($stmt->rowCount() > 0) {
                    // Row edited successfully
                    $_SESSION['success'] = "Category name edited";
                }

                else {
                    $_SESSION['error'] = "Category name not edited";
                }

            } else {
                $_SESSION['error'] = "Category name not edited";
            }
        } catch (PDOException $ex) {
            $_SESSION['error'] = "Category name not edited - " . $ex->getMessage();
        }

        // Redirect back to the dashboard
        header('Location: dash-catg.php');
        exit();
    } else {
        $_SESSION['error'] = "Category name and ID Cannot be empty";
        header('Location: dash-catg.php');
        exit();
    }
}

// Remove category
if (isset($_POST['remove'])) {

    $id = $_POST['id'];

    // Check if the category ID is not empty
    if (!empty($id)) {
        $stmt = $conn->prepare('DELETE FROM `category` WHERE `id`=:id');

        try {
            // Execute the SQL query to delete the category
            if ($stmt->execute(array(':id' => $id))) {
                if ($stmt->rowCount() > 0) {
                    // Row deleted successfully
                    $_SESSION['success'] = "Category deleted";
                }

                else {
                    $_SESSION['error'] = "Category not deleted";
                }

            } else {
                $_SESSION['error'] = "Category not deleted";
            }
        } catch (PDOException $ex) {
            $_SESSION['error'] = "Category not deleted - " . $ex->getMessage();
        }

        // Redirect back to the dashboard
        header('Location: dash-catg.php');
        exit();
    } else {
        $_SESSION['error'] = "Category ID is empty";
        header('Location: dash-catg.php');
        exit();
    }
}


?>
