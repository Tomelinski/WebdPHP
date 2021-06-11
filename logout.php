<?php
include("./includes/header.php");
    $current_user = $_SESSION['user'];

    logUserLogin($current_user['email_address'], "logout");

    logout();
    $_SESSION['message'] = "You have successfully logged out.";
    redirect('./login.php');

include("./includes/footer.php");
?>