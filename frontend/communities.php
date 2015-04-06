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
        <center>
        <?php
            if ($login >= 0)
                echo "<a href ='makecommunity.php'><div class='tag'>Start a Community</div></a><br/>";
            $result = pg_query($dbconn, "SELECT cid, name FROM community");
            if (!$result){
                echo "An error occurred.\n";
                exit;
            }
            while ($row = pg_fetch_row($result)) {
                $cid = $row[0];
                $name = $row[1];
                makeTag($cid, $name);
            }
        ?>
        </center>
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
