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
            $pid = $_GET['pid'];
            makeProjectPost($dbconn, $pid, $login);
            
            // Get the average of reviews
            $average = getAverage($dbconn, $pid, "Project");
            echo "<h3>Reviews</h3>
                  <h4>Average: $average</h4>";

            // Gather reviews
            if (sameCommunityProject($dbconn, $login, $pid) && !initiator($dbconn, $login, $pid))
                echo "<center><a href ='makeprojectreview.php?pid=$pid'><div class='tag'>Write a Review</div></a></center>";
            displayReviews($dbconn, $pid, "Project");
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
