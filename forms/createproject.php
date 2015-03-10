<!DOCTYPE HTML>
<html>
<head>
<style>
.error {color: #FF0000;}
</style>
</head>
<body>

<?php
// define variables and set to empty values
$pnameErr = $urlErr = $descrErr = $goalErr = $deadlineErr = $communityErr = "";
$pname = $url = $descr = $goal = $deadline = $community = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   if (empty($_POST["pname"])) {
     $pnameErr = "Project name is required";
   } else {
     $pname = test_input($_POST["pname"]);
     // check if name only contains letters and whitespace
     if (!preg_match("/^[a-zA-Z ]*$/",$pname)) {
       $pnameErr = "Only letters and white space allowed";
     }
   }

   if (empty($_POST["url"])) {
     $urlErr = "Project Image URL is required";
   } else {
     $url = test_input($_POST["url"]);
     // check if URL address syntax is valid (this regular expression also allows dashes in the URL)
     if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$url)) {
       $urlErr = "Invalid URL";
     }
   }

   if (empty($_POST["descr"])) {
     $descrErr = "Project description is required";
   } else {
     $descr = test_input($_POST["descr"]);
   }

   if (empty($_POST["goal"])) {
     $goalErr = "Funding goal is required";
   } else {
     $goal = test_input($_POST["goal"]);
     // check for number / decimal entries only
     if (!preg_match("/^\d*\.?\d*$/",$goal)) {
       $goalErr = "Invalid entry - please enter a numerical or decimal value";
     }
   }

   if (empty($_POST["deadline"])) {
     $deadlineErr = "Project deadline is required";
   } else {
     $deadline = test_input($_POST["deadline"]);
   }

   if (empty($_POST["community"])) {
     $communityErr = "Project deadline is required";
   }
}

function test_input($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   return $data;
}
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
   Project Deadline: <input type="text" name="deadline" value="<?php echo $deadline;?>">
   <span class="error">* <?php echo $deadlineErr;?></span>
   <br><br>
   Community:
   <select name="community">
     <option value="community1">Community 1</option>
     <option value="community2">Community 2</option>
     <option value="community3">Community 3</option>
   </select>
   <span class="error">* <?php echo $communityErr;?></span>
   <br><br>
   <input type="submit" name="submit" value="Submit">
</form>

<?php
echo "<h2>Your Input:</h2>";
echo $pname;
echo "<br>";
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

</body>
</html>
