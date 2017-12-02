<!DOCTYPE html>
<html>
<head>
	<?php require ("view/naglowek.view.php"); ?>
	<title>Lista faktur</title>
</head>
<body>
<?php if (isset($errorMessage))  : ?>
	<div class="statusZam">
		<?= $errorMessage ?>
	</div>
	<?php die(); ?>
<?php endif;  ?>


<div class="statusZam">
	<?= $tytul ?>
</div>
<div class="lista">
	<form id="form1" action="faktury.php?dodaj" method="POST" enctype="multipart/form-data">
		<!-- MAX_FILE_SIZE must precede the file input field -->
    	<input type="hidden" name="MAX_FILE_SIZE" value="300000" />
		<label>Wybierz fakturę do wysłania wielkość do 3 MB: </label> 
		<input type="file" name="plik">
		<label>Podaj opis faktury</label>
		<input type="text" name="opis">
		<button type="submit" name="submit">DODAJ</button>
	</form>
	<br>
	<table>
		<thead>
			<tr>
				<td>Data dodania</td>
				<td>Opis faktury </td>
				<td>Nazwa Pliku</td>
			</tr>	
		</thead>
		<tbody>
			<?php if ($faktury) : ?>
				<?php foreach ($faktury as $faktura) : ?>
					<tr>
						<td><= $faktura['dataDodania'] ?></td>
						<td><= $faktura['opis'] ?></td>
						<td><= $faktura['nazwaPliku'] ?></td>
					</tr>
				<?php endforeach ?>
			<?php endif; ?>
		</tbody>
	</table>
</div>
<script>
	$(document).ready(function() {
		$("#form1").submit(function(event) {
			$dane = $(this);
			$dane = $dane.serialize()
			alert('dane przekazane' + $dane);
		});
		
	});
</script>
</body>
</html>