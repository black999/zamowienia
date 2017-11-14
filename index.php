<html lang=''>
<head>
   <meta charset='utf-8'>
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="stylesheet" href="styles.css">
   <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
   <script src="script/script.js"></script>
   <title>Zamowienia</title>
</head>
<?php
session_start();
if (!isset($_SESSION['auth'])) {
	header("Location: login.php");
} 
?>
<FRAMESET ROWS=50,*>

        <FRAME SRC=gora.php scrolling=no noresize name="gora" FRAMEBORDER=0 >

    <FRAMESET COLS=170,*>

        <FRAME SRC=menu.php scrolling=no noresize name="menu" FRAMEBORDER=0>

        <FRAME SRC=srodek.php scrolling=yes noresize name="srodek" FRAMEBORDER=0>

    </FRAMESET>

</FRAMESET>

</html>
