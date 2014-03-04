<?php
include("config.php");
    $sql    =    "SELECT `adkats_records_main`.*,`tbl_server`.`ServerName` FROM `adkats_records_main` INNER JOIN `tbl_server` ON `tbl_server`.`ServerID` = `adkats_records_main`.`server_id` WHERE `adkats_records_main`.`command_type` = 8 ORDER BY `record_time` DESC LIMIT 50";
    $query    = mysqli_query($con, $sql) or die(mysqli_error($con));

    $atomlink = "http://nbfc.no/feed/banfeed.php";
    $title = "nbfc.no | Latest Bans";
    $titledescription = "The Norwegian Battlefield Community";
    $link = "http://www.nbfc.no";

header("Content-type: text/xml");

  echo "<?xml version='1.0' encoding='UTF-8'?>
        <!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN'
        'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>
        <rss version='2.0' xmlns:atom='http://www.w3.org/2005/Atom'>
        <channel>
        <atom:link href='$atomlink' rel='self' type='application/rss+xml' />
        <title>$title</title>
        <link>$link</link>
        <description>$titledescription</description>";

        //Get info
        while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
			if(strpos($row['record_message'], 'Temporary') !== FALSE)
			{
				continue;
			}
            $title            = $row['source_name'] . ' Banned: ' . $row['target_name'];
            $description    = htmlentities('<div>') . $row['source_name'] . htmlentities(' Banned: <a href="http://bf4db.com/players?name=') . $row['target_name'] . htmlentities('">') . $row['target_name'] . htmlentities('</a></div></br><div> Server: ') . htmlspecialchars($row['ServerName']) . htmlentities('</div></br></br>');
            $message        = ' Message: ' . htmlspecialchars($row['record_message']);
            $pubdate        = date('r', strtotime($row['record_time']));
            echo "
                <item>
                    <title>$title</title>
                        <description>
                            $description
                            $message
                        </description>
                    <pubDate>$pubdate</pubDate>
                </item>";
        }
echo "</channel></rss>";