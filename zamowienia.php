<!doctype html>
<html lang='pl'>
<head>
	<?php require ("view/naglowek.view.php"); ?>
	<title></title>
</head>
<body>
<?php
require_once 'nazwadb.inc.php';
require 'core/funkcje.php';
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