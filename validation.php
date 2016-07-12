<?php
include_once("config/database.php");
header("Location: index.php");
session_start();
if (isset($_GET["log"]) && isset($_GET["cle"])) {
	try {
		$db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$db->beginTransaction();
		$req = $db->prepare("SELECT clef FROM users WHERE login LIKE :log");
		$log = $_GET["log"];
		$req->bindParam(":log", $log);
		$req->execute();
		$tab = $req->fetch(PDO::FETCH_ASSOC);
		if ($tab["clef"] == $_GET["cle"])
			$_SESSION["logerr"] = "Votre compte est maintenant actif, connectez vous avec vos identifiants.";
		else {
			$_SESSION["logerr"] = "Une erreur est survenue lors de la validation";
			$db = NULL;
			return ;
		}
		$ald = $db->prepare("SELECT verif FROM users WHERE login LIKE :log");
		$ald->bindParam(":log", $log);
		$ald->execute();
		$tab = $ald->fetch(PDO::FETCH_ASSOC);
		if ($tab["verif"]) {
			$_SESSION["logerr"] = "Ce compte est deja actif.";
			$db = NULL;
			return ;
		}
		$verif = $db->prepare("UPDATE users SET verif=true WHERE login LIKE :log");
		$verif->bindParam(":log", $log);
		$verif->execute();
		$db->commit();
		$db = NULL;
	}
	catch (Exception $e) {
		$db->rollBack();
		$_SESSION["logerr"] = $e->getMessage();
		$db = NULL;
	}
}
else
	$_SESSION["logerr"] = "Non non non on rentre pas dans les pages comme dans un moulin.";
?>
