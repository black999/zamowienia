<?php 
	require_once '../nazwadb.inc.php';
	require '../core/funkcje.php';

	session_start();
	checkSesion();

	$link = polaczZBaza($host, $uzytkownik, $haslo, $nazwabazydanych);

	$wartoscZam = OblWartZam($_POST['idzam'], $link); // wyliczanie wartosci zamownienia
	$zamowienie = getZamowienie($link, $_POST['idzam']);
	$towaryNaZam = getTowaryNaZamowieniu($link, $_POST['idzam']);
	$fData = $zamowienie['Data'];	
	$koszt = $zamowienie['koszt'];
	$kosztOpis = $zamowienie['kosztOpis'];
	$personel = getPersonel($link);

	$lp = 1; //licznik
 ?>

 	<DIV class='listwaZam'>
		<span class='text'>Zamównienie z dnia :</span>
		<span class='dane' style="width: 90px"><?= $fData ?></span>
		<span class='text'>Zamawiający :</span>
		<span class='dane'><?= $zamowienie['Imie'] . " " . $zamowienie['Nazwisko'] ?></span>
		<span class='text'>Wartość zamówienia : </span>
		<span class='dane' style="width: 80px"><?= $wartoscZam ?> zł</span>
	</DIV>
	<br>
	<div class='lista'>
	<TABLE>
		<tr>
			<th>L.p.</th>
			<th>Nazwa towaru</th>
			<th>Ilość</th>
    		<th class='money'>Cena zakupu</th>
    		<th class='money'>Wartość</th>
    	</tr>
		<?php foreach ($towaryNaZam as $towar) : ?>
		<tr>
			<td style='width: 30px;'><?= $lp++ ?></td>
			<td style='width: 300px;'><?= $towar->nazwa ?></td>
			<td><?= $towar->Ilosc ?></td>
			<td class='money'><?= $towar->cenaZak ?></td>
			<td class='money'><?= $towar->wartosc ?></td>
		</tr>
		<?php endforeach; ?> 
    	<tr>
    		<td colspan="3"></td><td class='suma'>RAZEM :</td><td class='suma'><?= $wartoscZam . " zł" ?></td>
    	</tr>
    	<tr>
    		
    	</tr>
    	<tr>
			<th colspan="5">Koszt realizacji</th>
		</tr>
		<tr>
			<td colspan="3"><?= $kosztOpis ?></td>
			<td class="suma">CENA :</td>
			<td class="suma"><?= $koszt ?> zł</td>
		</tr>
	</TABLE>
</div>	
<br>
<button class="button exit">Zamknij</button>