<!DOCTYPE html>
<html>
<head>
	<meta charset="utf8">
	<title>Quack</title>
	<link rel="stylesheet" href="css/style.css">
</head>
<body>
	<font face = "'Alfa Slab One', cursive"> 
	<h1 class="main-title"><?php echo $title; ?></h1>
    <nav>
		<ul>
			<li><a href="quack.php?rt=myquacks" class="active">My quacks</a></li>
			<li><a href="quack.php?rt=following">Following</a></li>
			<li><a href="quack.php?rt=followers">Followers</a></li>
			<li><a href="quack.php?rt=searchMe">quacks @<?php echo $_SESSION['username'] ?></a></li>
			<li><a href="quack.php?rt=searchTag">#search</a></li>
			<li class="logout">
			<form method="post" action="<?php echo htmlentities( $_SERVER["PHP_SELF"] ); ?>">
			<button class="button" type="submit" name="odjava"> logout </button>
			</form>
			</li>
		</ul>
	</nav>