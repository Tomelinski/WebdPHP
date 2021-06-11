<?php 
/**
 * Database functions page 
 * 
 * All functions that require a connection to the database are used in this page.
 * 
 * @Author  Tom Zielinski <tom.zielinski@dcmail.ca>
 * @version 4.0 (dec, 18th 2020)
 */

// Function to connect to database
function db_connect() {
    //return pg_connect("host=localhost dbname=postgres user=postgres password=koperwas1963");

    return pg_connect("host=".DB_HOST." port=".DB_PORT." dbname=".DATABASE." user=".DB_ADMIN." password=".DB_PASSWORD);
}

$conn = db_connect();

/* ---------------------- Clients / agents ------------------- */

//insert Clients
function client_insert($firstName, $lastName, $email, $phoneNumber, $logoPath){
    global $conn;
    $insert = pg_prepare($conn, "client_insert", "INSERT INTO clients(first_name, last_name, email, phoneNumber, logo_path) VALUES($1, $2, $3, $4, $5)");

    return pg_execute($conn, 'client_insert', array($firstName, $lastName, $email, $phoneNumber, $logoPath));
}

function get_clientID($email){
    global $conn;
    $select = pg_prepare($conn, 'client_select', 'SELECT * FROM clients WHERE email = $1');

    $ex = pg_execute($conn, 'client_select', array($email));
    return pg_fetch_assoc($ex);
}


function call_insert($clientID, $userID){
    global $conn;
    $insert = pg_prepare($conn, "call_insert", "INSERT INTO calls(clientID, userID, callTime)  VALUES($1, $2, $3)");

    $date = getDateEST();
    return pg_execute($conn, 'call_insert', array($clientID, $userID, $date));
}

function get_agents(){
    global $conn;
    $select = pg_prepare($conn, 'agent_select', 'SELECT id, first_name, last_name FROM users WHERE type = $1');

    $ex = pg_execute($conn, 'agent_select', array(AGENT));
    return pg_fetch_all($ex);
}

function get_clients(){
    global $conn;
    $select = pg_prepare($conn, 'client_select', 'SELECT clientID, first_name, last_name FROM clients');

    $ex = pg_execute($conn, 'client_select', array());
    return pg_fetch_all($ex);
}

function check_client($email){
    global $conn;
    $selectClient = pg_prepare($conn, 'client_check', 'SELECT email FROM clients WHERE email = $1');

    $client = pg_execute($conn, 'client_check', array($email));

    return pg_fetch_assoc($client);
}

/* --------------------------- tables -------------------------------- */

function client_select_all($page, $currentUser){
    global $conn;

    $start_page = ($page - 1) * USERS_ON_PAGE;
    $clients = "";

    if($currentUser['type'] == ADMIN){
        $select = pg_prepare($conn, 'client_select_all', 'SELECT email, first_name, last_name, phoneNumber, logo_path FROM clients LIMIT $1 OFFSET $2');
        $clients =  pg_execute($conn, 'client_select_all', array(USERS_ON_PAGE, $start_page));
    }else{
        $select = pg_prepare($conn, 'client_select_all_users', 'SELECT c.email, c.first_name, c.last_name, c.phoneNumber, c.logo_path FROM clients c JOIN calls ON calls.clientID = c.clientID JOIN users u ON calls.userID = u.id WHERE u.email_address = $3 LIMIT $1 OFFSET $2');
        $clients =  pg_execute($conn, 'client_select_all_users', array(USERS_ON_PAGE, $start_page, $currentUser['email_address']));
    }
    return pg_fetch_all($clients);
}

function client_count($currentUser){
    global $conn;

    if($currentUser['type'] == ADMIN){
        $select = pg_prepare($conn, 'client_pages_all', 'SELECT * FROM clients ');
        $clients_rows =  pg_execute($conn, 'client_pages_all', array());
    }else{
        $select = pg_prepare($conn, 'client_pages_agent', 'SELECT c.email, c.first_name, c.last_name, c.phoneNumber, c.logo_path FROM clients c JOIN calls ON calls.clientID = c.clientID JOIN users u ON calls.userID = u.id WHERE u.email_address = $1');
        $clients_rows =  pg_execute($conn, 'client_pages_agent', array($currentUser['email_address']));
    }

    $pages = ceil(pg_num_rows($clients_rows) / USERS_ON_PAGE);
    return $pages;
}

