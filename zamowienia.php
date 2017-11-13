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
require_once 'nazwadb.inc.php';
require 'funkcje.php';
session_start();
checkSesion();

$sNazwisko = $_SESSION['sNazwisko'];
$sImie = $_SESSION['sImie'];
$sId = $_SESSION['sId'];
$sUpr = $_SESSION['sUpr'];
$sIdDzial = $_SESSION['sIdDzial'];


$link = polaczZBaza($host, $uzytkownik, $haslo, $nazwabazydanych);
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
		// $fIdzam = $_GET['id'];
		
		// // $wartoscZam = 0;  
		// $wartoscZam = OblWartZam($fIdzam, $link); // wyliczanie wartosci zamownienia
		
		// $sql = "SELECT zm.StatusZatw, zm.Data, zm.akcKier, zm.akcZam, zm.akcKsie, zm.akcPrez, zm.StatusReal, zm.Info, p.Imie, p.Nazwisko 
		// from zamowienia zm 
		// inner join personel p on zm.Zamawiajacy = p.id 
		// WHERE zm.IdZamowienia ='" . $fIdzam . "'";
		// $result = mysqli_query($link, $sql);
		// $row = mysqli_fetch_assoc($result);
		// $fData = $row['Data'];
		// $ImNaz = $row['Imie'] . " " . $row['Nazwisko'];
		// $akcKier = $row['akcKier'];
		// $akcZam = $row['akcZam'];
		// $akcKsie = $row['akcKsie'];
		// $akcPrez = $row['akcPrez'];
		// $statusZatw = $row['StatusZatw'];
		// $statusReal = $row['StatusReal'];
		// $Info = $row['Info'];
			
		// echo "<DIV class='listwaZam'>";
		// 	echo "<span class='text'>Zamównienie z dnia :</span><span class='dane'> " . $fData . "</span><span class='text'>Zamawiający :
		// 	</span><span class='dane'>" . $ImNaz . "</span><span class='text'>Wartość zamówienia : </span><span class='dane'>" . $wartoscZam . " zł</span>";
		// echo "</DIV>";
		
		// echo "<DIV class='statusZam'>";     // spr status zamownienia i wyswietlamy możlowość zatwierdzenia i realizacji
		// 	echo "<span>Szczegóły zamówienia</span>";
		// 		if ($statusZatw == '0')  {
		// 			echo "<span></span><span style='color: red'>NIEZATWIERDZONE</span> ";
		// 			//jesli zamownienie ma wartosc zero nie jest możliwe zatwierdzenie
		// 			if ($wartoscZam > 0) echo "<span><BUTTON type='button'  class='button' onclick=\"window.location.href='zamowienia.php?menu=zatw&fIdzam=" . $fIdzam . "'\">  Zatwierdz  </BUTTON></span>";
		// 			echo "<BUTTON type='button'  class='button' onclick=\"window.location.href='edycjazamowienia.php?fIdzam=". $fIdzam ."'\">  Edytuj  </BUTTON>";
		// 		}
		// 		if ($statusZatw == '1') {
		// 			echo "<span></span><span style='color: green'>ZATWIERDZONE</span>";
		// 			if ($statusReal != 1 && $akcKier != 0 && $akcZam != 0 && $akcKsie != 0 && $akcPrez !=0 && (($_SESSION['sUpr'] & 4) == 4)) { // jesli zaakceptowane i mamy uprawnienia
		// 				echo "<span><BUTTON type='button'  class='button' onclick='document.frealizujZam.submit();'>  REALIZUJ  </BUTTON></span>";
		// 			}
  //                   if ($statusReal == '1') {
		// 			   echo "<span></span><span style='color: blue'> ZAKOŃCZONE </span>";
  //                   }
		// 		}
				
				
		// echo "</DIV>";
		
		// echo "<div class='lista'>";  //odczytanie danych
		// 		$sql = "SELECT z.IdZamowieniaTow, z.Ilosc, z.CenaRealizacji, t.cenaZak, t.nazwa, z.Ilosc*t.cenaZak as wartosc 
  //                       from zamowieniatow z
		// 				inner join towary t on z.Towar = t.Id
		// 				where z.IdZam = '" . $fIdzam . "' ORDER BY IdZamowieniaTow DESC";
		// 		$result = mysqli_query($link, $sql);
		// 		$lp = 1;
		// 		if (mysqli_num_rows($result) > 0) {     	// output data of each row
		// 		echo "<TABLE><TR><TH>L.p.</TH><TH>Nazwa towaru</TH><TH>Ilość</TH>";
  //               if ($akcKier != 0 && $akcZam != 0 && $akcKsie != 0 && $akcPrez !=0 && (($_SESSION['sUpr'] & 4) == 4)) {   
  //                   //jesli do realizacji i may uprawnienia to wyświetlamy naglowek ceny zakkupu
  //                   echo "<TH class='money'>Cena</TH>";
  //               }
  //               echo "<TH class='money'>Cena zakupu</TH><TH class='money'>Wartość</TH></TR>";
		// 		while($row = mysqli_fetch_assoc($result)) {
		// 			echo "<TR>";
		// 			echo "<TD style='width: 30px;'>" .$lp++ . "</TD>";
		// 			echo "<TD style='width: 300px;'>" . $row["nazwa"]. "</TD>";
		// 			echo "<TD>" . $row["Ilosc"]. "</TD>";
  //                   if ($akcKier != 0 && $akcZam != 0 && $akcKsie != 0 && $akcPrez !=0 && (($_SESSION['sUpr'] & 4) == 4)) {
  //                       //jesli do realizacji i mamy uprawnienia to wyświetlamy okno do wprowadzenie ceny zakupu
  //                       echo "<FORM action='zamowienia.php?menu=realizujZam&fIdzam=".$fIdzam ."' method='POST' name='frealizujZam'>";
  //                       echo "<TD class='money'><INPUT type='text' name='". $row{"IdZamowieniaTow"} ."' value='". $row["CenaRealizacji"]. "'maxlength=7 size=5></TD>";
  //                   }
		// 			echo "<TD class='money'>" . $row["cenaZak"]. "</TD>";
		// 			echo "<TD class='money'>" . $row["wartosc"]. "</TD>";
		// 			//echo "<TD class='opcje'><a href='zamowienia.php?menu=usun&id=" . $fIdzam . "&idZamtow=" . $row["IdZamowieniaTow"] . "'>usuń</TD>";
		// 			echo "</TR>";
		// 		}
  //                if ($akcKier != 0 && $akcZam != 0 && $akcKsie != 0 && $akcPrez !=0 && (($_SESSION['sUpr'] & 4) == 4)) {   
  //                   //jesli do realizacji konczymy formatke
  //                   //echo "<INPUT type='SUBMIT' >";    
  //                   echo "</FORM>";     //zakonczenie formatki do wpisania ceny zakupu 
  //               }    
  //               echo "<TR'><TD></TD><TD></TD><TD></TD><TD class='suma'>RAZEM :</TD><TD class='suma'>" . $wartoscZam . " zł</TD></TR>";
		// 		echo "</TABLE>";
		// 		} 
		// echo "</div>";		
		
		// if ($statusZatw == '1') {   //jesli zamownienie jest zatwierdzone wyświetlamy listę akceptacji i możliwość akceptacji
		// 	echo "<div class='listaAkceptacji'>";
		// 		echo "<p><h3>Akceptacje </h3></p>";
		// 		echo "<TABLE>";
				
		// 		echo "<TR>";
		// 		echo "<TD><b>Kierownik<b></TD>";
		// 		if ($akcKier == 0) {  //spr czy jest akceptacja 
		// 			echo "<TD style='color: red'>Brak</TD>";
		// 			if (($sUpr & 2 ) == 2)  { /// spr. czy mamy uprawnienia
		// 				echo "<TD><span><BUTTON type='button'  class='button' onclick=\"window.location.href='zamowienia.php?menu=akc&fIdzam=". $fIdzam ."&ftyp=kier'\">  Akceptuj  </BUTTON></span></TD> ";
		// 				echo "<TD><BUTTON type='button'  class='button-danger' onclick=\"window.location.href='zamowienia.php?menu=odrzucZam&fIdzam=". $fIdzam ."'\">  Odrzuć  </BUTTON></TD> ";
		// 			}
		// 		} else {
		// 			echo "<TD style='color: green'>Akceptacja</TD>
		// 					<TD>" . "  " . "&nbsp&nbsp Akceptujący :&nbsp&nbsp" . $personel[$akcKier]->Nazwisko . " " . $personel[$akcKier]->Imie ."</TD>";
		// 		}
		// 		echo "</TR>";
				
		// 		echo "<TR>";
		// 		echo "<TD><b>Zam. publiczne</b></TD>";
		// 		if ($akcZam == 0) { //spr czy jest akceptacja 
		// 			echo "<TD style='color: red'>Brak</TD>";
		// 			if ((($sUpr & 4 ) == 4) && ($akcKier != 0)) {  // spr czy mamy uprawnienia
		// 				echo "<TD><span><BUTTON type='button'  class='button' onclick=\"window.location.href='zamowienia.php?menu=akc&fIdzam=". $fIdzam ."&ftyp=zamp'\">  Akceptuj  </BUTTON></span></TD> ";
		// 				echo "<TD><BUTTON type='button'  class='button-danger' onclick=\"window.location.href='zamowienia.php?menu=odrzucZam&fIdzam=". $fIdzam ."'\">  Odrzuć  </BUTTON></TD> ";
		// 			}
		// 		} else {
		// 			echo "<TD style='color: green'>Akceptacja</TD>
		// 					<TD>" . "  " . "&nbsp&nbsp Akceptujący :&nbsp&nbsp" . $personel[$akcZam]->Nazwisko . " " . $personel[$akcZam]->Imie . "</TD>";
		// 		}				
		// 		echo "</TR>";
				
		// 		echo "<TR>";
		// 		echo "<TD><b>Księgowość</b></TD>";
		// 		if ($akcKsie == 0) { //spr czy jest akceptacja 
		// 			echo "<TD style='color: red'>Brak</TD>";
		// 			if ((($sUpr & 8 ) == 8) && ($akcKier != 0)) {  //spr czy mamy uprawnienia 
		// 				echo "<TD><span><BUTTON type='button'  class='button' onclick=\"window.location.href='zamowienia.php?menu=akc&fIdzam=". $fIdzam ."&ftyp=ksie'\">  Akceptuj  </BUTTON></span></TD> ";
		// 				echo "<TD><BUTTON type='button'  class='button-danger' onclick=\"window.location.href='zamowienia.php?menu=odrzucZam&fIdzam=". $fIdzam ."'\">  Odrzuć  </BUTTON></TD> ";
		// 			}
		// 		} else {
		// 			echo "<TD style='color: green'>Akceptacja</TD>
		// 					<TD>" . "  " . "&nbsp&nbsp Akceptujący :&nbsp&nbsp" . $personel[$akcKsie]->Nazwisko . " " . $personel[$akcKsie]->Imie . "</TD>";
		// 		}			
		// 		echo "</TR>";

		// 		echo "<TR>";
		// 		echo "<TD><b>Prezes</b></TD>";
		// 		if ($akcPrez == 0) { //spr czy jest akceptacja 
		// 			echo "<TD style='color: red'>Brak</TD>";
		// 			if ((($sUpr & 16 ) == 16) && ($akcZam != 0) && ($akcKsie != 0)) {   //spr czy mamy uprawnienia
		// 			echo "<TD><span><BUTTON type='button'  class='button' onclick=\"window.location.href='zamowienia.php?menu=akc&fIdzam=". $fIdzam ."&ftyp=prez'\">  Akceptuj  </BUTTON></span></TD> ";
		// 			echo "<TD><BUTTON type='button'  class='button-danger' onclick=\"window.location.href='zamowienia.php?menu=odrzucZam&fIdzam=". $fIdzam ."'\">  Odrzuć  </BUTTON></TD> ";
		// 			}
		// 		} else {
		// 			echo "<TD style='color: green'>Akceptacja</TD>
		// 					<TD>" . "  " . "&nbsp&nbsp Akceptujący :&nbsp&nbsp" . $personel[$akcPrez]->Nazwisko . " " . $personel[$akcPrez]->Imie . "</TD>";
		// 		}					
		// 		echo "</TR>";
		// 		echo "</TABLE>";
		// 		echo "<BR><BR>";
		// 		echo "<FORM action='zamowienia.php?menu=dodajInfo&fIdzam=" . $fIdzam . "' method='POST'>";
		// 			echo "<h3>Komentarz do zamówienia:</h3>";
		// 			echo "<textarea rows='5' cols='60' name='fInfo'>". $Info . "\n" . $sImie . " " . $sNazwisko . ":\n</textarea><br>";
		// 			echo "<input type='submit' class='button' value='Zapisz komentarz'>";
		// 		echo "</FORM>";
		// 	echo "</div>";
		// }
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
			//echo "<TD>" . $row["cenaZak"]. "</TD>";
			//echo "<TD>" . $row["StatusZatw"]. "</TD>";
			// echo "<TD class='money'><a href='zamowienia.php?menu=lista&id=" .$row["IdZamowienia"]. "'>Szczegóły</a></TD>";
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
// if  (($_GET['menu']) == 'usun') {  //usuwanie poszczegolnych towarow z zamownienia
// 	// $fIdzam = $_GET['id'];
// 	// $fIdzamTow = $_GET['idZamtow'];
// 	// $sql = "DELETE FROM zamowieniatow WHERE IdZamowieniaTow = '" . $fIdzamTow . "'";
// 	// mysqli_query($link, $sql);
// 	delTowarZZamowienia($link, $_GET['idZamtow']);
// 	$string = "Location: zamowienia.php?menu=dodaj&fIdzam=" . $_GET['id'];
// 	header($string);
// }
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
	// foreach (array_keys($_POST) as $IdZam) {
 //        $cenaRealizacji = str_replace(",",".",$_POST[$IdZam]); 
 //        $sql = "UPDATE zamowieniatow SET CenaRealizacji='" . $cenaRealizacji . "' WHERE IdZamowieniaTow = '" . $IdZam . "'";
 //        mysqli_query($link, $sql) or die ('Błąd zapisu do bazy. Error ' . mysql_errno());
 //    }
     $sql ="UPDATE zamowienia SET StatusReal='1' WHERE IdZamowienia='". $fIdzam . "'";
     mysqli_query($link, $sql) or die ('Błąd zapisu do bazy. Error ' . mysql_errno());
    $string = "Location: listadorealizacji.php";
	header($string); 
}
    

?>
   
</body>
</html>