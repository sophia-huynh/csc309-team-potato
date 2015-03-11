<?php
    include 'db.php';
    include 'functions.php';
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
            $uid = $_GET['uid'];
            makeProfile($dbconn, $uid);

            //Projects funded
            echo "<h3>Projects Funded</h3>";
            displayFunded($dbconn, $uid);

            //Projects initiated
            echo "<h3>Projects Initiated</h3>";
            displayInitiated($dbconn, $uid);

            // Communities
            echo "<h3>Communities</h3>";
            displayCommunities($dbconn, $uid);

            // Reputation
            // Get the average of reviews
            $average = getAverage($dbconn, $uid, "User");
            echo "<h3>Reputation</h3>
                  <h4>Average: $average</h4>";
            // Gather reviews
            displayReviews($dbconn, $uid, "User");
        ?>
        
        <?
            include 'footer.php';
        ?>
        
        <script>
            document.getElementById("profile tab").className = "tab selected";
        </script>
  </body>
</html>
<?php
    closeDB($dbconn);
?>
