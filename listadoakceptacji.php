<?php

	require 'core/init.php';

	$sNazwisko = $_SESSION['sNazwisko'];
	$sImie = $_SESSION['sImie'];
	$sId = $_SESSION['sId'];
	$sUpr = $_SESSION['sUpr'];
	$sIdDzial = $_SESSION['sIdDzial'];
	$warunek = getWarunekByUprawnienia($sUpr, $sId, $sIdDzial);
	$zamowieniaAll = getZamowienia($link, $warunek); 

	if (!$zamowieniaAll) {
		$errorMessage = 'Brak zamówień do akceptacji';
	} else {
		foreach ($zamowieniaAll as $zamowienie) {
			switch ($zamowienie->StatusReal) {
				case '0':
					$zamowienie->StatusReal = 'Otwarte';
					break;
				case '1':
					$zamowienie->StatusReal = 'Zrealizowane';
					break;
				case '3':
					$zamowienie->StatusReal = 'Odrzucone';
					break;
				default:
					# code...
					break;
			};
		};
	}

	$tytul = 'Lista zamówień do akceptacji';

	include ('view/listazamowien.view.php');	
