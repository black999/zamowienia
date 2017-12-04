
<!doctype html>
<html lang=''>
<head>
  <?php require ("view/naglowek.view.php"); ?>
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
       <li><a href='listBiurowychDoRealizacji.php' TARGET='srodek'><span>Do realizacji biurowe</span></a></li>
       <li class='last'><a href='listazrealizowanych.php' TARGET='srodek'><span>Zrealizowane</span></a></li>
      </ul>
   </li>
   <li class='has-sub'><a href='#'><span>Towary</span></a>
      <ul>
         <li><a href='towary.php?menu=dodaj' TARGET='srodek'><span>Dodaj</span></a></li>
         <li class='last'><a href='towary.php?menu=lista' TARGET='srodek'><span>Lista/Edycja</span></a></li>
      </ul>
   </li>
    <li class='has-sub'><a href='#'><span>Faktury</span></a>
      <ul>
         <li><a href='faktury.php' TARGET='srodek'><span>Dodaj</span></a></li>
         <li class='last'><a href='' TARGET='srodek'><span>Lista/Edycja</span></a></li>
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
