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
    $cnameErr = "";
    $cname = "";
    $error = False;

    if ($login < 0)
        header("Location: index.php");

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
           $cid = createCommunity($dbconn, $cname, $login);
           if ($cid < 0)
               $cnameErr = "Community name already taken";
           else
               header("Location: communityexplorer.php?cid=$cid");
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
        <p><span class="errortext">* required field.</span></p>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
           Community Name: <input type="text" name="cname" value="<?php echo $cname;?>">
           <span class="errortext">* <?php echo $cnameErr;?></span>
           <br><br>
           <input type="submit" name="submit" value="Submit">
        </form>
        
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
