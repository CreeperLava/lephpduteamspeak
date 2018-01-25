<!DOCTYPE html>

<html>
<head>
    <title>TeamSpeak3 Online</title>
    <meta charset="utf-8">
    <meta http-equiv="refresh" content="20">
</head>
<body>
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
    $list = $ts3_VirtualServer->channelList();

// get channel name from formulary
    if(isset($_POST['name']) AND isset($_POST['password']) AND isset($_POST['game'])) {
        $name = $_POST['name'];
        $password = $_POST['password'];
        $game = $_POST['game'];
        // get all channel names from TS3
        if(in_array($name, $list)) {
            // join
            echo "<p>Le channel $name existe déjà. Vous pouvez le rejoindre via votre client TeamSpeak.</p>";
        } else {
            // init
            init($ts3_VirtualServer);

            // create
            $cpid = $ts3_VirtualServer->channelGetByName($game);
            $ts3_VirtualServer->channelCreate(array(
                "channel_name" => htmlspecialchars($name),
                "channel_topic" => "$name's channel !",
                "channel_codec" => TeamSpeak3::CODEC_SPEEX_NARROWBAND,
                "channel_flag_permanent" => TRUE,
                "channel_password" => htmlspecialchars($password),
                "cpid" => $ts3_VirtualServer->channelGetByName("$game"),
            ));
            echo "<p>Votre serveur a été créé. Enjoy !</p>
			  <table><tr>
			  <td>Nom du serveur</td><td>$name</td></tr><tr>
			  <td>Mot de passe</td><td>$password</td></tr><tr>
			  <td>Jeu</td><td>$game</td>
			  </tr></table>";
        }
    } else if(isset($_POST['nameFind'])) {
        $name = $_POST['nameFind'];
        if(in_array($name, $list)) {
            // join
            echo "<p>Le channel $name existe. Vous pouvez le rejoindre via votre client TeamSpeak.</p>";
        } else {
            echo "<p>Le channel $name n'existe pas. Créez-le !</p>";
        }
    }

} catch(Exception $e) {
    echo "<p class='server_error'> ERROR: </p>".$e->getMessage();
}

function init($ts3_VirtualServer) {
    // On vérifie que les channels racines existent déjà
    // Sinon, on les crée

    try {
        $top_cid = $ts3_VirtualServer->channelGetByName("ROOT");
    } catch (Exception $e){
        $top_cid = $ts3_VirtualServer->channelCreate(array(
            "channel_name" => "ROOT",
            "channel_topic" => "This is a top-level channel",
            "channel_codec" => TeamSpeak3::CODEC_SPEEX_WIDEBAND,
            "channel_flag_permanent" => TRUE,
        ));
    }

    $games = array('LoL', 'DOTA2', 'CS:GO', 'Autre');
    foreach($games as $g){
        try {
            $ts3_VirtualServer->channelGetByName($g);
        } catch (Exception $e){
            $ts3_VirtualServer->channelCreate(array(
                "channel_name" => $g,
                "channel_topic" => "$g channel",
                "channel_codec" => TeamSpeak3::CODEC_SPEEX_NARROWBAND,
                "channel_flag_permanent" => TRUE,
                "channel_password" => $g,
                "cpid" => $top_cid,
            ));
        }
    }
}
?>
<button onclick="location.href='index.php'">Go Back</button>

</body>
</html>
