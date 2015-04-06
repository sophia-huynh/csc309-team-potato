 <div class="rightcontainer" style="text-align:right">
    <?php
        echo "Logged in as <a href='profile.php?uid=$login'>".getUsername($dbconn, $login)."</a>";
    ?>
    <br>
    <a href="killsession.php"><div class="headerbutton" id="logout">Log Out</div></a>
</div>
