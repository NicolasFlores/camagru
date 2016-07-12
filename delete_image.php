<?php
session_start();
header("Location: gallery.php");
include_once("config/database.php");
foreach ($_POST as $k => $v)
	$id = $v;
if (isset($_SESSION["user"]) && !empty($_SESSION["user"]) && !empty($_POST)) {
	try {
		$db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->beginTransaction();
		$file = $db->prepare("SELECT src FROM images WHERE i_id = :id");
		$file->bindParam(":id", $id);
		$file->execute();
		$fl = $file->fetch(PDO::FETCH_ASSOC);
		$del = $db->prepare("DELETE FROM images WHERE i_id = :id");
		$del->bindParam(":id", $id);
		$del->execute();
		$comdel = $db->prepare("DELETE FROM comments WHERE i_id = :id");
		$comdel->bindParam(":id", $id);
		$comdel->execute();
		$likedel = $db->prepare("DELETE FROM love WHERE i_id = :id");
		$likedel->bindParam(":id", $id);
		$likedel->execute();
		$db->commit();
		$db = NULL;
		unlink($fl["src"]);
	}
	catch (Exception $e) {
		$db->rollBack();
		$_SESSION["logerr"] = $e->getMessage();
		$db = NULL;
	}
}
else
	$_SESSION["logerr"] = "Vous ne pouvez acceder a cette page";

?>
