<?php
$title = "Lab 04 - Reset password page";
$file = "login.php";
$description = "Lab 04 for webd3201";
$date = "Dec 9th, 2020";
$banner = "Lab 04";
include("./includes/header.php");


if($_SERVER['REQUEST_METHOD'] == "GET"){
    $email = "";

}else if($_SERVER['REQUEST_METHOD'] == "POST"){
    
    $email = trim($_POST['email']);
    
    if($conn){
        
        //check if email exists
        $checkEmail = check_email($email);

        if(isset($checkEmail) && ($checkEmail['email_address'] <> null || $checkEmail['email_address'] <> "")){
            $_SESSION['message'] = "Password reset email Sent to: " . $email . " Please follow the instructions in the email to reset your password";
            sendEmail($email, "Password Reset", "Hello $email " . PASSWORD_RESET_EMAIL);
            redirect("./reset.php");
        }else{
            $_SESSION['message'] = "Password reset email Sent to: " . $email . " Please follow the instructions in the email to reset your password";
            //$_SESSION['message'] = "User does not exist";
            redirect("./reset.php");
        }
    }else{
        $error.="connection error";
    }
}else{
    $error.="An error has occured while trying to connect to the data base";
}

?>   


<form action="<?PHP $_SERVER['PHP_SELF']; ?>" method = "POST" class="form-signin">
    <h4><?php echo $message; ?></h4>

    <h1 class="h3 mb-3 font-weight-normal">Please enter your email, We will send you a password reset link</h1>
    
    <h2> <?php echo $error; ?> </h2>

    <label for="inputEmail" class="sr-only">Email address</label>
    <input 
        type="email" 
        name="email" 
        id="inputEmail" 
        class="form-control" 
        placeholder="Email address" 
        value = "<?php echo @$email; ?>"
        required
        autofocus
    >
    <button class="btn btn-lg btn-primary btn-block" type="submit">Reset Password</button>
</form>

<?php
include("./includes/footer.php");
?>    