<!DOCTYPE html>
<html>
<head>
	<?php require ("view/naglowek.view.php"); ?>
	<title>Lista zamowień biurowych do realizacji</title>
</head>
<body>
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
			<th>Nazwa towaru</th>
			<th>Ilość </th>
		</tr>
		</thead>
		<tbody>
		<?php foreach ($zamowienia as $zamowienie) : ?>
			<tr>
				<td><?= $zamowienie['Data'] ?></td>
				<td><?= $zamowienie['Imie'] . " ". $zamowienie['Nazwisko'] ?></td>
				<td><?= $zamowienie['nazwa'] ?></td>
				<td><?= $zamowienie['Ilosc'] ?></td>
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