<?php
/*
===== TAGS =========================================================
    makeTag($cid, $name)
    makePostTag($dbconn, $cid)
*/
    
    /*
    Given a cid and name, creates a tag.
    */
    function makeTag($cid, $name){
        echo "<a href ='communityexplorer.php?cid=$cid'><div class='tag'>$name</div></a>";
    }

    /*
    Given a connection and pid, gets the community for which the project belongs
    and creates the tag.
    */
    function makePostTag($dbconn, $pid){
        $result = pg_query($dbconn, "SELECT name, cid FROM community " .
                                    "NATURAL JOIN projectcommunity ".
                                    "WHERE pid = $pid");
        if (!$result){
            echo "An error occurred.\n";
            exit;
        }
        $row = pg_fetch_row($result);
        $name = $row[0];
        $cid = $row[1];
        makeTag($cid, $name);
    }

/*
===== COMMUNITY FUNCTIONS ===========================================
    makeJoinButton($dbconn, $cid)
    
*/
    /*
    Given a connection and cid, create a join button.
    */
    function makeJoinButton($dbconn, $cid){
        // REPLACE index.php WITH THE COMMUNITY
        echo "<a href ='index.php?cid=$cid'><div class='join'>Join Community</div></a>";
    }

/*
===== PROFILE PAGE FUNCTIONS ========================================
    makeProfile($dbconn, $uid)
    displayFunded($dbconn, $uid);
    displayInitiated($dbconn, $uid);
    displayCommunities($dbconn, $uid);
*/
    /*
    makeProfile($dbconn, $uid)
        Given a connection and uid, generates the corresponding user profile.
    */
    function makeProfile($dbconn, $uid){
        $profile = pg_query($dbconn, "SELECT username, image, description ".
                                  "FROM userposts NATURAL JOIN userprofile ".
                                  "WHERE uid = $uid");
        if (!$profile){
            echo "An error occurred.\n";
            exit;
        }
        $row = pg_fetch_row($profile);
        $username = $row[0];
        $image = $row[1];
        $description = $row[2];

        if (!$image)
            $image = "http://puu.sh/gvwsH/2a681326a2.png";

        echo "<div class='userpage'>
                        <div class='outeruser'><div class='image'><img src=$image></div></div>
                        <div class='projectdata'><h1>$username</h1></div>
                        <p>$description</p>
                    </div>";
    }

    /*
    displayFunded($dbconn, $uid)
        Given a connection and uid, generates the corresponding projects funded.
    */
    function displayFunded($dbconn, $uid){
        $fundedprojects = pg_query($dbconn, "SELECT pid, name, image ".
                                            "FROM funder NATURAL JOIN project ".
                                            "WHERE uid = $uid");
        if (!$fundedprojects){
            echo "An error occurred.\n";
            exit;
        }
        echo "<div class='usercontainer'>";
        while ($row = pg_fetch_row($fundedprojects)) {
            $pid = $row[0];
            $name = $row[1];
            $image = "<img src=".$row[2]." alt=$name title=$name>";
            echo "<a href='project.php?pid=$pid'>
                    <div class='outer'><div class='image'>$image</div></div>
                  </a>";
        }
        echo "</div>";
    }
    
    /*
    displayInitiated($dbconn, $uid)
        Given a connection and uid, generates the corresponding projects initiated.
        -- This code is exactly the same as displayFunded except with a table,
           funder, being changed to initiator.
           There may be additions later on, however (funded flags, ongoing, etc.)
    */
    function displayInitiated($dbconn, $uid){
        $initiatedprojects = pg_query($dbconn, "SELECT pid, name, image ".
                                               "FROM initiator NATURAL JOIN project ".
                                               "WHERE uid = $uid");
        if (!$initiatedprojects){
            echo "An error occurred.\n";
            exit;
        }
        echo "<div class='usercontainer'>";
        while ($row = pg_fetch_row($initiatedprojects)) {
            $pid = $row[0];
            $name = $row[1];
            $image = "<img src=".$row[2]." alt=$name title=$name>";
            echo "<a href='project.php?pid=$pid'>
                    <div class='outer'><div class='image'>$image</div></div>
                  </a>";
        }
        echo "</div>";
    }

    /*
    displayCommunities($dbconn, $uid)
        Given a connection and uid, generates the corresponding communities the user belongs to.
    */
    function displayCommunities($dbconn, $uid){
        $communities = pg_query($dbconn, "SELECT cid, name ".
                                            "FROM usercommunity NATURAL JOIN community ".
                                            "WHERE uid = $uid");
        if (!$communities){
            echo "An error occurred.\n";
            exit;
        }
        echo "<div class='usercontainer'>";
        while ($row = pg_fetch_row($communities)) {
            $cid = $row[0];
            $name = $row[1];
            makeTag($cid, $name);
        }
        echo "</div>";
    }

