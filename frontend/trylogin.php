<?php
    include 'imports/imports.php';
    $result = 0;
    $pass = $_REQUEST["password"];
    $email = $_REQUEST["email"];
    
    // Validate password and email
    $email = testInput($email);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $result = -10;
    }
    
    $pass = testInput($pass);

    // Try to log in only if the input is valid
    if ($result == 0)
        $result = tryLogin($dbconn, $email, $pass);
    
    echo $result;
    
    if ($result >= 0){
        session_name("communityfund");
        session_start();
        $_SESSION['uid'] = $result;
    }
    
    closeDB($dbconn);
?>
