<?php
    include 'db.php';
?>
<html>
    <head>
        <script src="//code.jquery.com/jquery-1.10.2.js"></script>
        <link rel="stylesheet" type="text/css" media="screen" href="stylesheets/communityfund.css">
    </head>
    <body>
        <?php
            include 'header.php';
            include 'functions.php';
        ?>

        <?php
            makePost($dbconn, 1);
            makePost($dbconn, 2);
            makePost($dbconn, 3);
        ?>
        
        <?
            include 'footer.php';
        ?>
        
        <script>
            document.getElementById("projects tab").className = "tab selected";
        </script>
  </body>
</html>
<?php
    closeDB($dbconn);
?>
