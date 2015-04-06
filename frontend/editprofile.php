<?php
    include 'imports/imports.php';
    session_name('communityfund');
    session_start();
    $login = -1;
    if (isset($_SESSION['uid']))
        $login = $_SESSION['uid'];
?>
<?php
    if ($login < 0)
        header("Location: index.php");
    // define variables and set to empty values
    $urlErr = $descrErr = "";
    $url = $descr = "";
    $error = False;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
       if (empty($_POST["url"])) {
         $urlErr = "Profile Image URL is required";
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
         $descrErr = "Profile description is required";
         $error = True;
       } else {
         $descr = testInput($_POST["descr"]);
       }
       
       if (!$error){
           $descr = updateProfile($dbconn, $login, $url, $descr);
           header("Location: profile.php");
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

        
        <h2>User Profile Page</h2>
        <p><span class="errortext">* required field.</span></p>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
           Image URL: <input type="text" name="url" value="<?php echo $url;?>">
           <span class="errortext">* <?php echo $urlErr;?></span>
           <br><br>
           Description: <br><textarea name="descr" rows="5" cols="40"><?php echo $descr;?></textarea>
           <span class="errortext">* <?php echo $descrErr;?></span>
           <br><br>
           <input type="submit" name="submit" value="Submit">
        </form>
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
