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
$urlErr = $descrErr = "";
$url = $descr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
     $descr = testInput($_POST["descr"]);
   }

}

function test_input($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   return $data;
}
?>

<h2>User Profile Page</h2>
<p><span class="error">* required field.</span></p>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
   Image URL: <input type="text" name="url" value="<?php echo $url;?>">
   <span class="error"><?php echo $urlErr;?></span>
   <img src="<?php echo $url;?>" alt="imageurl" style="width:104px;height:142px">
   <br><br>
   Description: <textarea name="descr" rows="5" cols="40"><?php echo $descr;?></textarea>
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

</body>
</html>
