<html>
	<header>
		<link rel="stylesheet" href="flex.css">
		<title>Superbe site trop g&eacute;nial</title>
		<script src="image.js"></script>
		<h1>Bienvenue sur le site des images en folie</h1>
<?php
session_start();
if (isset($_SESSION["user"]) && !empty($_SESSION["user"])) {
?>
		<h3>Bonjour  <?php echo "  ".$_SESSION["user"]; ?></h3>
		<form class="lout" action="logout.php" method="post" >
			<input class="sub" type="submit" name="logout" value="logout" >
		</form>
		<a class="idh" href="gallery.php">Gallerie</a>
	</header>
	<body>
		<div class="core">
		<div class="main">
			<form class="upl" action="upload.php" method="post" enctype="multipart/form-data">
				Pas de webcam ? Ajouter une image : <input type="file" name="file" id="file" />
				<input type="submit" id="upload" name="upload" />
			</form>
			<form class="gen">
				<div class="rad">
				<input type="radio" name="image" id="palmier" value="img/palmier.png" checked/>
				<label for="palmier"><img src="img/palmier.png" alt="palmier" /></label/>
				<input type="radio" name="image" id="nez" value="img/nezrouge.png" />
				<label for="nez"><img src="img/nezrouge.png" alt="nez rouge" /></label/>
				<input type="radio" name="image" id="sabre" value="img/sabre.png" />
				<label for="sabre"><img src="img/sabre.png" alt="sabre" /></label/>
				</div>
				<video id="video"></video>
				<button id="button" name="button">Click Me</button>
				<canvas id="canvas" hidden="hidden"></canvas>
				<img id="photo">
				<button id="save" name="save" formmethod="post" formaction="image.php" hidden="hidden">Save image</button>
				<script src="photo.js"></script>
			</form>
		</div>
		<div class="side">
<?php
	if (isset($_SESSION["upim"])) {
		echo "<script>setimage('".$_SESSION["upim"]."')</script>";
	}
	if (isset($_SESSION["img"]) && !empty($_SESSION["img"])) {
		echo "<div class='mini'>";
		foreach ($_SESSION["img"] as $img) {
			if (file_exists($img))
				echo "<img src='".$img."' alt='miniature' width='128' height='96' >";
		}
		echo "</div>";
	}
	echo "</div>"; //Fermeture de side
	echo "</div>"; //fermeture de core
}
else {
?>
		<h3></h3>
		<a class="idh" href="subscribe.php">Inscription</a>
	</header>
	<body>
		<div class="core">
		<div class="main">
			<form class="log" action="login.php" method="post">
			Votre login : <input class="elem" type="text" id="login" name="login" required><br/>
			Mot de passe : <input class="elem" type="password" id="pass" name="pass" required><br/>
				<input class="sub" type="submit" name="OK" value="OK" >
				<br />
				<a class="fgmdp" href="reset.php">Mot de passe oubli&eacute</a>
			</form>
<?php
}
if (isset($_SESSION["logerr"]) && !empty($_SESSION["logerr"]))
	echo "<h5>".$_SESSION["logerr"]."</h5>";
unset($_SESSION["logerr"]);
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
