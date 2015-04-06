<?php
    include 'imports/imports.php';
    session_name('communityfund');
    session_start();
    $login = -1;
    if (isset($_SESSION['uid']))
        $login = $_SESSION['uid'];
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
            if ($login == -1){
                $result = pg_query($dbconn, "SELECT pid FROM project " .
                                            "ORDER BY startdate desc");
            }else{
                $result = pg_query($dbconn, "SELECT pid FROM usercommunity NATURAL JOIN projectcommunity " .
                                            "NATURAL JOIN project WHERE uid = $login ".
                                            "ORDER BY startdate desc");
            }
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
