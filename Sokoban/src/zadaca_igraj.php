<?php 
session_start();

if(isset( $_POST['ime'])){
	if(preg_match('/^[a-zA-Z]{3,20}$/', $_POST['ime'])){
		$_SESSION['ime'] = $_POST['ime'];
        $_SESSION['igra'] = $_POST['igra'];
	}
	else {
		header( 'Location: zadaca.php' );
		exit;
	}
}

if(!isset($_SESSION['ime'])) {
	header('Location: zadaca.php');
	exit;
}

$ime = $_SESSION['ime'];
$broj_poteza = $_SESSION['brojPoteza'];
$razina_igre = $_SESSION['igra'];

init();
//debug();
$rezultat = obradi_klik();
nacrtaj_plocu($rezultat);



//--------------------------------------------------

function init() {
    if ((!isset($_SESSION['tablica1']) && !isset($_SESSION['tablica2']) && $_SESSION['igra'] == 1) || (isset($_POST['opcija']) && $_POST['opcija'] == "1" && $_SESSION['igra'] == 1)) {
        $_SESSION['brojPoteza'] = 0;
        $m = 9;
        $n = 8;
        $_SESSION['tablica1'] = [];
        for ($i = 0; $i < $m; $i++)
            for ($j = 0; $j < $n; $j++)
                $_SESSION['tablica1'][$i][$j] = 'white';
        for ($i = 1; $i < $m; $i++) $_SESSION['tablica1'][$i][0] = 'blue';
        for ($i = 1; $i < $n; $i++) $_SESSION['tablica1'][$m - 1][$i] = 'blue';
        for ($i = 2; $i < $n - 1; $i++) $_SESSION['tablica1'][0][$i] = 'blue';
        for ($i = 1; $i < $m - 3; $i++) $_SESSION['tablica1'][$i][6] = 'blue';
        $_SESSION['tablica1'][5][$n - 1] = $_SESSION['tablica1'][6][$n - 1] = $_SESSION['tablica1'][7][$n - 1] = 'blue';
        $_SESSION['tablica1'][1][1] = $_SESSION['tablica1'][1][2] = 'blue';
        $_SESSION['tablica1'][3][1] = $_SESSION['tablica1'][3][2] = 'blue';
        $_SESSION['tablica1'][4][2] = $_SESSION['tablica1'][4][3] = 'blue';
        $_SESSION['tablica1'][5][2] = 'blue';
        $_SESSION['tablica1'][2][1] = $_SESSION['tablica1'][3][5] = $_SESSION['tablica1'][4][1] = $_SESSION['tablica1'][5][4] = $_SESSION['tablica1'][6][3] = $_SESSION['tablica1'][6][6] = $_SESSION['tablica1'][7][4] = 'yellow';

        for ($i = 0; $i < $m; $i++)
            for ($j = 0; $j < $n; $j++)
                $_SESSION['tablica2'][$i][$j] = '?';
        $_SESSION['tablica2'][2][2] = 'X';
        $_SESSION['tablica2'][2][3] = $_SESSION['tablica2'][3][4] = $_SESSION['tablica2'][4][4] = $_SESSION['tablica2'][6][1] = $_SESSION['tablica2'][6][3] = $_SESSION['tablica2'][6][4] = $_SESSION['tablica2'][6][5] = 'D';
        }
    if ((!isset($_SESSION['tablica1']) && !isset($_SESSION['tablica2']) && $_SESSION['igra'] == 2) || (isset($_POST['opcija']) && $_POST['opcija'] == "1" && $_SESSION['igra'] == 2)) {
        $_SESSION['brojPoteza'] = 0;
        $m = 10;
        $n = 14;
        $_SESSION['tablica1'] = [];
        for ($i = 0; $i < $m; $i++)
            for ($j = 0; $j < $n; $j++)
                $_SESSION['tablica1'][$i][$j] = 'white';
        for ($i = 0; $i < 7; $i++) $_SESSION['tablica1'][$i][0] = 'blue';
        for ($i = 1; $i <= 5; $i++) $_SESSION['tablica1'][6][$i] = 'blue';
        for ($i = 0; $i < 12; $i++) $_SESSION['tablica1'][0][$i] = 'blue';
        $_SESSION['tablica1'][1][11] = $_SESSION['tablica1'][1][12] = 'blue';
        for ($i = 1; $i < $m; $i++) $_SESSION['tablica'][$i][$n - 1] = 'blue';
        $_SESSION['tablica1'][5][$n - 2] = 'blue';
        for ($i = 2; $i < $n; $i++) $_SESSION['tablica1'][$m - 1][$i] = 'blue';
        for ($i = 1; $i < 9; $i++) $_SESSION['tablica1'][$i][$n - 1] = 'blue';
        $_SESSION['tablica1'][5][5] = 'blue';
        $_SESSION['tablica1'][7][2] = $_SESSION['tablica1'][8][2] = 'blue';
        $_SESSION['tablica1'][1][5] = $_SESSION['tablica1'][2][5] = $_SESSION['tablica1'][3][5] = 'blue';
        $_SESSION['tablica1'][6][7] = $_SESSION['tablica1'][6][8] = $_SESSION['tablica1'][5][7] = 'blue';
        $_SESSION['tablica1'][3][7] = $_SESSION['tablica1'][3][8] = $_SESSION['tablica1'][3][9] = $_SESSION['tablica1'][3][10] = 'blue';
        $_SESSION['tablica1'][4][9] = $_SESSION['tablica1'][4][10] = 'blue';
        for ($i = 1; $i <= 5; $i++)
            for ($j = 1; $j <= 2; $j++)
                $_SESSION['tablica1'][$i][$j] = 'yellow';

        for ($i = 0; $i < $m; $i++)
            for ($j = 0; $j < $n; $j++)
                $_SESSION['tablica2'][$i][$j] = '?';
        $_SESSION['tablica2'][4][7] = 'X';
        $_SESSION['tablica2'][7][4] = $_SESSION['tablica2'][7][7] = $_SESSION['tablica2'][7][9] = $_SESSION['tablica2'][7][11] = $_SESSION['tablica2'][6][11] = 'D';
        $_SESSION['tablica2'][3][6] = $_SESSION['tablica2'][2][7] = $_SESSION['tablica2'][2][10] = $_SESSION['tablica2'][5][10] = $_SESSION['tablica2'][6][9] = 'D';
    }
}

