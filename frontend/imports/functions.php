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
    makeJoinButton($dbconn, $cid, $uid)
    
*/
    /*
    Given a connection, cid, and uid, create a join button.
    */
    function makeJoinButton($dbconn, $uid, $cid){
        // REPLACE index.php WITH THE COMMUNITY
        echo "<a href ='joincommunity.php?uid=$uid&cid=$cid'><div class='join'>Join Community</div></a>";
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
        if ($ends >= 0){
            // Add a 'fund' button
            echo "<a href ='fund.php?pid=$pid'><div class='tag'>Fund it!</div></a>";
        }
        
        echo "              <br/>Started by <b><a href='profile.php?uid=$uid'>$username</a></b>
                            <br/>Posted $startdate
                            <br/>Ends in $ends Days ($enddate)
                            <br/><br/>
                            </div>
                        <p>$description</p>
                    </div>";
    }


/*
===== ADMIN PAGE FUNCTIONS ==========================================
*/
    function makeAdminPage($dbconn){
        $result = pg_query($dbconn, "SELECT (SELECT count(*) FROM users), " .
                                           "(SELECT count(*) FROM project), " . 
                                           "(SELECT count(*) FROM community), " . 
                                           "(SELECT avg(count) FROM (SELECT count(cid) FROM usercommunity GROUP BY uid) as c)");
        if (!$result){
            echo "An error occurred.\n";
            exit;
        }
        // Parse results from query
        $row = pg_fetch_row($result);
        // number of users
        $numusers = $row[0];
        // number of projects
        $numprojects = $row[1];
        // number of communities
        $numcommunities = $row[2];
        // average number of communities per user
        $numcomuser = $row[3];

        echo "Number of Users: $numusers<br/>
              Number of Projects: $numprojects<br/>
              Number of Communities: $numcommunities<br/>
              Average number of Communities per User: $numcomuser<br/>";
        
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
                      <a href='profile.php?uid=$uid'><div class='outer'><div class='image'>$image</div></div></a>
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
    createProject($dbconn, $pname, $url, $descr, $goal, $deadline, $community, $type, $uid)
    createCommunity($dbconn, $cname, $uid)
    updateProfile($dbconn, $uid, $url, $descr)
    joinCommunity($dbconn, $uid, $cid)
    createReview($dbconn, $uid, $pid, $rating, $review)
    createUserReview($dbconn, $uid, $reviewer, $rating, $review)
    fundProject($dbconn, $uid, $pid)
*/
    /*
    test_input($data)
        Given data, test if it's valid.
    */
    function testInput($data) {
       $data = trim($data);
       $data = stripslashes($data);
       $data = htmlspecialchars($data);
       $data = str_replace("'", "''", $data);
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
    
    /*
    createProject($dbconn, $pname, $url, $descr, $goal, $deadline, $community, $type, $uid)
        Given valid information, inserts a project into the database and/or returns an
        appropriate error message.
    */
    function createProject($dbconn, $pname, $url, $descr, $goal, $deadline, $community, $type, $uid){
        // Fill in the query for the donation and product fields.  This will change after we sanitize queries.
        if ($type == "donation"){
            $donation = 'true, false';
        }
         else if ($type == "product"){
            $donation = 'false, true';
        }
         else{
            $donation = 'true, true';
        }

        // Check if the name already exists as a project
        $testProject = pg_query($dbconn, "SELECT * FROM project WHERE name = '$pname'");
        if (!$testProject){
            echo "An error occurred.\n";
            exit;
        }

        $projectRow = pg_fetch_row($testProject);
        if ($projectRow){
            return "Project already exists.";
        }

        // Find the start and end time for the project (relative to current time)
        $time = time();
        $start = date('Y-m-d H:i:s', $time);
        $end = date('Y-m-d H:i:s', ($time + $deadline * (60*60*24)));

        // Insert the project
        $insertProject = pg_query($dbconn, "INSERT INTO project(name, image, description, goal, startdate, enddate, donation, product) " .
                                           "VALUES ('$pname', '$url', '$descr', $goal, '$start', '$end', $donation)");

        // get the pid of the recently inserted project
        $getPid = pg_query($dbconn, "SELECT pid FROM project WHERE name = '$pname'");
        if (!$getPid){
            echo "An error occurred.\n";
            exit;
        }
        $pidRow = pg_fetch_row($getPid);
        $pid = $pidRow[0];

        // insert into initiator and projectcommunity
        $insertInitiator = pg_query($dbconn, "INSERT INTO initiator VALUES ($uid, $pid)");
        $insertCommunity= pg_query($dbconn, "INSERT INTO projectcommunity VALUES ($community, $pid)");
        return "Success! pid is $pid.";
    }

    /*
    listFormCommunities($dbconn)
        Given a connection, print the options for each community.
        -- In phase IV, change this to list communities of which a user is part of.
    */
    function listFormCommunities($dbconn){
        $testCommunity = pg_query($dbconn, "SELECT cid, name FROM community");
        if (!$testCommunity){
            echo "An error occurred.\n";
            exit;
        }
        
        while ($row = pg_fetch_row($testCommunity)) {
            $cid = $row[0];
            $name = $row[1];
            echo "<option value='$cid'>$name</option>";
        }
    }

    /*
    createCommunity($dbconn, $cname, $uid)
        Given a community name and user, creates the corresponding community and return an
        appropriate error message.
    */
    function createCommunity($dbconn, $cname, $uid){
        $testCommunity = pg_query($dbconn, "SELECT * FROM community WHERE name = '$cname'");
        if (!$testCommunity){
            echo "An error occurred.\n";
            exit;
        }

        $communityRow = pg_fetch_row($testCommunity);
        if ($communityRow){
            return "Community already exists.";
        }

        // Insert the community
        $insertCommunity = pg_query($dbconn, "INSERT INTO community(name) VALUES ('$cname')");

        // get the pid of the recently inserted project
        $getCid = pg_query($dbconn, "SELECT cid FROM community WHERE name = '$cname'");
        if (!$getCid){
            echo "An error occurred.\n";
            exit;
        }
        $cidRow = pg_fetch_row($getCid);
        $cid = $cidRow[0];

        // insert user into community
        $insertUser = pg_query($dbconn, "INSERT INTO usercommunity(cid, uid) VALUES ($cid, $uid)");
        return "Success! cid is $cid.";
    }

    /*
    updateProfile($dbconn, $uid, $url, $descr)
        Given a username and profile information, update the profile for that user.
    */
    function updateProfile($dbconn, $uid, $url, $descr){
        // Update description
        $updateProfile = pg_query($dbconn, "UPDATE userprofile SET description='$descr' WHERE uid=$uid");

        // Update image
        $updateProfile = pg_query($dbconn, "UPDATE userposts SET image='$url' WHERE uid=$uid");

        return "Success! $uid's profile update!";
    }

    /*
    joinCommunity($dbconn, $uid, $cid)
        Adds a user to a community and returns an appropriate message.
    */
    function joinCommunity($dbconn, $uid, $cid){
        $testCommunity = pg_query($dbconn, "SELECT * FROM usercommunity WHERE uid = $uid AND cid = $cid");
        if (!$testCommunity){
            echo "An error occurred.\n";
            exit;
        }

        $communityRow = pg_fetch_row($testCommunity);
        if ($communityRow){
            return "User is already part of this community.";
        }

        // Insert the community
        $insertCommunity = pg_query($dbconn, "INSERT INTO usercommunity(uid, cid) VALUES ($uid, $cid)");
        return "Success!";
    }

    /*
    createProjectReview($dbconn, $uid, $pid, $rating, $review)
        Add a project review to the database.
    */
    function createProjectReview($dbconn, $uid, $pid, $rating, $review){
        $insertReview = pg_query($dbconn, "INSERT INTO projectreview(pid, reviewer, rating, review) VALUES ($pid, $uid, $rating, '$review')");
        return "Success!";
    }

    
    /*
    createUserReview($dbconn, $uid, $reviewer, $rating, $review)
        Add a user review to the database.
    */
    function createUserReview($dbconn, $uid, $reviewer, $rating, $review){
        $insertReview = pg_query($dbconn, "INSERT INTO userreview(uid, reviewer, rating, review) VALUES ($uid, $reviewer, $rating, '$review')");
        return "Success!";
    }

    /*
    fundProject($dbconn, $uid, $pid, $amount)
        Add a funder and their amount to the database.
    */
    function fundProject($dbconn, $uid, $pid, $amount){
        $insertFunding = pg_query($dbconn, "INSERT INTO funder(uid, pid, amount) VALUES ($uid, $pid, $amount)");
        return "Success!";
    }
?>

