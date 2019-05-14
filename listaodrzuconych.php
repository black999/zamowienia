<?php

	require 'core/init.php';
	
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
