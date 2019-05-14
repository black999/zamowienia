<?php

	require 'core/init.php';

	$zamowieniaAll = getZamowienia ($link, 'StatusReal = 1'); // StatusReal = 1 - zrealizowane

	if (!$zamowieniaAll) {
		$errorMessage = 'Brak zamówień zrealizowanych';
	}

	$tytul = 'Lista zamówień zrealizowanych';
	$widok = 'okno';

	include ('view/listazamowien.view.php');	