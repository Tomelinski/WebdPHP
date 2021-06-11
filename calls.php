<?php
$title = "Lab 02 - Calls";
$file = "calls.php";
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

if($_SERVER['REQUEST_METHOD'] == "POST")
{

    $selectedClient = trim($_POST['client']);

    if($conn){
        $result = call_insert($selectedClient, $userDetails['id']);
        $_SESSION['message'] = "You have successfully called a Client.";
        pg_close($conn);
        redirect('./calls.php');
           
    }else{
        $error.="An error has occured while trying to connect to the data base";
    }
    
}

$displayTable = array(
    array(
        "c.email"=>"Email",
        "client_first_name"=>"Client First Name",
        "client_last_name"=>"Client Last Name",
        "u.first_name"=>"Agent First Name",
        "u.last_name"=>"Agent Last Name",
        "u.email"=>"Agent Email",
    ),
    call_select_all($page),
    call_count(),
    $page
)

?>

<form action="<?PHP $_SERVER['PHP_SELF']; ?>" method = "POST" class="form-signin">
    <h4><?php echo $message; ?></h4>

    <h1 class="h3 mb-3 font-weight-normal">Place a Call</h1>
    
    <h2> <?php echo $error; ?> </h2>

    <?php 
    
        $client = get_clients();
        echo'
        <label for="client" class="sr-only">Client</label>
        <select name="client" id="client" class="form-control">';
        foreach($client as $c){
            echo'
                <option value="' . $c['clientid'] . '"> ' . $c['first_name'] . ' ' . $c['last_name'] . '</option>
            ';
        }
        echo'
        </select>
        ';
?>

    <button class="btn btn-lg btn-primary btn-block" type="submit">Call</button>
</form>
</div>

<div class="table-responsive">
<?php
    displayTable($displayTable, $file);
?>

<?php
include("./includes/footer.php");
?>    