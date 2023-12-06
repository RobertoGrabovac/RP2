<?php

require_once __DIR__ . '/../app/db.class.php';
require_once __DIR__ . '/sqlQuery.class.php';

class logreg{
    public function logout() {
        session_start();

        session_unset();
        session_destroy();
        header('Location: quack.php');
    }
    public function formaZaLogin($errorMSG = '') {
        $title = "Welcome";
        require_once __DIR__ . '/../view/login_forma.php';
        if (isset($_POST['odjava'])) $this->logout();
        if( $errorMSG !== '' )
		    echo '<p>Greška: ' . $errorMSG . '</p>'; 
    }

    public function formaZaNovog($errorMSG = '') {
        $title = "Register";
        require_once __DIR__ . '/../view/novi_forma.php';
        if( $errorMSG !== '' )
		    echo '<p>Greška: ' . $errorMSG . '</p>';
    }

    public function analizirajLogin() {
        if( !strlen( $_POST['username'] ) || !strlen( $_POST['password'] ) ) 
	    {
            $this->formaZaLogin( 'Trebate unijeti korisničko ime i lozinku.' );
            exit();
	    }

        if( !preg_match( '/^[a-zA-Z]{3,50}$/', $_POST['username'] ) )
	    {
            $this->formaZaLogin( 'Korisničko ime treba imati između 3 i 50 slova.' );
            exit();
	    }

        $db = DB::getConnection();

        try
        {
            $st = $db->prepare( 'SELECT username, password_hash, has_registered FROM dz2_users WHERE BINARY username=:username' );
            $st->execute( array( 'username' => $_POST['username'] ) );
        }
        catch( PDOException $e ) { exit( 'Greška u bazi: ' . $e->getMessage() ); }

        $row = $st->fetch();

	    if( $row === false )
        {
            $this->formaZaLogin( 'Korisnik s tim imenom ne postoji.' );
            exit();
        }
	    else if( $row['has_registered'] === '0' )
        {
            $this->formaZaLogin( 'Ne možete koristiti Quack jer niste verificirali profil. Provjerite e-mail.' );
            exit();
        }
	    else if( !password_verify( $_POST['password'], $row['password_hash'] ) )
        {
            $this->formaZaLogin( 'Lozinka nije ispravna.' );
            exit();
        }
        else
        {
            $_SESSION['username'] = $_POST['username'];
            $title = "Quack!";
            require_once __DIR__ . '/../view/glavna.php';
            $sqlcontr = new sql();
            $sqlcontr->myquacks();
            if (isset($_POST['odjava'])) $this->logout();
            exit();
        }
    }

    public function analizirajNovi() {
        if (!strlen($_POST['newpassword']) || !strlen( $_POST['email']))
	    {
            $this->formaZaNovog('Morate unijeti username, lozinku i ispravan mail!');
            exit();
	    }
        if( !preg_match( '/^[A-Za-z]{3,50}$/', $_POST['newusername'] ) )
	    {
            $this->formaZaNovog( 'Korisničko ime treba imati između 3 i 50 slova.' );
            exit();
	    }
	    else if( !filter_var( $_POST['email'], FILTER_VALIDATE_EMAIL) )
        {
            $this->formaZaNovog( 'E-mail adresa nije ispravna.' );
            exit();
        }
	    else 
        {
            $db = DB::getConnection();
            try
            {
                $st = $db->prepare( 'SELECT * FROM dz2_users WHERE username=:username' );
                $st->execute( array( 'username' => $_POST['newusername'] ) );
            }
		    catch( PDOException $e ) { exit( 'Greška u bazi: ' . $e->getMessage() ); }

            if( $st->rowCount() !== 0 )
            {
                
                $this->formaZaNovog( 'Korisnik s tim imenom već postoji u bazi.' );
                exit();
            }

            $reg_seq = '';
		    for( $i = 0; $i < 20; ++$i )
			    $reg_seq .= chr( rand(0, 25) + ord( 'a' ) );

            try
            {
                $st = $db->prepare( 'INSERT INTO dz2_users(username, password_hash, email, registration_sequence, has_registered) VALUES ' .
                                    '(:username, :password, :email, :reg_seq, 0)' );
                
                $st->execute( array( 'username' => $_POST['newusername'], 
                                    'password' => password_hash( $_POST['newpassword'], PASSWORD_DEFAULT ), 
                                    'email' => $_POST['email'], 
                                    'reg_seq'  => $reg_seq ) );
            }
		    catch( PDOException $e ) { exit( 'Greška u bazi: ' . $e->getMessage() ); }
            
            $to = $_POST['email'];
		    $subject = 'Registracijski mail';
		    $message = 'Poštovani ' . $_POST['newusername'] . "!\nZa dovršetak registracije kliknite na sljedeći link: ";
		    $message .= 'http://' . $_SERVER['SERVER_NAME'] . htmlentities( dirname( $_SERVER['PHP_SELF'] ) ) . '/quack.php?niz=' . $reg_seq . "\n";
		    $headers = 'From: rp2@studenti.math.hr' . "\r\n" .
		                'Reply-To: rp2@studenti.math.hr' . "\r\n" .
		                'X-Mailer: PHP/' . phpversion();

		    $isOK = mail($to, $subject, $message, $headers);

            if( !$isOK )
                exit( 'Greška: ne mogu poslati mail. (Pokrenite na rp2 serveru.)' );
            }
            
            $this->formaZaLogin('Uspješno ste se registrirali, no kako biste mogli koristiti Quack, morate se verificirati putem dobivenog linka na mail-u!');
        }

        public function verificiraj($kod) {
            $warningMSG = '';

            if(!preg_match( '/^[a-z]{20}$/', $kod ) )
	            return "Kreirani niz nije dobar...";

            $db = DB::getConnection();

            try
            {
                $st = $db->prepare( 'SELECT * FROM dz2_users WHERE registration_sequence=:reg_seq' );
                $st->execute( array( 'reg_seq' => $kod ) );
            }
            catch( PDOException $e ) { exit( 'Greška u bazi: ' . $e->getMessage() ); }
                
            $row = $st->fetch();

            if( $st->rowCount() !== 1 )
	            return 'Taj registracijski niz ima ' . $st->rowCount() . ' korisnika, a treba biti točno 1 takav.';
            else {
                try
	            {
		            $st = $db->prepare( 'UPDATE dz2_users SET has_registered=1 WHERE registration_sequence=:reg_seq' );
		            $st->execute( array( 'reg_seq' => $kod ) );
	            }
	            catch( PDOException $e ) { exit( 'Greška u bazi: ' . $e->getMessage() ); }
            }

            $warningMSG = 'Čestitamo! Uspješno ste verificirali Vaš korisnički profil. Sada možete koristiti quack!';

            return $warningMSG;
        }
}

?>