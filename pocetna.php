<?php
include("provjerimain.php");	
?>
    <div class="jumbotron">
        <h1>Web aplikacija za bolnice</h1>
        <p class="lead">Web aplikacija omogućuje prikaz i administraciju liječenja, narudžbi, računa, bolnica, medicinskih postupaka, termina, pacijenata i zaposlenika. Pristup u aplikaciju je dozvoljen samo zaposlenicama bolničke ustanove koji imaju kreiran i aktivan korisnički račun.</p>
        <?php if(!isset($_SESSION['user']['is_admin'])){?><p><a href="index.php?pg=6" class="btn btn-primary btn-lg">Prijava</a></p><?php } ?>
    </div>
