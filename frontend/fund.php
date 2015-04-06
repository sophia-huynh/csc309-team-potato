<?php
    include 'imports/imports.php';
    session_name('communityfund');
    session_start();
    $login = -1;
    if (isset($_SESSION['uid']))
        $login = $_SESSION['uid'];
?>
<?php
    // define variables and set to empty values
    $fundErr = "";
    $fund = "";
    $error = False;

    if (isset($_GET['pid'])){
       $pid = $_GET['pid'];
    }
    else{
       header("Location: index.php");
    }
    if ($login < 0)
       header("Location: index.php");
   
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
           $fund = fundProject($dbconn, $login, $pid, $fund, $_POST["product"]);
           header("Location: project.php?pid=$pid");
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

        <?php
            $name = getProjectName($dbconn, $pid);
            echo "<h2>Fund $name</h2>";
        ?>
        <p><span class="errortext">* required field.</span></p>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]), "?pid=$pid";?>">
           Funding: <input type="text" name="fund" value="<?php echo $fund;?>">
           <span class="errortext">* <?php echo $fundErr;?></span>
           <?php
               if (hasBoth($dbconn, $pid)){
                echo "<br>Funding Type:
                       <select name='product'>
                         <option value='0'>Donation</option>
                         <option value='1'>Product</option>
                       </select>";
               }
           ?>
           <br><br>
           <input type="submit" name="submit" value="Submit">
        </form>
        
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
