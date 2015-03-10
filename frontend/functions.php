<?php
    
    function makeTag($dbconn, $cid){
        $result = pg_query($dbconn, "SELECT name FROM community WHERE cid = $cid");
        if (!$result){
            echo "An error occurred.\n";
            exit;
        }
        $row = pg_fetch_row($result);
        $name = $row[0];
        echo "<a href ='communityexplorer.php?cid=$cid'><div class='tag'>$name</div></a>";
    }
    
    function makePost($dbconn, $pid){
        $result = pg_query($dbconn, "SELECT name, image, description, startdate, enddate, cid ".
                                    "FROM project NATURAL JOIN projectcommunity ".
                                    "WHERE pid = $pid");
        if (!$result){
            echo "An error occurred.\n";
            exit;
        }
        $row = pg_fetch_row($result);
        $name = $row[0];
        $image = $row[1];
        $description = $row[2];
        $funded = getFunded($dbconn, $pid);
        $daysLeft = timeUntilEnd($row[4]);
        $cid = $row[5];
        
        echo "<div class='post'><div class='postimage'><img src=$image></div>
                  <div class='description'>
                      <h1>$name</h1>
                      $description
                  </div>";
                  
        makeTag($dbconn, $cid);
        
        echo "    <div class='fundedamount'>$funded Funded</div>
                  <div class='daysleft'>$daysLeft Days Left</div>
              </div>";
    }
    
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
        if (!$funded)
            $funded = "$0.00";
        return $funded;
    }
    
    function timeUntilEnd($enddate){
        return floor((strtotime($enddate) - time()) / (60*60*24));
    }
?>
