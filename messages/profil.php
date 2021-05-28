<?php
session_start();
require '../elements/trycatch.php';
require '../elements/header.php';
require '../elements/navbar.php';
if (isset($_SESSION['email']) && isset($_SESSION['password'])) {
    $idUser=$_SESSION['id_user'];?>
    <body >
        <div class="container">
            <?php
            $profil=$_GET['idAmi'];
            $recupProfil="SELECT * FROM `users` WHERE id_user = $profil";
            $recupProfil=$connect->query($recupProfil);
            $bdd=$recupProfil->fetch(PDO::FETCH_ASSOC);
            ?>
            <h2 class="mt-5"><img class="rounded-circle mr-2 float-left" src="http://placehold.it/55x55" alt="profil picture">&nbsp;&nbsp;<?=ucfirst($bdd['first_name'])?>&nbsp;<?=ucfirst($bdd['name'])?></h2>
            <table class="mt-5 mb-5 table">
                <tr>
                    <td><i class="fas fa-venus-mars"></i>&nbsp;<?=ucfirst($bdd['sexe'])?></td>
                </tr>
                <tr>
                    <td><i class="fas fa-birthday-cake mr-2"></i>&nbsp;<?=$bdd['birth_date']?></td>
                </tr>
                <tr>
                    <td><i class="fas fa-map-marker-alt mr-2"></i>&nbsp;<?=$bdd['adress']?>&nbsp;-&nbsp;<?=$bdd['postal_code']?>&nbsp;<?=$bdd['city']?></td>
                </tr>
                <tr>
                    <td><i class="fas fa-envelope-open mr-2"></i>&nbsp;<?=$bdd['email']?></td>
                </tr>
                <tr>
                    <td><i class="fas fa-phone mr-2"></i>&nbsp;<?=$bdd['phone_number'];?></td>
                </tr>
            </table>
            <h4> Sport(s) : 
                <?php
                $profil=$_GET['idAmi'];
                $recupProfil="SELECT * FROM `preferences` WHERE id_user = $profil";
                $recupProfil=$connect->query($recupProfil);
                while ($bdd=$recupProfil->fetch(PDO::FETCH_ASSOC)) {
                    echo $bdd['sport'];
                }
                ?>
            </h4>
        </div>
    </body>    
<?php
} else {
    header("location:../connection/connect.php");
}?>