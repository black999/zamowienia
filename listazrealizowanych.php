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
	$zamowieniaAll = getZamowienia ($link, 'StatusReal = 1'); // StatusReal = 1 - zrealizowane

	if (!$zamowieniaAll) {
		$errorMessage = 'Brak zamówień zrealizowanych';
	}

	$tytul = 'Lista zamówień zrealizowanych';
	$widok = 'okno';

	include ('view/listazamowien.view.php');	