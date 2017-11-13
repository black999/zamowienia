<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="styles.css">
    <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
   	<script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js"></script>
	<title>Lista zamowień odrzuconych</title>
</head>
<body>

<?php if (isset($errorMessage))  : ?>
	<div class="statusZam">
		<?= $errorMessage ?>
	</div>
	<?php die(); ?>
<?php endif;  ?>


<div class="statusZam">
	<?= $tytul ?>
</div>

<br>

<div class="lista">
	<table id="tabela1" style="width: 90%">
		<thead>
		<tr>
			<th>Data zam.</th>
			<th>Zamawiający</th>
			<th>Dział</th>
			<!-- <th>Stat. Realizacji</th> -->
			<th>Kier</th>
			<th>Zam. Pub</th>
			<th>Księg</th>
			<th>Prezes</th>
			<th class="money">Wartość</th>
			<th class="money">Opcje</th>
		</tr>
		</thead>
		<tbody>
		<?php foreach ($zamowieniaAll as $zamowienie) : ?>
			<tr>
				<td><?= $zamowienie->Data ?></td>
				<td><?= $zamowienie->Imie . " ". $zamowienie->Nazwisko ?></td>
				<td><?= $zamowienie->Dzial ?></td>
				<!-- <td><?= $zamowienie->StatusReal ?></td> -->
				<td class="akceptacja"><?php if($zamowienie->akcKier != '0') echo "+" ?></td>
				<td class="akceptacja"><?php if($zamowienie->akcZam != '0') echo "+" ?></td>
				<td class="akceptacja"><?php if($zamowienie->akcKsie != '0') echo "+" ?></td>
				<td class="akceptacja"><?php if($zamowienie->akcPrez != '0') echo "+" ?></td>
				<td class="money"><?= $zamowienie->wartosc . " zł " ?></td>
				<td class="money"><a href="szczegolyzam.php?fIdzam=<?= $zamowienie->IdZamowienia ?>"> Szczegóły</a></td>
			</tr>
		<?php endforeach ; ?>
		</tbody>
	</table>
</div>
<script>
$(document).ready(function() {
	$("#tabela1").DataTable( {
    } );
})
</script>
</body>
</html>