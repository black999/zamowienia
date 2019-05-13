 <div class='statusZam'>
	 Lista towarów
 </div>	
 <div class='lista'>
	 <TABLE id='tabela1'>
		<THEAD>
			<TR>
				<TH>Nazwa towaru</TH>
				<TH>Dostawca</TH>
				<TH>Uwagi</TH>
				<TH>Grupa</TH>
				<TH>Biuro</TH>
				<TH class='money'>Cena zakupu</TH>
				<TH class='opcje'>Opcje</TH>
			</TR>
		</THEAD>
		<TBODY>
		<?php foreach ($towary as $towar) : ?>
			 <TR>
				 <TD style='width: 300px;'><?= $towar["nazwa"] ?></TD>
				 <TD><?= $towar["dostawca"] ?></TD>
				 <TD><?= $towar["uwagi"] ?></TD>
		         <TD><?= $towar['dzial'] ?></TD>
		         <TD><?= $towar['biurowy'] ?></TD>
				 <TD class='money'><?= $towar["cenaZak"] ?></TD>
				 <TD class='opcje'>
				 	<small>
				 	<a href='towary.php?menu=edycja&id=<?= $towar["id"] ?>'><span>Edycja</span></a>
				 	<a href="#"><span>Zam</span></a>
				 	</small>
				 </TD>
			 </TR>
		<?php endforeach ?>	 
		 </TBODY>
	 </TABLE>
 </div>