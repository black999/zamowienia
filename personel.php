<!doctype html>
<html lang='pl'>
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="stylesheet" href="styles.css">
   <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
   <script src="script.js"></script>
   <title></title>
</head>
<body>
<?php
require 'nazwadb.inc.php';
require 'funkcje.php';
session_start();
checkSesion();

$link = polaczZBaza($host, $uzytkownik, $haslo, $nazwabazydanych);

$personel = getPersonel($link);
// echo $personel[10]->Nazwisko;
// die(var_dump($personel));


if ($_SESSION['sUpr'] == 1) {
	echo "<H1>Brak uprawnień</h1>";
	exit();
}

if  (($_GET['menu']) == 'dodaj') {
	if (isset($_POST['fImie'])) {
		$fImie = $_POST['fImie'];
		$fNazwisko = $_POST['fNazwisko'];
		$femail = $_POST['femail'];
		$fLogin = $_POST['fLogin'];
		$fHaslo = $_POST['fHaslo'];
		$fDzial = $_POST['fDzial'];
		isset($_POST['fuPrac']) ? $fuPrac = $_POST['fuPrac'] : $fuPrac = '0';
		isset($_POST['fuKier']) ? $fuKier = $_POST['fuKier'] : $fuKier = '0';
		isset($_POST['fuZampub']) ? $fuZampub = $_POST['fuZampub'] : $fuZampub = '0';
		isset($_POST['fuKsieg']) ? $fuKsieg = $_POST['fuKsieg'] : $fuKsieg = '0';
		isset($_POST['fuPrez']) ? $fuPrez = $_POST['fuPrez'] : $fuPrez = '0';
		isset($_POST['fuAdmin']) ? $fuAdmin = $_POST['fuAdmin'] : $fuAdmin = '0';
		$query = "SELECT IdDzial from dzialy WHERE nazwa='$fDzial'";
		$result = mysqli_query($link, $query);
		$row=mysqli_fetch_assoc($result);
		$fIdDzial = $row['IdDzial'];
		//mysqli_free_result($result);
		$query = "INSERT INTO personel VALUES 
		(NULL, '$fImie', '$fNazwisko', '$femail', '$fLogin','$fHaslo','$fIdDzial', '$fuPrac', '$fuKier', '$fuZampub', '$fuKsieg', '$fuPrez', '$fuAdmin')";
		//echo "'$fImie', '$fNazwisko', '$femail', '$fLogin','$fHaslo','$fIdDzial', $fuPrac, $fuKier, $fuKsieg, $fuPrez, $fuAdmin, $fuZampub";
		mysqli_query($link, $query) or die ("Jakiś błąd" . mysqli_error($link));
	}


	echo "<div class='formatka'>";
		echo "<h2>Nowa osoba</h2><BR>";
		echo "<FORM action='personel.php?menu=dodaj' method='POST'>";
		echo "<TABLE>";

		echo "<TR>";
		echo "<TD>Imię</TD>";
		echo "<TD><INPUT name='fImie' class='pole' size=35 maxlength=20 required></TD>";
		echo "</TR>";

		echo "<TR>";
		echo "<TD>Nazwisko</TD>";
		echo "<TD><INPUT name='fNazwisko' class='pole' size=35 maxlength=25 required></TD>";
		echo "</TR>";
		
		echo "<TR>";
		echo "<TD>adres e-mail</TD>";
		echo "<TD><INPUT type='email' name='femail' class='pole' size=35 maxlength=45></TD>";
		echo "</TR>";		
		
		echo "<TR>";
		echo "<TD>Login</TD>";
		echo "<TD><INPUT name='fLogin' class='pole' size=35 maxlength=25 required></TD>";
		echo "</TR>";

		echo "<TR>";
		echo "<TD>Hasło</TD>";
		echo "<TD><INPUT name='fHaslo' type='password' class='pole' size=35 maxlength=25></TD>";
		echo "</TR>";
		
		echo "<TR>";
		echo "<TD>Wydział</TD>";
		echo "<TD><SELECT name='fDzial' class='pole'   maxlength=25>";
		$sql = "SELECT * FROM dzialy";
		$result = mysqli_query($link, $sql);
		while($row = mysqli_fetch_assoc($result)) {
			echo "<OPTION>".$row['Nazwa']."</OPTION>";
			}
		echo "</SELECT></TD>";
		echo "</TR>";
		
		echo "<TR>";
		echo "<TD><B>Poziom uprawnień : </B></TD>";
		echo "</TR>";		

		echo "<TR>";
		echo "<TD><INPUT type='checkbox' value='1' name='fuPrac'></TD>";
		echo "<TD>pracownik</TD>";
		echo "</TR>";		
		
		echo "<TR>";
		echo "<TD><INPUT type='checkbox' value='1' name='fuKier'></TD>";
		echo "<TD>kierownik</TD>";
		echo "</TR>";			
		
		echo "<TR>";
		echo "<TD><INPUT type='checkbox' value='1' name='fuZampub'></TD>";
		echo "<TD>zamównia publiczne</TD>";
		echo "</TR>";			
		
		echo "<TR>";
		echo "<TD><INPUT type='checkbox' value='1' name='fuKsieg'></TD>";
		echo "<TD>ksiegowość</TD>";
		echo "</TR>";			
		
		echo "<TR>";
		echo "<TD><INPUT type='checkbox' value='1' name='fuPrez'></TD>";
		echo "<TD>prezes</TD>";
		echo "</TR>";			
		
		echo "<TR>";
		echo "<TD><INPUT type='checkbox' value='1' name='fuAdmin'></TD>";
		echo "<TD>administrator</TD>";
		echo "</TR>";		
		
		echo "<TR>";
		echo "<TD colspan='2' align='right'><INPUT type='SUBMIT' value='    DODAJ   '></TD>";
		echo "</TR>";

		echo "</TABLE>";
		echo "</FORM><BR>";
	echo "</div>";

		echo "<div class='lista'>";  //odczytanie danych
			//if (!(isset($_POST['fNazwisko']))) $_POST['fNazwisko'] = '';
			$sql = "SELECT p.id, p.Imie, p.Nazwisko, p.Login, d.Nazwa, 
				CONCAT(p.uAdmin, p.uPrez, p.uKsieg, p.uZampub, p.uKier, p.uPrac) as upr
				FROM personel p, dzialy d where p.Dzial = d.IdDzial ORDER BY p.id DESC";

			$result = mysqli_query($link, $sql);
			if (mysqli_num_rows($result) > 0) {
				// output data of each row
			echo "<TABLE>
					<TR>
						<TH>ID</TH>
						<TH>Imię</TH>
						<TH>Nazwisko</TH>
						<TH>Login</TH>
						<TH>Dział</TH>
						<TH>Uprawnienia</TH>
						<TH>Opcje</TH>
					</TR>";
			while($row = mysqli_fetch_assoc($result)) {
				echo "<TR>";
				echo "<TD style='width: 30px;'>" .$row["id"] . "</TD>";
				echo "<TD style='width: 100px;'>" . $row["Imie"]. "</TD>";
				echo "<TD style='width: 100px;'>" . $row["Nazwisko"]. "</TD>";
				echo "<TD style='width: 100px;'>" . $row["Login"]. "</TD>";
				//echo "<TD style='width: 100px;'>" . $row["Haslo"]. "</TD>";
				echo "<TD>" . $row["Nazwa"]. "</TD>";
				echo "<TD>" . $row["upr"] . "</TD>";
				echo "<TD><a href='personel.php?menu=edycja&id=" .$row["id"]. "'> Edycja</TD>";
				echo "</TR>";
			}
			echo "</TABLE>";
			} 
			mysqli_close($link);
		echo "</div>";
}
if  (($_GET['menu']) == 'lista') {
		echo "<div class='formatka'>";        //formatka wyszukiwania po nazwisku
			echo "<FORM action='personel.php?menu=lista' method='POST'>";
			echo "<TABLE>";

			echo "<TR>";
			echo "<TD>Nazwisko </TD>";
			echo "<TD><INPUT name='fNazwisko' class='pole' size=35 maxlength=40></TD>";
					
			echo "<TD colspan='2' align='right'><INPUT type='SUBMIT' value='    SZUKAJ   '></TD>";
			echo "</TR>";

			echo "</TABLE>";
			echo "</FORM><BR><BR>";
		echo "</div>";
		echo "<div class='lista'>";  //odczytanie danych personelu
			if (!(isset($_POST['fNazwisko']))) $_POST['fNazwisko'] = ''; //uprawnienie wyciagamy w odwrotnej kolejnosci aby pracownik mial najnizsze 00001
			$sql = "SELECT p.id, p.Imie, p.Nazwisko, p.Login, d.Nazwa,
					CONCAT(p.uAdmin, p.uPrez, p.uKsieg, p.uZampub, p.uKier, p.uPrac) as upr  
					FROM personel p
					inner join dzialy d ON p.Dzial = d.IdDzial
					where p.Nazwisko LIKE '%" . $_POST['fNazwisko'] . "%'
					order by d.Nazwa";
			$result = mysqli_query($link, $sql);
			if (mysqli_num_rows($result) > 0) {   // output data of each row
			echo "<TABLE>
					<TR>
						<TH>Dział</TH>
						<TH>Nazwisko</TH>
						<TH>Imię</TH>
						<TH>Login</TH>
						<TH>Uprawnienia</TH>
						<TH>Opcje</TH>
					</TR>";
			while($row = mysqli_fetch_assoc($result)) {
				echo "<TR>";
				echo "<TD>" . $row["Nazwa"]. "</TD>";
				echo "<TD style='width: 100px;'>" . $row["Nazwisko"]. "</TD>";
				echo "<TD style='width: 100px;'>" . $row["Imie"]. "</TD>";
				echo "<TD style='width: 100px;'>" . $row["Login"]. "</TD>";
				//echo "<TD style='width: 100px;'>" . $row["Haslo"]. "</TD>";
				echo "<TD>" . $row["upr"] . "</TD>";
				echo "<TD><a href='personel.php?menu=edycja&id=" .$row["id"]. "'>Edycja</TD>";
				echo "</TR>";
			}
			echo "</TABLE>";
			} 
			mysqli_close($link);
		echo "</div>";
}
if  (($_GET['menu']) == 'edycja') {
	if (isset($_POST['fImie'])) {   //jesli jest zmienna fImie to updatujemy baze personelu i przechodzimy do listy personelu
			$id = $_GET['id'];
			$fImie = $_POST['fImie'];
			$fNazwisko = $_POST['fNazwisko'];
			$femail = $_POST['femail'];
			$fLogin = $_POST['fLogin'];
			$fHaslo = $_POST['fHaslo'];
			//$fDzial = $_POST['fDzial'];
			$fid = $_POST['fDzial'];
			isset($_POST['fuPrac']) ? $fuPrac = $_POST['fuPrac'] : $fuPrac = '0';
			isset($_POST['fuKier']) ? $fuKier = $_POST['fuKier'] : $fuKier = '0';
			isset($_POST['fuZampub']) ? $fuZampub = $_POST['fuZampub'] : $fuZampub = '0';
			isset($_POST['fuKsieg']) ? $fuKsieg = $_POST['fuKsieg'] : $fuKsieg = '0';
			isset($_POST['fuPrez']) ? $fuPrez = $_POST['fuPrez'] : $fuPrez = '0';
			isset($_POST['fuAdmin']) ? $fuAdmin = $_POST['fuAdmin'] : $fuAdmin = '0';
			//$query = "SELECT IdDzial from dzialy WHERE nazwa='$fDzial'";
			//$result = mysqli_query($link, $query);
			//$row=mysqli_fetch_assoc($result);
			//$fid = $row['IdDzial'];
			//mysqli_free_result($result);
			$query = "UPDATE personel SET 
				Imie='$fImie', 
				Nazwisko='$fNazwisko', 
				email='$femail',
				Login='$fLogin', 
				Haslo='$fHaslo', 
				Dzial='$fid',
				uPrac='$fuPrac',
				uKier='$fuKier',
				uZampub='$fuZampub',
				uKsieg='$fuKsieg',
				uPrez='$fuPrez',
				uAdmin='$fuAdmin'
				WHERE id='$id'";
			mysqli_query($link, $query) or die ("Jakiś błąd" . mysqli_error($link));
			header("Location: personel.php?menu=lista");
		}
		$sql = "SELECT * FROM personel WHERE id='".$_GET['id'] ."'";
		$result = mysqli_query($link, $sql);
		$row = mysqli_fetch_assoc($result);
		
		echo "<div class='formatka'>";
		echo "Edycja personelu <BR><BR>";
		echo "<FORM action='personel.php?menu=edycja&id=".$_GET['id']."' method='POST'>";
		echo "<TABLE>";

		echo "<TR>";
		echo "<TD>Imię </TD>";
		echo "<TD><INPUT name='fImie' class='pole' size=35 maxlength=20 value='".$row['Imie']."' required></TD>";
		echo "</TR>";

		echo "<TR>";
		echo "<TD>Nazwisko</TD>";
		echo "<TD><INPUT name='fNazwisko' class='pole' size=35 maxlength=30 value='".$row['Nazwisko']."'required ></TD>";
		echo "</TR>";
		
		echo "<TR>";
		echo "<TD>adres e-mail</TD>";
		echo "<TD><INPUT type='email' name='femail' class='pole' size=35 maxlength=45 value='" .$row['email']. "'required></TD>";
		echo "</TR>";	
		
		echo "<TR>";
		echo "<TD>Login</TD>";
		echo "<TD><INPUT name='fLogin' class='pole' size=35 maxlength=25 value='".$row['Login']."' required></TD>";
		echo "</TR>";

		echo "<TR>";
		echo "<TD>Hasło</TD>";
		echo "<TD><INPUT name='fHaslo' type='password' class='pole' size=35 maxlength=25 value='".$row['Haslo']."'></TD>";
		echo "</TR>";

		echo "<TR>";
		echo "<TD>Wydział</TD>";
		echo "<TD><SELECT name='fDzial' class='pole'   maxlength=25>";
		$sql = "SELECT * FROM dzialy";
		$result = mysqli_query($link, $sql);
		while($row2 = mysqli_fetch_assoc($result)) {
			echo "<OPTION value='". $row2['IdDzial']."' ";
			if ($row2['IdDzial'] == $row['Dzial']) echo "selected";
			echo ">".$row2['Nazwa']."</OPTION>";
			}
		echo "</SELECT></TD>";
		echo "</TR>";
		
		echo "<TR>";
		$checked = ($row['uPrac'] == 1) ? 'checked' : "";
		echo "<TD><INPUT type='checkbox' value='1' name='fuPrac'". $checked ."></TD>" ;
		echo "<TD>pracownik</TD>";
		echo "</TR>";		
		
		echo "<TR>";
		echo "<TD><INPUT type='checkbox' value='1' name='fuKier'";
		if ($row['uKier'] == '1') echo " checked";
		echo "></TD>";
		echo "<TD>kierownik</TD>";
		echo "</TR>";			
		
		echo "<TR>";
		echo "<TD><INPUT type='checkbox' value='1' name='fuZampub'";
		if ($row['uZampub'] == '1') echo " checked";
		echo "></TD>";
		echo "<TD>zamównia publiczne</TD>";
		echo "</TR>";			
		
		echo "<TR>";
		echo "<TD><INPUT type='checkbox' value='1' name='fuKsieg'";
		if ($row['uKsieg'] == '1') echo " checked";
		echo "></TD>";
		echo "<TD>ksiegowość</TD>";
		echo "</TR>";			
		
		echo "<TR>";
		echo "<TD><INPUT type='checkbox' value='1' name='fuPrez'";
		if ($row['uPrez'] == '1') echo " checked";
		echo "></TD>";
		echo "<TD>prezes</TD>";
		echo "</TR>";			
		
		echo "<TR>";
		echo "<TD><INPUT type='checkbox' value='1' name='fuAdmin'";
		if ($row['uAdmin'] == '1') echo " checked";
		echo "></TD>";
		echo "<TD>administrator</TD>";
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