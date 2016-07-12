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
			<form class="log" action="subscribe.php" method="post">
			Votre Login : <input class="elem" type="text" id="login" name="login" required><br/>
			Votre Mot de passe : <input class="elem" type="password" id="pass" name="pass" required><br/>
			Adresse Email : <input class="elem" type="email" id="mail" name="mail"><br/>
				<input class="sub" type="submit" name="register" value="register">
			</form>
<?php
if (isset($_POST["login"]) && isset($_POST["pass"]) && isset($_POST["mail"]) && isset($_POST["register"])) {
	if (empty($_POST["login"]) || empty($_POST["pass"]) || empty($_POST["mail"]))
		echo "<h5>Vous devez renseigner tous les champs</h5>".$foot;
	else {
		include_once("config/database.php");
		try {
			$db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$db->beginTransaction();
			$req = $db->prepare("INSERT INTO users (login, pass, email) VALUES ( ?, ?, ?)");
			$req->bindParam(1, $name);
			$req->bindParam(2, $pass);
			$req->bindParam(3, $email);
			$login = $db->prepare("SELECT login FROM users WHERE login LIKE :log");
			$mail = $db->prepare("SELECT email FROM users WHERE email LIKE :ml");
			$login->bindParam(":log", $_POST["login"]);
			$mail->bindParam(":ml", $_POST["mail"]);
			$login->execute();
			$mail->execute();
			if ($login->fetch(PDO::FETCH_ASSOC)["login"] != false) {
				echo "<h5>Ce login existe, veuillez en choisir un autre</h5>";
				echo $foot;
				$db->rollBack();
				$db = NULL;
				return ;
			}
			else if (filter_var($_POST["mail"], FILTER_VALIDATE_EMAIL) === false) {
				echo "<h5>Adresse email invalide</h5>".$foot;
				$db->rollBack();
				$db = NULL;
				return ;
			}
			if ($mail->fetch(PDO::FETCH_ASSOC)["email"] != false)
			{
				echo "<h5>Un compte existe pour cette adresse email</h5>".$foot;
				$db->rollback();
				$db = NULL;
				return ;
			}
			$name = $_POST["login"];
			$pass = hash("whirlpool", $_POST["pass"]);
			$email = $_POST["mail"];
			$req->execute();
			$name = $_POST["login"];
			$cle = md5(microtime() + 2500);
			$conf = $db->prepare("UPDATE users SET clef=:cle WHERE login LIKE :log");
			$conf->bindParam(":cle", $cle);
			$conf->bindParam(":log", $name);
			$dest = $_POST["mail"];
			$sujet = "Validation du compte";
			$ent = "From: validation@camagru.fr";
			$msg = "Bienvenue sur camagru,\n\nPour activer votre compte, cliquez sur le lien suivant ou le copier coller dans le navigateur.\n\n http://localhost:8080/camagru/validation.php?log=".urlencode($_POST["login"])."&cle=".urlencode($cle)."\n\n******\nCeci est un mail automatique, merci de ne pas repondre.";
			if (mail($dest, $sujet, $msg, $ent))
			{
				echo "<h5>Un Mail de confirmation a ete envoye a l'adresse mentionnee, cliquez sur le lien dans ce mail pour valider votre inscription..</h5>".$foot;
				$conf->execute();
			}
			else
			{
				echo "<h5>Une erreur est survenue lors de l'envoie du mail veuillez recommencer l'inscription.</h5>".$foot;
				$db->rollBack();
				return ;
			}
			$db->commit();
			$db = NULL;
			
		}
		catch (Exception $e)
		{
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
