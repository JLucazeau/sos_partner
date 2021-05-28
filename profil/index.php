<?php
session_start();


if (isset($_SESSION['email']) && isset($_SESSION['password'])) {
  require '../elements/trycatch.php';
require '../elements/header.php';
require '../elements/navbar.php';
$idUser=$_SESSION['id_user'];
    $select="SELECT * FROM users WHERE id_user = '$idUser'";
    $send=$connect->query($select);
    $bdd=$send->fetch(PDO::FETCH_ASSOC);
    $name=$bdd['name'];
    $first_name=$bdd['first_name'];
    $email=$_SESSION['email'];
    $password=$_SESSION['password'];
    $birth_date = date("d/m/Y", strtotime($bdd['birth_date']));
    $adress=$bdd['adress'];
    $postal_code=$bdd['postal_code'];
    $city=$bdd['city'];
    $phone_number=$bdd['phone_number'];
    $sexe=$bdd['sexe'];
    if ($sexe == 'femme') {
        $prefixe='Mme';
    }else {
        $prefixe='M.';
    }
    ?>
    <div class="container">
        <?php
        if (isset($_POST['modifier'])) {
            $choice=$_POST['choice'];
            $modif=$_POST['modif'];
            $update="UPDATE users SET ".$choice." = '".$modif."' WHERE id_user = ".$idUser."";
            $send=$connect->query($update);
            if ($send) {
                echo "
                <div class='alert alert-success alert-dismissible fade show mt-5 mb-5' role='alert'> Modification effectuée avec succès !
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                </div>";
            }else {
                echo "
                <div class='alert alert-danger alert-dismissible fade show mt-5 mb-5' role='alert'> Désolé, une erreur est survenue lors de la modification.
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                </div>";
            }
        }
        if (isset($_POST['enregistrer'])) {
            if ($_POST['passwordVerif']==$_SESSION['password']) {
                if ($_POST['passwordN1']==$_POST['passwordN2']) {
                    $NewPassword=$_POST['passwordN1'];
                    $update="UPDATE users SET password = '".$NewPassword."' WHERE id_user = ".$idUser."";
                    $send=$connect->query($update);
                    if ($send) {
                        echo "
                        <div class='alert alert-success alert-dismissible fade show mt-5 mb-5' role='alert'> Mot de passe enregistré !
                            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                <span aria-hidden='true'>&times;</span>
                            </button>
                        </div>";
                    }else {
                        echo "
                        <div class='alert alert-danger alert-dismissible fade show mt-5 mb-5' role='alert'> Désolé, une erreur est survenue lors de la modification.
                            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                <span aria-hidden='true'>&times;</span>
                            </button>
                        </div>";
                    }
                }else {
                    echo "
                    <div class='alert alert-danger alert-dismissible fade show mt-5 mb-5' role='alert'> Désolé, les mots de passe ne correspondent pas. Veuillez réessayer.
                        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                            <span aria-hidden='true'>&times;</span>
                        </button>
                    </div>";
                }
            }else {
                echo "
                <div class='alert alert-danger alert-dismissible fade show mt-5 mb-5' role='alert'> Désolé, le mot de passe est incorrect. Veuillez réessayer.
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                </div>";
            }

        }
        ?>
        
        <h1 class="display-4 mt-5">Profil</h1>
        <div class="row">
            <div class="col"><img class="mt-5 mb-5" src="http://placehold.it/400x300" alt="profil picture"></div>
            <div class="col">
                <table class="mt-5 mb-5 table">
                    <tr>
                        <td><i class="fas fa-user mr-2"></i>&nbsp;<?=$prefixe;?>&nbsp;<?=$first_name;?>&nbsp;<?=$name;?></td>
                    </tr>
                    <tr>
                        <td><i class="fas fa-birthday-cake mr-2"></i>&nbsp;<?=$birth_date?></td>
                    </tr>
                    <tr>
                        <td><i class="fas fa-map-marker-alt mr-2"></i>&nbsp;<?=$adress?>&nbsp;-&nbsp;<?=$postal_code?>&nbsp;<?=$city?></td>
                    </tr>
                    <tr>
                        <td><i class="fas fa-envelope-open mr-2"></i>&nbsp;<?=$email?></td>
                    </tr>
                    <tr>
                        <td><i class="fas fa-phone mr-2"></i>&nbsp;<?=$phone_number;?></td>
                    </tr>
                </table>
                <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseModifInfos">Modifier les informations</button>
                <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseModifPassword">Modifier le mot de passe</button>
            </div>
        </div>
        <div class="collapse mt-5 mb-5" id="collapseModifInfos">
            <div class="card card-body">
                <div class="container">
                    <form action="" method="post">
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <select class="custom-select mr-sm-4" name="choice" >
                                    <option value="name">Nom</option>
                                    <option value="first_name">Prénom</option>
                                    <option value="adress">Adresse</option>
                                    <option value="postal_code">Code postal</option>
                                    <option value="city">Ville</option>
                                    <option value="phone_number">Numéro de télephone</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="modif" placeholder="Modification">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-10">
                            <button type="submit" class="btn btn-primary" name="modifier">Modifer</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="collapse mt-5 mb-5" id="collapseModifPassword">
            <div class="card card-body">
                <div class="container">
                    <form action="" method="post">
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <input type=password class="form-control" name="passwordVerif" placeholder="Saisissez le mot de passe actuel">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <input type=password class="form-control" name="passwordN1" placeholder="Entrez un nouveau mot de passe">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <input type=password class="form-control" name="passwordN2" placeholder="Répétez le nouveau le mot de passe">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-10">
                            <button type="submit" class="btn btn-primary" name="enregistrer">Enregistrer</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <p class="lead">Vous pouvez personnalisez vos préférences ici !</p>
        <a class="btn btn-primary" href="preferences.php">Préférences</a>
    </div>
    <?php 
} else {
    header("location:../connection/connect.php");
}
