<?php
    include 'imports/imports.php';

    // define variables and set to empty values
    $nameErr = $emailErr = $passErr = "";
    $name = $email = $pass = "";
    $error = False;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = testInput($_POST["name"]);
        // check if name only contains letters and whitespace
        if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
            $nameErr = "Only letters and white space allowed";
            $error = True;
        }

         $email = testInput($_POST["email"]);
         // check if e-mail address is well-formed
         if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
           $emailErr = "Invalid email format";
           $error = True;
         }

         $pass = testInput($_POST["pass"]);

       if (!$error){
           // Add to database
           $pass = registerUser($dbconn, $name, $email, $pass);
       }
    }
?>
<html>
    <head>
        <script src="//code.jquery.com/jquery-1.10.2.js"></script>
        <script src="reg_script.js"></script>
        <link rel="stylesheet" type="text/css" media="screen" href="stylesheets/communityfund.css">
    </head>
    <body>
        <?php
            include 'header.php';
        ?>

        <h2>Register</h2>
        <p><span class="error">* required field.</span></p>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" id="myForm">
           Name: <input type="text" name="name" id="username1" value="<?php echo $name;?>" onblur="validate("name", this.value)">
           <span class="error">* <?php echo $nameErr;?></span>
           <br><br>
           E-mail: <input type="text" name="email" id="email1" value="<?php echo $email;?>" onblur="validate("email", this.value)">
           <span class="error">* <?php echo $emailErr;?></span>
           <br><br>
           Create Password: <input type="password" name="pass" id="password1" value="<?php echo $pass;?>" onblur="validate("pass", this.value)">
           <span class="error">* <?php echo $passErr;?></span>
           <br><br>
           <input type="submit" name="submit" value="Submit">
        </form>

        <?php
            echo "<h2>Your Input:</h2>";
            echo $name;
            echo "<br>";
            echo $email;
            echo "<br>";
            echo $pass;
        ?>

        <?
            include 'footer.php';
        ?>

  </body>
</html>
<?php
    closeDB($dbconn);
?>
