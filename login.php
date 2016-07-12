<?php
include_once("config/database.php");
header("Location: index.php");
session_start();
if (isset($_POST["OK"])) {
	try {
		$db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$db->beginTransaction();
		$log = $_POST["login"];
		$pass = hash("whirlpool", $_POST["pass"]);
		$req = $db->prepare("SELECT login, pass, verif FROM users WHERE login LIKE :log");
		$req->bindParam(":log", $log);
		$req->execute();
		$tab = $req->fetch(PDO::FETCH_ASSOC);
		if ($tab["login"] != $log || $tab["pass"] != $pass)
		{
			$_SESSION["logerr"] = "Login et/ou Mot de passe incorrect";
			$db = NULL;
			return ;
		}
		else if (!($tab["verif"]))
		{
			$_SESSION["logerr"] = "Ce compte n'est pas actif";
			$db = NULL;
			return ;
		}
		if (isset($_SESSION["logerr"]))
			unset($_SESSION["logerr"]);
		$_SESSION["user"] = $log;
		$db->commit();
		$db = NULL;
	}
	catch (Exception $e) {
		$db->rollback();
		$_SESSION["logerr"] = $e->getMessage();
		$db = NULL;
	}
}
else
	$_SESSION["logerr"] = "Vous ne pouvez pas acceder a cette page";
?>
