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
            $pid = $_GET['pid'];
            makeProjectPost($dbconn, $pid);
            
            // Get the average of reviews
            $average = getAverage($dbconn, $pid, "Project");
            echo "<h3>Reviews</h3>
                  <h4>Average: $average</h4>";

            // Gather reviews
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
