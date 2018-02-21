<!DOCTYPE html>

<html>
<head>
    <title>Administration TS3</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Bootstrap core CSS-->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom fonts for this template-->
    <link href="vendor/fonhtmlhtmlt-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <!-- Page level plugin CSS-->
    <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="css/sb-admin.css" rel="stylesheet">
    <meta http-equiv="refresh" content="60">
</head>
<body>
<?php
require_once("libraries/TeamSpeak3/TeamSpeak3.php");
// login using serveradmin account
// $password = get from script
// $ip = get from script

$instance = TeamSpeak3::factory("serverquery://127.0.0.1:10011");
$instance->login("serveradmin", "B9WRhIPU");
$ts3_VirtualServer = $instance->serverGetByPort(9987);
if ((isset($_POST['password']) AND $_POST['password'] == "Gilgalad") OR ((isset($_COOKIE['password']) AND $_COOKIE['password'] == "Gilgalad"))){
    if(isset($_POST['remove'])){
        try {
            $ts3_VirtualServer->channelDelete($ts3_VirtualServer->channelGetByName($_POST['remove']));
        } catch(Exception $e){
            echo "<p class='server_error'> ERROR: </p>".$e->getMessage();
   		}
   	}
setcookie("password","Gilgalad", time()+3600);
?>

    <div class="card mb-3">
        <div class="card-header">
            <i class="fa fa-table"></i> Remove Channel</div>
        <div class="card-body">
            <div style="display: inline">
                <input style="float: right" type='button' value='Refresh' onClick='window.location.reload()' title="Refresh page. There is an auto-refresh every 20 seconds."/>
                <form method='post' action='admin.php'>
                    <input type='text' name='remove' autofocus required/>
                    <input type='submit' value='Remove'/>
                </form>
            </div>
        </div>
    </div>
    <div class="card mb-3">
        <div class="card-header">
            <i class="fa fa-table"></i> Channel List</div>
        <div class="card-body">
            <b>Channel (Players/MaxPlayers)</b>
            <ul>
                <?php
                // display server information
                try {
                    $previous_level = 0;
                    foreach($ts3_VirtualServer->channelList() as $channel){
                        $level = $channel->getLevel();
                        $counter = $level;
                        $players = count($channel->clientList());

                        if($previous_level < $level){ # on descend d'un ou plusieurs niveau(x) dans l'arborescence
                        	while($previous_level < $counter){
		                    	$counter--;
		                        echo "<ul>";
                            }
                        } else if($previous_level > $level){ # on remonte
                        	while($previous_level > $counter){
		                    	$counter++;
		                        echo "</ul>";
                            }
                        }
                        echo "<li><div style=\"display: inline\">$channel </div><div style='float: right'>($players/∞)</div></li>\n";

                        $previous_level = $level;
                    }
                } catch(Exception $e) {
                    echo "<p class='server_error'> ERROR: </p>".$e->getMessage();
                }
                ?>
            </ul>
        </div>
        <div class="card-footer small text-muted">Dernière mise à jour <?php echo date('d/m/Y h:i:s'); ?></div>
    </div>

    <div class="card mb-3">
        <div class="card-header">
            <i class="fa fa-table"></i> Server Information</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>Key</th>
                        <th>Value</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>Key</th>
                        <th>Value</th>
                    </tr>
                    </tfoot>
                    <tbody>
                    <?php
                    try{
                        foreach($instance->getInfo() as $key => $val){
                            echo "<tr><td>$key</td><td>$val</td></tr>\n";
                        }
                    } catch(Exception $e) {
                        echo "<p class='server_error'> ERROR: </p>".$e->getMessage();
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php
} else {
    echo '<p>Mot de passe incorrect</p>';
}
?>
</body>
<button onclick="location.href='index.php'">Go Back</button>
</html>
