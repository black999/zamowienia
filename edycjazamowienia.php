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

	$menu = (isset($_GET['menu'])) ? $_GET['menu'] : "";

	switch ($menu) {
		case 'usun':
			delTowarZZamowienia($link, $_GET['id']);
			break;

		case 'dodaj':
			addTowarDoZamowienia($link, $_GET['fIdzam'], $_POST['fIdTowar'], $_POST['fIlosc']); 
			break;

		case 'zapisz':
			updateIlosciTowarowNaZamowieniu($link, $_POST['fIlosc']);
			if ($_POST['kosztOpis'] != "") {
				updateKosztZamowienia($link, $_GET['fIdzam'], $_POST['kosztOpis'], $_POST['koszt']);
			}
			if ($_POST['opcja'] == 'zapisz') {
				$string = "Location: zamowienia.php?menu=lista&id=" . $_GET['fIdzam'];
				$string = "Location: szczegolyzam.php?fIdzam=" . $_GET['fIdzam'];
				header($string);
			}
			break;
		
		default:
			# code...
			break;
	}

	$towaryNaZam = getTowaryNaZamowieniu($link, $_GET['fIdzam']);
	$towary = getTowary($link, $sIdDzial);
	$wartoscZam = OblWartZam($_GET['fIdzam'], $link);	
	$zamowienie = getZamowienie($link, $_GET['fIdzam']);
	$fData = $zamowienie['Data'];
	$koszt = $zamowienie['koszt'];
	$kosztOpis = $zamowienie['kosztOpis'];

	include ('view/edycjazamowienia.view.php');





