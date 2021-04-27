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
		and akcPrez <> '0' 
		and z.Data >= DATE_SUB(NOW(), Interval 1 year)
		order by z.Data DESC";
	$result = mysqli_query($link, $sql);
	while ($row = $result->fetch_assoc()) {
	    $rows[] = $row;
	}
	
	return $rows;
}


function getZamowienia($link, $warunek = ""){
	$warunek =  ($warunek != "") ? " WHERE " . $warunek : "";
	$sql = "SELECT zm.IdZamowienia, zm.StatusZatw, zm.Data, zm.akcKier, zm.akcZam, 
					zm.akcKsie, zm.akcPrez, zm.StatusReal, zm.Info, p.Imie, p.Nazwisko, p.Dzial,
					d.Nazwa as Dzial,
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
 
function getTowaryByIdDzial ($link, $sIdDzial){
	$sql = "SELECT * FROM towary
			WHERE (IdDzial ='" . $sIdDzial . "' or IdDzial = '0')
			ORDER BY Nazwa";
	$result = mysqli_query($link, $sql);
	if (mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_object($result)) {
			$wynik[$row->Id] = $row;  
		}
		return $wynik;
	} else {
		return false;
	}
}

function getTowaryOstatnioDodane($link){
	$wynik = [];
    $sql = "SELECT t.id, t.nazwa, t.cenaZak, t.uwagi, t.biurowy, d.Nazwa as dzial
    		FROM towary t
    		LEFT JOIN dzialy d on t.IdDzial = d.IdDzial   
    		ORDER BY t.id DESC LIMIT 20";
	$result = mysqli_query($link, $sql);
	if (mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_assoc($result)) {
			$wynik[] = $row;  
		}
		return $wynik;
	} else {
		return false;
	}
}

function getTowary($link){
	$wynik = [];
    $sql = "SELECT t.id, t.nazwa, t.cenaZak, t.uwagi, t.biurowy, t.dostawca, d.Nazwa as dzial
    		FROM towary t
    		LEFT JOIN dzialy d on t.IdDzial = d.IdDzial   
    		ORDER BY t.id ";
	$result = mysqli_query($link, $sql);
	if (mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_assoc($result)) {
			$wynik[] = $row;  
		}
		return $wynik;
	} else {
		return false;
	}
}

function getTowar($link, $id){
	$wynik = [];
    $sql = "SELECT t.id, t.nazwa, t.cenaZak, t.uwagi, t.biurowy, t.dostawca, d.Nazwa as dzial
    		FROM towary t
    		LEFT JOIN dzialy d on t.IdDzial = d.IdDzial   
    		WHERE t.id  = {$id}";
	$result = mysqli_query($link, $sql);
	if (mysqli_num_rows($result) > 0) {
		$wynik = mysqli_fetch_assoc($result); 
		return $wynik;
	} else {
		return false;
	}
}

function addTowar($link, $towar){
		$fNazwaTow = $towar['fNazwaTow'];
		$fCenaZak = str_replace(",", ".", $towar['fCenaZak']); 
        $fDzial = $towar['fDzial'];
		$fDostawca = $towar['fDostawca'];
		$fUwagi = $towar['fUwagi'];
		if (!isset($towar['fBiurowy'])){
			$fBiurowy = '0';
		} else {
			$fBiurowy = $towar['fBiurowy'];
		}
		$query = "INSERT INTO towary VALUES (NULL, '$fDzial', '$fNazwaTow', '$fCenaZak', '$fDostawca', '$fUwagi', '$fBiurowy')";
		//echo "dodano towary";
		mysqli_query($link, $query) or die ("Jakiś błąd");	
}

function delTowarZZamowienia($link, $idTow){
	$sql = "DELETE FROM zamowieniatow WHERE IdZamowieniaTow = '" . $idTow . "'";
	mysqli_query($link, $sql);
}

function updateKosztZamowienia($link, $Idzam, $kosztOpis, $koszt) {
	$sql= "UPDATE zamowienia SET koszt = '{$koszt}', kosztOpis = '{$kosztOpis}' WHERE IdZamowienia = {$Idzam}";	
	mysqli_query($link, $sql);
}

function getDzialById($link, $id){
	$sql = "Select * from dzialy where dzialy.idDzial = '{$id}'";
	$result = mysqli_query($link, $sql);
	return mysqli_fetch_object($result);
}

function getDzialy($link){
	$wynik = [];
	$sql = "Select * from dzialy";
	$result = mysqli_query($link, $sql);
	if (mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_assoc($result)) {
			$wynik[] = $row;  
		}
		return $wynik;
	} else {
		return false;
	}
}

function getWarunekByUprawnienia($sUpr, $sId, $sIdDzial) {
	$sUpr = ($sUpr & 31);  //filturjemy wszystkie uprawnienia bez uprawnie administratora 011111
	$pracownik = (($sUpr & 1) == '1') ? TRUE : FALSE;
	if ($sUpr == 1) { // jeśli tylko pracownik 
		$warunek = "StatusZatw = 1 AND StatusReal = 0 AND akcPrez = 0 AND p.id = {$sId}"; // StatusZatw = 1 - zamowienia zatwierdzone
	}
	if ($sUpr > 1 && $sUpr < 4) { // jeśli tylko kierownik
		if ($pracownik){   //jesli jednoczesnie pracownik to wyświetlamy wszystkie zmownienia łącznie z naszymi akceptacjami
			// StatusZatw = 1 - zamowienia zatwierdzone
			// ((Dzial = {$sIdDzial} AND akcKier = 0) - zlecenie nie zatwierdzone przez kierwonika i z działu kierownika
			// (akcKier !=0 and p.id = {$sId}) - zlecenia zatwierdzone przez kierwonika i należące do niego samego
			$warunek = "StatusZatw = 1 AND StatusReal = 0 AND akcPrez = 0 AND 
						((Dzial = {$sIdDzial} AND akcKier = 0) OR 
						(akcKier <> 0 and p.id = {$sId}))"; 
		} else {
			$warunek = "StatusZatw = 1 AND StatusReal = 0 AND Dzial = {$sIdDzial} AND akcKier = 0"; // StatusZatw = 1 - zamowienia zatwierdzone
		}
	}
	if ($sUpr >= 4) { // jeśli więcej niż kierownik
		$warunekOR = "";
		$warunek = "StatusZatw = 1 AND StatusReal = 0 AND akcPrez = 0 ";
		if (($sUpr & 16 ) == 16) {
			$warunekOR = "akcKier != 0"; // aby prezes mógł akceptować musi być akceptacja kierwonika
		}
		if (($sUpr & 8 ) == 8) {
			if (!$pracownik) {
				$warunekOR = (!isset($warunekOR)) ? "akcKsie = 0": " {$warunekOR} OR akcKsie = 0" ; // StatusZatw = 1 - zamowienia zatwierdzone
			}
		}
		if (($sUpr & 4 ) == 4) {
			if (!$pracownik) {
				$warunekOR = (!isset($warunekOR)) ? "akcZam = 0" : " {$warunekOR} OR akcZam = 0"; // StatusZatw = 1 - zamowienia zatwierdzone
			}
		}
		if ($warunekOR != "") {
			$warunek = $warunek . " AND ( " . $warunekOR . " )";
		}
	}
	return $warunek;
}

