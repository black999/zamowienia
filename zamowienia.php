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
require 'core/init.php';

$sNazwisko = $_SESSION['sNazwisko'];
$sImie = $_SESSION['sImie'];
$sId = $_SESSION['sId'];
$sUpr = $_SESSION['sUpr'];
$sIdDzial = $_SESSION['sIdDzial'];

$personel = getPersonel($link);

$menu = (isset($_GET['menu'])) ? $_GET['menu'] : "";

switch ($menu) {
	case 'zapisz':
		$fIdzam = addZamowienie($link, $_POST['fData'], $_POST['fIdZamOsoba']);
		$string = "Location: edycjazamowienia.php?fIdzam=" . $fIdzam;
		header($string);
		break;
	
	default:
		# code...
		break;
}

if  (($_GET['menu']) == 'dodaj'){  //dodawanie nowego zamownienia
	echo "<div class='formatka'>";
	echo "<h2>Nowe zamówienie</h2><BR><BR>";
	echo "<FORM action='zamowienia.php?menu=zapisz' method='POST'>";
	echo "<TABLE>";

	echo "<TR>";
	echo "<TD>Data</TD>";
	echo "<TD><INPUT type='date' name='fData' class='pole' size=10 maxlength=20 value='" . date('Y-m-d') ."'></TD>";
	echo "</tr>";
	echo "<tr>";
	echo "<TD>Zamawiający: </td><td><STRONG>" . $sImie . " " . $sNazwisko.  "</STRONG></TD>";
	echo "</tr>";
	echo "<tr></tr>";
	echo "<tr>";
	echo "<TD><INPUT type='SUBMIT' class='button' value='    DODAJ   '></TD>";
	echo "<INPUT type='hidden' name=fIdZamOsoba value='" . $sId . "'";
	echo "</TR>";

	echo "</TABLE>";
	echo "</FORM><BR><BR>";
	echo "</div>";
} 
if  (($_GET['menu']) == 'lista') {  //lista zamownienia
	if (isset($_GET['id'])) {   //jesli podano id wyświetlamy towary z zamownienia o  numerze id
		
	} else {
	$warunek2 = ($_SESSION['sUpr'] < 4) ? " WHERE Dzial='".$_SESSION['sIdDzial']."'" : ""; //jesli tylko kierownik to moze wybierac z personelu oboby ze swojego dzialu
	$sql = "SELECT * FROM personel" .$warunek2;				//Jesli nie podano id wyświetlamy wszystkie zamownienia
	$sqlPersonel = mysqli_query($link, $sql);
			
	echo "<div class='formatka'>";
		echo "<FORM action='zamowienia.php?menu=lista' method='POST' name='lista'>";
		echo "<TABLE>";

		echo "<TR>";
		if ($_SESSION['sUpr'] > 1) {    //sprwdzamy uprawnienia jesli powyzej pracownika to moze filtrowac osoby
			echo "<TD>Zamawiający: </TD>";
			echo "<TD><SELECT name='fIdZamOsoba' class='pole'>";
            //echo "<TD><SELECT name='fIdZamOsoba' class='pole' onChange='document.lista.submit();'>";
			echo "<OPTION></OPTION>";
			while($row = mysqli_fetch_assoc($sqlPersonel)) {
				echo "<OPTION value='" .  $row['id'] . "'>" . $row['Imie'] . " " . $row['Nazwisko'] . "</OPTION>";
			}
			echo "</SELECT></TD>";
		}
		
		echo "<TD>Status: </TD>";
		echo "<TD><SELECT name='fStatusZatw' class='pole'>";
		echo "<OPTION></OPTION>";
		echo "<OPTION value='1'>Zatwierdzone</OPTION>";
		echo "<OPTION value='0'>Niezatwierdzone</OPTION>";
		echo "</SELECT></TD>";
		
		echo "<TD><INPUT type='SUBMIT' value='    POKAŻ   '></TD>";
		echo "</TABLE>";
		echo "</FORM><BR><BR>";
	echo "</div>";

	if ($_SESSION['sUpr'] < 4 ) $warunek = "(p.Dzial = '".$_SESSION['sIdDzial']."')"; // jesli poziom upr mniejszy od 000100 to kierownik
	if ($_SESSION['sUpr'] == 1) $warunek = "(p.id = '".$_SESSION['sId']."')";  //sprawdzamy uprawnienia jesli 1 to tylko pracownik

	
	if ((isset($_POST['fIdZamOsoba'])) && ($_POST['fIdZamOsoba'] != "")) {   //sprawdzamy czy jest filtr na osobie
		$fIdZamOsoba = $_POST['fIdZamOsoba'];
		if (isset($warunek)) {
			$warunek = $warunek . " and " . "(p.id ='" . $fIdZamOsoba . "')";
		} else {
			$warunek = "(p.id ='" . $fIdZamOsoba . "')";
		}
	}
	
	if ((isset($_POST['fStatusZatw'])) && ($_POST['fStatusZatw'] != "")) {   // sprawdzamy czy jest filtr na statusie zatwierdzenia
		$fStatusZatw = $_POST['fStatusZatw'];
		if (isset($warunek)) {
			$warunek = $warunek . " and " . "(z.StatusZatw ='" . $fStatusZatw . "')";
		} else {
			$warunek = "(z.StatusZatw ='" . $fStatusZatw . "')";
		}
	}
	
	if (isset($_GET['fdoAkc'])) {   // spr czy zmienna fdoAkc jest jeśli tak to filtrujemy tylko nie zaakceptowane
		if (isset($warunek)) {
			$warunek = $warunek . " and " . "(z.StatusZatw='1') and (z.akcKier='0' or z.akcZam='0' or z.akcKsie='0' or z.akcPrez='0')";  // filtrujemy zamownienia zatwierdzone i nie zaakceptowane
		} else {
			$warunek = "(z.StatusZatw='1') and (z.akcKier='0' or z.akcZam='0' or z.akcKsie='0' or z.akcPrez='0')";
		}
	}
	
	if (isset($_GET['fdoRea'])) {   // spr czy zmienna fdoRea jest jeśli tak to filtrujemy tylko nie zrealizowane
		if (isset($warunek)) {
			$warunek = $warunek . " and " . "(z.StatusReal='0' and z.StatusZatw='1' and z.akcKier <> '0' and z.akcZam <> '0' and z.akcKsie<>'0' and z.akcPrez<>'0')";  // filtrujemy zamownienia zatwierdzone i nie zaakceptowane
		} else {
			$warunek = "(z.StatusReal='0' and z.StatusZatw='1' and z.akcKier <> '0' and z.akcZam <> '0' and z.akcKsie<>'0' and z.akcPrez<>'0')";
		}
	}
	
	$warunek = (isset($warunek)) ? "( ". $warunek . " ) and" : "";
	$sql = "SELECT	z.StatusZatw, z.IdZamowienia, z.Data, z.akcKier, z.akcZam, z.akcKsie, z.akcPrez,
			z.StatusReal, z.Info, p.id, p.Nazwisko, p.Imie, p.Dzial, d.Nazwa 
		 	from zamowienia z
		 	inner join personel p on z.Zamawiajacy = p.id
		 	inner join dzialy d on p.Dzial =d.IdDzial 
		 	WHERE (" . $warunek . "( z.StatusReal != 3))
		 	ORDER BY z.Data";	
	
	echo "<div class='lista'>";  // lista zamowien
		//echo $sql;
		$result = mysqli_query($link, $sql);
		echo "<TABLE><TR><TH>Data zam.</TH><TH>Zamawiający</TH><TH>Dział</TH><TH class='money'>Wartość</TH><TH>Status</TH><TH>Akceptacje</TH><TH class='opcje'>Opcje</TH></TR>";
		if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)) {
			$listaAkc = "";
			if ($row['akcKier'] != '0') $listaAkc = $listaAkc . "Kier ----> ";
			if ($row['akcZam'] != '0') $listaAkc = $listaAkc . "Zam ----> ";
			if ($row['akcKsie'] != '0') $listaAkc = $listaAkc . "Księ ----> ";
			if ($row['akcPrez'] != '0') $listaAkc = $listaAkc . "Prez";
			$sql = "SELECT z.Ilosc*t.cenaZak as wartosc from    
						zamowieniatow z
						inner join towary t on z.Towar = t.Id
						where z.IdZam = '" . $row['IdZamowienia'] . "'";
			$result2 = mysqli_query($link, $sql);                                //wyliczenie wartosci zamowienia 
			$wartoscZam = 0;
			$wartoscZam = number_format($wartoscZam,2,'.','');
			if (mysqli_num_rows($result2) > 0) {
				while($row2 = mysqli_fetch_assoc($result2)) {
					$wartoscZam = $wartoscZam + $row2['wartosc'];
				}
			} 
			echo "<TD>" .$row["Data"] . "</TD>";
			echo "<TD>" . $row["Imie"]. " " . $row['Nazwisko'] . "</TD>";
			echo "<TD>" . $row["Nazwa"]. "</TD>";
			echo "<TD class='money'>" . $wartoscZam . " zł</TD>";
			if ($row["StatusZatw"] == '0') {
				echo "<TD style='color: red'>Niezatwierdzone</TD>";
			}
            if ($row["StatusZatw"] == '1') {
                if ($row["StatusReal"] == '1') {
				    echo "<TD style='color: blue'>Zakończone</TD>";
                } else {
                    echo "<TD style='color: green'>Zatwierdzone</TD>";
                }
			}
			echo "<TD>" . $listaAkc . "</TD>";
			echo "<TD class='money'><a href='szczegolyzam.php?fIdzam=" .$row["IdZamowienia"]. "'>Szczegóły</a></TD>";
            if ($row["StatusZatw"] == '0') {
                //echo "<TD class='money' ><a href='zamowienia.php?menu=usunZam&id=" .$row["IdZamowienia"]. "' style='color: red'>Usuń</a></TD>";
                echo "<TD class='money' ><a href='zamowienia.php?menu=usunZam&id=" .$row["IdZamowienia"]. "' style='color: red' onclick=\"return confirm('Czy na pewno usunąć pozycję ?');\"><img src='images/kasuj.gif'</a></TD>";
            }
            echo "</TR>";
		}
		echo "</TABLE>";
		} 
		mysqli_close($link);
	echo "</div>";
	}
}

