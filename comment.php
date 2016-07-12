<?php
include_once("config/database.php");
session_start();
header("Location: gallery.php");
if (isset($_SESSION["user"]) && !empty($_SESSION["user"]) && !empty($_POST)) {
	try {
		$db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->beginTransaction();
		foreach ($_POST as $k => $v) {
			if ($k == "msg")
				$comment = $v;
			else
				$i_id = $v;
		}
		$usr = $db->prepare("SELECT u_id FROM users WHERE login LIKE :log");
		$usr->bindParam(":log", $_SESSION["user"]);
		$usr->execute();
		$usr = $usr->fetch(PDO::FETCH_ASSOC);
		$req = $db->prepare("INSERT INTO comments (u_id, i_id, com, date) VALUES (:uid, :iid, :com, :dt)");
		$req->bindParam(":uid", $usr["u_id"]);
		$req->bindParam(":iid", $i_id);
		$req->bindParam(":com", $comment);
		$req->bindparam(":dt", date("Y-m-d H:i:s"));
		$req->execute();
		$dest_id = $db->prepare("SELECT u_id FROM images WHERE i_id = :iid");
		$dest_id->bindParam(":iid", $i_id);
		$dest_id->execute();
		$dest_id = $dest_id->fetch(PDO::FETCH_ASSOC);
		$dest = $db->prepare("SELECT email FROM users WHERE u_id = :uid");
		$dest->bindParam(":uid", $dest_id["u_id"]);
		$dest->execute();
		$dest = $dest->fetch(PDO::FETCH_ASSOC);
		$sujet = "Nouveau commentaire";
		$ent = "From: comment@camagru.fr";
		$msg = "Un nouveau commentaire a ete ajoute sur votre image ".$i_id." par ".$_SESSION["user"]."\n\n******\nCeci est un mail automatique merci de ne pas repondre.";		
		if (!mail($dest["email"], $sujet, $msg, $ent)) {
			echo "<h5>Une erreur est survenue lors de l'envoi du mail</h5>";
			$db->rollBack();
		}
		else
			$db->commit();
		$db = NULL;				
	}
	catch (Exception $e) {
		$db->rollBack();
		$_SESSION["logerr"] = $e->getMessage();
		$db = NULL;
		echo "toto";
	}
}
else
	$_SESSION["logerr"] = "Cette page n'est pas accessible.";
?>
