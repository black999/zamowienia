	<div class='formatka'>   
		<h2>Nowy towar</h2><BR><BR>
		<FORM action='towary.php?menu=dodaj' method='POST'>
			<TABLE>
				<TR>
					<TD>Nazwa towaru (max 40 znaków)</TD>
					<TD><INPUT name='fNazwaTow' class='pole' size=35 maxlength=40 required autofocus ></TD>
				</TR>
				<TR>
					<TD>Cena zakupu brutto</TD>
					<TD><INPUT name='fCenaZak'class='pole' size=5 maxlength=7 required>   zł</TD>
				</TR>
				<TR>
					<TD>Grupa</TD>
					<TD><SELECT name='fDzial' class='pole'   maxlength=25>
						<OPTION value='0'></OPTION>
						<?php foreach($dzialy as $dzial) : ?> 
							<OPTION value="<?= $dzial['IdDzial'] ?>"><?= $dzial['Nazwa'] ?></OPTION>
						<?php endforeach ?>
				</SELECT></TD>
			</TR>  
			<TR>
				<TD>Dostawca</TD>
				<TD><INPUT name='fDostawca' class='pole' size=35 maxlength=40 ></TD>
			</TR>
			<TR>
				<TD>Uwagi</TD>
				<TD><INPUT name='fUwagi' class='pole' size=50 maxlength=49 ></TD>
			</TR>
			<TR>
				<TD>Towar biurowy</TD>
				<TD><INPUT type='checkbox' name='fBiurowy' value='1'></TD>
			</TR>
				<TD colspan='2' align='right'><INPUT type='SUBMIT' value='    DODAJ   '></TD>
			</TR>
		</TABLE>
	</FORM>
	<BR><BR>
</div>

<div class='lista'>  
	<BR><H2>Towary ostatnio dodane</H2>
	<TABLE>
		<TR>
			<TH>ID</TH>
			<TH>Nazwa towaru</TH>
			<TH>Grupa</TH>
			<TH>Cena zakupu</TH>
			<TH>Biurowy</TH>
			<TH>Uwagi</TH>
			<TH class='opcje'>Opcje</TH>
		</TR>
		<?php foreach ($towaryOstatnioDodane as $towar ) : ?>
			<TR>
				<TD style='width: 30px;'><?= $towar["id"] ?></TD>
				<TD style='width: 300px;'><?= $towar["nazwa"] ?></TD>
                <TD><?= $towar['dzial'] ?></TD>
				<TD><?= $towar["cenaZak"] ?></TD>
				<TD><?= $towar["biurowy"] ?></TD>
				<TD><?= $towar["uwagi"] ?></TD>
				<TD class='opcje'><a href='towary.php?menu=edycja&id=<?= $towar["id"] ?>'>Edycja</TD>
			</TR>
		<?php endforeach ?>
	</TABLE>
</div>