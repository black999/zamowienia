<?php 
   require_once 'nazwadb.inc.php';
   require 'core/funkcje.php';
	session_start();
   $link = polaczZBaza($host, $uzytkownik, $haslo, $nazwabazydanych);
   $dzial = getDzialById($link, $_SESSION['sIdDzial']);
   $warunek = "StatusZatw = 0 AND p.id = {$_SESSION['sId']}";
   $wEdycji = getZamowienia($link, $warunek);
   $warunek2 = getWarunekByUprawnienia($_SESSION['sUpr'], $_SESSION['sId'], $_SESSION['sIdDzial']);
   $doAkceptacji = getZamowienia($link, $warunek2); 
   $warunek3 = "p.Dzial = {$_SESSION['sIdDzial']} and zm.StatusReal = '0' and akcPrez <> '0' ";
   $doRealizacji = getZamowienia($link, $warunek3);


?>
<html>
<head>
   <meta charset='utf-8'>
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="stylesheet" href="css/styles.css">
   <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
   <script src="script/script.js"></script>
   <title></title>
</head>
<body>

<div class='zawartosc'>
	Program do obsługi zamówień<br>
	wersja 1.1<br>
</div>
<div class="formatka">
   <p>Zalogowany : <b><?= $_SESSION['sNazwisko'] ?> <?=$_SESSION['sImie'] ?></b></p>
   <p>Dział : <b><?= $dzial->Nazwa ?></b></p>
   <hr>
</div>
<?php if ($wEdycji) : ?>
   <div class="formatka okno">
      <h3>Lista zamówień w edycji</h3>
      <div class="lista">
         <table id="tabela1">
            <thead>
            <tr>
               <th>Data zam.</th>
               <th>Zamawiający</th>
               <th>Dział</th>
               <!-- <th>Stat. Realizacji</th> -->
               <th>Kier</th>
               <th>Zam. Pub</th>
               <th>Księg</th>
               <th>Prezes</th>
               <th class="money">Wartość</th>
               <th class="money">Opcje</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($wEdycji as $zamowienie) : ?>
               <tr>
                  <td><?= $zamowienie->Data ?></td>
                  <td><?= $zamowienie->Imie . " ". $zamowienie->Nazwisko ?></td>
                  <td><?= $zamowienie->Dzial ?></td>
                  <!-- <td><?= $zamowienie->StatusReal ?></td> -->
                  <td class="akceptacja"><?php if($zamowienie->akcKier != '0') echo "+" ?></td>
                  <td class="akceptacja"><?php if($zamowienie->akcZam != '0') echo "+" ?></td>
                  <td class="akceptacja"><?php if($zamowienie->akcKsie != '0') echo "+" ?></td>
                  <td class="akceptacja"><?php if($zamowienie->akcPrez != '0') echo "+" ?></td>
                  <td class="money"><?= $zamowienie->wartosc . " zł " ?></td>
                  <?php if (isset($widok) && $widok == 'okno') : ?>
                     <td class="money"><button data-id="<?= $zamowienie->IdZamowienia ?>"> Szczegóły</button></td>
                  <?php else : ?>
                     <td class="money"><a href="szczegolyzam.php?fIdzam=<?= $zamowienie->IdZamowienia ?>"> Szczegóły</a></td>
                  <?php endif ?>
               </tr>
            <?php endforeach ; ?>
            </tbody>
         </table>
      </div>
   </div>
<?php endif ; ?>
<?php if ($doAkceptacji) : ?>
   <div class="formatka okno">
      <h3>Lista zamówień do akceptacji</h3>
      <div class="lista">
         <table id="tabela1" >
            <thead>
            <tr>
               <th>Data zam.</th>
               <th>Zamawiający</th>
               <th>Dział</th>
               <!-- <th>Stat. Realizacji</th> -->
               <th>Kier</th>
               <th>Zam. Pub</th>
               <th>Księg</th>
               <th>Prezes</th>
               <th class="money">Wartość</th>
               <th class="money">Opcje</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($doAkceptacji as $zamowienie) : ?>
               <tr>
                  <td><?= $zamowienie->Data ?></td>
                  <td><?= $zamowienie->Imie . " ". $zamowienie->Nazwisko ?></td>
                  <td><?= $zamowienie->Dzial ?></td>
                  <!-- <td><?= $zamowienie->StatusReal ?></td> -->
                  <td class="akceptacja"><?php if($zamowienie->akcKier != '0') echo "+" ?></td>
                  <td class="akceptacja"><?php if($zamowienie->akcZam != '0') echo "+" ?></td>
                  <td class="akceptacja"><?php if($zamowienie->akcKsie != '0') echo "+" ?></td>
                  <td class="akceptacja"><?php if($zamowienie->akcPrez != '0') echo "+" ?></td>
                  <td class="money"><?= $zamowienie->wartosc . " zł " ?></td>
                  <?php if (isset($widok) && $widok == 'okno') : ?>
                     <td class="money"><button data-id="<?= $zamowienie->IdZamowienia ?>"> Szczegóły</button></td>
                  <?php else : ?>
                     <td class="money"><a href="szczegolyzam.php?fIdzam=<?= $zamowienie->IdZamowienia ?>"> Szczegóły</a></td>
                  <?php endif ?>
               </tr>
            <?php endforeach ; ?>
            </tbody>
         </table>
      </div>
   </div>
<?php endif ; ?>

<?php if ($doRealizacji) : ?>
   <div class="formatka okno">
      <h3>Lista zamówień do realizacji</h3>
      <div class="lista">
         <table id="tabela1" >
            <thead>
            <tr>
               <th>Data zam.</th>
               <th>Zamawiający</th>
               <th>Dział</th>
               <!-- <th>Stat. Realizacji</th> -->
               <th>Kier</th>
               <th>Zam. Pub</th>
               <th>Księg</th>
               <th>Prezes</th>
               <th class="money">Wartość</th>
               <th class="money">Opcje</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($doRealizacji as $zamowienie) : ?>
               <tr>
                  <td><?= $zamowienie->Data ?></td>
                  <td><?= $zamowienie->Imie . " ". $zamowienie->Nazwisko ?></td>
                  <td><?= $zamowienie->Dzial ?></td>
                  <!-- <td><?= $zamowienie->StatusReal ?></td> -->
                  <td class="akceptacja"><?php if($zamowienie->akcKier != '0') echo "+" ?></td>
                  <td class="akceptacja"><?php if($zamowienie->akcZam != '0') echo "+" ?></td>
                  <td class="akceptacja"><?php if($zamowienie->akcKsie != '0') echo "+" ?></td>
                  <td class="akceptacja"><?php if($zamowienie->akcPrez != '0') echo "+" ?></td>
                  <td class="money"><?= $zamowienie->wartosc . " zł " ?></td>
                  <?php if (isset($widok) && $widok == 'okno') : ?>
                     <td class="money"><button data-id="<?= $zamowienie->IdZamowienia ?>"> Szczegóły</button></td>
                  <?php else : ?>
                     <td class="money"><a href="szczegolyzam.php?fIdzam=<?= $zamowienie->IdZamowienia ?>"> Szczegóły</a></td>
                  <?php endif ?>
               </tr>
            <?php endforeach ; ?>
            </tbody>
         </table>
      </div>
   </div>
<?php endif ; ?>

</body>
</html>
