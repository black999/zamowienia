
<!doctype html>
<html lang='pl'>
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="stylesheet" href="css/styles.css">
   <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
   <script src="script/script.js"></script>
   <title></title>
</head>
<body>
<?php
require_once 'nazwadb.inc.php';
require 'core/funkcje.php';

session_start();
checkSesion();

if ($_SESSION['sUpr'] == 1) {
	echo "<H1>Brak uprawnień</h1>";
	exit();
}

$link = mysqli_connect($host, $uzytkownik, $haslo, $nazwabazydanych);
if (!$link) {
	echo "Error: Unable to connect to MySQL." . PHP_EOL;
	echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
	echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
	exit;
}
mysqli_query($link, "SET NAMES utf8");
mysqli_query($link, "SET CHARACTER SET utf8");
mysqli_query($link, "SET collation_connection = utf8_polish_ci");

if  (($_GET['menu']) == 'dodaj') {
	if (isset($_POST['fNazwa'])) {
		$fNazwa = $_POST['fNazwa'];
		$query = "INSERT INTO dzialy VALUES (NULL, '$fNazwa')";
		//echo "dodano dzialy";
		mysqli_query($link, $query) or die ("Jakiś błąd");
	}


	echo "<div class='formatka'>";
		echo "<h2>Nowy Dział</h2><BR><BR>";
		echo "<FORM action='dzialy.php?menu=dodaj' method='POST'>";
		echo "<TABLE>";

		echo "<TR>";
		echo "<TD>Nazwa działu</TD>";
		echo "<TD><INPUT name='fNazwa' class='pole' size=35 maxlength=15 required></TD>";
		echo "</TR>";

		echo "<TR>";
		echo "<TD colspan='2' align='right'><INPUT type='SUBMIT' value='    DODAJ   '></TD>";
		echo "</TR>";

		echo "</TABLE>";
		echo "</FORM><BR><BR>";
	echo "</div>";

	echo "<div class='lista'>";  //odczytanie danych

		$sql = "SELECT * FROM dzialy ";
		$result = mysqli_query($link, $sql);

		if (mysqli_num_rows($result) > 0) {
			// output data of each row
		echo "<TABLE><TR><TH>ID</TH><TH>Nazwa</TH><TH>Opcje</TH></TR>";
		while($row = mysqli_fetch_assoc($result)) {
				echo "<TR>";
				echo "<TD style='width: 30px;'>" .$row["IdDzial"] . "</TD>";
				echo "<TD style='width: 300px;'>" . $row["Nazwa"]. "</TD>";
				echo "<TD><a href='dzialy.php?menu=edycja&id=" .$row["IdDzial"]. "'>Edycja</TD>";
				echo "</TR>";
			}
		echo "</TABLE>";
		} 
		mysqli_close($link);
	echo "</div>";
}

if  (($_GET['menu']) == 'edycja') {
			if (isset($_POST['fNazwa'])) {
			$fNazwa = $_POST['fNazwa'];
			$fIdDzial = $_GET['id'];
			
			$query = "UPDATE dzialy SET nazwa='$fNazwa' WHERE IdDzial='$fIdDzial'";
			//echo "dodano dzialy";
			mysqli_query($link, $query) or die ("Jakiś błąd");
			header("Location: dzialy.php?menu=dodaj");
		}
		$sql = "SELECT nazwa FROM dzialy WHERE IdDzial='".$_GET['id'] ."'";
		$result = mysqli_query($link, $sql);
		$row = mysqli_fetch_assoc($result);
		
		echo "<div class='formatka'>";
		echo "Edycja działu<BR><BR>";
		echo "<FORM action='dzialy.php?menu=edycja&id=".$_GET['id']."' method='POST'>";
		echo "<TABLE>";

		echo "<TR>";
		echo "<TD>Nazwa działu</TD>";
		echo "<TD><INPUT name='fNazwa' class='pole' size=35 maxlength=40 value='".$row["nazwa"]."'></TD>";
		echo "</TR>";

		echo "<TR>";
		echo "<TD colspan='2' align='right'><INPUT type='SUBMIT' value='    POPRAW   '></TD>";
		echo "</TR>";

		echo "</TABLE>";
		echo "</FORM><BR><BR>";
	echo "</div>";
	mysqli_close($link);	

}
?>
</body>
</html>