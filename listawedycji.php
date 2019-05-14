<?php

	require 'core/init.php';
	
	$warunek = "StatusZatw = 0 AND p.id = {$sId}";
	$zamowieniaAll = getZamowienia($link, $warunek); // 

	if (!$zamowieniaAll) {
		$errorMessage = 'Brak zamówień w edycji';
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
	
	$tytul = 'Lista zamówień w edycji';

	include ('view/listazamowien.view.php');	
