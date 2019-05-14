<?php 
	require 'core/init.php';

	if ((isset($_GET['menu'])) and ($_GET['menu'] == 'dodajInfo')){
		dodajInfo($link, $_POST['fInfo'], $_GET['fIdzam']);
		$string = "Location: ?fIdzam=" . $_GET['fIdzam'];
		header($string);
	}

	$wartoscZam = OblWartZam($_GET['fIdzam'], $link); // wyliczanie wartosci zamownienia
	$zamowienie = getZamowienie($link, $_GET['fIdzam']);
	$towaryNaZam = getTowaryNaZamowieniu($link, $_GET['fIdzam']);
	$fData = $zamowienie['Data'];	
	$koszt = $zamowienie['koszt'];
	$kosztOpis = $zamowienie['kosztOpis'];
	$personel = getPersonel($link);
	$lp = 1; //licznik

	include ('view/szczegolyzam.view.php');