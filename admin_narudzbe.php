<?php

include("provjeriadminprava.php");

if (!($_SESSION['user']['rolamedosoblje'] == '1' || $_SESSION['user']['roladoktor'] == '1'))
{
	header("Location: index.php?pg=7");
}

$act = 1;

if(isset($_GET['act'])) { $act = $_GET['act']; }

if(isset($_POST['act'])) { $act = $_POST['act']; }

?>
<?php
if ($act == 1)
{
$query  = "Select n.*, kor.ime + ' ' + kor.prezime Pacijent, b.naziv Bolnica, mp.naziv Postupak, st.Naziv Status, DatumTermina ";
$query .="from narudzbe n inner join korisnici kor on n.Korisnik_Id=kor.id ";
$query .="inner join statusinarudzbe st on n.status_id=st.id ";
$query .="inner join termini t on n.termin_Id=t.id ";
$query .="inner join bolnice b on t.Bolnica_Id=b.id ";
$query .="inner join medicinskipostupci mp on t.MedicinskiPostupak_Id=mp.id";
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$result = @sqlsrv_query($MsSQL, $query, $params, $options);	
?>

    <table class="table table-striped">
        <thead>
          <tr>
            <th scope="col" style="width:150px"></th>
            <th scope="col">Broj</th>
			<th scope="col">Datum termina</th>
			<th scope="col">Bolnica</th>
			<th scope="col">Postupak</th>
			<th scope="col">Pacijent</th>
			<th scope="col">Status</th>
          </tr>
        </thead>
        <tbody>

<?php
while($row = @sqlsrv_fetch_array($result)) {
	
$items_id = $row['Id'];
$items_BrojNarudzbe= $row['BrojNarudzbe'];
$items_DatumTermina = $row['DatumTermina']->format('d. m. Y');
$items_Bolnica= $row['Bolnica'];
$items_Postupak = $row['Postupak'];
$items_Pacijent = $row['Pacijent'];
$items_Status = $row['Status'];
?>
          <tr>
            <td>
              <a href="index.php?pg=<?php print $pg ?>&act=2&id=<?php print $items_id ?>" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span></a>
              <a href="index.php?pg=<?php print $pg ?>&act=3&id=<?php print $items_id ?>" class="btn btn-success"><span class="glyphicon glyphicon-pencil"></span></a>
			  <a onclick="return otkazi();" href="index.php?pg=<?php print $pg ?>&act=5&id=<?php print $items_id ?>" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span></a>
            </td>
			<td><?php print $items_BrojNarudzbe ?></td>
            <td><?php print $items_DatumTermina ?></td>
            <td><?php print $items_Bolnica ?></td>
			<td><?php print $items_Postupak ?></td>
			<td><?php print $items_Pacijent ?></td>
			<td><?php print $items_Status ?></td>
          </tr>
<?php
}
?>
        </tbody>
      </table>
	  <a href="index.php?pg=<?php print $pg ?>&act=6" class="btn btn-primary btn-lg">Dodaj novo</a>
<?php
}
else if ($act == 2 && is_numeric($_GET['id']))
{
$query  = "Select n.*, kor.ime + ' ' + kor.prezime Pacijent, b.naziv Bolnica, mp.naziv Postupak, kor2.ime + ' ' + kor2.prezime Kreirao, st.Naziv Status, DatumTermina ";
$query .="from narudzbe n ";
$query .="inner join korisnici kor on n.Korisnik_Id=kor.id ";
$query .="inner join korisnici kor2 on n.Kreirao_Id=kor2.id ";
$query .="inner join statusinarudzbe st on n.status_id=st.id ";
$query .="inner join termini t on n.termin_Id=t.id ";
$query .="inner join bolnice b on t.Bolnica_Id=b.id ";
$query .="inner join medicinskipostupci mp on t.MedicinskiPostupak_Id=mp.id ";
$query .="where n.Id=" . $_GET['id'];
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$result = @sqlsrv_query($MsSQL, $query, $params, $options);
$row = @sqlsrv_fetch_array($result);
$rowcount = sqlsrv_num_rows($result);

if ($rowcount > 0) 
{
$items_id = $row['Id'];
$items_BrojNarudzbe= $row['BrojNarudzbe'];
$items_DatumTermina = $row['DatumTermina']->format('d. m. Y');
$items_Bolnica= $row['Bolnica'];
$items_Postupak = $row['Postupak'];
$items_Pacijent = $row['Pacijent'];
$items_Status = $row['Status'];
$items_Kreirao = $row['Kreirao'];
$items_DatumKreiranjaNarudzbe = $row['DatumKreiranjaNarudzbe']->format('d. m. Y');
?>
	<div class="details">
	<b>Narudžba:</b><br>
	<label>Id: </label>
	<div><?php print $items_id ?></div>
	<label>Broj: </label>
	<div><?php print $items_BrojNarudzbe ?></div>
	<label>Datum termina: </label>
	<div><?php print $items_DatumTermina ?></div>
	<label>Bolnica: </label>
	<div><?php print $items_Bolnica ?></div>
	<label>Medicinski postupak: </label>
	<div><?php print $items_Postupak ?></div>
	<label>Pacijent: </label>
	<div><?php print $items_Pacijent ?></div>
	<label>Status: </label>
	<div><?php print $items_Status ?></div>
	<label>Kreirao: </label>
	<div><?php print $items_Kreirao ?></div>
	<label>Datum kreiranja narudžbe: </label>
	<div><?php print $items_DatumKreiranjaNarudzbe ?></div>
	</div>
<?php
}
else{
	print '<div class="msginfo">Ne postoji odabrani zapis!</div>';
}
}
else if ($act == 3 && is_numeric($_GET['id']))
{
	$query  = "Select n.*, kor.ime + ' ' + kor.prezime Pacijent, b.naziv Bolnica, mp.naziv Postupak, kor2.ime + ' ' + kor2.prezime Kreirao, st.Naziv Status, DatumTermina ";
	$query .="from narudzbe n ";
	$query .="inner join korisnici kor on n.Korisnik_Id=kor.id ";
	$query .="inner join korisnici kor2 on n.Kreirao_Id=kor2.id ";
	$query .="inner join statusinarudzbe st on n.status_id=st.id ";
	$query .="inner join termini t on n.termin_Id=t.id ";
	$query .="inner join bolnice b on t.Bolnica_Id=b.id ";
	$query .="inner join medicinskipostupci mp on t.MedicinskiPostupak_Id=mp.id ";
	$query .="where n.Id=" . $_GET['id'];
	$params = array();
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	$result = @sqlsrv_query($MsSQL, $query, $params, $options);
	$row = @sqlsrv_fetch_array($result);
	$rowcount = sqlsrv_num_rows($result);

	if ($rowcount > 0) 
	{
	$items_id = $row['Id'];
	$items_BrojNarudzbe = $row['BrojNarudzbe'];
	$items_Termin = $row['Termin_Id'];
	$items_Status = $row['Status'];
	if ($items_Status != "Aktivna")
	{
		print '<div class="msginfo">Narudžba nije aktivna. Ne možete raditi promjene!</div>';
	}
	else
	{
?>
		<div class="details">
		<b>Narudžba:</b><br>
		<form action="" id="items_form" name="items_form" method="POST" enctype="multipart/form-data">
			<input type="hidden" id="act" name="act" value="4">
			
			<label>Broj *</label>
			<input type="text" id="brojNarudzbe" name="brojNarudzbe" value="<?php print $items_BrojNarudzbe ?>" placeholder="Broj narudžba.." disabled>
			
			<label>Termin *</label>
			<select id="termin" name="termin">
			<?php 
			$query  = "Select t.*, mp.naziv + ' ' + b.naziv + ' ' + format(t.datumtermina,'(dd.MM.yyyy)') Termin from termini t ";
			$query .="inner join bolnice b on t.Bolnica_Id=b.id ";
			$query .="inner join medicinskipostupci mp on t.MedicinskiPostupak_Id=mp.id ";
			$query .="left join narudzbe n on t.id=n.Termin_id ";
			$query .="left join statusinarudzbe st on n.status_id=st.id ";
			$query .="where (DatumTermina>=GETDATE() and (n.id is null or n.status_id=2)) or t.id=" . $items_Termin;
			$query .=" order by mp.naziv, DatumTermina asc";
			$result = @sqlsrv_query($MsSQL, $query);
			while($row = @sqlsrv_fetch_array($result)) {
			$selected='';
			if ($row['Id'] == $items_Termin)
			{
				$selected='selected';
			}
			print '<option ' . $selected . ' value="' . $row['Id'] . '">' . $row['Termin'] . '</option>';
			}
			?>
			</select>

			<hr>
			
			<input type="submit" class="btn btn-primary btn-lg" value="Izmijeni">
		</form>
		</div>
<?php
}
}
else{
	print '<div class="msginfo">Ne postoji odabrani zapis!</div>';
}
}
else if ($act == 4)
{
$query  = "SELECT * FROM Narudzbe";
$query .= " WHERE id=" . $_GET['id'];
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$result = @sqlsrv_query($MsSQL, $query, $params, $options);
$row = @sqlsrv_fetch_array($result);
$rowcount = sqlsrv_num_rows($result);
if ($rowcount > 0) 
{
$query  = "UPDATE Narudzbe SET Termin_Id=(?)";
$query .= " WHERE id=" . $_GET['id'];
$params = array($_POST['termin']);
$result = sqlsrv_query($MsSQL, $query, $params);

$items_id = $_GET['id'];

print '<div class="msginfo">Ažuriran je zapis id: '. $items_id .'.</div>';		
}
else{
	print '<div class="msginfo">Ne postoji odabrani zapis!</div>';
}
}
else if ($act == 5 && is_numeric($_GET['id']))
{
	$query  = "Select n.*, st.naziv Status from Narudzbe n ";
	$query .="inner join statusinarudzbe st on n.status_id=st.id ";
	$query .="where n.Id=" . $_GET['id'];
	$params = array();
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	$result = @sqlsrv_query($MsSQL, $query, $params, $options);
	$row = @sqlsrv_fetch_array($result);
	$rowcount = sqlsrv_num_rows($result);

	if ($rowcount > 0) 
	{
		$items_id = $row['Id'];
		$items_Status = $row['Status'];
		if ($items_Status != "Aktivna")
		{
			print '<div class="msginfo">Narudžba nije aktivna. Možete otkazati samo aktivnu narudžbu!</div>';
		}
		else
		{
			$query  = "UPDATE Narudzbe SET Status_Id=(?)";
			$query .= " WHERE id=" . $_GET['id'];
			$params = array(2);
			$result = sqlsrv_query($MsSQL, $query, $params);
			if ($result)
			{
			print '<div class="msginfo">Uspješno otkazan zapis id: '. $items_id .'.</div>';
			}
			else
			{
			print '<div class="msginfo">Ne može se obrisati odabrani zapis.</div>';
			}
		}
	}
else{
	print '<div class="msginfo">Ne postoji odabrani zapis!</div>';
}
}
else if ($act == 6)
{
	$query  = "Select t.*, mp.naziv + ' ' + b.naziv + ' ' + format(t.datumtermina,'(dd.MM.yyyy)') Termin from termini t ";
	$query .="inner join bolnice b on t.Bolnica_Id=b.id ";
	$query .="inner join medicinskipostupci mp on t.MedicinskiPostupak_Id=mp.id ";
	$query .="left join narudzbe n on t.id=n.Termin_id ";
	$query .="left join statusinarudzbe st on n.status_id=st.id ";
	$query .="where DatumTermina>GETDATE() and (n.id is null or n.status_id=2) ";
	$query .="order by mp.naziv, DatumTermina asc ";
	$params = array();
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	$resulttermini = @sqlsrv_query($MsSQL, $query, $params, $options);
	$rowcount = sqlsrv_num_rows($resulttermini);

	if ($rowcount == 0)
	{
		print '<div class="msginfo">Nema aktivnih termina!</div>';
	}
	else
	{
	?>	
	<div class="details">
		<b>Narudžba:</b><br>
		<form action="" id="items_form" name="items_form" method="POST" enctype="multipart/form-data">
			<input type="hidden" id="act" name="act" value="7">
			
			<label>Pacijent *</label>
			<select id="pacijent" name="pacijent">
			<?php 
			$query  = "Select *, prezime + ' ' + ime Pacijent from korisnici kor inner join pravakorisnika pr on kor.uloga_id=pr.id where pr.Naziv='Pacijent' order by prezime";
			$result = @sqlsrv_query($MsSQL, $query);
			while($row = @sqlsrv_fetch_array($result)) {
			print '<option value="' . $row['Id'] . '">' . $row['Pacijent'] . '</option>';
			}
			?>
			</select>

			<label>Termin *</label>
			<select id="termin" name="termin">
			<?php 
			while($row = @sqlsrv_fetch_array($resulttermini)) {
			print '<option value="' . $row['Id'] . '">' . $row['Termin'] . '</option>';
			}
			?>
			</select>

			<hr>
			
			<input type="submit" class="btn btn-primary btn-lg" value="Dodaj">
		</form>
		</div>
	<?php
	}
}
else if ($act == 7)
{
	$query  = "INSERT INTO Narudzbe (Korisnik_Id, Termin_Id, Kreirao_Id)";
	$query .= " VALUES (?, ?, ?); SELECT SCOPE_IDENTITY()";
	$params = array($_POST['pacijent'], $_POST['termin'], $_SESSION['user']['id']);
	$result = sqlsrv_query($MsSQL, $query, $params);
	sqlsrv_next_result($result); 
	sqlsrv_fetch($result); 
	$id=sqlsrv_get_field($result, 0); 

	$query  = "UPDATE Narudzbe SET BrojNarudzbe=(?)";
	$query .= " WHERE id=" . $id;
	$params = array('N'.date("Y").str_pad($id, 7, '0', STR_PAD_LEFT));
	$result = sqlsrv_query($MsSQL, $query, $params);

	print '<div class="msginfo">Dodan je novi zapis.</div>';	
}
?>	
<?php
if ($act != 1)
{
?>	
<div style="margin-top:20px;padding-left:15px"><a href="index.php?pg=<?php print $pg ?>&act=1">Natrag</a></div>
<?php
}
?>