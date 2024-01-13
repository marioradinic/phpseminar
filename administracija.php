<?php

include("provjeriadminprava.php");	

$h3title = "Korisnici";

	switch ($pg) 
	{
		case "2":
			$page = "admin_termini.php";
			$h3title="Termini";
			break;
		case "3":
				$page = "admin_racuni.php";
				$h3title="Računi";
				break;
		case "4":
			$page = "admin_lijecenja.php";
			$h3title="Liječenja";
			break;
		case "8":
		$page = "admin_postupci.php";
		$h3title="Medicinski postupci";
		break;
		case "9":
		$page = "admin_bolnice.php";
		$h3title="Bolnice";
		break;
		case "12":
		$page = "admin_narudzbe.php";
		$h3title="Narudžbe";
		break;
		default:
		$page = "admin_korisnici.php";
	}
?>
<script type="text/javascript">
function obrisi() {
	var conf = confirm("Želite obrisati odabrani zapis?");
	if (conf == true)
		return true;
	else
		return false;
	}
function otkazi() {
    var conf = confirm("Želite otkazati narudžbu ?");
    if (conf == true)
        return true;
    else
        return false;
}
</script>
<h2><?php print $title ?></h2>
<hr />
<div class="admin">
	<ul>
		<li><a href="index.php?pg=7&act=1" class="<?php ($pg == 7 ? print 'active' : '') ?>">Korisnici</a></li>
		<?php if($_SESSION['user']['rolaadmin'] == '1'){?><li><a href="index.php?pg=8&act=1" class="<?php ($pg == 8 ? print 'active' : '') ?>">Medicinski postupci</a></li><?php } ?>
		<?php if($_SESSION['user']['rolaadmin'] == '1'){?><li><a href="index.php?pg=9&act=1" class="<?php ($pg == 9 ? print 'active' : '') ?>">Bolnice</a></li><?php } ?>
		<?php if($_SESSION['user']['rolamedosoblje'] == '1' || $_SESSION['user']['roladoktor'] == '1'){?><li><a href="index.php?pg=2&act=1" class="<?php ($pg == 2 ? print 'active' : '') ?>">Termini</a></li><?php } ?>
		<?php if($_SESSION['user']['rolamedosoblje'] == '1' || $_SESSION['user']['roladoktor'] == '1'){?><li><a href="index.php?pg=12&act=1" class="<?php ($pg == 12 ? print 'active' : '') ?>">Narudžbe</a></li><?php } ?>
		<?php if($_SESSION['user']['rolamedosoblje'] == '1'){?><li><a href="index.php?pg=3&act=1" class="<?php ($pg == 3 ? print 'active' : '') ?>">Računi</a></li><?php } ?>
		<?php if($_SESSION['user']['roladoktor'] == '1'){?><li><a href="index.php?pg=4&act=1" class="<?php ($pg == 4 ? print 'active' : '') ?>">Liječenja</a></li><?php } ?>
	</ul>
	<hr class="clear" />
	<h4><?php print $h3title ?></h4>
    <?php
	include($page);	
	?>
</div>