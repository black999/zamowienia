<!doctype html>
<html lang='pl'>
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="stylesheet" href="css/styles.css">
   <script src="script/jquery.js" type="text/javascript"></script>
   <script src="script/dataTable.js" type="text/javascript"></script>
   <script src="script/script.js"></script>
   <link rel="stylesheet" href="css/dataTable.css">
   <title></title>
</head>
<body>
	
<?php
require 'core/init.php';

if  (($_GET['menu']) == 'dodaj') {
	if (isset($_POST['fNazwaTow'])) {
		addTowar($link, $_POST);
	}
	$dzialy = getDzialy($link);
	$towaryOstatnioDodane = getTowaryOstatnioDodane($link);
	include ('view/dodajTowar.view.php');
}
if  (($_GET['menu']) == 'lista') {     //formatka wyszukiwania towarow
	$towary = getTowary($link);
	include ('view/listaTowarow.view.php');
}
if  (($_GET['menu']) == 'edycja') {
		if (isset($_POST['fNazwaTow'])) {
			$fNazwaTow = $_POST['fNazwaTow'];
			$fCenaZak = $_POST['fCenaZak'];
			$fDostawca = $_POST['fDostawca'];
			$fUwagi = $_POST['fUwagi'];
			if (!isset($_POST['fBiurowy'])) $fBiurowy = '0';
			$fBiurowy = $_POST['fBiurowy'];
			$id = $_GET['id'];
            $fDzial = $_POST['fDzial'];
			$fCenaZak = str_replace(",",".",$fCenaZak); 
			
			$query = "UPDATE towary SET 
										nazwa='$fNazwaTow', 
										IdDzial='$fDzial', 
										cenaZak='$fCenaZak', 
										dostawca='$fDostawca',
										uwagi='$fUwagi',
										biurowy='$fBiurowy'
						WHERE id='$id'";
			//echo "dodano towary";
			mysqli_query($link, $query) or die ("Błąd zapisu do bazy");
			header("Location: towary.php?menu=lista");
		}
		$sql = "SELECT  t.nazwa, t.cenaZak, t.dostawca, t.uwagi, t.biurowy, d.IdDzial, d.Nazwa as dzial
            FROM towary t
            LEFT JOIN dzialy d on t.IdDzial = d.IdDzial   
            WHERE id='".$_GET['id'] ."'";
		$result = mysqli_query($link, $sql);
		$row = mysqli_fetch_assoc($result);
		
		echo "<div class='formatka'>";
		echo "<h2>Edycja towaru</h2><BR><BR>";
		echo "<FORM action='towary.php?menu=edycja&id=".$_GET['id']."' method='POST'>";
		echo "<TABLE>";

		echo "<TR>";
		echo "<TD>Nazwa towaru (max 40 znaków)</TD>";
		echo "<TD><INPUT name='fNazwaTow' class='pole' size=35 maxlength=40 value='".$row["nazwa"]."'></TD>";
		echo "</TR>";

		echo "<TR>";
		echo "<TD>Cena zakupu</TD>";
		echo "<TD><INPUT name='fCenaZak' class='pole' size=5 maxlength=7 value='".$row["cenaZak"]."'>   zł</TD>";
		echo "</TR>";

    	echo "<TR>";
		echo "<TD>Grupa</TD>";
		echo "<TD><SELECT name='fDzial' class='pole'   maxlength=25>";
        echo "<OPTION value='0' ></OPTION>";
		$sql = "SELECT * FROM dzialy";
		$result = mysqli_query($link, $sql);
		while($row2 = mysqli_fetch_assoc($result)) {
            $select=($row['IdDzial'] == $row2['IdDzial'])? "selected" : ""; //sprawdzamy czy 
			echo "<OPTION value='" . $row2['IdDzial'] . "'" . $select . ">" . $row2['Nazwa'] . "</OPTION>";
			}
		echo "</SELECT></TD>";
		echo "</TR>";  

		echo "<TR>";
		echo "<TD>Dostawca </TD>";
		echo "<TD><INPUT name='fDostawca' class='pole' size=35 maxlength=40 value='".$row["dostawca"]."'></TD>";
		echo "</TR>";	
	
		echo "<TR>";
		echo "<TD>Uwagi </TD>";
		echo "<TD><INPUT name='fUwagi' class='pole' size=50 maxlength=50 value='".$row["uwagi"]."'></TD>";
		echo "</TR>";

   		echo "<TR>";
		echo "<TD>Towar biurowy</TD>";
		echo "<TD><INPUT type='checkbox' value='1' name='fBiurowy' "; 
			if ($row['biurowy']) echo 'checked'; 
		echo "></TD>";
		echo "</TR>"; 

		echo "<TR>";
		echo "<TD colspan='2' align='right'><INPUT type='SUBMIT' value='    POPRAW   '></TD>";
		echo "</TR>";

		echo "</TABLE>";
		echo "</FORM><BR><BR>";
	echo "</div>";
	mysqli_close($link);	
}
?>
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