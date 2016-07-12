<?php
session_start();
header("Location: index.php");
if (isset($_POST["npass"]) && isset($_POST["OK"]) && isset($_SESSION["tmplog"])) {
	if (empty($_POST["npass"])) {
		$_SESSION["logerr"] = "Une erreur est survenue lors du changement de mot de passe, veuillez recommencer.";
	}
	else {
		include_once("config/database.php");
		try {
			$res = NULL;
			$db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$db->beginTransaction();
			$log = $_SESSION["tmplog"];
			$pw = hash('whirlpool', $_POST["npass"]);
			$req = $db->prepare("UPDATE users SET pass=:pw, reset=:res WHERE login LIKE :log");
			$req->bindParam(":pw", $pw);
			$req->bindParam(":res", $res);
			$req->bindParam(":log", $log);
			$req->execute();
			$_SESSION["logerr"] = "Mot de passe change avec succes";
			unset($_SESSION["tmplog"]);
			$db->commit();
			$db = NULL;
			echo "toto";
		}
		catch (Exception $e) {
			$db->rollBack();
			$_SESSION["logerr"] = $e->getMessage();
			$db = NULL;
			echo "tata";
		}
	}
}
else
	$_SESSION["logerr"] = "Vous ne pouvez pas acceder a cette page.";
print_r($_SESSION);
?>
