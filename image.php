<?php
session_start();
include_once("config/database.php");
header("Location: index.php");
header("Content-Type: image.png");
if (isset($_POST["save"]) && !empty($_POST["save"]) && isset($_SESSION["user"]) && isset($_POST["image"])) {
	try {
		if (isset($_SESSION["upim"])) {
			$upim = $_SESSION["upim"];
			unset($_SESSION["upim"]);
		}
		else {
			$img = $_POST["save"];
			$img = str_replace("data:image/png;base64,", "", $img);
		}
		$select = $_POST["image"];
		$log = $_SESSION["user"];
		$db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$db->beginTransaction();
		$uid = $db->prepare("SELECT u_id FROM users WHERE login LIKE :log");
		$uid->bindParam(":log", $log);
		$uid->execute();
		$uid = $uid->fetch(PDO::FETCH_ASSOC);
		$req = $db->prepare("INSERT INTO images (u_id, src) VALUES (:uid, :src)");
		$req->bindParam(":uid", $uid["u_id"]);
		$src = "img/".date("d-m-Y-H:i:s--").$log.".png";
		if ($select == "img/nezrouge.png") {
			$dst_x = 100;
			$dst_y = 60;
			$src_w = 120;
			$src_h = 120;
		}
		else if ($select == "img/palmier.png") {
			$dst_x = 100;
			$dst_y = 90;
			$src_w = 137;
			$src_h = 180;
		}
		else if ($select == "img/sabre.png") {
			$dst_x = 50;
			$dst_y = 60;
			$src_w = 220;
			$src_h = 176;
		}
		if (isset($img))
			$dest = imagecreatefromstring(base64_decode($img));
		else if (isset($upim)) {
			$dest = imagecreatefrompng($upim);
			$dst_x = 0;
			$dst_y = 0;
		}
		if ($dest == false) {
			$_SESSION["logerr"] = "Une erreur est survenue lors de l'encodage de l'image.";
			$db->rollBack();
			$db = NULL;
			return ;
		}
		$select = imagecreatefrompng($select);
		$cut = imagecreatetruecolor($src_w, $src_h);
		imagecopy($cut, $dest, 0, 0, $dst_x, $dst_y, $src_w, $src_h);
		imagecopy($cut, $select, 0, 0, 0, 0, $src_w, $src_h);
		if (imagecopymerge($dest, $cut, $dst_x, $dst_y, 0, 0, $src_w, $src_h, 100) == false) {
			$_SESSION["logerr"] = "Une erreur est survenue lors de la supperposition de l'image.";
			$db->rollBack();
			$db = NULL;
			return ;
		}
		if (imagepng($dest, $src) == false)
		{
			$_SESSION["logerr"] = "Une erreur est survenue lors de la sauvegarde de l'image.";
			$db->rollBack();
			$db = NULL;
			return ;
		}
		imagedestroy($dest);
		imagedestroy($select);
		$_SESSION["img"][] = $src;
		$req->bindParam(":src", $src);
		$req->execute();
		$db->commit();
		$db = NULL;
		$_SESSION["logerr"] = "image creee avec succes";
	}
	catch (Exception $e) {
		
		$db->rollBack();
		$_SESSION["logerr"] = $e->getMessage();
		$db = NULL;
	}
}
else if (isset($_SESSION["user"]))
	$_SESSION["logerr"] = "Vous ne pouvez pas sauvegarder cette image.";
else
	$_SESSION["logerr"] = "Vous ne pouvez pas acceder a cette page";
?>
