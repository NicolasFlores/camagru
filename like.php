<?php
include_once("config/database.php");
header("Location: gallery.php");
session_start();
if (isset($_SESSION["user"]) && !empty($_SESSION["user"]) && !empty($_POST)) {
	foreach ($_POST as $k => $v)
		$i_id = $v;
	try {
		$db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->beginTransaction();
		$usr = $db->prepare("SELECT u_id FROM users WHERE login LIKE :log");
        $usr->bindParam(":log", $_SESSION["user"]);
        $usr->execute();
		$usr = $usr->fetch(PDO::FETCH_ASSOC);
		$lik = $db->prepare("SELECT l_id FROM love WHERE u_id = :uid AND i_id = :iid");
		$lik->bindParam(":uid", $usr["u_id"]);
		$lik->bindParam(":iid", $i_id);
		$lik->execute();
		$lik = $lik->fetch(PDO::FETCH_ASSOC);
		if (!$lik) {
			$lov = $db->prepare("INSERT INTO love (u_id, i_id) VALUES (:uid, :iid)");
			$lov->bindParam(":uid", $usr["u_id"]);
			$lov->bindParam(":iid", $i_id);
			$lov->execute();
			$db->commit();
		}
		else
			$db->rollBack();
		$db = NULL;
	}
	catch (Exception $e) {
		$db->rollBack();
		$_SESSION["logerr"] = $e->getMessage();
		$db = NULL;
	}
}
?>
