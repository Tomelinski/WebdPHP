<?php 
    ob_start();
    require("constants.php");
    require("functions.php");
    require("db.php");
    session_start();
    
    //$isLoggedIn = isset($_SESSION['isLoggedIn']) ? $_SESSION['isLoggedIn'] : false;
    $message = isset($_SESSION['message']) ? $_SESSION['message']:"";
    $userDetails = isset($_SESSION['user']) ? $_SESSION['user'] : null;
    unset($_SESSION['message']);
    $error = "";

    ?>
    
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Tom Zielinski">
    <link rel="icon" href="/docs/4.0/assets/img/favicons/favicon.ico">

    <title>WEBD3201 - <?php echo $title ?></title>

    <!--
	Author: Tom Zielinski
	Filename: <?php echo $file ?>
	Date: <?php echo $date ?>
	Description: <?php echo $description ?>
	-->

    <!-- Bootstrap core CSS -->
    <link href="./css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="./css/styles.css" rel="stylesheet">
	
  </head>
  <body>
    <nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0">
        <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="./index.php">Company name</a>
        <?php 
                echo (isset($_SESSION['user']) ? 
                '<a class="nav-link ml-auto" href="./index.php"> Welcome ' . $userDetails['email_address'] . '</a>'
                :
                null);
            ?>
        <ul class="navbar-nav px-3 signout">
        <li class="nav-item text-nowrap">
            <?php 
                echo (isset($_SESSION['user']) ? 
                ('<a class="nav-link" href="./logout.php">Sign out</a>')
                :
                '<a class="nav-link" href="./login.php">Sign in</a>');
            ?>
        </li>
        </ul>
    </nav>
    <div class="container-fluid">
      <div class="row">
        
        <nav class="col-md-2 d-none d-md-block bg-light sidebar">
            <div class="sidebar-sticky">
            <ul class="nav flex-column">
                <li class="nav-item">
                <?php 
                    echo (isset($_SESSION['user']) ? 
                    '<a class="nav-link active" href="./dashboard.php">
                        <span data-feather="home"></span>
                        Dashboard <span class="sr-only">(current)</span>
                    </a>'
                    :
                    null);
                ?>
                </li>
                <?php 
                if($userDetails['type'] == ADMIN){
                    echo '<li class="nav-item">
                        <a class="nav-link" href="./salespeople.php">
                        <span data-feather="users"></span>
                        Sales People    
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./clients.php">
                            <span data-feather="users"></span>
                            Clients
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./calls.php">
                            <span data-feather="users"></span>
                            Calls
                        </a>
                    </li>';
                }else if($userDetails['type'] == AGENT){
                    echo '<li class="nav-item">
                        <a class="nav-link" href="./clients.php">
                            <span data-feather="users"></span>
                            Clients
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./calls.php">
                            <span data-feather="users"></span>
                            Calls
                        </a>
                    </li>';
                }              
                else if(isset($_SESSION['user'])){
                    echo '<li class="nav-item">
                        <a class="nav-link" href="./dashboard.php">
                            <span data-feather="users"></span>
                            Sales People
                        </a>
                    </li>';
                } else{
                    echo '<li class="nav-item">
                        <a class="nav-link" href="./login.php">
                            <span data-feather="users"></span>
                            Sales People
                        </a>
                    </li>';
                }
                ?>
                
                </li>
                <li class="nav-item">
                <a class="nav-link" href="#">
                    <span data-feather="file"></span>
                    Orders
                </a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="#">
                    <span data-feather="shopping-cart"></span>
                    Products
                </a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="#">
                    <span data-feather="users"></span>
                    Customers
                </a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="#">
                    <span data-feather="bar-chart-2"></span>
                    Reports
                </a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="#">
                    <span data-feather="layers"></span>
                    Integrations
                </a>
                </li>
            </ul>

            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                <span>Saved reports</span>
                <a class="d-flex align-items-center text-muted" href="#">
                <span data-feather="plus-circle"></span>
                </a>
            </h6>
            <ul class="nav flex-column mb-2">
                <li class="nav-item">
                <a class="nav-link" href="#">
                    <span data-feather="file-text"></span>
                    Current month
                </a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="#">
                    <span data-feather="file-text"></span>
                    Last quarter
                </a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="#">
                    <span data-feather="file-text"></span>
                    Social engagement
                </a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="#">
                    <span data-feather="file-text"></span>
                    Year-end sale
                </a>
                </li>
            </ul>
            </div>
        </nav>

        <main class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">