/*
===== PROJECT PAGE FUNCTIONS ========================================
    makeProjectPost($dbconn, $pid)
*/
    /*
    makeProjectPost($dbconn, $pid)
        Given a connection and pid, generates the corresponding project page.
    */
    function makeProjectPost($dbconn, $pid){
        $project = pg_query($dbconn, "SELECT name, image, description, goal, ".
                                  "startdate, enddate, product, donation ".
                                  "FROM project WHERE pid = $pid");
        if (!$project){
            echo "An error occurred.\n";
            exit;
        }
        $row = pg_fetch_row($project);
        $name = $row[0];
        $image = $row[1];
        $description = $row[2];
        $goal = $row[3];
        $startdate = $row[4];
        $enddate = $row[5];
        $product = $row[6];
        $donation = $row[7];
        $ends = timeUntilEnd($enddate);

        // Get the initiator of the project
        $initiator = pg_query($dbconn, "SELECT uid, username ".
                                       "FROM initiator NATURAL JOIN userposts ".
                                       "WHERE pid = $pid");
        if (!$initiator){
            echo "An error occurred.\n";
            exit;
        }
        $row = pg_fetch_row($initiator);
        $uid = $row[0];
        $username = $row[1];
        
        echo "<div class='project'>
                        <div class='postimage'><center><img src=$image></center></div>
                        <div class='projectdata'><h1>$name</h1>";
                        
        makePostTag($dbconn, $pid);
        
        echo "              <br/>Started by <b><a href='profile.php?uid=$uid'>$username</a></b>
                            <br/>Posted $startdate
                            <br/>Ends in $ends Days ($enddate)
                            <br/><br/>
                            </div>
                        <p>$description</p>
                    </div>";
    }


/*
===== MULTI-USE FUNCTIONS ===========================================
    makePost($dbconn, $pid, $type)
    displayReviews($dbconn, $pid/$uid, $type)
    getFunded($dbconn, $pid)
    getAverage($dbconn, $pid/$uid, $type)
    timeUntilEnd($enddate)
*/

    /*
    makePost($dbconn, $pid, $type)
        Given a connection, pid and type, creates the dashboard/community explorer 
        post for the corresponding project.

        $type = "Dashboard" or "Community"
            If it's a community, then not tag is added.
    */
    function makePost($dbconn, $pid, $type){
        $result = pg_query($dbconn, "SELECT name, image, description, startdate, enddate ".
                                    "FROM project WHERE pid = $pid");
        if (!$result){
            echo "An error occurred.\n";
            exit;
        }
        // Parse results from query
        $row = pg_fetch_row($result);
        $name = $row[0];
        $image = $row[1];
        $description = $row[2];
        $funded = getFunded($dbconn, $pid);
        $daysLeft = timeUntilEnd($row[4]);

        // Print the post
        echo "<div class='post'>
                <div class='postimage'>
                    <center><a href='project.php?pid=$pid'><img src=$image></a></center>
                </div>
                <div class='description'>
                    <a href='project.php?pid=$pid'><h1>$name</h1></a>
                    $description
                </div>";

        // Make the community tag
        if ($type != "Community"){
            makePostTag($dbconn, $pid);
        }
        
        echo "    <div class='fundedamount'>$funded Funded</div>
                  <div class='daysleft'>$daysLeft Days Left</div>
              </div>";
    }
    
    /*
    displayReviews($dbconn, $id, $type)
        Given a connection, pid/uid and type, creates the project page content
        for the corresponding project/user.

        $type = "Project" or "User"
    */
    function displayReviews($dbconn, $id, $type){
        if ($type == "Project"){
            $table = "projectreview";
            $matchOn = "pid";
        }
        else{
            $table = "userreview";
            $matchOn = "userreview.uid";
        }
        $result = pg_query($dbconn, "SELECT reviewer, rating, review, username, image ".
                                    "FROM $table JOIN userposts ".
                                    "ON reviewer = userposts.uid WHERE $matchOn = $id");
        if (!$result){
            echo "An error occurred.\n";
            exit;
        }
        while ($row = pg_fetch_row($result)) {
            $uid = $row[0];
            $rating = $row[1];
            $review = $row[2];
            $username = $row[3];
            $image = "<img src=".$row[4].">";
            echo "<div class='review'>
                      <div class='outer'><div class='image'>$image</div></div>
                      <h4><a href='profile.php?uid=$uid'>$username</a></h4>
                      <h5>Rating: $rating</h5>
                      $review
                  </div>";
        }
    }

    /*
    getFunded($dbconn, $pid)
        Given a connection and pid, finds the total amount of funding the project
        has received.
    */    
    function getFunded($dbconn, $pid){
        $result = pg_query($dbconn, "SELECT sum(amount) as total ".
                                    "FROM funder WHERE pid = $pid ".
                                    "GROUP BY pid");
        if (!$result){
            echo "An error occurred.\n";
            exit;
        }
        $row = pg_fetch_row($result);
        $funded = $row[0];
        // If the project has not received any funding, set funded to $0.00
        if (!$funded)
            $funded = "$0.00";
        return $funded;
    }

    /*
    getAverage($dbconn, $val, $type)
        Given a connection, pid/uid, and type, finds the average reputation of the
        project or user.

        $type = "Project" or "User"
    */    
    function getAverage($dbconn, $val, $type){
        if ($type == "Project"){
            $table = "projectreview";
            $cond = "pid";
        }
        else {
            $table = "userreview";
            $cond = "uid";
        }
        $result = pg_query($dbconn, "SELECT round(avg(rating), 2)".
                                    "FROM $table WHERE $cond = $val");
        if (!$result){
            echo "An error occurred.\n";
            exit;
        }
        $row = pg_fetch_row($result);
        $avg = $row[0];
        // If the project has not received any ratings, set the average to -
        if (!$avg)
            $avg = "-";
        return $avg;
    }

    /*
    timeUntilEnd($enddate)
        Given a date, find the time from the present until then (in days).
    */
    function timeUntilEnd($enddate){
        return floor((strtotime($enddate) - time()) / (60*60*24));
    }
