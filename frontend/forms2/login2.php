<?php
    include 'imports/imports.php';

    // define variables and set to empty values
    $emailErr = $passErr = "";
    $email = $pass = "";
    $error = False;
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
         $email = testInput($_POST["email"]);
         // check if e-mail address is well-formed
         if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
           $emailErr = "Invalid email format";
           $error = True;
         }

         $pass = testInput($_POST["pass"]);

       if (!$error){
           // Test on database
           $pass = tryLogin($dbconn, $email, $pass);
       }
    }
?>
<html>
    <head>
        <script src="//code.jquery.com/jquery-1.10.2.js"></script>
        <script src="log_script.js"></script>
        <link rel="stylesheet" type="text/css" media="screen" href="stylesheets/communityfund.css">
    </head>
    <body>
        <?php
            include 'header.php';
        ?>
        <h2>User Login</h2>
        <p><span class="error">* required field.</span></p>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" id="myForm">
           E-mail: <input type="text" name="email" id="email1" value="<?php echo $email;?>" onblur="validate("email", this.value)">
           <span class="error">* <?php echo $emailErr;?></span>
           <br><br>
           Password: <input type="password" name="pass" id="password1" value="<?php echo $pass;?>" onblur="validate("pass", this.value)">
           <span class="error">* <?php echo $passErr;?></span>
           <br><br>
           <input type="submit" name="submit" value="Submit">
        </form>
        <?php
            echo "<h2>Your Input:</h2>";
            echo $email;
            echo "<br>";
            echo $pass;
            echo "<br>";
        ?>

        <?
            include 'footer.php';
        ?>

  </body>
</html>
<?php
    closeDB($dbconn);
?>
