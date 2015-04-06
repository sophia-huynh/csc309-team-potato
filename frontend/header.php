<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="imports/functions.js"></script>
<?php
    if (isset($_GET['iid'])){
        $iid = $_GET['iid'];
    }else{
        $iid = -1;
    }
    echo "<script type='text/javascript'>";
    echo "var uid = $login;
          var iid = $iid;";
    echo "</script>";
?>
<script type="text/javascript">
    
    $(document).ready(function() {
        $('#username').hide();
        var register = 0;
        $('body').on('click', '#register', function(){
            if (!register){
                register = 1;
                $('#username').show(450);
            }else{
                var email = $('#email').val();
                var username = $('#username').val();
                var password = $('#password').val();
                tryRegister(email, username, password);
            }
        });
        $('body').on('click', '#login', function(){
            if (register){
                register = 0;
                $('#username').hide(450);
            }else{
                var email = $('#email').val();
                var password = $('#password').val();
                tryLogIn(email, password);
            }
        });
        $('body').on('click', '#email', function(){
            $('#email').removeClass("error");
        });
        $('body').on('click', '#password', function(){
            $('#password').removeClass("error");
        });
        $('body').on('click', '#username', function(){
            $('#username').removeClass("error");
        });
    });

</script>

<!-- Header -->
<div class="header">
    <div class="headercontent">
        <a href="index.php"><img src="header.png" class="logo" align="left"></a>
        <?php
            if ($login < 0)
                include "auth.php";
            else
                include "logout.php";
        ?>
    </div>
</div>
<!-- Tabs -->
<div id="tabs">
  <div class="tabwrapper">
    <a href="index.php"><div class="tab unselected" id="projects tab">Projects</div></a>
    <a href="communities.php"><div class="tab unselected" id="communities tab">Communities</div></a>
    <a href="profile.php"><div class="tab unselected" id="profile tab">Profile</div></a>
    <?php
        if (isAdmin($dbconn, $login))
            echo "<a href='admin.php'><div class='tab unselected' id='admin tab'>Admin</div></a>";
    ?>
  </div>
</div>
<div id="content">
    <div class = "contentwrapper">
        <!-- This is where content goes -->
