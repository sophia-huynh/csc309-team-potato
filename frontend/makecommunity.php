<?php
    include 'imports/imports.php';
    // define variables and set to empty values
    $cnameErr = "";
    $cname = "";
    $error = False;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
       if (empty($_POST["cname"])) {
         $cnameErr = "Community name is required";
         $error = True;
       } else {
         $cname = testInput($_POST["cname"]);
         // check if name only contains letters and whitespace
         if (!preg_match("/^[a-zA-Z ]*$/",$cname)) {
           $cnameErr = "Only letters and white space allowed";
           $error = True;
         }
       }

       if (!$error){
           // Add to database
           if (isset($_GET['uid'])){
               $uid = $_GET['uid'];
           }
           else{
             $uid = 2;
           }
           $cname = createCommunity($dbconn, $cname, $uid);
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

        
        <h2>Create Your Community</h2>
        <p><span class="error">* required field.</span></p>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
           Community Name: <input type="text" name="cname" value="<?php echo $cname;?>">
           <span class="error">* <?php echo $cnameErr;?></span>
           <br><br>
           <input type="submit" name="submit" value="Submit">
        </form>

        <?php
            echo "<h2>Your Input:</h2>";
            echo $cname;
            echo "<br>";
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
