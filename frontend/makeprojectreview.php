<?php
    include 'imports/imports.php';
    // define variables and set to empty values
    $ratingErr = $reviewErr = "";
    $rating = $review = "";
    $error = False;

    if (isset($_GET['pid'])){
        $pid = $_GET['pid'];
    }
    else{
      $pid = 1;
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
       if (!empty($_POST["rating"])) {
         $rating = testInput($_POST["rating"]);
       }

       if (empty($_POST["review"])) {
         $reviewErr = "Review is required.";
         $error = True;
       } else {
         $review = testInput($_POST["review"]);
       }
       
       if (!$error){
           // Add to database
           if (isset($_GET['uid'])){
               $uid = $_GET['uid'];
           }
           else{
             $uid = 2;
           }
           $review = createProjectReview($dbconn, $uid, $pid, $rating, $review);
       }
    }
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

        
        <h2>Write a Review</h2>
        <p><span class="error">* required field.</span></p>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]), "?pid=$pid";?>">
           Review Description: <textarea name="review" rows="5" cols="40"><?php echo $review;?></textarea>
           <span class="error">* <?php echo $reviewErr;?></span>
           <br><br>
           Rating:
           <select name="rating">
             <option value="1">1</option>
             <option value="2">2</option>
             <option value="3">3</option>
             <option value="4">4</option>
             <option value="5">5</option>
           </select>
           <span class="error">* <?php echo $ratingErr;?></span>
           <br><br>
           <input type="submit" name="submit" value="Submit">
        </form>

        <?php
            echo "<h2>Your Input:</h2>";
            echo $rating;
            echo "<br>";
            echo $review;
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
