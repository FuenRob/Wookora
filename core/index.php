<?php
// Includes files for configuration, database functions, logs and mails
require ('config.php');
require ('db.php');
require ('log.php');
require ('PHPMailer/class.phpmailer.php');
//Include core actions
include('functions.php');

//Data of the WebService
//Action: Is a more important data. If you don't send this data, the core not run
if(isset($_POST['action'])){
    $action = $_POST['action'];
}else{
    //$array = array(0,"Oops... We have a problem","Action can't be null.");
    //echo $state = json_encode($array);
    print_r("Action can't be null.");
    exit;
}

//Include modules
include('modules.php');

if(isset($_POST['username'])){
    $username = $_POST['username'];
}

if(isset($_POST['password'])){
    $pass = $_POST['password'];
}

if(isset($_POST['name'])){
    $name = $_POST['name'];
}else{
    $name = null;
}

if(isset($_POST['city'])){
    $city = $_POST['city'];
}else{
    $city = null;
}

if(isset($_POST['initdate'])){
    $initdate = $_POST['initdate'];
}

if(isset($_POST['enddate'])){
    $enddate = $_POST['enddate'];
}

if(isset($_POST['description'])){
    $description = $_POST['description'];
}

if(isset($_POST['img'])){
    $img = $_POST['img'];
}

if(isset($_POST['country'])){
    $country = $_POST['country'];
}else{
    $country = null;
}

if(isset($_POST['code'])){
    $code = $_POST['code'];
}

if(isset($_POST['email'])){
    $email = $_POST['email'];
}else{
    $email = null;
}

if(isset($_POST['phone'])){
    $phone = $_POST['phone'];
}else{
    $phone = null;
}

if(isset($_POST['postalcode'])){
    $postalcode = $_POST['postalcode'];
}else{
    $postalcode = null;
}

if(isset($_POST['date'])){
    $date = $_POST['date'];
}

if(isset($_POST['passport'])){
    $passport = $_POST['passport'];
}else{
    $passport = null;
}

// Type Cookie
if(isset($_COOKIE['session_us'])){
    $id_user = $_COOKIE['session_us'];
}

//Named class for the functions
$Core =  new Core();

//Select a function depending on the action
switch ($action){
    case 'login':
        $Core->login($email,$pass);
        break;
    case 'logout':
        $Core->logout($id_user);
        break;
    case 'saveuser':
        $Core->setUser($name, $email, $pass);
        break;
    case 'userlist':
        $Core->getUsers();
        break;
}
?>
