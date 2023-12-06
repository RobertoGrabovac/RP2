<?php
require_once __DIR__ . '/../app/db.class.php';

class sql {
	public function getUserId() {
		try
		{
			$db = DB::getConnection();
			$st = $db->prepare( 'SELECT id FROM dz2_users WHERE username=:username' );
			$st->execute(array( 'username' => $_SESSION['username'] ));
		}
        catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
        
        $row = $st->fetch();
        return $row['id'];
	}

    public function myquacks() {
    	$id = $this->getUserId();
        
        try
		{
			$db = DB::getConnection();
			$st = $db->prepare( 'SELECT quack, date FROM dz2_quacks WHERE id_user=:id ORDER BY date DESC' );
			$st->execute(array( 'id' => $id )); //':id'?
		}
        catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
		
		$title = "Popis Vaših objava: ";
		$column = 'quack';
		require_once __DIR__ . '/../view/ispis_query.php';
		
		echo '<p class="waviy" style="font-size:16px">';
		echo '<span style="--i:1">Objavi</span>
		<span style="--i:2">novi</span>
		<span style="--i:3">quack:</span>';
		echo '<form method="post" action="' . htmlentities( $_SERVER["PHP_SELF"] ) . '">';
		echo '<textarea class="input-text" name="newquack" rows="5" cols="40"></textarea><br> ';
		echo '<button class="button" type="submit" name="objava"> Objavi! </button>';
		echo '</form>';
		echo '</p>';
	}

	public function post_Quack() {
		$warningMSG = '';
		if (strlen($_POST['newquack']) === 0) {
			$warningMSG = 'Greška: niste unijeli objavu!';
		}
		else if (strlen($_POST['newquack']) > 140) {
			$warningMSG = 'Greška: objava mora biti duljine maksimalno 140 znakova!';
		} else {
		
			$id = $this->getUserId();
			date_default_timezone_set( 'CET' );
			$mysqldate = date("Y-m-d H:i:s");
			
			try
			{
				$db = DB::getConnection();
				$st = $db->prepare('INSERT INTO dz2_quacks(id_user, quack, date) VALUES ' .
				'(:id_user, :quack, :date)' );
				$st->execute(['id_user' => $id, 'quack' => $_POST['newquack'], 'date' => $mysqldate ]);
			}
			catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
		}
		return $warningMSG;
	}

	public function followers() {
		$id = $this->getUserId();
		try
		{
			$db = DB::getConnection();
			$st = $db->prepare( 'SELECT username FROM dz2_follows, dz2_users WHERE id = id_user AND id_followed_user =:id_naseg' );
			$st->execute(array( 'id_naseg' => $id ));
		}
        catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
		
		$title = "Vaši pratitelji: ";
		$column = 'username';
		require_once __DIR__ . '/../view/ispis_query.php';
	}

	public function searchMe() {
		$myUsername = '@' . $_SESSION['username'] . '[^a-zA-Z0-9]|' . '@' . $_SESSION['username'] . '$';
		try
		{
			$db = DB::getConnection();
			$st = $db->prepare( 'SELECT username, quack, date FROM dz2_users, dz2_quacks WHERE dz2_users.id = id_user AND BINARY quack REGEXP :pattern ORDER BY date DESC');
			$st->execute([':pattern' => $myUsername]);
		}
        catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
		
		$title = "Objave u kojima ste spomenuti: ";
		require_once __DIR__ . '/../view/ispis_query.php';
	}

	public function searchTag() {
		echo '<p class="waviy">';
		echo '<span style="--i:1">Unesi</span>
		<span style="--i:2">tag</span>
		<span style="--i:3">koji</span>
		<span style="--i:4">te</span>
		<span style="--i:5">zanima:</span>';
		echo '<p>';
		echo '<form method="post" action="' . htmlentities( $_SERVER["PHP_SELF"] ) . '">';
		echo '<label for="searchtag"></label>';
		echo '<input class="input-text" type=text" name="searchtag"> ';
		echo '<button class="button" type="submit" name="tag"> Traži! </button>';
		echo '</form>';
		echo '</p>';
		echo '</p>';
	}

