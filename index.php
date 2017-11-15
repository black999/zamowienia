<html lang=''>
<head>
  <?php require ("view/naglowek.view.php"); ?>
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
