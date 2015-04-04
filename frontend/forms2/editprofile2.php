<?php
    include 'imports/imports.php';
    // define variables and set to empty values
    $urlErr = $descrErr = "";
    $url = $descr = "";
    $error = False;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
         $url = testInput($_POST["url"]);
         // check if URL address syntax is valid (this regular expression also allows dashes in the URL)
         if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$url)) {
           $urlErr = "Invalid URL";
           $error = True;
         }
       }

         $descr = testInput($_POST["descr"]);

       if (!$error){
           $uid = 2;
           $descr = updateProfile($dbconn, $uid, $url, $descr);
       }
    }
?>
<html>
    <head>
        <script src="//code.jquery.com/jquery-1.10.2.js"></script>
        <script src="ep_script.js"></script>
        <link rel="stylesheet" type="text/css" media="screen" href="stylesheets/communityfund.css">
    </head>
    <body>
        <?php
            include 'header.php';
        ?>


        <h2>User Profile Page</h2>
        <p><span class="error">* required field.</span></p>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" id="myForm">
           Image URL: <input type="text" name="url" id="website1" value="<?php echo $url;?>" onblur="validate("url", this.value)" >
           <span class="error"><?php echo $urlErr;?></span>
           <img src="<?php echo $url;?>" alt="imageurl" style="width:104px;height:142px">
           <br><br>
           Description: <textarea name="descr" rows="5" cols="40" id="descr1" onblur="validate("descr", this.value)"><?php echo $descr;?></textarea>
           <span class="error">* <?php echo $descrErr;?></span>
           <br><br>
           <input type="submit" name="submit" value="Submit">
        </form>

        <?php
            echo "<h2>Your Input:</h2>";
            echo $url;
            echo "<br>";
            echo $descr;
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
