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
        
        <?php
            // Get the CID and name of the community
            $cid = $_GET['cid'];

            // If there is no CID, then redirect to communities.php
            if (!$cid){
                header("Location: communities.php");
                die();
            }

            $community = pg_query($dbconn, "SELECT name FROM community " .
                            "WHERE cid = $cid");
            if (!$community){
                echo "An error occurred.\n";
                exit;
            }
            $row = pg_fetch_row($community);
            $name = $row[0];

            // Create the header tag and join button for the community
            makeTag($cid, $name);
            
            if ($login >= 0){
                if (inCommunity($dbconn, $login, $cid)){
                    makeLeaveButton($dbconn, $login, $cid);
                }else{
                    makeJoinButton($dbconn, $login, $cid);
                }
            }
            
            // Get the community's project pages
            $result = pg_query($dbconn, "SELECT pid FROM projectcommunity " .
                                        "WHERE cid = $cid");
            if (!$result){
                echo "An error occurred.\n";
                exit;
            }
            while ($row = pg_fetch_row($result)) {
                makePost($dbconn, $row[0], "Community");
            }
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
