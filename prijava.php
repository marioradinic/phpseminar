<?php

include("provjerimain.php");

if (isset($_SESSION['user']['is_logon']) && $_SESSION['user']['is_logon'] == '1')
{
	header("Location: index.php?pg=7");
}

$act = 2;
$msginfo =  "";

if(isset($_POST['act'])) { $act = $_POST['act']; }

if ($act == 1)
{
	$query  = "Select kor.*, pk.Naziv Uloga from korisnici kor inner join pravakorisnika pk on kor.uloga_id=pk.id";
	$query .= " where Aktivan=1 and pk.Zaposlenik=1 and KorisnickoIme='" . $_POST['username'] . "'";
	$query .= " and Lozinka='" . $_POST['password'] . "'";
	$params = array();
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	$result = @sqlsrv_query($MsSQL, $query, $params, $options);
	$row = @sqlsrv_fetch_array($result);
	$rowcount = sqlsrv_num_rows($result);

	if ($rowcount > 0) 
	{
		$user_isadmin = ($row['Uloga'] == "Administrator");
		$user_isdoktor = ($row['Uloga'] == "Doktor");
		$user_ismedosoblje = ($row['Uloga'] == "Medicinsko osoblje");
		$_SESSION['user']['is_logon'] = '1';
		$_SESSION['user']['id'] = $row['Id'];
		$_SESSION['user']['is_admin'] = ($user_isadmin || $user_isdoktor || $user_ismedosoblje ? '1' : '0');
		$_SESSION['user']['rolaadmin'] = ($user_isadmin ? '1' : '0');
		$_SESSION['user']['roladoktor'] = ($user_isdoktor ? '1' : '0');
		$_SESSION['user']['rolamedosoblje'] = ($user_ismedosoblje ? '1' : '0');
		header("Location: index.php?pg=7");
	}
	else 
	{
		$msginfo =  "Neuspješna prijava!";
	}	
}

?>
<h2><?php print $title ?></h2>
<hr />
<div>
	<div class="msginfo"><?php print $msginfo ?></div>
   <form action="" name="myForm" id="myForm" method="POST">
      <input type="hidden" id="act" name="act" value="1">
      <label for="username">Korisničko ime: *</label>
      <input type="text" id="username" name="username" value="" required>
      <label for="password">Lozinka: *</label>
      <input type="password" id="password" name="password" value="" required>
      <input type="submit" class="btn btn-primary btn-lg" value="Prijavi se">
   </form>
</div>