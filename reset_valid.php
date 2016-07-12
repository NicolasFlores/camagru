<?php
session_start();
?>
<html>
	<header>
		<link rel="stylesheet" href="flex.css">
		<title>Superbe site trop g&eacute;nial</title>
		<h1>Bienvenue sur le site des images en folie</h1>
<?php
if (isset($_GET["log"]) && isset($_GET["cle"]) && !isset($_SESSION["user"])) {
	include_once("config/database.php");
?>
		<h3></h3>
		<form class="lout"></form>
        <a class="idh" href="index.php">Accueil</a>
    </header>
	<body>
		<div class="core">
		<div class="main">
			<form class="log" action="changepass.php" method="post">
			Nouveau mot de passe :<input class="elem" type="password" id="npass" name="npass" required>
				<input class="sub" type="submit" name="OK" value="OK">
			</form>
<?php
	try {
		$db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$db->beginTransaction();
		$log = $_GET["log"];
		$req = $db->prepare("SELECT reset FROM users WHERE login LIKE :log");
		$req->bindParam(":log", $log);
		$req->execute();
		$tab = $req->fetch(PDO::FETCH_ASSOC);
		if ($tab["reset"] == $_GET["cle"]) {
			$db->commit();
			$db = NULL;
			$_SESSION["tmplog"] = $log;
		}
		else {
			echo "<h5>Une erreur est survenue lors du changement de mot de passe</h5>";
			$db->rollBack();
			$db = NULL;
		}
	}
	catch (Exception $e) {
		$db->rollBack();
		echo "<h5>".$e->getMessage()."</h5>";
		$db = NULL;
	}
}
else {
	if (isset($_SESSION["user"])) {
?>
		<h3>Bonjour  <?php echo "  ".$_SESSION["user"]; ?></h3>
        <form class="lout" action="logout.php" method="post" >
            <input class="sub" type="submit" name="logout" value="logout" >
        </form>
     </header>
<?php
	}
	else {
?>
		<h3></h3>
		<form class="lout"></form>
        <a class="idh" href="index.php">Accueil</a>
    </header>
<?php
	}
?>
	<body>
		<div class="core">
		<div class="main">
			<h5>Petit malin retour a l&#146;accueil avec ce lien <a href="index.php">ICI</a></h5>
<?php
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
