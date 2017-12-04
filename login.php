<!doctype html>
<html lang=''>
<head>
   <meta charset='utf-8'>
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="stylesheet" href="css/login.css">
   <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
   <script src="script/script.js"></script>
   <title></title>
</head>
<body>
<div class="center">
	<div id="panel">
		<?php
		session_start();
		require_once 'nazwadb.inc.php';
		require 'core/funkcje.php';
		$link = polaczZBaza($host, $uzytkownik, $haslo, $nazwabazydanych);
		
		if (isset($_GET['menu']) && ($_GET['menu'] == "logout")) {
			$_SESSION = array();
			session_destroy();
			echo "<script>
					if(typeof(parent) != 'undefined') {
						parent.window.location.href='login.php';
					} else {
						window.location.href='login.php';
					}
				</script>";
		}
		
      
		if (isset($_POST['flogin'])) {
			$flogin = $_POST['flogin'];
			$fpassword = $_POST['fpassword'];
			echo $flogin, $fpassword;
			$sql = "SELECT * FROM personel WHERE Login = '$flogin'";
			$wynik = mysqli_query($link, $sql) or die ('nie moge odczytacj bazy');
			if (mysqli_num_rows($wynik) > 0) {
				$wiersz = mysqli_fetch_assoc($wynik);
				if (($wiersz['Login'] == $flogin)  && ($wiersz['Haslo'] == $fpassword)) {
					$_SESSION['auth'] = 'true';
					$_SESSION['loginTime'] = time();
					$_SESSION['sImie'] = $wiersz['Imie'];
					$_SESSION['sNazwisko'] = $wiersz['Nazwisko'];
					$_SESSION['sId'] = $wiersz['id'];
					$_SESSION['sIdDzial'] = $wiersz['Dzial'];
					// uprawnienia wyciagamy w odwrotnej kolejności niż w bazie aby pracownik mial najnizsze 000001
					$_SESSION['sUpr'] = bindec($wiersz['uAdmin'].$wiersz['uPrez'].$wiersz['uKsieg'].$wiersz['uZampub'].$wiersz['uKier'].$wiersz['uPrac']);
					$_SESSION["loginTime"] = time();
					header ('Location: index.php');
				} else {
					unset($_POST['flogin']);
					header ('Location: login.php');
				}
			} else {
					unset($_POST['flogin']);
					header ('Location: login.php');
			}
		} else { ?>
				<form action="login.php" method ="post">
					<label for="username">Nazwa użytkownika:</label>
					<input type="text" id="username" name="flogin" required autofocus>
					<label for="password">Hasło:</label>
					<input type="password" id="password" name="fpassword" required>
					<div id="lower">
						<input type="submit" value="Zaloguj">
					</div>
				</form>
			<?php
		}
		?>
	</div>
</div>
</body>
</html>