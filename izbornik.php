<?php
include("provjerimain.php");	
?>
<div class="navbar navbar-inverse">
   <div class="container">
      <div class="navbar-header">
         <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
         <span class="icon-bar"></span>
         <span class="icon-bar"></span>
         <span class="icon-bar"></span>
         </button>
         <a href="./index.php?pg=1" class="navbar-brand">Naslovna</a>
      </div>
      <div class="navbar-collapse collapse">
         <ul class="nav navbar-nav navbar-right">
<?php
if (!isset($_SESSION['user']['is_logon']) || !$_SESSION['user']['is_logon'] == '1')
{
?>
            <li><a href="index.php?pg=6">Prijava</a></li>
<?php
}
else if ($_SESSION['user']['is_logon'] == '1')
{
	if ($_SESSION['user']['is_admin'] == '1')
	{
?>
            <li><a href="index.php?pg=7">Administracija</a></li>
<?php
	}
	else
	{
?>
<?php
	}
?>
			<li><a href="index.php?pg=5">Moj profil</a></li>
         <li><a href="odjava.php" onclick="return odjava();">Odjava</a></li>		
<?php
}
?>
         </ul>
      </div>
   </div>
</div>