<?php 
require_once __DIR__ . '/_header.php'; ?>

    <form method="post" action="<?php echo htmlentities( $_SERVER["PHP_SELF"] ); ?>">
		Odaberite korisničko ime:
		<input type="text" name="newusername" />
		<br />
		Odaberite lozinku:
		<input type="password" name="newpassword" />
		<br />
		Vaša mail-adresa:
		<input type="text" name="email" />
		<br />
		<button type="submit">Stvori korisnički račun!</button>
	</form>
	<p>
		Povratak na <a href="quack.php">početnu stranicu</a>.
	</p>

<?php require_once __DIR__ . '/_footer.php'; ?>