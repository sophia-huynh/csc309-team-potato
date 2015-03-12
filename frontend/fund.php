<?php
    include 'imports/imports.php';
    // define variables and set to empty values
    $fundErr = "";
    $fund = "";
    $error = False;

    if (isset($_GET['pid'])){
       $pid = $_GET['pid'];
    }
    else{
     $pid = 2;
    }
    if (isset($_GET['uid'])){
       $uid = $_GET['uid'];
    }
    else{
     $uid = 2;
    }
   
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
       if (empty($_POST["fund"])) {
         $fundErr = "Funding amount is required";
         $error = True;
       } else {
         $fund = testInput($_POST["fund"]);
         // check for number / decimal entries only
         if (!preg_match("/^\d*\.?\d*$/",$fund)) {
           $fundErr = "Invalid entry - please enter a numerical or decimal value";
           $error = True;
         }
       }

       if (!$error){
           // Add to database
           $fund = fundProject($dbconn, $uid, $pid, $fund);
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

        
        <h2>Fund the Project</h2>
        <p><span class="error">* required field.</span></p>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]), "?pid=$pid&uid=$uid";?>">
           Funding: <input type="text" name="fund" value="<?php echo $fund;?>">
           <span class="error">* <?php echo $fundErr;?></span>
           <br><br>
           <input type="submit" name="submit" value="Submit">
        </form>

        <?php
            echo "<h2>Your Input:</h2>";
            echo $fund;
            echo "<br>";
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
