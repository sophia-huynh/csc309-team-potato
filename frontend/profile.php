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
            if (isset($_GET['uid']) && $login != $_GET['uid']){
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

                // Friends
                echo "<h3>Friends</h3>";
                displayFriends($dbconn, $uid);

                // Reputation
                // Get the average of reviews
                $average = getAverage($dbconn, $uid, "User");
                echo "<h3>Reputation</h3>
                      <h4>Average: $average</h4>";
                // Gather reviews
                if ($login >= 0 && sameCommunity($dbconn, $uid, $login))
                    echo "<center><a href ='makeuserreview.php?uid=$uid'><div class='tag'>Write a Review</div></a></center>";
                displayReviews($dbconn, $uid, "User");
            }
            else{
                if ($login >= 0){
                    echo "<center><a href ='editprofile.php'><div class='tag'>Edit Profile</div></a></center>";
                    makeProfile($dbconn, $login);

                    //Projects funded
                    echo "<h3>Projects Funded</h3>";
                    displayFunded($dbconn, $login);

                    //Projects initiated
                    echo "<h3>Projects Initiated</h3>";
                    displayInitiated($dbconn, $login);

                    // Communities
                    echo "<h3>Communities</h3>";
                    displayCommunities($dbconn, $login);

                    // Friends
                    echo "<h3>Friends</h3>";
                    displayFriends($dbconn, $login);

                    // Reputation
                    // Get the average of reviews
                    $average = getAverage($dbconn, $login, "User");
                    echo "<h3>Reputation</h3>
                          <h4>Average: $average</h4>";
                    displayReviews($dbconn, $login, "User");
                }else{
                    echo "You are not logged in.";
                }
            }
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
