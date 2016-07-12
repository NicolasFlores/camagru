<?php
session_start();
header("Location:index.php");
if (isset($_SESSION["user"]) && isset($_POST["logout"]))
	unset($_SESSION["user"]);
else
	$_SESSION["logerr"] = "Vous ne pouvez pas acceder a cette page";
?>
