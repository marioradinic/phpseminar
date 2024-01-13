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
$query  = "Select b.*, gr.naziv Grad from bolnice b inner join gradovi gr on b.grad_id=gr.id";
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$result = @sqlsrv_query($MsSQL, $query, $params, $options);	
?>

    <table class="table table-striped">
        <thead>
          <tr>
            <th scope="col" style="width:150px"></th>
            <th scope="col">Naziv</th>
			<th scope="col">Adresa</th>
            <th scope="col">Grad</th>
          </tr>
        </thead>
        <tbody>

<?php
while($row = @sqlsrv_fetch_array($result)) {
	
$items_id = $row['Id'];
$items_name= $row['Naziv'];
$items_adresa = $row['Adresa'];
$items_grad = $row['Grad'];
?>
          <tr>
            <td>
              <a href="index.php?pg=<?php print $pg ?>&act=2&id=<?php print $items_id ?>" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span></a>
              <a href="index.php?pg=<?php print $pg ?>&act=3&id=<?php print $items_id ?>" class="btn btn-success"><span class="glyphicon glyphicon-pencil"></span></a>
			  <a onclick="return obrisi();" href="index.php?pg=<?php print $pg ?>&act=5&id=<?php print $items_id ?>" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span></a>
            </td>
			<td><?php print $items_name ?></td>
            <td><?php print $items_adresa ?></td>
			<td><?php print $items_grad ?></td>
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
$query  = "Select b.*, gr.naziv Grad from bolnice b";
$query .= " inner join gradovi gr on b.grad_id=gr.id where b.Id=" . $_GET['id'];
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$result = @sqlsrv_query($MsSQL, $query, $params, $options);
$row = @sqlsrv_fetch_array($result);
$rowcount = sqlsrv_num_rows($result);

if ($rowcount > 0) 
{
$items_id = $row['Id'];
$items_name= $row['Naziv'];
$items_adresa = $row['Adresa'];
$items_grad = $row['Grad'];
$items_email = $row['Email'];
?>
	<div class="details">
	<b>Bolnica:</b><br>
	<label>Id: </label>
	<div><?php print $items_id ?></div>
	<label>Naziv: </label>
	<div><?php print $items_name ?></div>
	<label>Adresa: </label>
	<div><?php print $items_adresa ?></div>
	<label>Grad: </label>
	<div><?php print $items_grad ?></div>
	<label>Email: </label>
	<div><?php print $items_email ?></div>
	</div>
<?php
}
else{
	print '<div class="msginfo">Ne postoji odabrani zapis!</div>';
}
}
else if ($act == 3 && is_numeric($_GET['id']))
{
$query  = "SELECT * FROM bolnice";
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
$items_adresa = $row['Adresa'];
$items_email = $row['Email'];
?>
		<div class="details">
		<b>Bolnica:</b><br>
		<form action="" id="items_form" name="items_form" method="POST" enctype="multipart/form-data">
			<input type="hidden" id="act" name="act" value="4">
			
			<label>Naziv *</label>
			<input type="text" id="name" name="name" value="<?php print $items_name ?>" placeholder="Naziv.." required>
			
			<label>Adresa *</label>
			<input type="text" id="adresa" name="adresa" value="<?php print $items_adresa ?>" placeholder="Adresa.." required>
			
			<label>Email *</label>
			<input type="text" id="email" name="email" value="<?php print $items_email ?>" placeholder="Email.." required>
			
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
$query  = "SELECT * FROM bolnice";
$query .= " WHERE id=" . $_GET['id'];
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$result = @sqlsrv_query($MsSQL, $query, $params, $options);
$row = @sqlsrv_fetch_array($result);
$rowcount = sqlsrv_num_rows($result);
if ($rowcount > 0) 
{
$query  = "UPDATE bolnice SET Naziv=(?), Adresa=(?), Email=(?)";
$query .= " WHERE id=" . $_GET['id'];
$params = array($_POST['name'], $_POST['adresa'], $_POST['email']);
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
	$query  = "SELECT * FROM bolnice";
	$query .= " WHERE id=" . $_GET['id'];
	$params = array();
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	$result = @sqlsrv_query($MsSQL, $query, $params, $options);
	$row = @sqlsrv_fetch_array($result);
	$rowcount = sqlsrv_num_rows($result);

	if ($rowcount > 0) 
	{
	$items_id = $row['Id'];

		$query  = "DELETE FROM bolnice";
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
		<b>Bolnica:</b><br>
		<form action="" id="items_form" name="items_form" method="POST" enctype="multipart/form-data">
			<input type="hidden" id="act" name="act" value="7">
			
			<label>Naziv *</label>
			<input type="text" id="name" name="name" placeholder="Naziv.." required>
			
			<label>Email *</label>
			<input type="text" id="email" name="email" placeholder="Email.." required>

			<label>Adresa *</label>
			<input type="text" id="adresa" name="adresa" placeholder="Adresa.." required>
			
			<label>Grad *</label>
			<select id="grad" name="grad">
			<?php 
			$query  = "SELECT * FROM Gradovi";
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
	$query  = "INSERT INTO bolnice (Naziv, Email, Adresa, Grad_Id)";
	$query .= " VALUES (?, ?, ?, ?)";
	$params = array($_POST['name'], $_POST['email'], $_POST['adresa'], $_POST['grad']);
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