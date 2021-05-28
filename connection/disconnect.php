<?php
session_start();
session_destroy();
?>
<?php
session_start();
require '../elements/trycatch.php';
require '../elements/header.php';
require '../elements/navbar.php';
?>
<div class="container">
    <div class="row mt-5">
        <div class="col"></div>
        <div class="col-5">
            <p class="mt-5">Vous avez été deconnécté(e).</p><br>
            <a href="../index.php" class="btn btn-primary"><i class="fas fa-home"></i>&nbsp;&nbsp;Accueil</a>
        </div>
        <div class="col"></div>
    </div>
</div>
