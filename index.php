<!DOCTYPE html>

<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Gestion du serveur</title>
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

<body class="bg-dark">

<!-- Bootstrap core JavaScript-->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Core plugin JavaScript-->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>

<div class="grid-container">
    <div class="container">
        <div class="card card-register mx-auto mt-5">
            <div class="card-header">Accès joueur</div>
            <div class="card-body">
                <form method="post" action="server.php">
                    <div class="form-group">
                        <label>Créer un nouveau channel</label>
                        <input class="form-control" placeholder="nom du serveur" type="text" name="name" required/>
                        <input class="form-control" placeholder="mot de passe" type="text" name="password" required/>
                        <select class="form-control" name="game" required>
                            <option value="LoL">LoL</option>
                            <option value="CS:GO">CS:GO</option>
                            <option value="DOTA2">DOTA2</option>
                            <option value="Autre">Autre</option>
                        </select>
                        <input class="btn btn-primary btn-block" type="submit" value="Go!"/>
                    </div></form>
                <form method="post" action="server.php">
                    <div class="form-group">
                        <label>Trouver un channel existant</label>
                        <input class="form-control" type="text" name="nameFind" placeholder="nom du serveur recherché" required/>
                        <input class="btn btn-primary btn-block" type="submit" value="Go!"/>
                    </div></form>
                <button onclick="window.history.back()">Go Back</button>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card card-login mx-auto mt-5">
            <div class="card-header">CORE ACCESS - CONFIDENTIAL</div>
            <div class="card-body">
                <form method="post" action="admin.php">
                    <div class="form-group">
                        <label>Pour les maîtres du jeu.</label>
                        <input class="form-control" placeholder="username" type="text" name="username" required/>
                        <input class="form-control" placeholder="password" type="password" name="password" required/>
                        <input class="btn btn-primary btn-block" type="submit" value="Go!" required/>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>