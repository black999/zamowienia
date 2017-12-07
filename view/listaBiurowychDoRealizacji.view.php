<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/select2.css">
    <link rel="stylesheet" href="css/styles.css">
    <script type="text/javascript" src="script/jquery.js"></script>
    <script type="text/javascript" src="script/select2.js"></script>
	<title>Lista zamowień biurowych do realizacji</title>
</head>
<body>
<?php if ($_SESSION['srealBiuro'] != 1)  {
	echo "<H2>Brak uprawnień</h2>";
	exit();
} ?>

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