<?php
/**
 * change password page.
 * 
 * @Author  Tom Zielinski <tom.zielinski@dcmail.ca>
 * @version 2.0 (dec, 18th 2020)
 */
$title = "Lab 03 - change password";
$file = "change-password.php";
$description = "Lab 03 for webd3201";
$date = "Nov 6th, 2020";
$banner = "Lab 03";
include("./includes/header.php");


if($_SERVER['REQUEST_METHOD'] == "GET"){
    $password = "";
    $confirm_password = "";

}else if($_SERVER['REQUEST_METHOD'] == "POST"){
    
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    
    if($conn){

        if(validateConfirmPassword($password, $confirm_password)){

            update_password($userDetails['email'] ,$password, $confirm_password);
            
            $_SESSION['message'] = "</br>You have successfully changed your password.";
            redirect("./dashboard.php");
        }else{
            $error .="Confirm password is not identical";
        }
        
    }else{
        $error.="connection error";
    }
}else{
    $error.="An error has occured while trying to connect to the data base";
}

$displayForm = array(
    array(
        "type"=>"text",
        "name"=>"password",
        "value"=>"$password",
        "label"=>"New Password"
    ),
    array(
        "type"=>"text",
        "name"=>"confirm_password",
        "value"=>"$confirm_password",
        "label"=>"Confirm Password"
    )
);

?>   


<form action="<?PHP $_SERVER['PHP_SELF']; ?>" method = "POST" class="form-signin">
    <h4><?php echo $message; ?></h4>

    <h1 class="h3 mb-3 font-weight-normal">Change Password</h1>
    
    <h2> <?php echo $error; ?> </h2>

    <?php displayForm($displayForm); ?>

    <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
</form>

<?php
include("./includes/footer.php");
?>    