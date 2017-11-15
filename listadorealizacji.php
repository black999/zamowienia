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

	$sUpr = ($sUpr & 31);  //filturjemy wszystkie uprawnienia bez uprawnie administratora 011111

	$link = polaczZBaza($host, $uzytkownik, $haslo, $nazwabazydanych);

	if ($sUpr == 1) { // jeśli tylko pracownik 
		$warunek = "StatusZatw = 1 AND StatusReal = 0 AND akcPrez !=0 AND p.id = {$sId}"; // StatusZatw = 1 - zamowienia zatwierdzone
	}
	if ($sUpr > 1 && $sUpr < 4) { // jeśli tylko kierownik
		$warunek = "StatusZatw = 1 AND StatusReal = 0 AND akcPrez !=0 AND Dzial = {$sIdDzial}"; // StatusZatw = 1 - zamowienia zatwierdzone
	}
	if ($sUpr >= 4) { // jeśli więcej niż kierownik
		$warunek = "StatusZatw = 1 AND StatusReal = 0 AND akcPrez !=0";
	}

	$zamowieniaAll = getZamowienia($link, $warunek); 

	if (!$zamowieniaAll) {
		$errorMessage = 'Brak zamówień do realizacji';
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

	$tytul = 'Lista zamówień do realizacji';

	include ('view/listazamowien.view.php');	
