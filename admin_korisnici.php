<?php

include("provjeriadminprava.php");	

$act = 1;

if(isset($_GET['act'])) { $act = $_GET['act']; }

if(isset($_POST['act'])) { $act = $_POST['act']; }

?>
<?php
if ($act == 1)
{
$query  = "Select kor.*, pk.naziv Uloga from korisnici kor inner join";
$query .= " pravakorisnika pk on kor.uloga_id=pk.id where 1=1";
if ($_SESSION['user']['rolaadmin'] == '0')
{
$query .= " and pk.naziv='Pacijent'";
}
$query .= " ORDER BY VrijemeKreiranja DESC";
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$result = @sqlsrv_query($MsSQL, $query, $params, $options);	
?>

    <table class="table table-striped">
        <thead>
          <tr>
            <th scope="col" style="width:150px"></th>
            <th scope="col">Ime</th>
			<th scope="col">Prezime</th>
            <th scope="col">Korisničko ime</th>
			<th scope="col">Aktivan</th>
			<th scope="col">Uloga</th>
            <th scope="col">Vrijeme kreiranja</th>        
          </tr>
        </thead>
        <tbody>

<?php
while($row = @sqlsrv_fetch_array($result)) {
	
$items_id = $row['Id'];
$items_name= $row['Ime'];
$items_lastname = $row['Prezime'];
$items_username = $row['KorisnickoIme'];
$items_active = ($row['Aktivan'] ? 'DA' : 'NE');
$items_uloga = $row['Uloga'];
$items_created_at = $row['VrijemeKreiranja']->format('d. m. Y H:i:s');

?>
          <tr>
            <td>
              <a href="index.php?pg=<?php print $pg ?>&act=2&id=<?php print $items_id ?>" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span></a>
              <a href="index.php?pg=<?php print $pg ?>&act=3&id=<?php print $items_id ?>" class="btn btn-success"><span class="glyphicon glyphicon-pencil"></span></a>
			  <a onclick="return obrisi();" href="index.php?pg=<?php print $pg ?>&act=5&id=<?php print $items_id ?>" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span></a>
            </td>
			<td><?php print $items_name ?></td>
            <td><?php print $items_lastname ?></td>
			<td><?php print $items_username ?></td>
			<td><?php print $items_active ?></td>
            <td><?php print $items_uloga ?></td>
            <td><?php print $items_created_at ?></td>
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
$query  = "Select kor.*, pk.naziv Uloga from korisnici kor inner join";
$query .= " pravakorisnika pk on kor.uloga_id=pk.id where 1=1";
$query .= " and kor.id=" . $_GET['id'];
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$result = @sqlsrv_query($MsSQL, $query, $params, $options);
$row = @sqlsrv_fetch_array($result);
$rowcount = sqlsrv_num_rows($result);

if ($rowcount > 0) 
{
$items_id = $row['Id'];
$items_name= $row['Ime'];
$items_lastname = $row['Prezime'];
$items_username = $row['KorisnickoIme'];
$items_email = $row['Email'];
$items_active = ($row['Aktivan'] ? 'DA' : 'NE');
$items_uloga = $row['Uloga'];
$items_created_at = $row['VrijemeKreiranja']->format('d. m. Y H:i:s');
?>
	<div class="details">
	<b>Korisnik:</b><br>
	<label>Id: </label>
	<div><?php print $items_id ?></div>
	<label>Ime: </label>
	<div><?php print $items_name ?></div>
	<label>Prezime: </label>
	<div><?php print $items_lastname ?></div>
	<label>Email: </label>
	<div><?php print $items_email ?></div>
	<label>Korisničko ime: </label>
	<div><?php print $items_username ?></div>
	<label>Aktivan: </label>
	<div><?php print $items_active ?></div>
	<label>Uloga: </label>
	<div><?php print $items_uloga ?></div>
	<label>Vrijeme kreiranja: </label>
	<div><?php print $items_created_at ?></div>
	</div>
<?php
}
else{
	print '<div class="msginfo">Ne postoji odabrani zapis!</div>';
}
}
else if ($act == 3 && is_numeric($_GET['id']))
{
$query  = "SELECT * FROM korisnici";
$query .= " WHERE id=" . $_GET['id'];
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$result = @sqlsrv_query($MsSQL, $query, $params, $options);
$row = @sqlsrv_fetch_array($result);
$rowcount = sqlsrv_num_rows($result);

if ($rowcount > 0) 
{
$items_id = $row['Id'];
$items_name= $row['Ime'];
$items_lastname = $row['Prezime'];
$items_email = $row['Email'];
$items_oib = $row['Oib'];
$items_maticniBrojOsiguranika = $row['MaticniBrojOsiguranika'];
$items_active = $row['Aktivan'];
//$items_uloga = $row['Uloga'];
$items_iscurrent = ($row['Id'] == $_SESSION['user']['id'] ? 'Y' : 'N');
?>
		<div class="details">
		<b>Korisnik:</b><br>
		<form action="" id="items_form" name="items_form" method="POST" enctype="multipart/form-data">
			<input type="hidden" id="act" name="act" value="4">
			
			<label>Ime *</label>
			<input type="text" id="name" name="name" value="<?php print $items_name ?>" placeholder="Ime.." required>
			
			<label>Prezime *</label>
			<input type="text" id="lastname" name="lastname" value="<?php print $items_lastname ?>" placeholder="Prezime.." required>
			
			<label>Email *</label>
			<input type="text" id="email" name="email" value="<?php print $items_email ?>" placeholder="Email.." required>
			
			<label>Oib *</label>
			<input type="text" id="oib" name="oib" value="<?php print $items_oib ?>" placeholder="Oib.." maxlength="11" minlength="11" required>
			
			<label>Matični Broj Osiguranika *</label>
			<input type="text" id="maticniBrojOsiguranika" value="<?php print $items_maticniBrojOsiguranika ?>" maxlength="9" minlength="9" name="maticniBrojOsiguranika" placeholder="Matični Broj Osiguranika.." required>
			
			<label>Aktivan:</label>
            <input type="radio" <?php ($items_iscurrent == 'Y' ? print 'disabled' : '') ?> name="active" value="Y" <?php ($items_active ? print 'checked' : '') ?>> DA &nbsp;&nbsp;
			<input type="radio" <?php ($items_iscurrent == 'Y' ? print 'disabled' : '') ?> name="active" value="N" <?php ($items_active == false ? print 'checked' : '') ?>> NE
			
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
$query  = "SELECT * FROM korisnici";
$query .= " WHERE id=" . $_GET['id'];
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$result = @sqlsrv_query($MsSQL, $query, $params, $options);
$row = @sqlsrv_fetch_array($result);
$rowcount = sqlsrv_num_rows($result);
if ($rowcount > 0) 
{
$items_iscurrent = ($row['Id'] == $_SESSION['user']['id'] ? 'Y' : 'N');

$query  = "UPDATE korisnici SET Ime=(?), Prezime=(?), Email=(?), Oib=(?), MaticniBrojOsiguranika=(?)";
$query .= " WHERE id=" . $_GET['id'];
$params = array($_POST['name'], $_POST['lastname'], $_POST['email'], $_POST['oib'], $_POST['maticniBrojOsiguranika']);
$result = sqlsrv_query($MsSQL, $query, $params);

if ($items_iscurrent == 'N')
{
	$query  = "UPDATE korisnici SET Aktivan=(?)";
	$query .= " WHERE id=" . $_GET['id'];
	$params = array($_POST['active'] == 'Y');
	$result = @sqlsrv_query($MsSQL, $query, $params);
}

$items_id = $_GET['id'];

print '<div class="msginfo">Ažuriran je zapis id: '. $items_id .'.</div>';		
}
else{
	print '<div class="msginfo">Ne postoji odabrani zapis!</div>';
}
}
else if ($act == 5 && is_numeric($_GET['id']))
{
$query  = "SELECT * FROM korisnici";
$query .= " WHERE id=" . $_GET['id'];
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$result = @sqlsrv_query($MsSQL, $query, $params, $options);
$row = @sqlsrv_fetch_array($result);
$rowcount = sqlsrv_num_rows($result);

if ($rowcount > 0) 
{
$items_id = $row['Id'];
$items_iscurrent = ($row['Id'] == $_SESSION['user']['id'] ? 'Y' : 'N');

if ($items_iscurrent == 'N')
{
	$query  = "DELETE FROM korisnici";
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
		<b>Korisnik:</b><br>
		<form action="" id="items_form" name="items_form" method="POST" enctype="multipart/form-data">
			<input type="hidden" id="act" name="act" value="7">
			
			<label>Uloga *</label>
			<select id="uloga" name="uloga">
			<?php 
			$query  = "SELECT * FROM pravakorisnika where 1=1";
			if ($_SESSION['user']['rolaadmin'] == '0')
			{
			$query .= " and naziv='Pacijent'";
			}
			$result = @sqlsrv_query($MsSQL, $query);
			while($row = @sqlsrv_fetch_array($result)) {
			print '<option value="' . $row['Id'] . '">' . $row['Naziv'] . '</option>';
			}
			?>
			</select>

			<label>Ime *</label>
			<input type="text" id="name" name="name" placeholder="Ime.." required>
			
			<label>Prezime *</label>
			<input type="text" id="lastname" name="lastname" placeholder="Prezime.." required>
			
			<label>Email *</label>
			<input type="text" id="email" name="email" placeholder="Email.." required>

			<label>Korisničko ime *</label>
			<input type="text" id="korisnickoIme" name="korisnickoIme" placeholder="Korisničko ime.." required>
			
			<label>Lozinka *</label>
			<input type="text" id="lozinka" name="lozinka" placeholder="Lozinka.." required>

			<label>Oib *</label>
			<input type="text" id="oib" name="oib" maxlength="11" minlength="11" placeholder="Oib.." required>
			
			<label>Matični Broj Osiguranika *</label>
			<input type="text" id="maticniBrojOsiguranika" maxlength="9" minlength="9" name="maticniBrojOsiguranika" placeholder="Matični Broj Osiguranika.." required>
			
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

			<label>Aktivan:</label>
            <input type="radio" name="active" value="Y" checked> DA &nbsp;&nbsp;
			<input type="radio" name="active" value="N"> NE

			<hr>
			
			<input type="submit" class="btn btn-primary btn-lg" value="Dodaj">
		</form>
		</div>
	<?php	
}
else if ($act == 7)
{
	$query  = "INSERT INTO korisnici (Ime, Prezime, Email, Aktivan, KorisnickoIme, Lozinka, Oib, MaticniBrojOsiguranika, Adresa, Grad_Id, Uloga_Id)";
	$query .= " VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
	$params = array($_POST['name'], $_POST['lastname'], $_POST['email'], $_POST['active'] == 'Y', $_POST['korisnickoIme'], $_POST['lozinka'], $_POST['oib'], $_POST['maticniBrojOsiguranika'], $_POST['adresa'], $_POST['grad'], $_POST['uloga']);
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