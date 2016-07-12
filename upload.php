<?php
session_start();
include_once("config/database.php");
header("Location: index.php");
if (isset($_POST["upload"]) && !empty($_POST["upload"]) && isset($_SESSION["user"]) && isset($_FILES["file"])) {
		$img = "img/up".basename($_FILES["file"]["name"]);
		if (pathinfo($img, PATHINFO_EXTENSION) != "png") {
			$_SESSION["logerr"] = "Vous ne pouvez envoyer que des fichiers png.";
			return ;
		}
		$verif = getimagesize($_FILES["file"]["tmp_name"]);
		if ($verif[0] > 320 && $verif[1] > 240) {
			$_SESSION["logerr"] = "Votre image ne peut pas exceder 320x240 px";
			return ;
		}
		else if ($verif == false) {
			$_SESSION["logerr"] = "Vous ne pouvez envoyer que des fichiers png.";
			return ;
		}
		$im = imagecreatefrompng($_FILES["file"]["tmp_name"]);
		imagepng($im, $img);
		imagedestroy($im);
		$_SESSION["upim"] = $img;
}
else
	$_SESSION["logerr"] = "Vous ne pouvez pas acceder a cette page.";
?>
