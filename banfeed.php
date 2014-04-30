<?php
include("config.php");
	$sql    =    "SELECT `adkats_records_main`.*,`tbl_server`.`ServerName` FROM `adkats_records_main` INNER JOIN `tbl_server` ON `tbl_server`.`ServerID` = `adkats_records_main`.`server_id` WHERE `adkats_records_main`.`command_type` = 8 ORDER BY `record_time` DESC LIMIT 20";
	$query    = mysqli_query($con, $sql) or die(mysqli_error($con));

	$atomlink = "http://nbfc.no/feed/banfeed.php";
	$title = "nbfc.no | Latest Bans";
	$titledescription = "The Norwegian Battlefield Community";
	$link = "http://www.nbfc.no";
	$webadminlink = "http://webadmin.nbfc.no";

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
			$description    = htmlspecialchars($row['source_name'] . ' Banned: <a href="'. $webadminlink .'/bf4/playerinfo/' . $row['target_id'] . '">' . $row['target_name'] . '</a></div></br><div> Server: ' . $row['ServerName'] . '</div></br></br>');
			$message        = ' Message: ' . htmlspecialchars($row['record_message']);
			$pubdate        = date('r', strtotime($row['record_time']));
			$guidlink		= ''. $webadminlink .'/bf4/playerinfo/' . $row['target_id'];
			echo "
				<item>
					<title>$title</title>
						<description>
							$description
							$message
						</description>
					<pubDate>$pubdate</pubDate>
					<guid isPermaLink='false'>$guidlink</guid>
				</item>";
		}
echo "</channel></rss>";