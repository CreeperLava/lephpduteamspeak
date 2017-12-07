<?php
require_once("libraries/TeamSpeak3/TeamSpeak3.php");

try {
// https://openclassrooms.com/courses/concevez-votre-site-web-avec-php-et-mysql/ecrire-son-premier-script
// ./ts3server_startscript.sh start

// connect and grab TeamSpeak3_Node_Host object
$instance = TeamSpeak3::factory("serverquery://127.0.0.1:10011");
// login using serveradmin account
$instance->login("serveradmin", "B9WRhIPU");
// select virtual server and grab TeamSpeak3_Node_Server object
$ts3_VirtualServer = $instance->serverGetByPort(9987);
// grab TeamSpeak3_Node_Servergroup object
$group = $ts3_VirtualServer->serverGroupGetByName("Server Admin");

// get channel name from formulary
if(isset($_POST['name']) AND isset($_POST['password']) AND isset($_POST['game'])) {
	$name = $_POST['name'];
	$password = $_POST['password'];
	$game = $_POST['game'];
	// get all channel names from TS3
	$list = $ts3_VirtualServer->channelList();
	if(in_array($name, $list)) {
		// join
	} else {
		// create
		$sub_cid = $ts3_VirtualServer->channelCreate(array(
		 "channel_name" => htmlspecialchars($name),
		 "channel_topic" => "$name's channel !",
		 "channel_codec" => TeamSpeak3::CODEC_SPEEX_NARROWBAND,
		 "channel_flag_permanent" => TRUE,
		 "channel_password" => htmlspecialchars($password),
		 "cpid" => "$ts3_VirtualServer->channelGetByName($game)",
		));
	}
} else if(isset($_POST['nameFind'])) {
	$name = $_POST['nameFind'];
	// do something ?
}

/*

TODO : script d'init du serveur

$top_cid = $ts3_VirtualServer->channelCreate(array(
 "channel_name" => "ROOT",
 "channel_topic" => "This is a top-level channel",
 "channel_codec" => TeamSpeak3::CODEC_SPEEX_WIDEBAND,
 "channel_flag_permanent" => TRUE,
));
// create a sub-level channel and get its ID
$sub_cid = $ts3_VirtualServer->channelCreate(array(
 "channel_name" => "LoL",
 "channel_topic" => "This is a sub-level channel",
 "channel_codec" => TeamSpeak3::CODEC_SPEEX_NARROWBAND,
 "channel_flag_permanent" => TRUE,
 "channel_password" => "LoL",
 "cpid" => $top_cid,
));
$sub_cid = $ts3_VirtualServer->channelCreate(array(
 "channel_name" => "CS:GO",
 "channel_topic" => "This is a sub-level channel",
 "channel_codec" => TeamSpeak3::CODEC_SPEEX_NARROWBAND,
 "channel_flag_permanent" => TRUE,
 "channel_password" => "CS:GO",
 "cpid" => $top_cid,
));
$sub_cid = $ts3_VirtualServer->channelCreate(array(
 "channel_name" => "DOTA 2",
 "channel_topic" => "This is a sub-level channel",
 "channel_codec" => TeamSpeak3::CODEC_SPEEX_NARROWBAND,
 "channel_flag_permanent" => TRUE,
 "channel_password" => "CS:GO",
 "cpid" => $top_cid,
));
$sub_cid = $ts3_VirtualServer->channelCreate(array(
 "channel_name" => "Autre",
 "channel_topic" => "This is a sub-level channel",
 "channel_codec" => TeamSpeak3::CODEC_SPEEX_NARROWBAND,
 "channel_flag_permanent" => TRUE,
 "channel_password" => "CS:GO",
 "cpid" => $top_cid,
));
*/

} catch(Exception $e) {
    echo "<p class='server_error'> ERROR: </p>".$e->getMessage();
}
?>
