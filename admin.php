<!DOCTYPE html>
<?xml version="1.0" encoding="UTF-8"?>

<html>
    <head>
        <title>Administration TS3</title>
    	<meta http-equiv="refresh" content="3">
    </head>
    <body>
    <?php
	require_once("libraries/TeamSpeak3/TeamSpeak3.php");
			
    if ((isset($_POST['password']) AND $_POST['password'] == "root") OR ((isset($_COOKIE['root']) AND $_COOKIE['root'] == "root"))){
		setcookie("root","root", time()+3600);
		try {
			// login using serveradmin account
			$instance = TeamSpeak3::factory("serverquery://serveradmin:B9WRhIPU@127.0.0.1:10011");
			$ts3_VirtualServer = $instance->serverGetByPort(9987);
			// display server information
			echo "<p><b>Liste des channels</b></p>";
			echo "<table><tr>";
			foreach($ts3_VirtualServer->channelList() as $channel){
				$level = $channel->getLevel();
				$indent = "";
				for ($i = 0; $i < $level; $i++) {
					$indent .= "--";
				}
				echo "<td>$indent>$channel</td></tr><tr>\n";
			}
			echo "</tr></table>";

			echo "<p><b>Informations complémentaires</b></p>";
			echo "<table><tr>";
			foreach($instance->getInfo() as $key => $val){
				echo "<td>$key</td><td>$val</td></tr><tr>\n";
			}
			echo "</tr></table>";
			
			// display list of channels
			// TODO
		} catch(Exception $e) {
			echo "<p class='server_error'> ERROR: </p>".$e->getMessage();
		}
    } else {
        echo '<p>Mot de passe incorrect</p>';
    }
    ?>
    </body>
    <button onclick="location.href='index.php'">Go Back</button>
</html>
