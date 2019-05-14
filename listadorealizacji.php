<?php

	require 'core/init.php';

	$sUpr = ($sUpr & 31);  //filturjemy wszystkie uprawnienia bez uprawnie administratora 011111

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
