<?php

$db_hostname="";
$db_username="";
$db_password="";


$feed_url="http://archives.deccanchronicle.com/rss/sports/rss.xml";

echo "Starting to work with feed URL '" . $feed_url . "'";

libxml_use_internal_errors(true);
$RSS_DOC = simpleXML_load_file($feed_url);
if (!$RSS_DOC) {
    echo "Failed loading XML\n";
    foreach(libxml_get_errors() as $error) {
        echo "\t", $error->message;
    }
}

try
{
	/*  query the database */
	// $db = getCon();

	$db = mysql_connect($db_hostname,$db_username,$db_password);
	if (!$db)
	{
		die("Could not connect: " . mysql_error());
	}

        mysql_select_db("", $db);

  $rss_title = $RSS_DOC->channel->title;
	$rss_link = $RSS_DOC->channel->link;
	$rss_editor = $RSS_DOC->channel->managingEditor;
	$rss_copyright = $RSS_DOC->channel->copyright;
	$rss_date = $RSS_DOC->channel->pubDate;

	//Loop through each item in the RSS document

	foreach($RSS_DOC->channel->item as $RSSitem)
	{

		$item_id 	= md5($RSSitem->link);
		$fetch_date = date("Y-m-j G:i:s"); //NOTE: we don't use a DB SQL function so its database independant
		$item_title = $RSSitem->title;
		$item_title=addcslashes($item_title,"'");
		$item_date  = date("Y-m-j G:i:s", strtotime($RSSitem->pubDate));
		$item_url	= $RSSitem->link;

		echo "Processing item '" , $item_id , "' on " , $fetch_date 	, "<br/>";
		echo $item_title, " - ";
		echo $item_date, "<br/>";
		echo $item_url, "<br/>";



    $item_exists_sql = "SELECT item_id FROM FEED6 where item_id = '" . $item_id . "'";
		$item_exists = mysql_query($item_exists_sql, $db);
		if(mysql_num_rows($item_exists)<1)
		{
			echo "<font color=green>Inserting new item..</font><br/>";
			$item_insert_sql = "INSERT INTO FEED6(item_id, feed_url, item_title, item_date, item_url, fetch_date) VALUES ('" . $item_id . "', '" . $feed_url . "','".$item_title."','" . $item_date . "', '" . $item_url . "', '" . $fetch_date . "')";
			$insert_item = mysql_query($item_insert_sql, $db);
            if(!$insert_item){die('Av -- Could not enter data: --' . mysql_error());}

        }
		else
		{
			echo "<font color=blue>Not inserting existing item..</font><br/>";
		}





		echo "<br/>";
}
        }
catch (Exception $e)
{
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}
?>
