<html lang=pl>
<head>
   <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" >
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="stylesheet" href="styles.css">
   <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
   <script src="script.js"></script>
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