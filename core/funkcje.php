<?php
function dd($string){
	die(var_dump($string));
}

function checkSesion() {
	if (!isset($_SESSION['auth'])) {
		header("Location:login.php");
	} else {
	    if ((time() - $_SESSION["loginTime"]) > 600) {   //sprawdzamy kiedy ostatni raz byla aktywnosc
	        header("Location: login.php?menu=logout");  // jesli powyzej 28800 (8 godzin) sekund to wylogowujemy
    	} else {
        	$_SESSION["loginTime"] = time();
    	}
	}
}

function polaczZBaza($host, $uzytkownik, $haslo, $nazwabazydanych) {
	$link = mysqli_connect($host, $uzytkownik, $haslo, $nazwabazydanych);
	if (!$link) {
		echo "Error: Unable to connect to MySQL." . PHP_EOL;
		echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
		echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
		exit;
	}
	mysqli_query($link, "SET NAMES utf8");
	mysqli_query($link, "SET CHARACTER SET utf8");
	mysqli_query($link, "SET collation_connection = utf8_polish_ci");
	return $link;
}

function OblWartZam($IdZam, $link) {
	$suma = 0;
	$sql = "SELECT z.Ilosc*t.cenaZak as wartosc from 
			zamowieniatow z
			inner join towary t on z.Towar = t.Id
			where z.IdZam = '" . $IdZam . "'";
	$result = mysqli_query($link, $sql);
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)) {
			$suma = $suma + $row['wartosc'];
		}
		$suma = number_format($suma,2,'.','');
		return $suma;
	} else {
		return false;
	} 
}

function addTowarDoZamowienia($link, $idZam, $idTowaru, $ilosc, $cenaRalizacji = '0'){
	$query = "INSERT INTO zamowieniatow VALUES (NULL, '$idZam', '$idTowaru', '$ilosc', '$cenaRalizacji')";
	mysqli_query($link, $query) or die ("Błąd przy dodawaniu towaru do bazy");
	return true;
}

function getPersonel($link) {
	$personel = [];
	$sql = 'SELECT * from personel';
	$result = mysqli_query($link, $sql);
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_object($result)) {
			$personel[$row->id] = $row;  //wynik jest zwracany w postaci tablicy personel[id_osoby] {dane osoby jak z zapytania sql}
		}
		return $personel;
	} else {
		return false;
	}
}

function dodajInfo($link, $info, $idzam ) {
	$sql = "UPDATE zamowienia SET Info ='". $info . "' WHERE IdZamowienia = '" . $idzam . "'";
	mysqli_query($link, $sql);
	return true;
}

function addZamowienie($link, $fData, $IdZamOsoba ) {  // zapisuje nowe zamowienie do bazy i zwraca id nowego zam.
	$query = "INSERT INTO zamowienia VALUES (null, '$fData', '$IdZamOsoba', '0', '0', '0000-00-00', '0', '0000-00-00', '0', '0000-00-00', '0', '0000-00-00', '0', '', '', '')";
	mysqli_query($link, $query) or die ("Błąd przy zapisnie nowego zamówienie do bazy");
	$fIdzam = mysqli_insert_id($link);
	return $fIdzam;
}

function getZamowienie($link, $idZam){
	$sql = "SELECT zm.StatusZatw, zm.Data, zm.akcKier, zm.akcZam, zm.akcKsie, zm.akcPrez, zm.StatusReal, zm.Info, zm.koszt, zm.kosztOpis, 
					zm.dataKier, zm.dataZam, zm.dataKsie, zm.dataPrez, p.Imie, p.Nazwisko, p.Dzial 
	from zamowienia zm 
	inner join personel p on zm.Zamawiajacy = p.id 
	WHERE zm.IdZamowienia ='" . $idZam . "'";
	$result = mysqli_query($link, $sql);
	$row = mysqli_fetch_assoc($result);

	return $row;
}

