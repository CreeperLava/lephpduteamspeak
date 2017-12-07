<!DOCTYPE html>
<html>
    <head>
        <title>Administration du TS3</title>
    </head>
    <body>
    <?php
	require_once("libraries/TeamSpeak3/TeamSpeak3.php");
    if (isset($_POST['password']) AND $_POST['password'] == "root"){
		try {
			$instance = TeamSpeak3::factory("serverquery://127.0.0.1:10011");
			// login using serveradmin account
			$instance->login("serveradmin", "B9WRhIPU");
	
			// display server information
			echo "<table><tr>
			<td>Server Status:</td><td class='server_online'>".$instance->virtualserver_status."</td>
			</tr><tr>
			<td>Server Name</td><td class='server_name'>".$instance->virtualserver_name."</td>
			</tr><tr>
			<td>Server Adress:</td><td class='server_adress'>".$instance->getAdapterHost()."</td>
			</tr><tr>
			<td>Server Uptime:</td><td class='server_uptime'>".TeamSpeak3_Helper_Convert::seconds($instance->virtualserver_uptime)."</td>
			</tr><tr>
			<td>Users:</td><td class='server_users'>".($instance->virtualserver_clientsonline-$instance->virtualserver_queryclientsonline)."/".$instance->virtualserver_maxclients."</td>
			</tr><tr>
			<td>Channels:</td><td class='server_channels'>".$instance->virtualserver_channelsonline."</td>
			</tr><tr>
			<td>Download:</td><td class='server_download'>".TeamSpeak3_Helper_Convert::bytes($instance->connection_filetransfer_bytes_received_total + $instance->connection_bytes_received_total)."</td>
			</tr><tr>
			<td>Upload:</td><td class='server_upload'>".TeamSpeak3_Helper_Convert::bytes($instance->connection_filetransfer_bytes_sent_total + $instance->connection_bytes_sent_total)."</td>
			</tr></table>";
		
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
</html>
