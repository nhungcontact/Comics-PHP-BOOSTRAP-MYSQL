<?php
 
// Starting the session, necessary
// for using session variables
session_start();
  
// Declaring and hoisting the variables
$username = "";
$email    = "";
$errors = array();
// $_SESSION['success'] = "";
  
// DBMS connection code -> hostname,
// username, password, database name
$db = mysqli_connect('localhost', 'root', '', 'ct275_lab3');
  
// Registration code
if (isset($_POST['reg_user'])) {
  
    // Receiving the values entered and storing
    // in the variables
    // Data sanitization is done to prevent
    // SQL injections
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
    $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);
  
    // Ensuring that the user has not left any input field blank
    // error messages will be displayed for every blank input
    if (empty($username)) { array_push($errors, "Nhập tên đăng nhập!"); }
    if (empty($email)) { array_push($errors, "Nhập email đăng nhập!"); }
    if (empty($password_1)) { array_push($errors, "Nhập mật khẩu đăng nhập!"); }
    //
    $sqlname = "SELECT * FROM users WHERE username = '$username'";
    $resultname = mysqli_query($db, $sqlname);
    // Nếu kết quả trả về lớn hơn 1 thì nghĩa là username hoặc email đã tồn tại trong CSDL
    if (mysqli_num_rows($resultname) > 0)
    {
        array_push($errors, "Tên đăng nhập này đã có người dùng!");}
    $sqlemail = "SELECT * FROM users WHERE email = '$email'";
    $resultemail = mysqli_query($db, $sqlemail);
    // Nếu kết quả trả về lớn hơn 1 thì nghĩa là username hoặc email đã tồn tại trong CSDL
    if (mysqli_num_rows($resultemail) > 0)
    {
        array_push($errors, "Email này đã có người dùng!");}
    //kiem tra mat khau
    if ($password_1 != $password_2) {
        array_push($errors, "Mật khẩu không trùng khớp");
        // Checking if the passwords match
    }
  
    // If the form is error free, then register the user
    if (count($errors) == 0) {
         
        // Password encryption to increase data security
        $password = md5($password_1);
         
        // Inserting data into table
        $query = "INSERT INTO users (username, email, password)
                  VALUES('$username', '$email', '$password')";
         
        mysqli_query($db, $query);
  
        // Storing username of the logged in user,
        // in the session variable
        $_SESSION['username'] = $username;
         
        // Welcome message
        // $_SESSION['success'] = "You have logged in";
         
        // Page on which the user will be
        // redirected after logging in
        header('location: trangchu.php');
    }
}
  
// User login
if (isset($_POST['login_user'])) {
     
    // Data sanitization to prevent SQL injection
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $password = mysqli_real_escape_string($db, $_POST['password']);
  
    // Error message if the input field is left blank
    if (empty($username)) {
        array_push($errors, "Nhập tên đăng nhập!");
    }
    if (empty($password)) {
        array_push($errors, "Nhập mật khẩu!");
    }
    
    // Checking for the errors
    if (count($errors) == 0) {
         
        // Password matching
        $password = md5($password);
         
        $query = "SELECT * FROM users WHERE username=
                '$username' AND password='$password'";
        $results = mysqli_query($db, $query);
  
        // $results = 1 means that one user with the
        // entered username exists
        if (mysqli_num_rows($results) == 1) {
             
            // Storing username in session variable
            $_SESSION['username'] = $username;
             
            // Welcome message
            // $_SESSION['success'] = "You have logged in!";
             
            // Page on which the user is sent
            // to after logging in
            header('location: trangchu.php');
        }
        else {
             
            // If the username and password doesn't match
            array_push($errors, "Tên đăng nhập và mật khẩu không chính xác");
        }
    }
}
  
?>