/*
===== FORM FUNCTIONS ===============================================
    testInput($data)
    tryLogin($dbconn, $email, $pass)
    registerUser($dbconn, $name, $email, $pass)
*/
    /*
    test_input($data)
        Given data, test if it's valid.
    */
    function testInput($data) {
       $data = trim($data);
       $data = stripslashes($data);
       $data = htmlspecialchars($data);
       return $data;
    }

    /*
    tryLogin($dbconn, $email, $pass)
        Given a connection, test if the login and return an appropriate message.
    */
    function tryLogin($dbconn, $email, $pass) {
       $result = pg_query($dbconn, "SELECT uid, password FROM users WHERE email = '$email'");
        if (!$result){
            echo "An error occurred.\n";
            exit;
        }
        $row = pg_fetch_row($result);
        if (!$row){
            return "No such email found.";
        }
        $uid = $row[0];
        $password = $row[1];
        if ($password != $pass){
            return "Invalid password.";
        }

        return "Success! Hello user $uid!";
    }
    
    /*
    registerUser($dbconn, $name, $email, $pass)
        Given valid credentials, insert the user into the database or return an
        appropriate error message.
    */
    function registerUser($dbconn, $name, $email, $pass){
        $testEmail = pg_query($dbconn, "SELECT * FROM users WHERE email = '$email'");
        if (!$testEmail){
            echo "An error occurred.\n";
            exit;
        }
        $emailRow = pg_fetch_row($testEmail);
        if ($emailRow){
            return "Email already exists.";
        }
        
        $testName = pg_query($dbconn, "SELECT * FROM userposts WHERE username = '$name'");
        if (!$testName){
            echo "An error occurred.\n";
            exit;
        }
        $nameRow = pg_fetch_row($testName);
        if ($nameRow){
            return "Username already exists.";
        }

        $insertUser = pg_query($dbconn, "INSERT INTO users(email, password) VALUES ('$email', '$pass')");
        $getUid = pg_query($dbconn, "SELECT uid FROM users WHERE email = '$email'");
        if (!$getUid){
            echo "An error occurred.\n";
            exit;
        }
        $uidRow = pg_fetch_row($getUid);
        $uid = $uidRow[0];
        
        $insertPosts = pg_query($dbconn, "INSERT INTO userposts VALUES ($uid, '$name', '')");
        $insertProfile = pg_query($dbconn, "INSERT INTO userprofile VALUES ($uid, '')");

        return "Success! Hello user $uid!";
    }
?>

