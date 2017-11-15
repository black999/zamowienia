<!DOCTYPE html>
<html>
<head>
	<?php require ("view/naglowek.view.php"); ?>
	<title>Edycja zamowienia</title>
</head>
<body>

<DIV class='listwaZam'>
	<span class='text'>Zamównienie z dnia :</span>
	<span class='dane'><?= $fData ?></span>
	<span class='text'>Zamawiający :</span>
	<span class='dane'><?= " {$sImie}  {$sNazwisko} " ?></span>
	<span class='text'>Wartość zamówienia : </span>
	<span class='dane'><?= $wartoscZam ?> zł</span>
</DIV>

<div class="statusZam">
	Edycja zamówienia
</div>
<div class="formatka">
	<form action='?menu=dodaj&fIdzam=<?= $_GET['fIdzam'] ?>' method='POST'>
		<label>Towar: </label>
		<select name='fIdTowar' class='pole' autofocus >
			<?php foreach ($towary as $towarId) : ?>
				<OPTION value="<?= $towarId->Id ?>">
					<?= $towarId->nazwa . "&nbsp&nbsp Cena &nbsp" . $towarId->cenaZak . "&nbspzł"?>
				</OPTION> 
			<?php endforeach ; ?>
		</select>
		<label> Ilość: </label>
		<input type='number' name='fIlosc' value='1' class='pole-input' size=7 min='1' max='5000' required>
		<input type="submit" value="DODAJ">
	</form>
</div>
<br>

<div class="lista">
	<form action='?menu=zapisz&fIdzam=<?= $_GET['fIdzam'] ?>' method='POST'>
		<table style="width: 70%"">
			<tr>
				<th>Nazwa</th>
				<th>Ilość</th>
				<th class="money">Cena zakupu</th>
				<th class="money">Wartość</th>
				<th class="money">Opcje</th>
			</tr>
			<?php if ($towaryNaZam) : ?>   <!-- jesli jest jakis towar na zamowieniu -->
				<?php foreach ($towaryNaZam as $towar) : ?>
					<tr>
						<td><?= $towar->nazwa ?></td>
						<td> <input type="number" 
								name="fIlosc['<?= $towar->IdZamowieniaTow ?>']" 
								class="pole-input" min="1"  max="1000"
								value="<?= $towar->Ilosc ?>">
						</td>
						<td class="money"><?= $towar->cenaZak ?></td>
						<td class="money"><?= $towar->wartosc ?></td>
						<td class="money">
							<a href="?menu=usun&id=<?= $towar->IdZamowieniaTow ?>&fIdzam=<?= $_GET['fIdzam'] ?>">
							<img src='images/kasuj.gif'></a>
						</td>
					</tr>
				<?php endforeach ; ?>
			<?php endif; ?>
			<tr>
				<TD colspan="3" class='suma'>RAZEM :</TD><TD class='suma'><?= "{$wartoscZam} zł" ?></TD>
			</TR>
			<tr>
				<th colspan="5">Koszt realizacji</th>
			</tr>
			<tr>
				<td colspan="2"><input type="text" name="kosztOpis" placeholder="Opis np. Transport" value="<?= $kosztOpis ?>"></td>
				<td class="suma">CENA :</td>
				<td class="suma"><input type="text" class="inputPrawy" name="koszt" value="<?= $koszt ?>"> zł</td>
			</tr>
		</table>
		<div>
			<br>
			<button type="submit" class="button" name="opcja" value="zapisz">Zapisz</button>
			<button type="submit" class="button" name="opcja" value="przelicz">Przelicz</button>
		</div>
	</form>
</div>
<script>
$(document).ready(function() {
    $('#pole').select2();  //zmienic na ".pole"
    theme: "classic";
});
</script>

</body>
</html>