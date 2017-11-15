<html lang=pl>
<head>
	<?php require ("view/naglowek.view.php"); ?>
   <title></title>
</head>
<body>
<?php 
session_start();
if (!isset($_SESSION['auth'])) {
	header("Location:login.php");
}
?>
<div class='naglowek'>
	Zalogowany: <STRONG><?php echo $_SESSION['sImie'] . " " . $_SESSION['sNazwisko']; ?></STRONG>
	<BUTTON type=button class="button" onclick="window.location.href='login.php?menu=logout'"> Wyloguj </BUTTON>
</div>

</body>
</html>