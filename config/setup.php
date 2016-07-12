<?php
include_once("database.php");
header("Location: ../index.php");
unset($_SESSION);
try {
	$db = new PDO("mysql:host=localhost", $DB_USER, $DB_PASSWORD);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$db->beginTransaction();
	$db->exec("DROP DATABASE IF EXISTS camagru;");
	$db->exec("CREATE DATABASE camagru;");
	$db->exec("USE camagru;");
	$db->exec("CREATE TABLE users (u_id int PRIMARY KEY AUTO_INCREMENT NOT NULL, login varchar(50) NOT NULL, pass varchar(255) NOT NULL, email varchar(100) NOT NULL, verif boolean DEFAULT FALSE, clef varchar(32), reset varchar(32));");
	$pass = hash("whirlpool", "admin");
	$db->exec("INSERT INTO users (login, pass, email, verif) VALUES ('admin', '$pass', 'floresnicolas86@gmail.com', true);");
	$db->exec("CREATE TABLE images (i_id int PRIMARY KEY AUTO_INCREMENT NOT NULL, u_id int, CONSTRAINT u_id FOREIGN KEY (u_id) REFERENCES users(u_id), src VARCHAR(1000) NOT NULL);");
	$db->exec("CREATE TABLE comments (c_id int PRIMARY KEY AUTO_INCREMENT NOT NULL, u_id int, i_id int, com varchar(1000) NOT NULL, date datetime);");
	$db->exec("CREATE TABLE love (l_id int PRIMARY KEY AUTO_INCREMENT NOT NULL, u_id int, i_id int);");
	$db->commit();
	$db = NULL;
}
catch (Exception $e) {
	$db-rollBack();
	$e->getMessage();
	$db = NULL;
}
?>
