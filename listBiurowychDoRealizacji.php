<?php 
	require 'core/init.php';

	$zamowienia = getZamowienieBiuroweDoRealizacji($link);
	$tytul = "Lista towarów biurowych do realizacji";

	require 'view/listaBiurowychDoRealizacji.view.php';

