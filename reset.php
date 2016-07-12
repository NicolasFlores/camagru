<?php
session_start();
$foot = '</div></div><footer><div class="foot">Copyright &copy; nflores</div><div class="foot">Contact : <a href="mailto:nflores@student.42.fr">nflores</a></div><div class="foot">2016 Camagru</div></footer></body></html>';
?>
<html>
    <header>
		<link rel="stylesheet" href="flex.css">
		<title>Superbe site trop g&eacute;nial</title>
        <h1>Bienvenue sur le site des images en folie</h1>
<?php
if (isset($_SESSION["user"])) {
?>
	<h3>Bonjour  <?php echo "  ".$_SESSION["user"]; ?></h3>
        <form class="lout" action="logout.php" method="post" >
            <input class="sub" type="submit" name="logout" value="logout" >
        </form>
     </header>
     <body>
        <div class="core">
        <div class="main">
			<h5>Petit malin retour a l&#146;accueil avec ce lien <a href="index.php">ICI</a></h5>
        </div>
        </div>
        <footer>
			<div class="foot">Copyright &copy; nflores</div>
            <div class="foot">Contact : <a href="mailto:nflores@student.42.fr">nflores</a></div>
            <div class="foot">2016 Camagru</div>
        </footer>
    </body>
</html>
<?php
}
else {
?>
		<h3></h3>
		<form class="lout"></form>
		<a class="idh" href="index.php">Accueil</a>
	</header>
    <body>
		<div class="core">
		<div class="main">
			<form class="log" action="reset.php" method="post">
			Votre Login : <input class="elem" type="text" id="login" name="login" required><br/>
				<input class="sub" type="submit" name="reset" value="reset">
			</form>
<?php
if (isset($_POST["login"]) && isset($_POST["reset"])) {
	if (empty($_POST["login"]))
		echo "<h5>Vous devez renseigner tous les champs</h5>".$foot;
	else {
		include_once("config/database.php");
		try {
			$db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$db->beginTransaction();
			$log = $_POST["login"];
			$reql = $db->prepare("SELECT login, verif FROM users WHERE login LIKE :log");
			$reql->bindParam(":log", $log);
			$reql->execute();
			$tab = $reql->fetch();
			if ($log != $tab["login"] || !$tab["verif"]) {
				echo "<h5>Ce login n'existe pas</h5>".$foot;
				return ;
			}
			$cle = md5(microtime() + 1516);
			$reset = $db->prepare("UPDATE users SET reset=:cle WHERE login LIKE :log");
			$reset->bindParam(":log", $log);
			$reset->bindParam(":cle", $cle);
			$mail = $db->prepare ("SELECT email FROM users WHERE login LIKE :log");
			$mail->bindParam(":log", $log);
			$mail->execute();
			$dest = $mail->fetch(PDO::FETCH_ASSOC);
			$sujet = "Reset de votre mot de passe";
			$ent = "From: reset@camagru.fr";
			$msg = "Vous avez demander un reset de mot de passe.\n\nCliquez sur le lien suivant ou le copier coller dans le navigateur.\n\n http://localhost:8080/camagru/reset_valid.php?log=".urlencode($log)."&cle=".urlencode($cle)."\nSi vous n'etes pas l'initiateur de cette demande vous pouvew simplement ignorer ce message.\n\n******\nCeci est un mail automatique.";
			if (mail($dest["email"], $sujet, $msg, $ent))
			{
				echo "<h5>Un mail de reset de mot de passe a ete envoye.</h5>".$foot;
				$reset->execute();
			}
			else
				echo "<h5>Une erreur est survenue veuilez recommencer.</h5>".$foot;
			$db->commit();
			$db = NULL;
		}
		catch (Exception $e) {
			$db->rollBack();
			echo "<h5>".$e->getMessage()."</h5>".$foot;
			$db = NULL;
		}
	}
	return ;
}
?>
		</div>
		</div>
		<footer>
			<div class="foot">Copyright &copy; nflores</div>
			<div class="foot">Contact : <a href="mailto:nflores@student.42.fr">nflores</a></div>
			<div class="foot">2016 Camagru</div>
		</footer>
    </body>					
</html>
<?php
}
?>
