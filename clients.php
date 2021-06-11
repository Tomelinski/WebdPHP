<?php
/**
 * Clients page
 * 
 * Admins and Salespeople have access to this page, 
 * 
 * @Author  Tom Zielinski <tom.zielinski@dcmail.ca>
 * @version 2.0 (dec, 18th 2020)
 */
$title = "Lab 02 - Clients";
$file = "clients.php";
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
    $phoneNumber ="";
} 
else if($_SERVER['REQUEST_METHOD'] == "POST")
{
    
    $firstName = trim($_POST['first_name']);
    $lastName = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phoneNumber = trim($_POST['phoneNumber']);
    
    $valid_f = validateFirstName($firstName);
    $valid_l = validateLastName($lastName);
    $valid_e = validateEmail($email);
    $valid_p = validatePhoneNumber($phoneNumber);

    if(isset($_FILES)){
        $valid_fp = validateFile($_FILES, $email);
    }else{
        $valid_fp = false;
        $error .= "Error selecting file";
    }
    
    
    if($valid_p && $valid_f && $valid_l && $valid_e && $valid_fp){
        

        $tmp_name = $_FILES['filePath']['tmp_name'];
        $fileName = basename($_FILES['filePath']['name']);
        if (!move_uploaded_file($tmp_name, "./uploads/$fileName-$email")){
            $error .= "Error uploading image";
        }

        if($conn){
            
            if(!check_client($email)){
                
                    $result = client_insert($firstName, $lastName, $email, $phoneNumber,  $fileName);
    
                if(!$result){
                    $error .= "Failed to insert to database";
                }
                else
                {
                    $cId =  get_clientID($email);
                    if($userDetails['type'] == AGENT){
                        call_insert($cId['clientid'], $userDetails['id']);
                    }else if($userDetails['type'] == ADMIN){
                        $selectedAgent = trim($_POST['agent']);
                        call_insert($cId['clientid'], $selectedAgent);
                    }
                    

                    $_SESSION['message'] = "You have successfully added a Client.";
                    pg_close($conn);
                    redirect('./clients.php');
     
                }
            }else{
                $_SESSION['message'] = "Error client already exists.";
            }

        }else{
            $error.="An error has occured while trying to connect to the data base";
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
        "type"=>"number",
        "name"=>"phoneNumber",
        "value"=>"$phoneNumber",
        "label"=>"Phone number"
    ),
    array(
        "type"=>"file",
        "name"=>"filePath",
        "value"=>"",
        "label"=>"Logo"
    )
);

$displayTable = array(
    array(
        "email"=>"Email",
        "first_name"=>"First Name",
        "last_name"=>"Last Name",
        "phoneNumber"=>"Phone Number",
        "logo_path"=>"Logo"
    ),
    client_select_all($page, $userDetails),
    client_count($userDetails),
    $page
)

?>

<form action="<?PHP $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" method = "POST" class="form-signin">
    <h4><?php echo $message; ?></h4>

    <h1 class="h3 mb-3 font-weight-normal">Enter Client data</h1>
    
    <h2> <?php echo $error; ?> </h2>
    
    <?php 
        displayForm($displayForm); 

        if($userDetails['type'] == ADMIN){
            $agents = get_agents();
            echo'
            <label for="agent" class="sr-only">Assign Agent</label>
            <select name="agent" id="agent" class="form-control">';
            foreach($agents as $a){
                echo'
                    <option value="' . $a['id'] . '"> ' . $a['first_name'] . ' ' . $a['last_name'] . ' </option>
                ';
            }
            echo'
            </select>
            ';
        }
    ?>

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