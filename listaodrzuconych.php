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
	$warunek = "StatusReal = 3 AND p.id = {$sId}";
	$zamowieniaAll = getZamowienia($link, $warunek); // StatusReal = 3 - odrzucone

	if (!$zamowieniaAll) {
		$errorMessage = 'Brak zamówień odrzuconych';
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

	$tytul = 'Lista zamówień odrzuconych';

	include ('view/listazamowien.view.php');	