if  (($_GET['menu']) == 'usunZam') {  //usuwanie  zamownienia
	$fIdzam = $_GET['id'];
	$sql = "DELETE FROM zamowienia WHERE IdZamowienia = '" . $fIdzam . "'";
	mysqli_query($link, $sql);
	$string = "Location: listawedycji.php";
	header($string);
}

if (($_GET['menu']) == 'odrzucZam'){
	$sql = "UPDATE zamowienia SET StatusReal='3' WHERE IdZamowienia = '" . $_GET['fIdzam'] . "'";
	mysqli_query($link, $sql);
	$string = "Location: zamowienia.php?menu=lista";
	header($string);
}

if (($_GET['menu']) == 'dodajInfo'){
	dodajInfo($link, $_POST['fInfo'], $_GET['fIdzam']);
	$string = "Location: zamowienia.php?menu=lista&id=" . $_GET['fIdzam'];
	header($string);
}
if  (($_GET['menu']) == 'zatw') {  //zatwierdzanie zamownienia 
	$fIdzam = $_GET['fIdzam'];
	$sql = "UPDATE zamowienia SET StatusZatw='1' WHERE IdZamowienia = '" . $fIdzam . "'";
	//echo $sql;
	mysqli_query($link, $sql) or die ('Błąd zapisu do bazy. Error ' . mysql_errno());
	// $string = "Location: zamowienia.php?menu=lista&id=" . $fIdzam;
	$string = "Location: szczegolyzam.php?fIdzam=" . $fIdzam;
	header($string);
}	
if  (($_GET['menu']) == 'akc') {
	$fIdzam = $_GET['fIdzam'];
	$ftyp = $_GET['ftyp'];
	if ($ftyp == 'kier') $sql = "UPDATE zamowienia SET akcKier='" . $_SESSION['sId'] . "', dataKier='" . date('Y-m-d') . "' WHERE IdZamowienia = '" . $fIdzam . "'";
	if ($ftyp == 'zamp') $sql = "UPDATE zamowienia SET akcZam='" . $_SESSION['sId'] . "', dataZam='" . date('Y-m-d') . "' WHERE IdZamowienia = '" . $fIdzam . "'";
	if ($ftyp == 'ksie') $sql = "UPDATE zamowienia SET akcKsie='" . $_SESSION['sId'] . "', dataKsie='" . date('Y-m-d') . "' WHERE IdZamowienia = '" . $fIdzam . "'";
	if ($ftyp == 'prez') $sql = "UPDATE zamowienia SET akcPrez='" . $_SESSION['sId'] . "', dataPrez='" . date('Y-m-d') . "' WHERE IdZamowienia = '" . $fIdzam . "'";	
	mysqli_query($link, $sql) or die ('Błąd zapisu do bazy. Error ' . mysql_errno());
	// $string = "Location: zamowienia.php?menu=lista&id=" . $fIdzam;
	$string = "Location: szczegolyzam.php?fIdzam=" . $fIdzam;
	header($string);
}
if  (($_GET['menu']) == 'realizujZam') {
	$fIdzam = $_GET['fIdzam'];
    $sql ="UPDATE zamowienia SET StatusReal='1' WHERE IdZamowienia='". $fIdzam . "'";
    mysqli_query($link, $sql) or die ('Błąd zapisu do bazy. Error ' . mysql_errno());
    $string = "Location: listadorealizacji.php";
	header($string); 
}
    

?>
   
</body>
</html>