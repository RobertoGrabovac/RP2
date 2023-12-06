<?php

session_start();
$_SESSION['brojPoteza'] = 0;

if(isset($_SESSION['ime'])) {
	header('Location: zadaca_igraj.php');
	exit;
}

?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Prva zadaća</title>
</head>
<body>
    <h1>Sokoban</h1>
<form action="zadaca_igraj.php" method="post">
	<label for="ime"> Unesi ime igrača: </label>
	<input type="text" id="ime" name="ime" /> <br>
	Odaberi igru:<br>
	<label for="prva_igra"><input type="radio" id="prva_igra" name="igra" value="1" checked/>Prva igra (lakša)</label><br>
	<label for="druga_igra"><input type="radio" id="druga_igra" name="igra" value="2"/>Druga igra (teža)</label>
	<br><br>
	<button type="submit">Započni igru!</button>
</form> 	
</body>
</html>