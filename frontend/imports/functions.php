<?php
/*
===== TAGS =========================================================
    makeTag($cid, $name)
    makePostTag($dbconn, $cid)
*/
    
    /*
    makeTag($cid, $name)
        Given a cid and name, creates a tag.
    */
    function makeTag($cid, $name){
        echo "<a href ='communityexplorer.php?cid=$cid'><div class='tag'>$name</div></a>";
    }

    /*
    makePostTag($dbconn, $pid)
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
    makeLeaveButton($dbconn, $uid, $cid)
    inCommunity($dbconn, $uid, $cid)
    sameCommunity($dbconn, $uid, $other)
    sameCommunityProject($dbconn, $uid, $pid)
    
*/
    /*
    makeJoinButton($dbconn, $cid, $uid)
        Given a connection, uid, and cid, create a join button.
    */
    function makeJoinButton($dbconn, $uid, $cid){
        echo "<a href ='joincommunity.php?uid=$uid&cid=$cid'><div class='join'>Join Community</div></a>";
    }
    
    /*
    makeLeaveButton($dbconn, $cid, $uid)
        Given a connection, uid, and cid, create a leave button.
    */
    function makeLeaveButton($dbconn, $uid, $cid){
        echo "<a href ='leavecommunity.php?uid=$uid&cid=$cid'><div class='join'>Leave Community</div></a>";
    }
    
    /*
    inCommunity($dbconn, $uid, $cid)
        Given a connection, uid, and cid, checks if the user is in that community.
    */
    function inCommunity($dbconn, $uid, $cid){
        $result = pg_query($dbconn, "SELECT uid FROM usercommunity WHERE uid=$uid AND cid=$cid");
        if (!$result){
            echo "An error occurred.\n";
            exit;
        }
        if ($row = pg_fetch_row($result)){
            return 1;
        }
        return 0;
    }

    /*
    sameCommunity($dbconn, $uid, $other)
        Given two uids, determines if they share at least one community in common.
    */
    function sameCommunity($dbconn, $uid, $other){
        $result = pg_query($dbconn, "SELECT * FROM usercommunity AS u1 JOIN usercommunity AS u2 ON u1.cid = u2.cid WHERE u1.uid = $uid AND u2.uid = $other");
        if (!$result){
            echo "An error occurred.\n";
            exit;
        }
        if ($row = pg_fetch_row($result)){
            return 1;
        }
        return 0;
        
    }

    /*
    sameCommunityProject($dbconn, $uid, $pid)
        Given a uid and pid, determines if the user and project are in the same
        community.
    */
    function sameCommunityProject($dbconn, $uid, $pid){
        $community = pg_query($dbconn, "SELECT cid FROM projectcommunity WHERE pid = $pid");
        
        if (!$community){
            echo "An error occurred.\n";
            exit;
        }
        $cid = pg_fetch_row($community)[0];
        return inCommunity($dbconn, $uid, $cid);
        
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
        $fundedprojects = pg_query($dbconn, "SELECT DISTINCT pid, name, image ".
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
    displayCommunities($dbconn, $uid)
        Given a connection and uid, generates all friends of the user.
    */
    function displayFriends($dbconn, $uid){
        $friends = pg_query($dbconn, "SELECT * FROM friend WHERE uid = $uid OR friend = $uid");
        if (!$friends){
            echo "An error occurred.\n";
            exit;
        }
        echo "<div class='usercontainer'>";
        $friendslist = [];
        while ($row = pg_fetch_row($friends)) {
            if ($row[0] == $uid)
                $friend = $row[1];
            else
                $friend = $row[0];
                
            $posted = 0;
            foreach ($friendslist as $friended){
                if ($friended == $friend)
                    $posted = 1;
            }
            if (!$posted){
                $friendslist[] = $friend;
                generateFriend($dbconn, $friend);
            }
        }
        echo "</div>";
    }


    /*
    generateFriend($dbconn, $uid)
        Given a connection and uid, generates the post for icon for the friend.
    */
    function generateFriend($dbconn, $uid){
        $result = pg_query($dbconn, "SELECT username, image FROM userposts WHERE uid = $uid");
        if (!$result){
            echo "An error occurred.\n";
            exit;
        }
        while ($row = pg_fetch_row($result)) {
            $username = $row[0];
            $image = "<img src=".$row[1].">";
            echo "<a href='profile.php?uid=$uid'><div class='outer'><div class='image'>$image</div></div></a>";
        }
    }

/*
===== PROJECT PAGE FUNCTIONS ========================================
    makeProjectPost($dbconn, $pid, uid)
    hasBoth($dbconn, $pid)
    initiator($dbconn, $uid, $pid)
*/
    /*
    makeProjectPost($dbconn, $pid, uid)
        Given a connection and pid, generates the corresponding project page.
    */
    function makeProjectPost($dbconn, $pid, $login){
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
        $startdate = formatDate($startdate);
        $enddate = $row[5];
        $product = $row[6];
        $donation = $row[7];
        $ends = timeUntilEnd($enddate);
        $enddate = formatDate($enddate);
        $funded = getFunded($dbconn, $pid);
        if ($ends < 0){
            $endsin = "Ended on $enddate";
        }else{
            $endsin = "Ends in $ends Days ($enddate)";
        }

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
        if ($ends >= 0 && sameCommunityProject($dbconn, $login, $pid)){
            // Add a 'fund' button
            echo "<a href ='fund.php?pid=$pid'><div class='tag'>Fund it!</div></a>";
        }
        
        echo "              <br/>Started by <b><a href='profile.php?uid=$uid'>$username</a></b>
                            <br/>Posted $startdate
                            <br/>$endsin
                            <br/>$funded out of $goal Funded<br/><br/>
                            </div>
                        <p>$description</p>
                    </div>";
    }

    /*
    hasBoth($dbconn, $pid)
        Given a pid, checks if a prroject accepts both donations and purchases.
    */
    function hasBoth($dbconn, $pid){
        $result = pg_query($dbconn, "SELECT * FROM project WHERE pid=$pid AND product=true AND donation=true");
        if (!$result){
            echo "An error occurred.\n";
            exit;
        }
        if ($row = pg_fetch_row($result)){
            return 1;
        }
        return 0;
    }
    
    /*
    initiator($dbconn, $uid, $pid)
        Given a uid and pid, checks if the user is the project's initiator.
    */
    function initiator($dbconn, $uid, $pid){
        $initiatedprojects = pg_query($dbconn, "SELECT * ".
                                               "FROM initiator WHERE uid = $uid AND pid = $pid");
        if (!$initiatedprojects){
            echo "An error occurred.\n";
            exit;
        }
        if ($row = pg_fetch_row($initiatedprojects)) {
            return 1;
        }
        return 0;
    }

/*
===== ADMIN PAGE FUNCTIONS ==========================================
    makeAdminPage($dbconn)
*/
    /*
    makeAdminPage($dbconn)
        Given a connection, generates the statistics for the administrator's page.
    */
    function makeAdminPage($dbconn){
        $result = pg_query($dbconn, "SELECT (SELECT count(*) FROM users), " .
                                           "(SELECT count(*) FROM project), " . 
                                           "(SELECT count(*) FROM community), " . 
                                           "(SELECT avg(count) FROM (SELECT count(cid) FROM usercommunity GROUP BY uid) as c), ".
                                           "(SELECT count(*) FROM funded), ".
                                           "(SELECT (avg(date-startdate)) FROM project NATURAL JOIN funded)");
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
        // Number of projects funded
        $fundedprojects = $row[4];
        // Average days to reach a fund goal
        $avgtogoal = $row[5];
        
        echo "Number of Users: $numusers<br/>
              Number of Projects: $numprojects<br/>
              Number of Communities: $numcommunities<br/>
              Average number of Communities per User: $numcomuser<br/>
              Number of Funded Projects: $fundedprojects<br/>
              Average Time to Goal: $avgtogoal";
    }

    /*
    isAdmin($dbconn, $uid)
        Given a UID, checks if that user is an admin or not.
    */
    function isAdmin($dbconn, $uid){
        $result = pg_query($dbconn, "SELECT uid FROM admins WHERE uid=$uid");
        if (!$result){
            echo "An error occurred.\n";
            exit;
        }
        if ($row = pg_fetch_row($result)){
            return 1;
        }
        return 0;
    }

/*
===== MULTI-USE FUNCTIONS ===========================================
    formatDate($date)
    makePost($dbconn, $pid, $type)
    displayReviews($dbconn, $pid/$uid, $type)
    getFunded($dbconn, $pid)
    getAverage($dbconn, $pid/$uid, $type)
    timeUntilEnd($enddate)
    getUsername($dbconn, $uid)
*/
 /*
    formatDate($date)
        Given a date, returns it in Month day, Year, Time format.
    */
    function formatDate($date){
        return date("F j, Y, g:i a", strtotime($date));
    }
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

        if ($daysLeft < 0){
            $daysleftmessage = "Ended";
        }else{
            $daysleftmessage = "$daysLeft Days Left";
        }
        
        echo "    <div class='fundedamount'>$funded Funded</div>
                  <div class='daysleft'>$daysleftmessage</div>
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
    getUsername($dbconn, $uid)
        Given a uid, returns the corresponding username.
    */
    function getUsername($dbconn, $uid){
        $usernames = pg_query($dbconn, "SELECT username FROM userposts WHERE uid = $uid");
        if (!$usernames){
            echo "An error occurred.\n";
            exit;
        }
        return pg_fetch_row($usernames)[0];
    }

    
    /*
    getProjectName($dbconn, $uid)
        Given a pid, returns the corresponding project name.
    */
    function getProjectName($dbconn, $pid){
        $name = pg_query($dbconn, "SELECT name FROM project WHERE pid = $pid");
        if (!$name){
            echo "An error occurred.\n";
            exit;
        }
        return pg_fetch_row($name)[0];
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
       $result = pg_query($dbconn, "SELECT uid, password FROM users WHERE lower(email) = lower('$email')");
        if (!$result){
            echo "An error occurred.\n";
            exit;
        }
        $error = 0;
        $row = pg_fetch_row($result);
        if (!$row){
            $error -= 1;
        }
        $uid = $row[0];
        $password = $row[1];
        if ($password != $pass){
            $error -= 2;
        }

        if ($error < 0)
            $uid = $error;

        return $uid;
    }
    
    /*
    registerUser($dbconn, $name, $email, $pass)
        Given valid credentials, insert the user into the database or return an
        appropriate error message.
    */
    function registerUser($dbconn, $name, $email, $pass){
    
        $testEmail = pg_query($dbconn, "SELECT * FROM users WHERE lower(email) = lower('$email')");
        $uid = 0;
        if (!$testEmail){
            echo "An error occurred.\n";
            exit;
        }
        $emailRow = pg_fetch_row($testEmail);
        if ($emailRow){
            $uid -= 1;
        }
        
        $testName = pg_query($dbconn, "SELECT * FROM userposts WHERE lower(username) = lower('$name')");
        if (!$testName){
            echo "An error occurred.\n";
            exit;
        }
        $nameRow = pg_fetch_row($testName);
        if ($nameRow){
            $uid -= 2;
        }

        if ($uid == 0){
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
        }
        
        return $uid;
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
        $testProject = pg_query($dbconn, "SELECT * FROM project WHERE lower(name) = lower('$pname')");
        if (!$testProject){
            echo "An error occurred.\n";
            exit;
        }

        $projectRow = pg_fetch_row($testProject);
        if ($projectRow){
            return -2;
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
        return $pid;
    }

    /*
    listFormCommunities($dbconn, $uid)
        Given a connection, print the options for each community.
    */
    function listFormCommunities($dbconn, $uid){
        $testCommunity = pg_query($dbconn, "SELECT cid, name FROM community NATURAL JOIN usercommunity WHERE uid = $uid");
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
        $testCommunity = pg_query($dbconn, "SELECT * FROM community WHERE lower(name) = lower('$cname')");
        if (!$testCommunity){
            echo "An error occurred.\n";
            exit;
        }

        $communityRow = pg_fetch_row($testCommunity);
        if ($communityRow){
            return -1;
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
        return $cid;
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
            return 0;
        }

        // Insert the community
        $insertCommunity = pg_query($dbconn, "INSERT INTO usercommunity(uid, cid) VALUES ($uid, $cid)");
        return 1;
    }

    /*
    leaveCommunity($dbconn, $uid, $cid)
        Removes a user from a community and returns an appropriate message.
    */
    function leaveCommunity($dbconn, $uid, $cid){
        $testCommunity = pg_query($dbconn, "DELETE FROM usercommunity WHERE uid = $uid AND cid = $cid");
        if (!$testCommunity){
            echo "An error occurred.\n";
            exit;
        }

        return 1;
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
    function fundProject($dbconn, $uid, $pid, $amount, $type){
        if (hasBoth($dbconn, $pid)){
            if ($type == "1"){
                $bought = "true";
            }
            else{
                $bought = "false";
            }
        }else{
            $result = pg_query($dbconn, "SELECT * FROM project WHERE pid=$pid AND product=true");
            if (!$result){
                echo "An error occurred.\n";
                exit;
            }
            if ($row = pg_fetch_row($result)){
                $bought = "true";
            }else{
                $bought = "false";
            }
        }
        $insertFunding = pg_query($dbconn, "INSERT INTO funder(uid, pid, amount, bought) VALUES ($uid, $pid, $amount, $bought)");
        if (!$insertFunding){
            echo "An error occurred.\n";
            exit;
        }
        if (justFunded($dbconn, $pid)){
            $time = time();
            $date = date('Y-m-d H:i:s', $time);
            $insertFunded = pg_query($dbconn, "INSERT INTO funded(pid, date) VALUES ($pid, '$date')");
            if (!$insertFunded){
                echo "An error occurred.\n";
                exit;
            }
        }
        return $pid;
    }

    /*
    justFunded($dbconn, $pid)

        Checks if a project has just been funded or not. If it has, then update
        the database. If not, don't do anything.
    */
    function justFunded($dbconn, $pid){
        $alreadyFunded = pg_query($dbconn, "SELECT * FROM funded WHERE pid = $pid");
        if (!$alreadyFunded){
            echo "An error occurred.\n";
            exit;
        }
        // The project was already funded
        if ($row = pg_fetch_row($alreadyFunded))
            return 0;

        $project = pg_query($dbconn, "SELECT goal FROM project WHERE pid = $pid");
        if (!$project){
            echo "An error occurred.\n";
            exit;
        }
        
        $row = pg_fetch_row($project);
        $goal = $row[0];

        if (getFunded($dbconn, $pid) >= $goal)
            return 1;
            
        return 0;
    }
?>

