<?php
    include 'imports/imports.php';
    // define variables and set to empty values
    $pnameErr = $urlErr = $descrErr = $goalErr = $deadlineErr = $communityErr = $productErr = "";
    $pname = $url = $descr = $goal = $deadline = $community = $donation = $product = "";
    $error = False;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
       if (empty($_POST["pname"])) {
         $pnameErr = "Project name is required";
         $error = True;
       } else {
         $pname = testInput($_POST["pname"]);
         // check if name only contains letters and whitespace
         if (!preg_match("/^[a-zA-Z ]*$/",$pname)) {
           $pnameErr = "Only letters and white space allowed";
           $error = True;
         }
       }

       if (empty($_POST["url"])) {
         $urlErr = "Project Image URL is required";
         $error = True;
       } else {
         $url = testInput($_POST["url"]);
         // check if URL address syntax is valid (this regular expression also allows dashes in the URL)
         if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$url)) {
           $urlErr = "Invalid URL";
           $error = True;
         }
       }

       if (empty($_POST["descr"])) {
         $descrErr = "Project description is required";
         $error = True;
       } else {
         $descr = testInput($_POST["descr"]);
       }

       if (empty($_POST["goal"])) {
         $goalErr = "Funding goal is required";
         $error = True;
       } else {
         $goal = testInput($_POST["goal"]);
         // check for number / decimal entries only
         if (!preg_match("/^\d*\.?\d*$/",$goal)) {
           $goalErr = "Invalid entry - please enter a numerical or decimal value";
           $error = True;
         }
       }

       if (empty($_POST["deadline"])) {
         $deadlineErr = "Project deadline is required";
         $error = True;
       } else {
         $deadline = testInput($_POST["deadline"]);
         if (!preg_match("/^\d+$/", $deadline)) {
         // check for number entries only
           $deadlineErr = "Invalid entry - please enter a numerical or decimal value";
           $error = True;
         }
       }

       if (empty($_POST["community"])) {
         $communityErr = "Community is required";
         $error = True;
       } else {
         $community = testInput($_POST["community"]);
       }

       if (empty($_POST["community"])) {
         $communityErr = "Community is required";
         $error = True;
       } else {
         $community = testInput($_POST["community"]);
       }

       if (empty($_POST["product"])) {
         $productErr = "Type is required";
         $error = True;
       } else {
         $type = testInput($_POST["product"]);
       }
       
       if (!$error){
           // Add to database
           if (isset($_GET['uid'])){
               $uid = $_GET['uid'];
           }
           else{
             $uid = 2;
           }
           $community = createProject($dbconn, $pname, $url, $descr, $goal, $deadline, $community, $type, $uid);
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

        
        <h2>Create Your Project</h2>
        <p><span class="error">* required field.</span></p>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
           Project Name: <input type="text" name="pname" value="<?php echo $pname;?>">
           <span class="error">* <?php echo $pnameErr;?></span>
           <br><br>
           Project Image URL: <input type="text" name="url" value="<?php echo $url;?>">
           <span class="error"><?php echo $urlErr;?></span>
           <br><br>
           Project Description: <textarea name="descr" rows="5" cols="40"><?php echo $descr;?></textarea>
           <span class="error">* <?php echo $descrErr;?></span>
           <br><br>
           Funding Goal: <input type="text" name="goal" value="<?php echo $goal;?>">
           <span class="error">* <?php echo $goalErr;?></span>
           <br><br>
           Days Until Deadline: <input type="text" name="deadline" value="<?php echo $deadline;?>">
           <span class="error">* <?php echo $deadlineErr;?></span>
           <br><br>
           Community:
           <select name="community">
             <?php listFormCommunities($dbconn);?>
           </select>
           <span class="error">* <?php echo $communityErr;?></span>
           <br><br>
           Project Type:
           <select name="product">
             <option value="donation">Donation</option>
             <option value="product">Product</option>
             <option value="both">Both</option>
           </select>
           <span class="error">* <?php echo $productErr;?></span>
           <br><br>
           <input type="submit" name="submit" value="Submit">
        </form>

        <?php
            echo "<h2>Your Input:</h2>";
            echo $pname;
            echo "<br>";
            echo "<img src='$url' alt='imageurl' style='width:300px;height:300px'>";
            echo $url;
            echo "<br>";
            echo $descr;
            echo "<br>";
            echo $goal;
            echo "<br>";
            echo $deadline;
            echo "<br>";
            echo $community;
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
