<?php 
require_once __DIR__ . '/_header.php'; 
?>

    <form method="post" action="<?php echo htmlentities( $_SERVER["PHP_SELF"] ); ?>">
		Korisničko ime:
		<input type="text" name="username" />
		<br />
		Lozinka:
		<input type="password" name="password" />
		<br />
		<button type="submit">Ulogiraj se!</button>
	</form>
    <p>
		Ako nemate korisnički račun, otvorite ga jednim klikom:
		<form method="post" action="<?php echo htmlentities( $_SERVER["PHP_SELF"] ); ?>">
		<button type="submit" name="register">Registriraj se!</button>
		</form>
	</p>

<?php require_once __DIR__ . '/_footer.php'; ?>