function getZamowienieBiuroweDoRealizacji($link){
	$sql = "SELECT z.IdZamowienia, p.Imie, p.Nazwisko, z.Data, t.nazwa, zt.Ilosc FROM `zamowienia` z 
		join  zamowieniatow zt on z.IdZamowienia = zt.IdZam
		join towary t on zt.Towar = t.Id
		join personel p on z.Zamawiajacy = p.id
		where t.biurowy = true and
		z.StatusReal = '0'
		and akcPrez <> '0' ";
	$result = mysqli_query($link, $sql);
	while ($row = $result->fetch_assoc()) {
	    $rows[] = $row;
	}
	
	return $rows;
}

function getZamowienia($link, $warunek = ""){
	$warunek =  ($warunek != "") ? " WHERE " . $warunek : "";
	$sql = "SELECT zm.IdZamowienia, zm.StatusZatw, zm.Data, zm.akcKier, zm.akcZam, 
					zm.akcKsie, zm.akcPrez, zm.StatusReal, zm.Info, p.Imie, p.Nazwisko, d.Nazwa as Dzial,
					sum(zt.Ilosc*t.cenaZak) as wartosc
			from zamowienia zm
			inner join personel p on zm.Zamawiajacy = p.id
			left join zamowieniatow zt on zm.IdZamowienia = zt.IdZam
			left join towary t on zt.Towar = t.Id
			inner join dzialy d on p.Dzial = d.IdDzial" . $warunek . " group by zm.IdZamowienia order by zm.Data DESC";
	$result = mysqli_query($link, $sql);
	if (mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_object($result)) {
			$wynik[$row->IdZamowienia] = $row;  //wynik jest zwracany w postaci tablicy
		}
		return $wynik;
	} else {
		return false;  //brak wyników
	}
}

function getTowaryNaZamowieniu($link, $idZam){
	$wynik = [];
	$sql = "SELECT
            z.IdZamowieniaTow, 
            z.Ilosc,
            z.CenaRealizacji,
            t.cenaZak, 
            t.nazwa, 
            z.Ilosc*t.cenaZak as wartosc 
            from zamowieniatow z
			inner join towary t on z.Towar = t.Id
			where z.IdZam = '" . $idZam . "' ORDER BY IdZamowieniaTow DESC";
	$result = mysqli_query($link, $sql);
	if (mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_object($result)) {
			$wynik[$row->IdZamowieniaTow] = $row;  //wynik jest zwracany w postaci tablicy personel[id_osoby] {dane osoby jak z zapytania sql}
		}
		return $wynik;
	} else {
		return false;
	}
}

function updateIlosciTowarowNaZamowieniu($link, $towary){
	foreach ($towary as $idZamTow => $ilosc){
		$query = "UPDATE zamowieniatow SET Ilosc = '" . $ilosc . "' WHERE IdZamowieniaTow = " . $idZamTow . "";
		mysqli_query($link, $query) or die ("Błąd przy dodawaniu do bazy");
	}

}
 
function getTowary ($link, $sIdDzial){
	$sql = "SELECT * FROM towary
			WHERE (IdDzial ='" . $sIdDzial . "' or IdDzial = '0')
			ORDER BY Nazwa";
	$result = mysqli_query($link, $sql);
	if (mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_object($result)) {
			$wynik[$row->Id] = $row;  //wynik jest zwracany w postaci tablicy personel[id_osoby] {dane osoby jak z zapytania sql}
		}
		return $wynik;
	} else {
		return false;
	}
}

function delTowarZZamowienia($link, $idTow){
	$sql = "DELETE FROM zamowieniatow WHERE IdZamowieniaTow = '" . $idTow . "'";
	mysqli_query($link, $sql);
}

function updateKosztZamowienia($link, $Idzam, $kosztOpis, $koszt) {
	$sql= "UPDATE zamowienia SET koszt = '{$koszt}', kosztOpis = '{$kosztOpis}' WHERE IdZamowienia = {$Idzam}";	
	mysqli_query($link, $sql);
}