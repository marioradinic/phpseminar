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
$query  = "Select t.*, b.naziv Bolnica, mp.naziv Postupak from termini t inner join bolnice b on t.Bolnica_Id=b.id inner join medicinskipostupci mp on t.MedicinskiPostupak_Id=mp.id";
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$result = @sqlsrv_query($MsSQL, $query, $params, $options);	
?>

    <table class="table table-striped">
        <thead>
          <tr>
            <th scope="col" style="width:150px"></th>
            <th scope="col">Datum termina</th>
			<th scope="col">Bolnica</th>
            <th scope="col">Postupak</th>
          </tr>
        </thead>
        <tbody>

<?php
while($row = @sqlsrv_fetch_array($result)) {
	
$items_id = $row['Id'];
$items_DatumTermina = $row['DatumTermina']->format('d. m. Y');
$items_Bolnica= $row['Bolnica'];
$items_Postupak = $row['Postupak'];
?>
          <tr>
            <td>
              <a href="index.php?pg=<?php print $pg ?>&act=2&id=<?php print $items_id ?>" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span></a>
              <a href="index.php?pg=<?php print $pg ?>&act=3&id=<?php print $items_id ?>" class="btn btn-success"><span class="glyphicon glyphicon-pencil"></span></a>
			  <a onclick="return obrisi();" href="index.php?pg=<?php print $pg ?>&act=5&id=<?php print $items_id ?>" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span></a>
            </td>
			<td><?php print $items_DatumTermina ?></td>
            <td><?php print $items_Bolnica ?></td>
			<td><?php print $items_Postupak ?></td>
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
$query  = "Select t.*, b.naziv Bolnica, mp.naziv Postupak from termini t inner join bolnice b on t.Bolnica_Id=b.id inner join medicinskipostupci mp on t.MedicinskiPostupak_Id=mp.id where t.Id=" . $_GET['id'];
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$result = @sqlsrv_query($MsSQL, $query, $params, $options);
$row = @sqlsrv_fetch_array($result);
$rowcount = sqlsrv_num_rows($result);

if ($rowcount > 0) 
{
$items_id = $row['Id'];
$items_DatumTermina = $row['DatumTermina']->format('d. m. Y');
$items_Bolnica= $row['Bolnica'];
$items_Postupak = $row['Postupak'];
?>
	<div class="details">
	<b>Termin:</b><br>
	<label>Id: </label>
	<div><?php print $items_id ?></div>
	<label>Datum termina: </label>
	<div><?php print $items_DatumTermina ?></div>
	<label>Bolnica: </label>
	<div><?php print $items_Bolnica ?></div>
	<label>Medicinski postupak: </label>
	<div><?php print $items_Postupak ?></div>
	</div>
<?php
}
else{
	print '<div class="msginfo">Ne postoji odabrani zapis!</div>';
}
}
else if ($act == 3 && is_numeric($_GET['id']))
{
$query  = "Select t.*, b.naziv Bolnica, mp.naziv Postupak from termini t inner join bolnice b on t.Bolnica_Id=b.id inner join medicinskipostupci mp on t.MedicinskiPostupak_Id=mp.id where t.Id=" . $_GET['id'];
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$result = @sqlsrv_query($MsSQL, $query, $params, $options);
$row = @sqlsrv_fetch_array($result);
$rowcount = sqlsrv_num_rows($result);

if ($rowcount > 0) 
{
$items_id = $row['Id'];
$items_DatumTermina = $row['DatumTermina']->format('d.m.Y');
$items_Bolnica= $row['Bolnica_Id'];
$items_Postupak = $row['MedicinskiPostupak_Id'];
?>
		<div class="details">
		<b>Termin:</b><br>
		<form action="" id="items_form" name="items_form" method="POST" enctype="multipart/form-data">
			<input type="hidden" id="act" name="act" value="4">
			
			<label>Datum termina *</label>
			<input type="text" class="daterange" id="datumTermina" name="datumTermina" value="<?php print $items_DatumTermina ?>" placeholder="Datum termina.." required>
			
			<label>Bolnica *</label>
			<select id="bolnica" name="bolnica">
			<?php 
			$query  = "SELECT * FROM bolnice order by naziv";
			$result = @sqlsrv_query($MsSQL, $query);
			while($row = @sqlsrv_fetch_array($result)) {
			$selected='';
			if ($row['Id'] == $items_Bolnica)
			{
				$selected='selected';
			}
			print '<option ' . $selected . ' value="' . $row['Id'] . '">' . $row['Naziv'] . '</option>';
			}
			?>
			</select>

			<label>Medicinski postupak *</label>
			<select id="postupak" name="postupak">
			<?php 
			$query  = "SELECT * FROM medicinskipostupci order by naziv";
			$result = @sqlsrv_query($MsSQL, $query);
			while($row = @sqlsrv_fetch_array($result)) {
			$selected='';
			if ($row['Id'] == $items_Postupak)
			{
				$selected='selected';
			}
			print '<option ' . $selected . ' value="' . $row['Id'] . '">' . $row['Naziv'] . '</option>';
			}
			?>
			</select>

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
$query  = "SELECT * FROM termini";
$query .= " WHERE id=" . $_GET['id'];
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$result = @sqlsrv_query($MsSQL, $query, $params, $options);
$row = @sqlsrv_fetch_array($result);
$rowcount = sqlsrv_num_rows($result);
if ($rowcount > 0) 
{
$query  = "UPDATE termini SET DatumTermina=(?), Bolnica_Id=(?), MedicinskiPostupak_Id=(?)";
$query .= " WHERE id=" . $_GET['id'];
$params = array(date('Y-m-d',strtotime($_POST['datumTermina'])), $_POST['bolnica'], $_POST['postupak']);
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
	$query  = "SELECT * FROM termini";
	$query .= " WHERE id=" . $_GET['id'];
	$params = array();
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	$result = @sqlsrv_query($MsSQL, $query, $params, $options);
	$row = @sqlsrv_fetch_array($result);
	$rowcount = sqlsrv_num_rows($result);

	if ($rowcount > 0) 
	{
	$items_id = $row['Id'];

		$query  = "DELETE FROM termini";
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
		<b>Termin:</b><br>
		<form action="" id="items_form" name="items_form" method="POST" enctype="multipart/form-data">
			<input type="hidden" id="act" name="act" value="7">
			
			<label>Datum termina *</label>
			<input type="text" class="daterange" id="datumTermina" name="datumTermina" value="<?php print date("d.m.Y") ?>" placeholder="DD.MM.YYYY" required>
			
			<label>Bolnica *</label>
			<select id="bolnica" name="bolnica">
			<?php 
			$query  = "SELECT * FROM bolnice order by naziv";
			$result = @sqlsrv_query($MsSQL, $query);
			while($row = @sqlsrv_fetch_array($result)) {
			$selected='';
			if ($row['Id'] == 1)
			{
				$selected='selected';
			}
			print '<option ' . $selected . ' value="' . $row['Id'] . '">' . $row['Naziv'] . '</option>';
			}
			?>
			</select>

			<label>Medicinski postupak *</label>
			<select id="postupak" name="postupak">
			<?php 
			$query  = "SELECT * FROM medicinskipostupci order by naziv";
			$result = @sqlsrv_query($MsSQL, $query);
			while($row = @sqlsrv_fetch_array($result)) {
			$selected='';
			if ($row['Id'] == 1)
			{
				$selected='selected';
			}
			print '<option ' . $selected . ' value="' . $row['Id'] . '">' . $row['Naziv'] . '</option>';
			}
			?>
			</select>

			<hr>
			
			<input type="submit" class="btn btn-primary btn-lg" value="Dodaj">
		</form>
		</div>
	<?php	
}
else if ($act == 7)
{
	$query  = "INSERT INTO termini (DatumTermina, Bolnica_Id, MedicinskiPostupak_Id)";
	$query .= " VALUES (?, ?, ?)";
	$params = array(date('Y-m-d',strtotime($_POST['datumTermina'])), $_POST['bolnica'], $_POST['postupak']);
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