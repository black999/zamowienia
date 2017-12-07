<?php 
require_once 'nazwadb.inc.php';
require 'core/funkcje.php';

session_start();
checkSesion();



$link = polaczZBaza($host, $uzytkownik, $haslo, $nazwabazydanych);
$zamowienia = getZamowienieBiuroweDoRealizacji($link);
$tytul = "Lista towarów biurowych do realizacji";

require 'view/listaBiurowychDoRealizacji.view.php';

?>