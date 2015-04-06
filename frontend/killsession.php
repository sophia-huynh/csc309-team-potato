<?php
    session_name("communityfund");
    session_start();
    session_unset(); 
    session_destroy();
    header('Location: index.php');
?>
