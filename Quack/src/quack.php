<?php 

require_once __DIR__ . '/model/logreg.class.php';

session_start();

$lr = new logreg();

if (isset($_POST['register'])) {
	$lr->formaZaNovog();
	exit();
} else if (isset($_POST['newusername'])) {
	$lr->analizirajNovi();
	exit();
}

if(isset($_SESSION['username']))
{
	$title = "Quack!";
	require_once 'view/glavna.php';
    if (isset($_POST['odjava'])) $lr->logout();

	$router = new sql();

	if( isset( $_GET['rt'] ) ) {
		$route = $_GET['rt'];
		require_once 'model/sqlQuery.class.php';

		if ($route === 'myquacks') $router->myquacks();
		else if ($route === 'following') $router->following();
		else if ($route === 'followers') $router->followers();
		else if ($route === 'searchMe') $router->searchMe();
		else if ($route === 'searchTag') $router->searchTag();
	} else if (isset ($_GET['hashtag'])) {
		$htag = $_GET['hashtag'];
		$router->searchTag();
		$router->printTag('#' . $htag);
	} 

	if (isset($_POST['objava'])) {
		$warningMSG = $router->post_Quack(); 
		$router->myquacks();
		if ($warningMSG !== '')
			echo $warningMSG;
	} else if (isset($_POST['tag'])) {
		$router->searchTag(); 
		$router->printTag($_POST['searchtag']); 
	} else if (isset($_POST['changeFollowers'])) {
		$warningMSG = '';
		if ($_POST['(un)follow'] === 'follow' && strlen($_POST['otherUsername'])) $warningMSG = $router->follow();
		else if ($_POST['(un)follow'] === 'unfollow' && strlen($_POST['otherUsername'])) $warningMSG = $router->unfollow();
		$router->following();
		if ($warningMSG !== '')
			echo $warningMSG;
	}
	
	exit();
}

if (isset($_GET['niz'])) {
	$warningMSG = $lr->verificiraj($_GET['niz']);
	$lr->formaZaLogin();
	if ($warningMSG != '')
		echo $warningMSG;
	exit();
}

if(isset($_POST['username']))
{
	$lr->analizirajLogin();
	exit();
}
else
{
	$lr->formaZaLogin();
	exit();
}

?>