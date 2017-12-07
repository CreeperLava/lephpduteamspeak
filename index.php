<!DOCTYPE html>

<html>
<header><title>This is title</title></header>
<body>
Hello world
<form method="post" action="server.php">
Si votre équipe a déjà un channel, entrez son nom ici pour reçevoir votre mot de passe.
<input type="text" name="nameFind" required/>
<input type="submit" value="Go!"/>
</form>
<form method="post" action="server.php">
Sinon, entrez le nom et mot de passe ainsi que le jeu concerné, pour créer votre nouveau channel.
<input type="text" name="name" required/>
<input type="text" name="password" required/>
<select name="game" required>
    <option value="LoL">LoL</option>
    <option value="CS:GO">CS:GO</option>
    <option value="DOTA 2">DOTA 2</option>
    <option value="Autre">Autre</option>
</select>
<input type="submit" value="Go!"/>
</form>
<form method="post" action="admin.php">
Pour les maîtres du jeu.
<input type="text" name="username" required/>
<input type="text" name="password" required/>
<input type="submit" value="Go!" required/>
</form>
</body>

<p>Aujourd'hui nous sommes le <?php echo date('d/m/Y h:i:s'); ?>.</p>

</html>
