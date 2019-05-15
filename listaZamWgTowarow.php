<?php

	require 'core/init.php';

	$sql = "zm.IdZamowienia in (select zm.IdZamowienia from zamowienia zm 
								inner join zamowieniatow zt on zm.IdZamowienia = zt.IdZam 
								inner join towary t on zt.Towar = t.id 
								where t.id = {$_GET['id']} )";
	$zamowieniaAll = getZamowienia($link, $sql); 
	$towar = getTowar($link, $_GET['id']);
	if (!$zamowieniaAll) {
		$errorMessage = "Brak zamówień dla towaru <b>{$towar['nazwa']}</b>";
	}

	$tytul = "Lista zamówień na których znajduje się <b>{$towar['nazwa']}</b>";
	$widok = 'okno';

	include ('view/listazamowien.view.php');	