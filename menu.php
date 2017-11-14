
<!doctype html>
<html lang=''>
<head>
   <meta charset='utf-8'>
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="stylesheet" href="styles.css">
   <!-- <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script> -->
    <script src="script/jquery.js" type="text/javascript"></script>
    <script src="script/DataTable.js" type="text/javascript"></script>
 
   <script src="script/menu.js"></script>
   <title></title>
</head>
<body>
<?php 
	session_start();
	if (!isset($_SESSION['auth'])) {
	header("Location:login.php");
}
?>
<div id='cssmenu'>
<ul>
   <li class='active'><a href='srodek.php' TARGET='srodek'><span>Home</span></a></li>
   <li class='has-sub'><a href='#'><span>Zamowienia</span></a>
      <ul>
       <li><a href='listaodrzuconych.php' TARGET='srodek'><span>Odrzucone</span></a></li>
       <li><a href='zamowienia.php?menu=dodaj' TARGET='srodek'><span>Dodaj</span></a></li>
		 <li><a href='listawedycji.php' TARGET='srodek'><span>W edycji</span></a></li>
 		 <li><a href='listadoakceptacji.php' TARGET='srodek'><span>Do akceptacji</span></a></li>
       <li><a href='listadorealizacji.php' TARGET='srodek'><span>Do realizacji</span></a></li>
       <li class='last'><a href='listazrealizowanych.php' TARGET='srodek'><span>Zrealizowane</span></a></li>
       
      </ul>
   </li>
   <li class='has-sub'><a href='#'><span>Towary</span></a>
      <ul>
         <li><a href='towary.php?menu=dodaj' TARGET='srodek'><span>Dodaj</span></a></li>
         <li class='last'><a href='towary.php?menu=lista' TARGET='srodek'><span>Lista/Edycja</span></a></li>
      </ul>
   </li>
   <li class='has-sub'><a href='#'><span>Personel</span></a>
      <ul>
         <li><a href='personel.php?menu=dodaj' TARGET='srodek'><span>Dodaj</span></a></li>
         <li class='last'><a href='personel.php?menu=lista' TARGET='srodek'><span>Lista/Edycja</span></a></li>
      </ul>
   </li>
   <li class='has-sub'><a href='#'><span>Wydziały</span></a>
      <ul>
		  <li class='last'><a href='dzialy.php?menu=dodaj' TARGET='srodek'><span>Dodaj/Edytuj</span></a></li>
      </ul>
   </li>
   <li class='last'><a href='#'><span>Info</span></a></li>
</ul>
</div>

</body>
</html>
