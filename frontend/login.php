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
    $emailErr = $passErr = "";
    $email = $pass = "";
    $error = False;
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
       if (empty($_POST["email"])) {
         $emailErr = "Email is required";
         $error = True;
       } else {
         $email = testInput($_POST["email"]);
         // check if e-mail address is well-formed
         if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
           $emailErr = "Invalid email format";
           $error = True;
         }
       }

       if (empty($_POST["pass"])) {
         $passErr = "Name is required";
         $error = True;
       } else {
         $pass = testInput($_POST["pass"]);
       }

       if (!$error){
           // Test on database
           $pass = tryLogin($dbconn, $email, $pass);
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
        <h2>User Login</h2>
        <p><span class="error">* required field.</span></p>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
           E-mail: <input type="text" name="email" value="<?php echo $email;?>">
           <span class="error">* <?php echo $emailErr;?></span>
           <br><br>
           Password: <input type="password" name="pass" value="<?php echo $password;?>">
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
