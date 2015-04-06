<?php
    include 'imports/imports.php';
    session_name('communityfund');
    session_start();
    $login = -1;
    if (isset($_SESSION['uid']))
        $login = $_SESSION['uid'];
?>
<?php
    // define variables and set to empty values
    
    if (isset($_GET['cid'])){
        $cid = $_GET['cid'];
    }
    $error = joinCommunity($dbconn, $login, $cid);
?>
<html>
    <head>
        <script src="//code.jquery.com/jquery-1.10.2.js"></script>
        <link rel="stylesheet" type="text/css" media="screen" href="stylesheets/communityfund.css">
    </head>
    <body>
        <?php
            include 'header.php';
        ?>

        <?php
            header("Location: communityexplorer.php?cid=$cid")
        ?>
        
        <?
            include 'footer.php';
        ?>
        
        <script>
            document.getElementById("communities tab").className = "tab selected";
        </script>
  </body>
</html>
<?php
    closeDB($dbconn);
?>
