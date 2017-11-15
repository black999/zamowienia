<!DOCTYPE html>
<html>
<head>
	<?php require ("view/naglowek.view.php"); ?>
	<title>Szczegóły zamowienia</title>
</head>
<body>

	<DIV class='listwaZam'>
		<span class='text'>Zamównienie z dnia :</span>
		<span class='dane'><?= $fData ?></span>
		<span class='text'>Zamawiający :</span>
		<span class='dane'><?= $sImie . " " . $sNazwisko ?></span>
		<span class='text'>Wartość zamówienia : </span>
		<span class='dane'><?= $wartoscZam ?> zł</span>
	</DIV>
	
	<DIV class='statusZam'>      
		<table width="80%">
			<tr>
				<td> Szczegóły zamówienia </td>
				<td>
					<?php if ($zamowienie['StatusZatw'] == '0') : ?> 
						<span style='color: red'>W EDYCJI</span>
						<!-- //jesli zamownienie ma wartosc zero nie jest możliwe zatwierdzenie -->
						<?php if ($wartoscZam > 0) : ?>
							<a class="button" href="zamowienia.php?menu=zatw&fIdzam=<?= $_GET['fIdzam'] ?>">Prześlij do akceptacji</a>
						<?php endif; ?>	
						<a class="button" href="edycjazamowienia.php?fIdzam=<?= $_GET['fIdzam'] ?>">Edytuj</a>
						<button type='button'  class='button-danger' 
									onclick="window.location.href='zamowienia.php?menu=usunZam&id=<?= $_GET['fIdzam'] ?>'">  Usuń  
						</button>
					<?php endif; ?>	
					<?php if ($zamowienie['StatusZatw'] == '1' &&  $zamowienie['akcPrez'] == '0' ) : ?>
						<span style='color: green'>DO AKCEPTACJI</span>
		           	<?php endif; ?> 
		           	<?php if ($zamowienie['StatusReal'] == '0' &&  $zamowienie['akcPrez'] != '0' && (($_SESSION['sUpr'] & 4) == 4)) : ?> <!-- // jesli zaakceptowane i mamy uprawnienia -->
						<a class="button" href="zamowienia.php?menu=realizujZam&fIdzam=<?= $_GET['fIdzam'] ?>">Zaznacz jako zrealizowane</a>
					<?php endif; ?>	
					<?php if ($zamowienie['StatusReal'] == '1') : ?>
					   <span style='color: blue'> ZAKOŃCZONE </span>
		           	<?php endif; ?> 
				</td>
			</tr>
		</table>
	</DIV>
	<?php if (!$towaryNaZam) : ?>
		<?php die("<div class='lista'>Brak towarów na zamówieniu</div>"); ?>
	<?php endif; ?>
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
				<th colspan="5">Koszt realizacji</th>
			</tr>
			<tr>
				<td colspan="3"><?= $kosztOpis ?></td>
				<td class="suma">CENA :</td>
				<td class="suma"><?= $koszt ?> zł</td>
			</tr>
		</TABLE>
	</div>		
	
	<?php if ($zamowienie['StatusZatw'] == '1') : ?>   <!-- //jesli zamownienie jest zatwierdzone wyświetlamy listę akceptacji i możliwość akceptacji -->
		<div class='listaAkceptacji'>
			<p><h3>Akceptacje </h3></p>
			<table>
				<tr>
					<td><b>Zamawiający</b></td>
					<td><?= $zamowienie['Imie'] . " ". $zamowienie['Nazwisko']?></td>
				</tr>
				<tr>
					<td><b>Kierownik</b></td>
					<?php if ($zamowienie['akcKier'] == 0) : ?> <!-- //spr czy jest akceptacja  -->
					<td style='color: red'>Brak</td>
						<?php if (($sUpr & 2 ) == 2 && $zamowienie['StatusReal'] == '0' && $zamowienie['Dzial'] == $sIdDzial)  : ?> <!-- spr. czy mamy uprawnienia i czy zamowienie jest z tego działu co kierownik-->
							<td>
								<button type='button'  class='button' 
									onclick="window.location.href='zamowienia.php?menu=akc&fIdzam=<?= $_GET['fIdzam'] ?>&ftyp=kier'">  Akceptuj  
								</button>
							</td>
							<td>
								<button type='button'  class='button-danger' 
									onclick="window.location.href='zamowienia.php?menu=odrzucZam&fIdzam=<?= $_GET['fIdzam'] ?>'">  Odrzuć  
								</button>
							</td>
						<?php endif ; ?>	
					<?php  else : ?>
						<td style='color: green'>Akceptacja</td>
						<td>&nbsp&nbsp Akceptujący :&nbsp&nbsp 
							<?= $personel[$zamowienie['akcKier']]->Nazwisko . " " . $personel[$zamowienie['akcKier']]->Imie ?>
						</td>
						<td>
							<b>&nbsp&nbsp Data akceptacji : </b>
							<?= $zamowienie['dataKier'] ?>
						</td>
					<?php endif; ?>
				</tr>
					<td><b>Zam. publiczne</b></td>
					<?php if ($zamowienie['akcZam'] == 0) : ?> <!-- //spr czy jest akceptacja  -->
					<td style='color: red'>Brak</td>
						<?php if ((($sUpr & 4 ) == 4) && ($zamowienie['akcKier'] != 0) && $zamowienie['StatusReal'] == '0')  : ?> <!-- /// spr. czy mamy uprawnienia i czy jest już akc. kierownika-->
							<td>
								<button type='button'  class='button' 
									onclick="window.location.href='zamowienia.php?menu=akc&fIdzam=<?= $_GET['fIdzam'] ?>&ftyp=zamp'">  Akceptuj  
								</button>
							</td>
							<td>
								<button type='button'  class='button-danger' 
									onclick="window.location.href='zamowienia.php?menu=odrzucZam&fIdzam=<?= $_GET['fIdzam'] ?>'">  Odrzuć  
								</button>
							</td>
						<?php endif ; ?>	
					<?php  else : ?>
						<td style='color: green'>Akceptacja</td>
						<td>&nbsp&nbsp Akceptujący :&nbsp&nbsp 
							<?= $personel[$zamowienie['akcZam']]->Nazwisko . " " . $personel[$zamowienie['akcZam']]->Imie ?>
						</td>
						<td>
							<b>&nbsp&nbsp Data akceptacji : </b>
							<?= $zamowienie['dataZam'] ?>
						</td>
					<?php endif; ?>
				</tr>
				</tr>
					<td><b>Księgowość</b></td>
					<?php if ($zamowienie['akcKsie'] == 0) : ?> <!-- spr czy jest akceptacja  -->
					<td style='color: red'>Brak</td>  
						<?php if ((($sUpr & 8 ) == 8) && ($zamowienie['akcKier'] != 0) && $zamowienie['StatusReal'] == '0')  : ?> <!-- spr. czy mamy uprawnienia czy jest już akc. kierownika-->
							<td>
								<button type='button'  class='button' 
									onclick="window.location.href='zamowienia.php?menu=akc&fIdzam=<?= $_GET['fIdzam'] ?>&ftyp=ksie'">  Akceptuj  
								</button>
							</td>
							<td>
								<button type='button'  class='button-danger' 
									onclick="window.location.href='zamowienia.php?menu=odrzucZam&fIdzam=<?= $_GET['fIdzam'] ?>'">  Odrzuć  
								</button>
							</td>
						<?php endif ; ?>	
					<?php  else : ?>
						<td style='color: green'>Akceptacja</td>
						<td>&nbsp&nbsp Akceptujący :&nbsp&nbsp 
							<?= $personel[$zamowienie['akcKsie']]->Nazwisko . " " . $personel[$zamowienie['akcKsie']]->Imie ?>
						</td>
						<td>
							<b>&nbsp&nbsp Data akceptacji : </b>
							<?= $zamowienie['dataKsie'] ?>
						</td>
					<?php endif; ?>
				</tr>
				</tr>
					<td><b>Prezes</b></td>
					<?php if ($zamowienie['akcPrez'] == 0 ) : ?> <!-- spr czy jest akceptacja -->
					<td style='color: red'>Brak</td>
						<?php if (($sUpr & 16 ) == 16 && $zamowienie['akcKier'] != 0 && $zamowienie['StatusReal'] == '0')  : ?> <!-- spr. czy mamy uprawnienia i czy jest akceptacja kierownika-->
							<td>
								<button type='button'  class='button' 
									onclick="window.location.href='zamowienia.php?menu=akc&fIdzam=<?= $_GET['fIdzam'] ?>&ftyp=prez'">  Akceptuj  
								</button>
							</td>
							<td>
								<button type='button'  class='button-danger' 
									onclick="window.location.href='zamowienia.php?menu=odrzucZam&fIdzam=<?= $_GET['fIdzam'] ?>'">  Odrzuć  
								</button>
							</td>
						<?php endif ; ?>	
					<?php  else : ?>
						<td style='color: green'>Akceptacja</td>
						<td>&nbsp&nbsp Akceptujący :&nbsp&nbsp 
							<?= $personel[$zamowienie['akcPrez']]->Nazwisko . " " . $personel[$zamowienie['akcPrez']]->Imie ?>
						</td>
						<td>
							<b>&nbsp&nbsp Data akceptacji : </b>
							<?= $zamowienie['dataPrez'] ?>
						</td>	
					<?php endif; ?>
				</tr>
			</TABLE>
			<form action='?menu=dodajInfo&fIdzam=<?= $_GET['fIdzam'] ?>' method='POST'>
				<h3>Komentarz do zamówienia:</h3>
				 <textarea rows='6' cols='80' name='fInfo'><?= $zamowienie['Info'] . "\n" . $sImie . " " . $sNazwisko . ":  " ?></textarea><br> 
				<input type='submit' class='button' value='Zapisz komentarz'>
			</form>
		</div>
	<?php endif ; ?>	
</body>
</html>