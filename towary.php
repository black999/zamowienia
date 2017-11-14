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
require_once 'nazwadb.inc.php';
require 'funkcje.php';
session_start();
checkSesion();
$link = polaczZBaza($host, $uzytkownik, $haslo, $nazwabazydanych);

if  (($_GET['menu']) == 'dodaj') {
	if (isset($_POST['fNazwaTow'])) {
		$fNazwaTow = $_POST['fNazwaTow'];
		$fCenaZak = $_POST['fCenaZak'];
        $fDzial = $_POST['fDzial'];
		$fCenaZak = str_replace(",",".",$fCenaZak); 
		$fDostawca = $_POST['fDostawca'];
		$fUwagi = $_POST['fUwagi'];
		$query = "INSERT INTO towary VALUES (NULL, '$fDzial', '$fNazwaTow', '$fCenaZak', '$fDostawca', '$fUwagi')";
		//echo "dodano towary";
		mysqli_query($link, $query) or die ("Jakiś błąd");
	}


	echo "<div class='formatka'>";   //formatka dodania nowego towaru
		echo "<h2>Nowy towar</h2><BR><BR>";
		echo "<FORM action='towary.php?menu=dodaj' method='POST'>";
		echo "<TABLE>";

		echo "<TR>";
		echo "<TD>Nazwa towaru (max 40 znaków)</TD>";
		echo "<TD><INPUT name='fNazwaTow' class='pole' size=35 maxlength=40 required autofocus ></TD>";
		echo "</TR>";

		echo "<TR>";
		echo "<TD>Cena zakupu brutto</TD>";
		echo "<TD><INPUT name='fCenaZak' class='pole' size=5 maxlength=7 required>   zł</TD>";
		echo "</TR>";
    
		echo "<TR>";
		echo "<TD>Grupa</TD>";
		echo "<TD><SELECT name='fDzial' class='pole'   maxlength=25>";
        echo "<OPTION value='0'></OPTION>";
		$sql = "SELECT * FROM dzialy";
		$result = mysqli_query($link, $sql);
		while($row = mysqli_fetch_assoc($result)) {
			echo "<OPTION value='".$row['IdDzial']."'>".$row['Nazwa']."</OPTION>";
			}
		echo "</SELECT></TD>";
		echo "</TR>";  

		echo "<TR>";
		echo "<TD>Dostawca</TD>";
		echo "<TD><INPUT name='fDostawca' class='pole' size=35 maxlength=40 ></TD>";
		echo "</TR>";

		echo "<TR>";
		echo "<TD>Uwagi</TD>";
		echo "<TD><INPUT name='fUwagi' class='pole' size=50 maxlength=49 ></TD>";
		echo "</TR>";

		echo "<TR>";
		echo "<TD>Towar biurowy</TD>";
		echo "<TD><INPUT type='checkbox' name='fbiurowy' value='1'></TD>";
		echo "</TR>";

		echo "<TR>";
		echo "<TD colspan='2' align='right'><INPUT type='SUBMIT' value='    DODAJ   '></TD>";
		echo "</TR>";

		echo "</TABLE>";
		echo "</FORM><BR><BR>";
	echo "</div>";

	echo "<div class='lista'>";  //odczytanie danych towarów
	
		echo "<BR><H2>Towary ostatnio dodane</H2>";
        $sql = "SELECT t.id, t.nazwa, t.cenaZak, t.uwagi, d.Nazwa as dzial
            FROM towary t
            LEFT JOIN dzialy d on t.IdDzial = d.IdDzial   
            ORDER BY t.id DESC LIMIT 20";
		$result = mysqli_query($link, $sql);

		if (mysqli_num_rows($result) > 0) {
			// output data of each row
		echo "<TABLE><TR><TH>ID</TH><TH>Nazwa towaru</TH><TH>Grupa</TH><TH>Cena zakupu</TH><TH>Uwagi</TH><TH class='opcje'>Opcje</TH></TR>";
		while($row = mysqli_fetch_assoc($result)) {
				echo "<TR>";
				echo "<TD style='width: 30px;'>" .$row["id"] . "</TD>";
				echo "<TD style='width: 300px;'>" . $row["nazwa"]. "</TD>";
                echo "<TD>" . $row['dzial'] . "</TD>";
				echo "<TD>" . $row["cenaZak"]. "</TD>";
				echo "<TD>" . $row["uwagi"]. "</TD>";
				echo "<TD class='opcje'><a href='towary.php?menu=edycja&id=" .$row["id"]. "'>Edycja</TD>";
				echo "</TR>";
			}
		echo "</TABLE>";
		} 
		mysqli_close($link);
	echo "</div>";
}
if  (($_GET['menu']) == 'lista') {     //formatka wyszukiwania towarow
		// echo "<div class='formatka'>";
		// 	echo "<FORM action='towary.php?menu=lista' method='POST'>";
		// 	echo "<TABLE>";

		// 	echo "<TR>";
		// 	echo "<TD>Nazwa towaru </TD>";
		// 	echo "<TD><INPUT name='fNazwaTow' class='pole' size=35 maxlength=40></TD>";
					
		// 	echo "<TD colspan='2' align='right'><INPUT type='SUBMIT' value='    SZUKAJ   '></TD>";
		// 	echo "</TR>";

		// 	echo "</TABLE>";
		// 	echo "</FORM><BR><BR>";
		// echo "</div>";
		echo "<div class='statusZam'>";
			echo "Lista towarów";
		echo "</div>";	
		echo "<div class='lista'>";  //odczytanie danych towarów
			if (!(isset($_POST['fNazwaTow']))) $_POST['fNazwaTow'] = '';
			// uzywamy ponizej left join bo gdy nie ma dzialu towar to by sie nie wyswietlil na liscie
            $sql = "SELECT t.id, t.nazwa, t.cenaZak, t.uwagi, d.Nazwa as dzial, t.dostawca
            FROM towary t
            LEFT JOIN dzialy d on t.IdDzial = d.IdDzial   
            WHERE t.nazwa LIKE '%".$_POST['fNazwaTow']."%'
            ORDER BY t.nazwa";
			$result = mysqli_query($link, $sql);

			if (mysqli_num_rows($result) > 0) {
				// output data of each row
			echo "<TABLE id='tabela1'>
					<THEAD>
						<TR>
							<TH>Nazwa towaru</TH>
							<TH>Dostawca</TH>
							<TH>Uwagi</TH>
							<TH>Grupa</TH>
							<TH class='money'>Cena zakupu</TH>
							<TH class='opcje'>Opcje</TH>
						</TR>
					</THEAD>";
			echo "<TBODY>";
			while($row = mysqli_fetch_assoc($result)) {
				echo "<TR>";
				echo "<TD style='width: 300px;'>" . $row["nazwa"]. "</TD>";
				echo "<TD>" . $row["dostawca"]. "</TD>";
				echo "<TD>" . $row["uwagi"]. "</TD>";
                echo "<TD>" . $row['dzial'] . "</TD>";
				echo "<TD class='money'>" . $row["cenaZak"]. "</TD>";
				echo "<TD class='opcje'><a href='towary.php?menu=edycja&id=" .$row["id"]. "'>Edycja</TD>";
				echo "</TR>";
			}
			echo "</TBODY>";
			echo "</TABLE>";
			} 
			mysqli_close($link);
		echo "</div>";
}
if  (($_GET['menu']) == 'edycja') {
			if (isset($_POST['fNazwaTow'])) {
			$fNazwaTow = $_POST['fNazwaTow'];
			$fCenaZak = $_POST['fCenaZak'];
			$fDostawca = $_POST['fDostawca'];
			$fUwagi = $_POST['fUwagi'];
			$id = $_GET['id'];
            $fDzial = $_POST['fDzial'];
			$fCenaZak = str_replace(",",".",$fCenaZak); 
			
			$query = "UPDATE towary SET 
										nazwa='$fNazwaTow', 
										IdDzial='$fDzial', 
										cenaZak='$fCenaZak', 
										dostawca='$fDostawca',
										uwagi='$fUwagi'
						WHERE id='$id'";
			//echo "dodano towary";
			mysqli_query($link, $query) or die ("Jakiś błąd");
			header("Location: towary.php?menu=lista");
		}
		$sql = "SELECT  t.nazwa, t.cenaZak, t.dostawca, t.uwagi, d.IdDzial, d.Nazwa as dzial
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
		echo "<TD><INPUT type='checkbox' name='fbiurowy' value='1'></TD>";
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