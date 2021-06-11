<?php
/**
 * The login page to my webd3201 lab website
 * 
 * @Author  Tom Zielinski <tom.zielinski@dcmail.ca>
 * @version 2.0 (dec, 18th 2020)
 */
$title = "Lab 01 - Loggin page";
$file = "login.php";
$description = "Lab 01 for webd3201";
$date = "Sept 9th, 2020";
$banner = "Lab 01";
include("./includes/header.php");


if($_SERVER['REQUEST_METHOD'] == "GET"){
    $email = "";
    $password = "";

}else if($_SERVER['REQUEST_METHOD'] == "POST"){
    
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    if($conn){
        
        $current_time_stamp = getDateEST();
        $activeCheck = check_active($email);
        //printdata($activeCheck);

        if(user_authenticate($email, $password) && $activeCheck['enable'] == "t"){
            update_login($email, $current_time_stamp);
            
            $_SESSION['user'] = get_user($email);
            
            logUserLogin($email,  "success");
            $_SESSION['message'] = "</br>You have successfully logged in.";
            redirect("./dashboard.php");
        }else{
            logUserLogin($email, "fail");
            $error.="Could not login";
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

    <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
    
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
    <label for="inputPassword" class="sr-only">Password</label>
    <input 
        type="password" 
        name="password" 
        id="inputPassword" 
        class="form-control" 
        placeholder="Password" 
        value = "<?php echo @$password; ?>" 
        required
    >
    <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
    <a class="btn btn-lg btn-primary btn-block" href="./reset.php"> Reset Password</a>
</form>

<?php
include("./includes/footer.php");
?>    