<?php
session_start();

$mainchecker = "1";

include ("db.php");

$user_active_name = '';
$user_active_lastname = '';
$user_active_email = '';

if (isset($_SESSION['user']['is_logon']) 
	&& $_SESSION['user']['is_logon'] == '1'
	&& isset($_SESSION['user']['id']))
{
	$query  = "Select kor.*, pk.Naziv Uloga from korisnici kor inner join pravakorisnika pk on kor.uloga_id=pk.id";
	$query .= " WHERE kor.id=" . $_SESSION['user']['id'];
	$params = array();
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	$result = @sqlsrv_query($MsSQL, $query, $params, $options);
	$row = @sqlsrv_fetch_array($result);
	$rowcount = sqlsrv_num_rows($result);
	
	if ($rowcount > 0) 
	{
		$user_active = $row['Aktivan'];
		if ($user_active == false)
		{
			header("Location: odjava.php");
		}
		$user_isadmin = ($row['Uloga'] == "Administrator");
		$user_isdoktor = ($row['Uloga'] == "Doktor");
		$user_ismedosoblje = ($row['Uloga'] == "Medicinsko osoblje");
		$_SESSION['user']['is_admin'] = ($user_isadmin || $user_isdoktor || $user_ismedosoblje ? '1' : '0');
		$user_active_name = $row['Ime'];
		$user_active_lastname = $row['Prezime'];
		$user_active_email = $row['Email'];
	}
	else
	{
		header("Location: odjava.php");
	}
}

$pg = 1;
if(isset($_GET['pg'])) { $pg = $_GET['pg']; }

?>

<?php
	switch ($pg) 
	{
		case "5":
		$page = "profil.php";
		$title="Moj profil";
		break;
		case "6":
		$page = "prijava.php";
		$title="Prijava";
		break;
		case "2":case "3":case "4":
		case "7":
		case "8":
		case "9":
		case "12":
		$page = "administracija.php";
		$title="Administracija";
		break;
		case "11":
		$page = "narudzba.php";
		$title="Moja narudžba";
		break;
		case "13":
		$page = "mojenarudzbe.php";
		$title="Moje narudžbe";
		break;
		default:
		$page = "pocetna.php";
		$title="Portal za bolnice";
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<meta name="description" content="Portal za bolnice">
<meta name="keywords" content="Portal za bolnice">
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link href="css/bootstrap.css" rel="stylesheet"/>
<link href="css/Site.css" rel="stylesheet"/>
<link href="css/glyphicon.css" rel="stylesheet"/>

<meta itemprop="name" content="<?php print $title ?>">
<meta itemprop="description" content="Portal za bolnice">

<meta property="og:title" content="<?php print $title ?>">
<meta property="og:description" content="Portal za bolnice">

<meta name="twitter:title" content="<?php print $title ?>">
<meta name="twitter:description" content="Portal za bolnice">

<meta name="author" content="mario radinic">
<link rel="icon" href="favicon.ico" type="image/x-icon"/>
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon"/>

<title><?php print $title ?></title>
</head>
<body>
<script type="text/javascript">
function odjava() {
    var conf = confirm("Želite se odjaviti ?");
    if (conf == true)
        return true;
    else
        return false;
}
</script>
<script src="js/jquery-3.4.1.js" type="text/javascript"></script>
<script src="js/bootstrap.js" type="text/javascript"></script>
<div class="head-img"></div>
<?php include("izbornik.php"); ?>
<div class="container body-content">           
<?php
	include($page);	
?>
<hr class="clear" />
<footer>
<p>&copy; <?php print date('Y'); ?> - Portal za bolnice</p>
</footer>
</div>
</body>
</html>
