<?php
	require_once 'nazwadb.inc.php';
	require 'core/funkcje.php';

	session_start();
	checkSesion();

	$link = polaczZBaza($host, $uzytkownik, $haslo, $nazwabazydanych);
	if (isset($_GET['dodaj'])){
		dodajFakture($link);
	}

	$faktury = getFaktury($link);
	$tytul = "Dodawanie faktur";

	require("view/listaFaktur.view.php");