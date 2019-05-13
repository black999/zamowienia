<?php

	require 'core/init.php';

	$sNazwisko = $_SESSION['sNazwisko'];
	$sImie = $_SESSION['sImie'];
	$sId = $_SESSION['sId'];
	$sUpr = $_SESSION['sUpr'];
	$sIdDzial = $_SESSION['sIdDzial'];

	$zamowieniaAll = getZamowienia($link, "t.id = {$_GET['id']}"); 
	$towar = getTowar($link, $_GET['id']);
	if (!$zamowieniaAll) {
		$errorMessage = "Brak zamówień dla towaru <b>{$towar['nazwa']}</b>";
	}

	$tytul = "Lista zamówień na których znajduje się <b>{$towar['nazwa']}</b>";
	$widok = 'okno';

	include ('view/listazamowien.view.php');	