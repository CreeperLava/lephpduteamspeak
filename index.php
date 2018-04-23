<!DOCTYPE html>

<?php
// INITIALIZE
require_once("libraries/TeamSpeak3/TeamSpeak3.php");
error_reporting(E_ALL); // display PHP errors directly on web page
ini_set('display_errors', 1);

$admin_pass = password_hash('Gilgalad', PASSWORD_DEFAULT);
// set TS instance used by the rest of the website
// instance is shared between all PHP files
$username = "serveradmin";
$password = "lKWgmGLl";
$ip = "127.0.0.1";
$query_port = '10011';
$server_port = '9987';

// connect to local server, authenticate and spawn an object for the virtual server on port 9987
$instance = TeamSpeak3::factory("serverquery://" . $username . ":" . $password . "@" . $ip . ":" . $query_port);
$ts3_VirtualServer = $instance->serverGetByPort($server_port);

// TODO
// check if our IP is in the query_ip_whitelist.txt file
?>

<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>TeamSpeak3 Online</title>
    <!-- Bootstrap core CSS-->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom fonts for this template-->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <!-- Custom styles for this template-->
    <link href="css/sb-admin.css" rel="stylesheet">
</head>

<style>
    .grid-container {
        display: grid;
        grid-template-columns: 60% 40%;
    }
</style>

<!-- Bootstrap core JavaScript-->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Core plugin JavaScript-->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>

<?php
function go_back() {
    unset($_POST['server']);
    unset($_POST['admin']);
    window.location.reload();
}

function init($ts3_VirtualServer) {
    // On vérifie que les channels racines existent déjà
    // Sinon, on les crée
    try {
        $top_cid = $ts3_VirtualServer->channelGetByName("ROOT");
    } catch (Exception $e) {
        $top_cid = $ts3_VirtualServer->channelCreate(array(
            "channel_name" => "ROOT",
            "channel_topic" => "This is a top-level channel",
            "channel_codec" => TeamSpeak3::CODEC_SPEEX_WIDEBAND,
            "channel_flag_permanent" => TRUE,
        ));
    }

    $games = array('LoL', 'DOTA2', 'CS:GO', 'Autre');
    foreach ($games as $g) {
        try {
            $ts3_VirtualServer->channelGetByName($g);
        } catch (Exception $e) {
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

if (isset($_POST['server']) || isset($_POST['admin']))
    echo '<form method="post" action=""><input style="position: fixed; right: 0; float: right; z-index: 2;" type="submit" name="back" value="Go back"/></form>';

if (isset($_POST['server'])) { /////////////////////// server
    echo "<body class=\"bg-light\">";
    $list = $ts3_VirtualServer->channelList();
    if (isset($_POST['name']) AND isset($_POST['password']) AND isset($_POST['game'])) {
        $name = $_POST['name'];
        $password = $_POST['password'];
        $game = $_POST['game'];
        // get all channel names from TS3
        if (in_array($name, $list)) {
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
    } else if (isset($_POST['nameFind'])) {
        $name = $_POST['nameFind'];
        if (in_array($name, $list)) {
            // join
            echo "<p>Le channel $name existe. Vous pouvez le rejoindre via votre client TeamSpeak.</p>";
        } else {
            echo "<p>Le channel $name n'existe pas. Créez-le !</p>";
        }
    }



} else if (isset($_POST['admin'])) { /////////////////////// admin
    echo "<body class=\"bg-light\">";
    if ( (isset($_POST['password']) AND password_verify($_POST['password'], $admin_pass))
        OR ((isset($_COOKIE['password']) AND password_verify($_COOKIE['password'], $admin_pass))) ) {
        if (isset($_POST['remove']))
            $ts3_VirtualServer->channelDelete($ts3_VirtualServer->channelGetByName($_POST['remove']));
        setcookie("password", $admin_pass, time() + 3600);

        echo '
    <div class="card mb-3">
        <div class="card-header">
            <i class="fa fa-table"></i> Remove Channel</div>
        <div class="card-body">
            <div style="display: inline">
                <input style="float: right" type="button" value="Refresh" onClick="window.location.reload()" title="Refresh page"/>
                <form method="post" action="">
                    <input type="text" name="remove" autofocus required/>
                    <input type="submit" value="Remove"/>
                </form>
            </div>
        </div>
    </div>
    <div class="card mb-3">
        <div class="card-header">
            <i class="fa fa-table"></i> Channel List</div>
        <div class="card-body">
            <b>Channel (Players/MaxPlayers)</b>
            <ul>';

        // display server information
        $previous_level = 0;
        foreach ($ts3_VirtualServer->channelList() as $channel) {
            $level = $channel->getLevel();
            $counter = $level;
            $players = count($channel->clientList());

            if ($previous_level < $level) { # on descend d"un ou plusieurs niveau(x) dans l"arborescence
                while ($previous_level < $counter) {
                    $counter--;
                    echo "<ul>";
                }
            } else if ($previous_level > $level) { # on remonte
                while ($previous_level > $counter) {
                    $counter++;
                    echo "</ul>";
                }
            }
            echo "<li><div style=\"display: inline\">$channel </div><div style=\"float: right\">($players/∞)</div></li>\n";
            $previous_level = $level;
        }
        echo '
</ul>
    </div>
    <div class="card-footer small text-muted">Dernière mise à jour : '; echo date("d/m/Y H:i:s"); echo '</div>
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
                    <tbody>';
        foreach ($instance->getInfo() as $key => $val) {
            echo "<tr><td>$key</td><td>$val</td></tr>\n";
        }
        echo '</tbody>
                </table>
            </div>
        </div>
    </div>
    ';
    } else {
        echo '<p>Mot de passe incorrect</p>';
    }




} else { /////////////////////// index par défaut
    echo "<body class=\"bg-dark\">";
    echo '
<div class="grid-container">
<div class="container">
    <div class="card card-register mx-auto mt-5">
        <div class="card-header">Accès joueur</div>
        <div class="card-body">
            <form action="" method="post">
                <div class="form-group">
                    <label>Créer un nouveau channel :</label>
                    <input class="form-control" placeholder="nom du serveur" type="text" name="name" required/>
                    <input class="form-control" placeholder="mot de passe" type="text" name="password" required/>
                    <select class="form-control" name="game" required>
                        <option value="LoL">LoL</option>
                        <option value="CS:GO">CS:GO</option>
                        <option value="DOTA2">DOTA2</option>
                        <option value="Autre">Autre</option>
                    </select>
                    <input class="btn btn-primary btn-block" name="server" type="submit" value="Go!"/>
                </div></form>
            <form action="" method="post">
                <div class="form-group">
                    <label>Trouver un channel existant :</label>
                    <input class="form-control" type="text" name="nameFind" placeholder="nom du serveur recherché" required/>
                    <input class="btn btn-primary btn-block" name="server" type="submit" value="Go!"/>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="container">
    <div class="card card-login mx-auto mt-5">
        <div class="card-header">KERNEL ACCESS - CONFIDENTIAL</div>
        <div class="card-body">
            <form method="post" action="">
                <div class="form-group">
                    <label>Pour les maîtres du jeu.</label>
                    <input class="form-control" placeholder="username" type="text" name="username" required/>
                    <input class="form-control" placeholder="password" type="password" name="password" required/>
                    <input class="btn btn-primary btn-block" name="admin" type="submit" value="Go!" required/>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
';
}
?>

</body>
</html>
