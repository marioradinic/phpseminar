<?php

include("provjeriadminprava.php");	

if ($_SESSION['user']['rolaadmin'] == '0')
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
$query  = "Select * from medicinskipostupci order by Naziv";
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$result = @sqlsrv_query($MsSQL, $query, $params, $options);	
?>

    <table class="table table-striped">
        <thead>
          <tr>
            <th scope="col" style="width:150px"></th>
            <th scope="col">Naziv</th>   
          </tr>
        </thead>
        <tbody>

<?php
while($row = @sqlsrv_fetch_array($result)) {
	
$items_id = $row['Id'];
$items_name= $row['Naziv'];
?>
          <tr>
            <td>
              <a href="index.php?pg=<?php print $pg ?>&act=2&id=<?php print $items_id ?>" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span></a>
              <a href="index.php?pg=<?php print $pg ?>&act=3&id=<?php print $items_id ?>" class="btn btn-success"><span class="glyphicon glyphicon-pencil"></span></a>
			  <a onclick="return obrisi();" href="index.php?pg=<?php print $pg ?>&act=5&id=<?php print $items_id ?>" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span></a>
            </td>
			<td><?php print $items_name ?></td>
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
$query  = "Select * from medicinskipostupci where id=" . $_GET['id'];
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$result = @sqlsrv_query($MsSQL, $query, $params, $options);
$row = @sqlsrv_fetch_array($result);
$rowcount = sqlsrv_num_rows($result);

if ($rowcount > 0) 
{
$items_id = $row['Id'];
$items_name= $row['Naziv'];
?>
	<div class="details">
	<b>Medicinski postupak:</b><br>
	<label>Id: </label>
	<div><?php print $items_id ?></div>
	<label>Naziv: </label>
	<div><?php print $items_name ?></div>
	</div>
<?php
}
else{
	print '<div class="msginfo">Ne postoji odabrani zapis!</div>';
}
}
else if ($act == 3 && is_numeric($_GET['id']))
{
$query  = "SELECT * FROM medicinskipostupci";
$query .= " WHERE id=" . $_GET['id'];
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$result = @sqlsrv_query($MsSQL, $query, $params, $options);
$row = @sqlsrv_fetch_array($result);
$rowcount = sqlsrv_num_rows($result);

if ($rowcount > 0) 
{
$items_id = $row['Id'];
$items_name= $row['Naziv'];
?>
		<div class="details">
		<b>Medicinski postupak:</b><br>
		<form action="" id="items_form" name="items_form" method="POST" enctype="multipart/form-data">
			<input type="hidden" id="act" name="act" value="4">
			
			<label>Naziv *</label>
			<input type="text" id="name" name="name" value="<?php print $items_name ?>" placeholder="Naziv.." required>
			
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
$query  = "SELECT * FROM medicinskipostupci";
$query .= " WHERE id=" . $_GET['id'];
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$result = @sqlsrv_query($MsSQL, $query, $params, $options);
$row = @sqlsrv_fetch_array($result);
$rowcount = sqlsrv_num_rows($result);
if ($rowcount > 0) 
{
$query  = "UPDATE medicinskipostupci SET Naziv=(?)";
$query .= " WHERE id=" . $_GET['id'];
$params = array($_POST['name']);
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
$query  = "SELECT * FROM medicinskipostupci";
$query .= " WHERE id=" . $_GET['id'];
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$result = @sqlsrv_query($MsSQL, $query, $params, $options);
$row = @sqlsrv_fetch_array($result);
$rowcount = sqlsrv_num_rows($result);

if ($rowcount > 0) 
{
	$items_id = $row['Id'];

	$query  = "DELETE FROM medicinskipostupci";
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
		<b>Medicinski postupak:</b><br>
		<form action="" id="items_form" name="items_form" method="POST" enctype="multipart/form-data">
			<input type="hidden" id="act" name="act" value="7">
			
			<label>Naziv *</label>
			<input type="text" id="name" name="name" placeholder="Naziv.." required>
			
			<hr>
			
			<input type="submit" class="btn btn-primary btn-lg" value="Dodaj">
		</form>
		</div>
	<?php	
}
else if ($act == 7)
{
	$query  = "INSERT INTO medicinskipostupci (Naziv)";
	$query .= " VALUES (?)";
	$params = array($_POST['name']);
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