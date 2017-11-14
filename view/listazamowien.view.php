<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/styles.css">
    <script src="script/jquery.js" type="text/javascript"></script>
   	<script src="script/dataTable.js" type="text/javascript"></script>
   	 <link rel="stylesheet" href="css/dataTable.css">
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
		"lengthMenu": [ [ 20, 50, 75, 100, -1 ], [ 20, 50, 75, 100, "All" ] ],
        "order": [ 0, 'desc' ],
        "language": {
            "emptyTable":     "Brak danych w tabeli",
            "info":           "Widok _START_ do _END_ z _TOTAL_ pozycji",
            "infoEmpty":      "Widok 0 do 0 z 0 pozycji",
            "infoFiltered":   "(filtrowane z _MAX_ pozycji)",
            "infoPostFix":    "",
            "lengthMenu":     "Pokaż _MENU_ pozycji",
            "loadingRecords": "Ładowanie...",
            "processing":     "Processing...",
            "search":         "Szukaj:",
            "zeroRecords":    "Nie znaleziono pasujących wpisów",
            "paginate": {
                "first":      "Pierwszy",
                "last":       "Ostatni",
                "next":       "Następny",
                "previous":   "Poprzedni"
            },
        }
    });
})
</script>
</body>
</html>