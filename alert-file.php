<?php

// Check if there is an error message in the session
if(isset($_SESSION['error'])){
    // Display an error message
    echo '<div class="alert error"><p>'.$_SESSION['error'].'</p>
          <span class="close-alert">&times;</span></div>';
}

// Check if there is a success message in the session
if(isset($_SESSION['success'])){
    // Display a success message
    echo '<div class="alert success"><p>'.$_SESSION['success'].'</p>
          <span class="close-alert">&times;</span></div>';
}

// Clear the error and success messages from the session
unset($_SESSION['error']);
unset($_SESSION['success']);

?>