function agent_select_all($page){
    global $conn;

    $start_page = ($page - 1) * USERS_ON_PAGE;

    $select = pg_prepare($conn, 'agent_select_all', 'SELECT id, email_address, first_name, last_name, enable FROM users WHERE type = $1 ORDER BY id LIMIT $2 OFFSET $3');
    $agents =  pg_execute($conn, 'agent_select_all', array(AGENT, USERS_ON_PAGE, $start_page));
    return pg_fetch_all($agents);
}

function agent_count(){
    global $conn;

    $select = pg_prepare($conn, 'agent_pages', 'SELECT * FROM users WHERE type = $1');

    $agent_rows =  pg_execute($conn, 'agent_pages', array(AGENT));

    $pages = ceil(pg_num_rows($agent_rows) / USERS_ON_PAGE);
    return $pages;
}

function update_active($userId, $active){
    global $conn;

    $updateAcive = pg_prepare($conn, 'update_active' , "UPDATE users SET enable = $2 WHERE id = $1");

    $excecute = pg_execute($conn, 'update_active', array($userId, $active));
}

function call_select_all($page){
    global $conn;

    $start_page = ($page - 1) * USERS_ON_PAGE;

    $select = pg_prepare($conn, 'call_select_all', 'SELECT c.email, c.first_name AS client_first_name, c.last_name AS client_last_name, u.first_name, u.last_name, u.email_address FROM calls JOIN clients c ON calls.clientID = c.clientID JOIN users u ON calls.userID = u.id LIMIT $1 OFFSET $2');
    $calls =  pg_execute($conn, 'call_select_all', array(USERS_ON_PAGE, $start_page));
    return pg_fetch_all($calls);
}

function call_count(){
    global $conn;

    $select = pg_prepare($conn, 'call_pages', 'SELECT c.email, c.first_name AS client_first_name, c.last_name AS client_last_name, u.first_name, u.last_name, u.email_address FROM calls JOIN clients c ON calls.clientID = c.clientID JOIN users u ON calls.userID = u.id');

    $call_rows =  pg_execute($conn, 'call_pages', array());

    $pages = ceil(pg_num_rows($call_rows) / USERS_ON_PAGE);
    return $pages;
}



/* --------------------------- User -------------------------------- */

//insert users
function user_insert($firstName, $lastName, $email, $password){
    global $conn;
    $insert = pg_prepare($conn, "user_insert", "INSERT INTO users(email_address, first_name, last_name, password, enrol_date, last_access, enable, type)  VALUES($1, $2, $3, $4, $5, $6, $7, $8)");
    
    $insertPass = password_hash($password, PASSWORD_BCRYPT);    // Encrypt the Password!
    return pg_execute($conn, 'user_insert', array($email, $firstName, $lastName, $insertPass, getDateEST(), getDateEST(), true, AGENT));
}

function get_user($email) {
    global $conn;
    $select = pg_prepare($conn, 'user_select', 'SELECT * FROM users WHERE email_address = $1');

    $user = pg_execute($conn, 'user_select', array($email));

    return pg_fetch_assoc($user);
}

function check_email($email) {
    global $conn;
    $select = pg_prepare($conn, 'check_email', 'SELECT email_address FROM users WHERE email_address = $1');

    $user = pg_execute($conn, 'check_email', array($email));

    return pg_fetch_assoc($user);
}

function check_active($email) {
    global $conn;
    $select = pg_prepare($conn, 'check_user', 'SELECT enable FROM users WHERE email_address = $1');

    $user = pg_execute($conn, 'check_user', array($email));

    return pg_fetch_assoc($user);
}

function user_authenticate($email, $plain_password) {
    $user = get_user($email);
    return password_verify($plain_password, $user['password']);
}

function update_login($email, $current_time_stamp){
    global $conn;

    $lastlogin = pg_prepare($conn, 'update_last_login' , "UPDATE users SET last_access = current_timestamp WHERE email_address = $1");

    $excecute = pg_execute($conn, 'update_last_login', array($email));
}

function update_password($email, $password){
    global $conn;

    $updatePassword = pg_prepare($conn, 'update_password', "UPDATE users SET password = $1 WHERE email_address = $2"); 
    
    $insertPass = password_hash($password, PASSWORD_BCRYPT);
    $excecute = pg_execute($conn, 'update_password', array($insertPass, $email));
}
?>