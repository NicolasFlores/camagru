<html>
    <header>
        <link rel="stylesheet" href="flex.css">
		<title>Superbe site trop g&eacute;nial</title>
		<script src="hide.js"></script>
		<h1>Bienvenue sur le site des images en folie</h1>
<?php
session_start();
header("Content-Type: image.png");
if (isset($_SESSION["user"]) && !empty($_SESSION["user"])) {
?>
		<h3>Bonjour  <?php echo "  ".$_SESSION["user"]; ?></h3>
        <form class="lout" action="logout.php" method="post" >
			<input class="sub" type="submit" name="logout" value="logout" >
        </form>
        <br/>
		<a class="idh" href="index.php">Accueil</a>
    </header>
	<body>
<?php
	include_once("config/database.php");
	try {
		$db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$db->beginTransaction();
		$usr = $db->prepare("SELECT u_id FROM users WHERE login LIKE :log");
		$usr->bindParam(":log", $_SESSION["user"]);
		$usr->execute();
		$uid = $usr->fetch(PDO::FETCH_ASSOC);
		$page = $db->prepare("SELECT COUNT(i_id) AS 'nb' FROM images");
		$page->execute();
		$page = $page->fetch(PDO::FETCH_ASSOC);
		$nbpage = ceil($page["nb"]/5);
		if (isset($_GET["page"])) {
			$cur = intval($_GET["page"]);
			if ($cur <= 0 || $cur > $nbpage)
				$cur = 1;
		}
		else
			$cur = 1;
		$req = $db->prepare("SELECT i_id, src, u_id FROM images ORDER BY i_id LIMIT :cur , 5");
		$req->bindValue(":cur", ($cur - 1) * 5, PDO::PARAM_INT);
		$req->execute();
		$tab = $req->fetchAll();
		echo "<div class='gal'>";
		foreach ($tab as $v) {
			if (file_exists($v["src"])) {
				echo "<div class='pict'>";
				echo "<img src='".$v["src"]."' alt='".$v["src"]."'>";
				if ($uid["u_id"] == $v["u_id"] || $uid["u_id"] == 1) {
					echo "<form>";
					echo "<button id='del".$v["i_id"]."' name='del".$v["i_id"]."' formmethod='post' formaction='delete_image.php' value='".$v["i_id"]."' >Supprimer l'image</button>";
					echo "</form>";
				}
				echo "<form>";
				echo "<input type='text' name='msg' id='msg' value='Votre texte ici' onclick='this.value = \"\";' >";
				echo "<button id='com".$v["i_id"]."' name='com".$v["i_id"]."' formmethod='post' formaction='comment.php' value='".$v["i_id"]."' >Poster un commentaire</button>";
				echo "<button id='like".$v["i_id"]."' name='like".$v["i_id"]."' formmethod='post' formaction='like.php' value='".$v["i_id"]."' >J'aime</button>";
				echo "</form>";
				$like = $db->prepare("SELECT u_id FROM love WHERE i_id = :iid");
				$like->bindParam(":iid", $v["i_id"]);
				$like->execute();
				$like = $like->fetchAll(PDO::FETCH_ASSOC);
				foreach ($like as $k =>$val) {
					if ($val["u_id"] == $uid["u_id"]) {
?>
						<script>hide(<?php echo $v["i_id"]; ?>)</script>
<?php
					}
				}
				$nb = $db->prepare("SELECT COUNT(l_id) AS 'nb' FROM love WHERE i_id = :iid");
				$nb->bindParam(":iid", $v["i_id"]);
				$nb->execute();
				$nb = $nb->fetch();
				echo "<p>".$nb["nb"]." j'aime</p>";
				$com = $db->prepare("SELECT * FROM comments WHERE i_id = :iid");
				$com->bindParam(":iid", $v["i_id"]);
				$com->execute();
				$com = $com->fetchAll(PDO::FETCH_ASSOC);
				if(!empty($com)) {
					for ($i = 0; $i < count($com); $i++) {
						$usr = $db->prepare("SELECT login FROM users WHERE u_id = :uid");
						$usr->bindParam(":uid", $com[$i]["u_id"]);
						$usr->execute();
						$usr = $usr->fetch(PDO::FETCH_ASSOC);
						echo "<p>".$usr["login"]." a ecrit : ".$com[$i]["com"]."</p>";
					}
				}
				
				echo "</div>";
			}
		}
		if ($cur == 1 && $nbpage > 1)
			echo "<div class='page'>Page 1 <a href='gallery.php?page=2'>Next</a></div>";
		else if ($cur < $nbpage)
			echo "<div class='page'><a href='gallery.php?page=".($cur - 1)."'>Previous</a> Page $cur <a href='gallery.php?page=".($cur + 1)."'>Next</a></div>";
		else if ($cur == $nbpage && $nbpage != 1)
			echo "<div class='page'><a href='gallery.php?page=".($cur - 1)."'>Previous</a> Page $cur</div>";
		$db->commit();
		$db = NULL;
	}
	catch (Exception $e) {
		$db->rollBack();
		$_SESSION["logerr"] = $e->getMessage();
		$db = NULL;
	}
	echo "</div>";
}
else {
?>
	</header>
	</body>
		<h5>Vous ne pouvez pas acceder a cette page</h5><br/>
		<a class="idb" href="index.php">accueil</a>
<?php
}
if (isset($_SESSION["logerr"]) && !empty($_SESSION["logerr"]))
	echo "<h5>".$_SESSION["logerr"]."</h5>";
unset($_SESSION["logerr"]);
?>
    <footer>
		<div class="foot">Copyright &copy; nflores</div>
        <div class="foot">Contact : <a href="mailto:nflores@student.42.fr">nflores</a></div>
        <div class="foot">2016 Camagru</div>
    </footer>
	</body>
</html>
