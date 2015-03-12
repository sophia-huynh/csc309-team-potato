<?php
    include 'imports/imports.php';
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
            makeAdminPage($dbconn);
        ?>
        
        <?
            include 'footer.php';
        ?>
        
        <script>
            document.getElementById("admin tab").className = "tab selected";
        </script>
  </body>
</html>
<?php
    closeDB($dbconn);
?>
