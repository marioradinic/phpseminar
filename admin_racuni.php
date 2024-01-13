<?php

include("provjeriadminprava.php");

if ($_SESSION['user']['rolamedosoblje'] == '0')
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
$query  = "Select r.*, kor.ime + ' ' + kor.prezime Pacijent from racuni r inner join korisnici kor on r.Korisnik_Id=kor.id";
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$result = @sqlsrv_query($MsSQL, $query, $params, $options);	
?>

    <table class="table table-striped">
        <thead>
          <tr>
            <th scope="col" style="width:150px"></th>
            <th scope="col">Broj</th>
			<th scope="col">Datum</th>
			<th scope="col">Iznos</th>
			<th scope="col">Pacijent</th>
			<th scope="col">Plačeno</th>
          </tr>
        </thead>
        <tbody>

<?php
while($row = @sqlsrv_fetch_array($result)) {
	
$items_id = $row['Id'];
$items_BrojRacuna= $row['BrojRacuna'];
$items_DatumRacuna = $row['DatumRacuna']->format('d. m. Y');
$items_Iznos= $row['Iznos'];
$items_Pacijent = $row['Pacijent'];
$items_Aktivan = ($row['Aktivan'] ? 'DA' : 'NE');
$items_Placen = ($row['Placen'] ? 'DA' : 'NE');
?>
          <tr>
            <td>
              <a href="index.php?pg=<?php print $pg ?>&act=2&id=<?php print $items_id ?>" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span></a>
              <a href="index.php?pg=<?php print $pg ?>&act=3&id=<?php print $items_id ?>" class="btn btn-success"><span class="glyphicon glyphicon-pencil"></span></a>
			  <a onclick="return obrisi();" href="index.php?pg=<?php print $pg ?>&act=5&id=<?php print $items_id ?>" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span></a>
            </td>
			<td><?php print $items_BrojRacuna ?></td>
            <td><?php print $items_DatumRacuna ?></td>
            <td><?php print $items_Iznos ?></td>
			<td><?php print $items_Pacijent ?></td>
			<td><?php print $items_Placen ?></td>
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
$query  = "Select r.*, kor.ime + ' ' + kor.prezime Pacijent, kor2.ime + ' ' + kor2.prezime Kreirao from racuni r inner join korisnici kor on r.Korisnik_Id=kor.id inner join korisnici kor2 on r.Kreirao_Id=kor2.id where r.Id=" . $_GET['id'];
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$result = @sqlsrv_query($MsSQL, $query, $params, $options);
$row = @sqlsrv_fetch_array($result);
$rowcount = sqlsrv_num_rows($result);

if ($rowcount > 0) 
{
$items_id = $row['Id'];
$items_BrojRacuna= $row['BrojRacuna'];
$items_DatumRacuna = $row['DatumRacuna']->format('d. m. Y');
$items_Iznos= $row['Iznos'];
$items_Pacijent = $row['Pacijent'];
$items_Aktivan = ($row['Aktivan'] ? 'DA' : 'NE');
$items_Placen = ($row['Placen'] ? 'DA' : 'NE');
$items_Kreirao = $row['Kreirao'];
?>
	<div class="details">
	<b>Račun:</b><br>
	<label>Id: </label>
	<div><?php print $items_id ?></div>
	<label>Broj: </label>
	<div><?php print $items_BrojRacuna ?></div>
	<label>Datum računa: </label>
	<div><?php print $items_DatumRacuna ?></div>
	<label>Iznos: </label>
	<div><?php print $items_Iznos ?></div>
	<label>Pacijent: </label>
	<div><?php print $items_Pacijent ?></div>
	<label>Plačeno: </label>
	<div><?php print $items_Placen ?></div>
	<label>Kreirao: </label>
	<div><?php print $items_Kreirao ?></div>
	</div>
<?php
}
else{
	print '<div class="msginfo">Ne postoji odabrani zapis!</div>';
}
}
else if ($act == 3 && is_numeric($_GET['id']))
{
$query  = "Select r.*, kor.ime + ' ' + kor.prezime Pacijent, kor2.ime + ' ' + kor2.prezime Kreirao from racuni r inner join korisnici kor on r.Korisnik_Id=kor.id inner join korisnici kor2 on r.Kreirao_Id=kor2.id where r.Id=" . $_GET['id'];
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$result = @sqlsrv_query($MsSQL, $query, $params, $options);
$row = @sqlsrv_fetch_array($result);
$rowcount = sqlsrv_num_rows($result);

if ($rowcount > 0) 
{
$items_id = $row['Id'];
$items_BrojRacuna= $row['BrojRacuna'];
$items_DatumRacuna = $row['DatumRacuna']->format('d.m.Y');
$items_Iznos= $row['Iznos'];
$items_Pacijent = $row['Korisnik_Id'];
$items_Placen = $row['Placen'];
?>
		<div class="details">
		<b>Račun:</b><br>
		<form action="" id="items_form" name="items_form" method="POST" enctype="multipart/form-data">
			<input type="hidden" id="act" name="act" value="4">
			
			<label>Broj *</label>
			<input type="text" id="brojRacuna" name="brojRacuna" value="<?php print $items_BrojRacuna ?>" placeholder="Broj računa.." disabled>
			
			<label>Datum računa *</label>
			<input type="text" id="datumRacuna" name="datumRacuna" class="daterange" value="<?php print $items_DatumRacuna ?>" placeholder="Datum računa.." required>
			
			<label>Iznos *</label>
			<input type="text" id="iznos" name="iznos" value="<?php print $items_Iznos ?>" placeholder="Iznos.." required>
			
			<label>Pacijent *</label>
			<select id="korisnik" name="korisnik">
			<?php 
			$query  = "Select kor.*, prezime + ' ' + ime Pacijent from korisnici kor inner join pravakorisnika pr on kor.uloga_id=pr.id where pr.Naziv='Pacijent' order by prezime";
			$result = @sqlsrv_query($MsSQL, $query);
			while($row = @sqlsrv_fetch_array($result)) {
			$selected='';
			if ($row['Id'] == $items_Pacijent)
			{
				$selected='selected';
			}
			print '<option ' . $selected . ' value="' . $row['Id'] . '">' . $row['Pacijent'] . '</option>';
			}
			?>
			</select>

			<label>Plačeno:</label>
            <input type="radio" name="placen" value="Y" <?php ($items_Placen ? print 'checked' : '') ?>> DA &nbsp;&nbsp;
			<input type="radio" name="placen" value="N" <?php ($items_Placen == false ? print 'checked' : '') ?>> NE
			
			<hr>
			
			<input type="submit" class="btn btn-primary btn-lg" value="Izmijeni">
		</form>
		</div>
<?php
}
else{
	print '<div class="msginfo">Ne postoji odabrani zapis!</div>';
}
}
else if ($act == 4)
{
$query  = "SELECT * FROM racuni";
$query .= " WHERE id=" . $_GET['id'];
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$result = @sqlsrv_query($MsSQL, $query, $params, $options);
$row = @sqlsrv_fetch_array($result);
$rowcount = sqlsrv_num_rows($result);
if ($rowcount > 0) 
{
$query  = "UPDATE racuni SET DatumRacuna=(?), Korisnik_Id=(?), Iznos=(?), Placen=(?)";
$query .= " WHERE id=" . $_GET['id'];
$params = array(date('Y-m-d',strtotime($_POST['datumRacuna'])), $_POST['korisnik'], $_POST['iznos'], $_POST['placen'] == 'Y');
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
	$query  = "SELECT * FROM racuni";
	$query .= " WHERE id=" . $_GET['id'];
	$params = array();
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	$result = @sqlsrv_query($MsSQL, $query, $params, $options);
	$row = @sqlsrv_fetch_array($result);
	$rowcount = sqlsrv_num_rows($result);

	if ($rowcount > 0) 
	{
	$items_id = $row['Id'];

		$query  = "DELETE FROM racuni";
		$query .= " WHERE id=" . $_GET['id'];
		$result = @sqlsrv_query($MsSQL, $query);
		if ($result)
		{
		print '<div class="msginfo">Obrisan je zapis id: '. $items_id .'.</div>';
		}
		else
		{
		print '<div class="msginfo">Ne može se obrisati odabrani zapis.</div>';
		}
	}
else{
	print '<div class="msginfo">Ne postoji odabrani zapis!</div>';
}
}
else if ($act == 6)
{
	?>	
	<div class="details">
		<b>Račun:</b><br>
		<form action="" id="items_form" name="items_form" method="POST" enctype="multipart/form-data">
			<input type="hidden" id="act" name="act" value="7">
			
			<label>Datum računa *</label>
			<input type="text" id="datumRacuna" name="datumRacuna" class="daterange" value="<?php print date("d.m.Y") ?>" placeholder="DD.MM.YYYY" required>
			
			<label>Iznos *</label>
			<input type="text" id="iznos" name="iznos" placeholder="0.00" required>
			
			<label>Pacijent *</label>
			<select id="korisnik" name="korisnik">
			<?php 
			$query  = "Select kor.*, prezime + ' ' + ime Pacijent from korisnici kor inner join pravakorisnika pr on kor.uloga_id=pr.id where pr.Naziv='Pacijent' order by prezime";
			$result = @sqlsrv_query($MsSQL, $query);
			while($row = @sqlsrv_fetch_array($result)) {
			print '<option value="' . $row['Id'] . '">' . $row['Pacijent'] . '</option>';
			}
			?>
			</select>

			<label>Plačeno:</label>
            <input type="radio" name="placen" value="Y" checked> DA &nbsp;&nbsp;
			<input type="radio" name="placen" value="N"> NE

			<hr>
			
			<input type="submit" class="btn btn-primary btn-lg" value="Dodaj">
		</form>
		</div>
	<?php	
}
else if ($act == 7)
{
	$query  = "INSERT INTO racuni (DatumRacuna, Korisnik_Id, Iznos, Placen, Kreirao_Id)";
	$query .= " VALUES (?, ?, ?, ?, ?); SELECT SCOPE_IDENTITY()";
	$params = array(date('Y-m-d',strtotime($_POST['datumRacuna'])), $_POST['korisnik'], $_POST['iznos'], $_POST['placen'] == 'Y', $_SESSION['user']['id']);
	$result = sqlsrv_query($MsSQL, $query, $params);
	sqlsrv_next_result($result); 
	sqlsrv_fetch($result); 
	$racunid=sqlsrv_get_field($result, 0); 

	$query  = "UPDATE racuni SET BrojRacuna=(?)";
	$query .= " WHERE id=" . $racunid;
	$params = array('R'.date("Y").str_pad($racunid, 7, '0', STR_PAD_LEFT));
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
<link rel="stylesheet" href="js/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" />
<script src="js/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="js/bootstrap-datepicker/locales/bootstrap-datepicker.hr.min.js"></script>
<script type="text/javascript">
	$('#items_form input.daterange').datepicker({
    format: 'dd.mm.yyyy',
    todayBtn: true,
    clearBtn: true,
    todayHighlight: true,
    language: 'hr'
});
</script>