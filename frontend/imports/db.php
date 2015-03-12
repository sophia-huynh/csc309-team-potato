<?php
    $dbconn = pg_connect("host=communityfund.ct7zfceeh5ag.us-west-2.rds.amazonaws.com dbname=communityfund user=masteruser password=mypassword")
        or die('Could not connect: ' . pg_last_error());
    pg_query($dbconn, "SET search_path TO communityfund");

    function closeDB($dbconn){
        pg_close($dbconn);
    }
?>
