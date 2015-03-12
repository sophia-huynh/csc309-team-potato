<?php
    include 'imports/imports.php';
    // define variables and set to empty values
    if (isset($_GET['uid'])){
        $uid = $_GET['uid'];
    }else{
        $uid = 2;
    }
    
    if (isset($_GET['cid'])){
        $cid = $_GET['cid'];
    }else{
        $cid = 1;
    }
    $error = joinCommunity($dbconn, $uid, $cid);
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
            echo "Uid $uid joined community $cid with error $error";
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