	public function printTag($str_tag) {
		if (strlen($str_tag) === 0) {
			echo "Greška: niste unijeli tag!";
			exit();
		} else if (substr($str_tag, 0, 1) !== '#') {
			echo "Greška: niste ispravno unijeli tag!";
			exit();
		}
		$ourTag = $str_tag . '[^a-zA-Z0-9]|' . $str_tag . '$';
		try
		{
			$db = DB::getConnection();
			$st = $db->prepare( 'SELECT quack, date, username FROM dz2_users, dz2_quacks WHERE dz2_users.id = id_user AND BINARY quack REGEXP :pattern ORDER BY date DESC');
			$st->execute([':pattern' => $ourTag]);
		}
        catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
		
		$title = 'Objave u kojima se pojavljuje tag ' . $str_tag . ': ';
		require_once __DIR__ . '/../view/ispis_query.php';
	}

	public function following() {
		$id = $this->getUserId();
		try
		{
			$db = DB::getConnection();
			$st = $db->prepare( 'SELECT quack, date, username FROM dz2_users, dz2_follows, dz2_quacks WHERE dz2_users.id = id_followed_user AND dz2_follows.id_user LIKE :pattern AND id_followed_user = dz2_quacks.id_user ORDER BY date DESC');
			$st->execute([':pattern' => $id]);
		}
        catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

		$title = "Objave osoba koje pratim: ";
		$column = 'quack';
		require_once __DIR__ . '/../view/ispis_query.php';

		echo '<p class="waviy">';
		echo '<span style="--i:1">Unesi</span>
		<span style="--i:2">username</span>
		<span style="--i:3">korisnika:</span>';
		echo '<form method="post" action="' . htmlentities( $_SERVER["PHP_SELF"] ) . '">';
		echo '<input class="input-text" type="text" name="otherUsername"> ';
		echo '<label for="follow1"><input type="radio" name="(un)follow" id="follow1" value="follow" checked/>Follow</label>';
		echo '<label for="unfollow1"><input type="radio" name="(un)follow" id="unfollow1" value="unfollow" />Unfollow</label> <br><br>';
		echo '<button class="button" type="submit" name="changeFollowers"> Potvrdi </button>';
		echo '</form>';
		echo '</p>';
	}

	public function follow() {
		$warningMSG = '';
		try
		{
			$db = DB::getConnection();
			$st = $db->prepare( 'SELECT id, has_registered FROM dz2_users WHERE username=:username' );
			$st->execute(array( ':username' => $_POST['otherUsername'] ));
		}
        catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

		$row = $st->fetch();
		if (!$row) return "Korisnik " . $_POST['otherUsername'] . " ne postoji.";
		else if ($row['has_registered'] == 0) return "Korisnik " . $_POST['otherUsername'] . " još nije verificirao mail pa ga ne možete zapratiti.";

		$other_id = $row['id'];
		$our_id = $this->getUserId();

		try
		{
			$db = DB::getConnection();
			$st = $db->prepare('SELECT id_followed_user FROM dz2_follows WHERE id_user = :our_id AND id_followed_user = :other_id');
			$st->execute(array(':our_id' => $our_id, ':other_id' => $other_id));
		} 
        catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

		if ($st->rowCount() != 0)
			return "Već pratite korisnika " . $_POST['otherUsername'] . "!";

		try
		{
			$db = DB::getConnection();
			$st = $db->prepare( 'INSERT INTO dz2_follows (id_user, id_followed_user) VALUES (:our_id, :other_id)' );
			$st->execute(array( ':our_id' => $our_id, ':other_id' => $other_id ));
		} 
        catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

		return "Uspješno ste zapratili korisnika " . $_POST['otherUsername'] . "!";
	}

	public function unfollow() {
		$warningMSG = '';
		try
		{
			$db = DB::getConnection();
			$st = $db->prepare( 'SELECT id FROM dz2_users WHERE username=:username' );
			$st->execute(array( ':username' => $_POST['otherUsername'] ));
		}
        catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
        
        $row = $st->fetch();
		if (!$row) 
			return "Korisnika " . $_POST['otherUsername'] . " niste ni pratili.";
		
		$other_id = $row['id'];
		$our_id = $this->getUserId();

		try
		{
			$db = DB::getConnection();
			$st = $db->prepare( 'DELETE FROM dz2_follows WHERE id_user=:user AND id_followed_user=:other_user' );
			$st->execute(array( ':user' => $our_id , ':other_user' => $other_id));
		}
        catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

		return "Unfollow korisnika " . $_POST['otherUsername'] . " je uspješno odrađen.";
	}
}

?>