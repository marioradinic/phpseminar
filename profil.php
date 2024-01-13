<?php

include("provjerimain.php");

if (!isset($_SESSION['user']['is_logon']) 
	|| !$_SESSION['user']['is_logon'] == '1')
{
	header("Location: index.php?pg=1");
}

$act = 2;
$msginfo =  "";

if(isset($_POST['act'])) { $act = $_POST['act']; }
	
if ($act == 2)
{
	$query  = "SELECT * FROM korisnici";
	$query .= " WHERE id=" . $_SESSION['user']['id'];
	$params = array();
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	$result = @sqlsrv_query($MsSQL, $query, $params, $options);
	$row = @sqlsrv_fetch_array($result);
	$rowcount = sqlsrv_num_rows($result);

	if ($rowcount > 0) 
	{
		$users_name= $row['Ime'];
		$users_lastname = $row['Prezime'];
		$users_email = $row['Email'];
		$users_oib = $row['Oib'];
		$users_maticniBrojOsiguranika = $row['MaticniBrojOsiguranika'];
	}	
}
else if ($act == 1)
{
	$query  = "SELECT * FROM korisnici";
	$query .= " WHERE id=" . $_SESSION['user']['id'];
	$params = array();
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	$result = @sqlsrv_query($MsSQL, $query, $params, $options);
	$row = @sqlsrv_fetch_array($result);
	$rowcount = sqlsrv_num_rows($result);
	
	if ($rowcount > 0)  
	{
		$query  = "UPDATE korisnici SET Ime=(?), Prezime=(?), Email=(?), Oib=(?), MaticniBrojOsiguranika=(?)";
		$query .= " WHERE id=" . $_SESSION['user']['id'];
		$params = array($_POST['name'], $_POST['lastname'], $_POST['email'], $_POST['oib'], $_POST['maticniBrojOsiguranika']);
		$result = sqlsrv_query($MsSQL, $query, $params);

		$users_name= $_POST['name'];
		$users_lastname = $_POST['lastname'];
		$users_email = $_POST['email'];
		$users_oib = $_POST['oib'];
		$users_maticniBrojOsiguranika = $_POST['maticniBrojOsiguranika'];

		$msginfo =  "Uspješno izmijenjeno!";
	}
	else 
	{
		$msginfo =  "Neuspješna promjena!";
	}
}

?>
<h2><?php print $title ?></h2>
<hr />
<div>
	<div class="msginfo"><?php print $msginfo ?></div>
   <form action="" id="profile_form" name="profile_form" method="POST">
      <input type="hidden" id="act" name="act" value="1">
      <label for="name">Ime: *</label>
      <input type="text" id="name" value="<?php print $users_name ?>" name="name" placeholder="Ime.." required>
      <label for="lastname">Prezime: *</label>
      <input type="text" id="lastname" value="<?php print $users_lastname ?>" name="lastname" placeholder="Prezime.." required>
      <label for="email">E-mail: *</label>
      <input type="email" id="email" value="<?php print $users_email ?>" name="email" placeholder="Email.." required>
      <label>Oib: *</label>
	  <input type="text" id="oib" name="oib" value="<?php print $users_oib ?>" placeholder="Oib.." maxlength="11" minlength="11" required>
	  <label>Matični Broj Osiguranika: *</label>
	  <input type="text" id="maticniBrojOsiguranika" value="<?php print $users_maticniBrojOsiguranika ?>" maxlength="9" minlength="9" name="maticniBrojOsiguranika" placeholder="Matični Broj Osiguranika.." required>
	  <input type="submit" class="btn btn-primary btn-lg" value="Izmijeni">
   </form>
</div>