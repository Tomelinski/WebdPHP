<?php
/**
 * Sales people Page, only admins have access. Admins are allowed to deactivate salespeople accounts.
 * 
 * @Author  Tom Zielinski <tom.zielinski@dcmail.ca>
 * @version 2.0 (dec, 18th 2020)
 */
$title = "Lab 02 - Salesperson";
$file = "salesperson.php";
$description = "Lab 02 for webd3201";
$date = "Oct 23rd, 2020";
$banner = "Lab 02";
include("./includes/header.php");

if($userDetails['type'] != ADMIN && $userDetails['type'] != AGENT){
    $_SESSION['message'] = "</br>You do not have access to this page.";
    redirect("./index.php");
}

if(!isset($_GET['page'])){
    $page = 1;
} else {
    $page = $_GET['page'];
}

if($_SERVER['REQUEST_METHOD'] == "GET"){
    $firstName = "";
    $lastName = "";
    $email = "";
    $password = "";
} 
else if($_SERVER['REQUEST_METHOD'] == "POST")
{
    //printdata($_POST);
    if(isset($_POST['active'])){
        $firstName = "";
        $lastName = "";
        $email = "";
        $password = "";
        foreach($_POST['active'] as $userId => $active){
            //printdata($userId . " " . $active);
            if($active == "Active"){
                update_active($userId, 1);
            }else if($active == "Inactive"){
                update_active($userId, 0);
            }
            //update_active($userId, $active);
        }
    }else{

        $firstName = trim($_POST['first_name']);
        $lastName = trim($_POST['last_name']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        
        $valid_f = validateFirstName($firstName);
        $valid_l = validateLastName($lastName);
        $valid_e = validateEmail($email);
        $valid_p = validatePassword($password);

        if($valid_p && $valid_f && $valid_l && $valid_e){

            if($conn){

                $result = user_insert($firstName, $lastName, $email, $password);
                if(!$result){
	    			$error .= "Failed to insert to database";
	    		}
	    		else
	    		{    
                    $_SESSION['message'] = "You have successfully Created a Salesperson.";
	    			pg_close($conn);
                    redirect('./salespeople.php');
	    		}
            }else{
                $error.="An error has occured while trying to connect to the data base";
            }
        } 
    }
    
}

$displayForm = array(
    array(
        "type"=>"text",
        "name"=>"first_name",
        "value"=>"$firstName",
        "label"=>"First Name"
    ),
    array(
        "type"=>"text",
        "name"=>"last_name",
        "value"=>"$lastName",
        "label"=>"Last Name"
    ),
    array(
        "type"=>"email",
        "name"=>"email",
        "value"=>"$email",
        "label"=>"Email"
    ),
    array(
        "type"=>"text",
        "name"=>"password",
        "value"=>"$password",
        "label"=>"Password"
    ),
);

$displayTable = array(
    array(
        "id"=>"",
        "email"=>"Email",
        "first_name"=>"First Name",
        "last_name"=>"Last Name",
        "active"=>"Is Active"
    ),
    agent_select_all($page),
    agent_count(),
    $page
);

?>

<form action="<?PHP $_SERVER['PHP_SELF']; ?>" method = "POST" class="form-signin">
    <h4><?php echo $message; ?></h4>

    <h1 class="h3 mb-3 font-weight-normal">Add a new Agent</h1>
    
    <h2> <?php echo $error; ?> </h2>

    <?php displayForm($displayForm); ?>

    <button class="btn btn-lg btn-primary btn-block" type="submit">Create</button>
</form>
</div>

<div class="table-responsive">
<?php
    displayTable($displayTable, $file);
?>

<?php
include("./includes/footer.php");
?>    