function obradi_klik() {
    $m = count($_SESSION['tablica2']);
    $n = count($_SESSION['tablica2'][0]);
    if (isset($_POST['opcija']) && $_POST['opcija'] === "1") init();
    if (isset($_POST['cont']) && isset($_POST['opcija']) && $_POST['opcija'] === "2") {
        $broj = $_POST['cont'];
        for ($i = 0; $i < $m; $i++)
            for ($j = 0; $j < $n; $j++) {
                $test = $i . $j;
                if ($broj === $test) $_SESSION['tablica2'][$i][$j] = '?';
            }
    }

    $flag = false;
    for ($i = 0; $i < $m; $i++) {
        for ($j = 0; $j < $n; $j++)
            if ($_SESSION['tablica2'][$i][$j] == 'X') {
                $flag = true;
                break;
            }
        if ($flag) break;
    }

    if (isset($_POST['gore'])) {
        if ($_SESSION['tablica2'][$i - 1][$j] === 'D') {
            $_SESSION['tablica2'][$i - 2][$j] = 'D';
            $_SESSION['tablica2'][$i - 1][$j] = '?';
        }
        $_SESSION['tablica2'][$i][$j] = '?';
        $_SESSION['tablica2'][$i - 1][$j] = 'X';
        $_SESSION['brojPoteza']++;
        unset($_POST['gore']);
        return provjeri_igru();
    } else if (isset($_POST['desno'])) {
        if ($_SESSION['tablica2'][$i][$j + 1] === 'D') {
            $_SESSION['tablica2'][$i][$j + 2] = 'D';
            $_SESSION['tablica2'][$i][$j + 1] = '?';
        }
        $_SESSION['tablica2'][$i][$j] = '?';
        $_SESSION['tablica2'][$i][$j + 1] = 'X';
        $_SESSION['brojPoteza']++;
        return provjeri_igru();
    } else if (isset($_POST['lijevo'])) {
        if($_SESSION['tablica2'][$i][$j - 1] === 'D'){
            $_SESSION['tablica2'][$i][$j - 2] = 'D';
            $_SESSION['tablica2'][$i][$j - 1] = '?';
        }
        $_SESSION['tablica2'][$i][$j] = '?';
        $_SESSION['tablica2'][$i][$j - 1] = 'X';
        $_SESSION['brojPoteza']++;
        return provjeri_igru();
    } else if (isset($_POST['dolje'])) {
        if ($_SESSION['tablica2'][$i + 1][$j] === 'D') {
            $_SESSION['tablica2'][$i + 2][$j] = 'D';
            $_SESSION['tablica2'][$i + 1][$j] = '?';
        }
        $_SESSION['tablica2'][$i][$j] = '?';
        $_SESSION['tablica2'][$i + 1][$j] = 'X';
        $_SESSION['brojPoteza']++;
        return provjeri_igru();
    }
    return '?';
}

function provjeri_igru() {
    $m = count($_SESSION['tablica1']);
    $n = count($_SESSION['tablica1'][0]);
    for ($i = 0; $i < $m; $i++)
        for ($j = 0; $j < $n; $j++)
            if ($_SESSION['tablica2'][$i][$j] === 'D' && $_SESSION['tablica1'][$i][$j] !== 'yellow') return 0;
    return 1;
}

