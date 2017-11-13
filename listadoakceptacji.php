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

	$sUpr = ($sUpr & 31);  //filturjemy wszystkie uprawnienia bez uprawnie administratora 011111
	$pracownik = (($sUpr & 1) == '1') ? TRUE : FALSE;

	$link = polaczZBaza($host, $uzytkownik, $haslo, $nazwabazydanych);
	if ($sUpr == 1) { // jeśli tylko pracownik 
		$warunek = "StatusZatw = 1 AND StatusReal = 0 AND akcPrez = 0 AND p.id = {$sId}"; // StatusZatw = 1 - zamowienia zatwierdzone
	}
	if ($sUpr > 1 && $sUpr < 4) { // jeśli tylko kierownik
		if ($pracownik){   //jesli jednoczesnie pracownik to wyświetlamy wszystkie zmownienia łącznie z naszymi akceptacjami
			$warunek = "StatusZatw = 1 AND StatusReal = 0 AND akcPrez = 0 AND p.id = {$sId}"; // StatusZatw = 1 - zamowienia zatwierdzone
		} else {
			$warunek = "StatusZatw = 1 AND StatusReal = 0 AND Dzial = {$sIdDzial} AND akcKier = 0"; // StatusZatw = 1 - zamowienia zatwierdzone
		}
	}
	if ($sUpr >= 4) { // jeśli więcej niż kierownik
		$warunekOR = "";
		$warunek = "StatusZatw = 1 AND StatusReal = 0 AND akcPrez = 0 ";
		if (($sUpr & 16 ) == 16) {
			$warunekOR = "akcKier != 0"; // aby prezes mógł akceptować musi być akceptacja kierwonika
		}
		if (($sUpr & 8 ) == 8) {
			if (!$pracownik) {
				$warunekOR = (!isset($warunekOR)) ? "akcKsie = 0": " {$warunekOR} OR akcKsie = 0" ; // StatusZatw = 1 - zamowienia zatwierdzone
			}
		}
		if (($sUpr & 4 ) == 4) {
			if (!$pracownik) {
				$warunekOR = (!isset($warunekOR)) ? "akcZam = 0" : " {$warunekOR} OR akcZam = 0"; // StatusZatw = 1 - zamowienia zatwierdzone
			}
		}
		if ($warunekOR != "") {
			$warunek = $warunek . " AND ( " . $warunekOR . " )";
		}
	}
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
