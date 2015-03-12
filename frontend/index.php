<?php
    include 'imports/imports.php'
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

        <center><a href ='makeproject.php'><div class='tag'>Start a Project</div></a></center>
        
        <?php
            $result = pg_query($dbconn, "SELECT pid FROM project " .
                                        "ORDER BY startdate desc LIMIT 10");
            if (!$result){
                echo "An error occurred.\n";
                exit;
            }
            while ($row = pg_fetch_row($result)) {
                makePost($dbconn, $row[0], "Dashboard");
            }
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