function nacrtaj_plocu($rezultat) {
    echo '<h1>Sokoban</h1>';
    echo '<p>';
		echo 'Igrač ' . htmlentities( $_SESSION['ime'], ENT_QUOTES ) . " je dosad napravio " . htmlentities( $_SESSION['brojPoteza'], ENT_QUOTES ) . " pomaka!";
	echo '</p>';
    echo '<table>';
    $m = count($_SESSION['tablica1']);
    $n = count($_SESSION['tablica1'][0]);
    for ($i = 0; $i < $m; $i++) {
        echo '<tr>';
        
        for ($j = 0; $j < $n; $j++) {
            echo '<td style = "background-color:' . $_SESSION['tablica1'][$i][$j] . ';">';
            if ($_SESSION['tablica2'][$i][$j] === 'D') echo '&#x2666;';
            else if ($_SESSION['tablica2'][$i][$j] === 'X') echo '&#128515;';
            echo '</td>'; 
        }
        echo '</tr>';
    }
    echo '</table> <br>';
    if( $rezultat === 1 ){
        echo 'Čestitamo ' . $_SESSION['ime'] . '! Igru ste završili nakon ' . $_SESSION['brojPoteza'] . ' koraka!<br><br>';
        session_unset();
        session_destroy();
    }
}
function debug()
    {
        echo '<pre>';

        echo '$_POST = '; print_r( $_POST );
        echo '$_SESSION = '; print_r( $_SESSION );

        echo '</pre>';
    }
?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Prva zadaća</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form action="zadaca_igraj.php" method="post">
    <?php
    if (isset($_SESSION['tablica2'])) {
    echo 'Pomakni igrača za jedno mjesto u smjeru: <br>';
    $m = count($_SESSION['tablica2']);
    $n = count($_SESSION['tablica2'][0]);
    $flag = false;
    for ($i = 0; $i < $m; $i++) {
        for ($j = 0; $j < $n; $j++)
            if ($_SESSION['tablica2'][$i][$j] == 'X') {
                $flag = true;
                break;
            }
        if ($flag) break;
    }
    echo '<button type="submit" name="gore" value="up" ';
    if (($i - 1 >= 0) && $_SESSION['tablica1'][$i - 1][$j] !== 'blue') {
        if ($_SESSION['tablica2'][$i - 1][$j] === 'D') {
            if ($i - 2 < 0 || $_SESSION['tablica1'][$i - 2][$j] === 'blue' || $_SESSION['tablica2'][$i - 2][$j] === 'D')
                echo 'disabled';
        }
    } else echo 'disabled';
    echo '>Gore </button>';

    echo '<button type="submit" name="lijevo" value="left" ';
    if (($j - 1 >= 0) && $_SESSION['tablica1'][$i][$j - 1] !== 'blue') {
        if ($_SESSION['tablica2'][$i][$j - 1] === 'D') {
            if ($j - 2 < 0 || $_SESSION['tablica1'][$i][$j - 2] === 'blue' || $_SESSION['tablica2'][$i][$j - 2] === 'D')
                echo 'disabled';
        }
    } else echo 'disabled';
    echo '>Lijevo </button>';

    echo '<button type="submit" name="desno" value="right" ';
    if (($j + 1 < $n) && $_SESSION['tablica1'][$i][$j + 1] !== 'blue') {
        if ($_SESSION['tablica2'][$i][$j + 1] === 'D') {
            if ($j + 2 >= $n || $_SESSION['tablica1'][$i][$j + 2] === 'blue' || $_SESSION['tablica2'][$i][$j + 2] === 'D')
                echo 'disabled';
        }
    } else echo 'disabled';
    echo '>Desno </button>';

    echo '<button type="submit" name="dolje" value="down" ';
    if (($i + 1 < $m) && $_SESSION['tablica1'][$i + 1][$j] !== 'blue') {
        if ($_SESSION['tablica2'][$i + 1][$j] === 'D') {
            if ($i + 2 >= $m || $_SESSION['tablica1'][$i + 2][$j] === 'blue' || $_SESSION['tablica2'][$i + 2][$j] === 'D')
                echo 'disabled';
        }
    } else echo 'disabled';
    echo '>Dolje </button>';
    }
    ?>
    </form>
    <!--
    <div class="top"><button type="submit">Gore</button></div>
    <br><br>
    <div class="left"><button type="submit">Lijevo</button></div>
    <div class="right"><button type="submit">Desno</button></div>
    <br><br>
    <div class="bottom"><button type="submit">Dolje</button></div>
    -->
	<form action="zadaca_igraj.php" method="post">
        <?php
        if (isset($_SESSION['tablica1']) && isset($_SESSION['tablica2'])) {
            echo 'Ili odaberi željenu akciju:<br>';
            echo '<label for="reset"><input type="radio" id="reset" name="opcija" value="1"/>Pokreni sve ispočetka</label><br>';
            echo '<label for="brisi"><input type="radio" id="brisi" name="opcija" value="2" checked/>Obriši dijamant s pozicije (red, stupac) = ';
            echo '<select name="cont" id="cont1">';
            $m = count($_SESSION['tablica2']);
            $n = count($_SESSION['tablica2'][0]);
                for ($i = 0; $i < $m; $i++) {
                    for ($j = 0; $j < $n; $j++)
                        if ($_SESSION['tablica2'][$i][$j] === 'D') echo '<option value="' . $i . $j . '">' . '('. ($i + 1) . ', ' . ($j + 1) .')</option>';
                }
            echo '</select>' . '</label>' . '<br><br>' . '<button type="submit">Izvrši akciju!</button>'; 
        }
        ?>
	</form>
</body>
</html>