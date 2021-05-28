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
            <form class="" action="" method="post">
                <h4 class="mb-4">Informations personnelles</h4>
                <div class="form-group row">
                    <label for="inputName" class="col-sm-4 col-form-label">Nom</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="inputName" name="name" >
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputFName" class="col-sm-4 col-form-label">Prénom</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="inputFName" name="first_name" >
                    </div>
                </div>
                <div class="form-group row">
                    <label for="selectSexe" class="col-sm-4 col-form-label">Sexe</label>
                    <div class="col-sm-5">
                        <select class="custom-select mr-sm-4" id="selectSexe" name="sexe" >
                              <option value="homme">Homme</option>
                              <option value="femme">Femme</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputBDate" class="col-sm-4 col-form-label">Date de naissance</label>
                    <div class="col-sm-8">
                        <input type="date" class="form-control" id="inputBDate" name="birth_date" >
                    </div>
                </div>
                <hr>
                <h4 class="mb-4">Localisation</h4>
                <div class="form-group row">
                    <label for="inputAdress" class="col-sm-4 col-form-label">Adresse</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="inputAdress" name="adress" >
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputCp" class="col-sm-4 col-form-label">Code Postal</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="inputCp" name="postal_code" maxlength="5" >
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputCity" class="col-sm-4 col-form-label">Ville</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="inputCity" name="city" >
                    </div>
                </div>
                <hr>
                <h4 class="mb-4">Contact</h4>
                <div class="form-group row">
                    <label for="inputPhone" class="col-sm-4 col-form-label">Téléphone</label>
                    <div class="col-sm-8">
                        <input type="tel" class="form-control" id="inputPhone" name="phone_number"pattern="[0-9]{10}" maxlength="10"  >
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputEmail" class="col-sm-4 col-form-label">Email</label>
                    <div class="col-sm-8">
                        <input type="email" class="form-control" id="inputEmail" name="email" >
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputPswd1" class="col-sm-4 col-form-label">Mot de passe</label>
                    <div class="col-sm-8">
                        <input type=password class="form-control" id="inputPswd1" name="password1" >
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputPswd2" class="col-sm-4 col-form-label">Répétez le mot de passe</label>
                    <div class="col-sm-8">
                        <input type=password class="form-control" id="inputPswd2" name="password2" >
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-10">
                    <button type="submit" class="btn btn-primary" name="valider">Enregistrer</button>
                    </div>
                </div>
            </form>
            <?php
                if (isset($_POST['valider'])) {
                    if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                        $email=strtolower($_POST['email']);
                        $password1=$_POST['password1'];
                        $password2=$_POST['password2'];
                        if ($password1==$password2) {
                            $password=$_POST['password1'];
                            $sexe=$_POST['sexe'];
                            $name=strtolower(utf8_encode(addslashes($_POST['name'])));
                            $first_name=strtolower(utf8_encode(addslashes($_POST['first_name'])));
                            $birth_date=$_POST['birth_date'];
                            $adress=strtolower(utf8_encode(addslashes($_POST['adress'])));
                            $postal_code=$_POST['postal_code'];
                            $city=strtoupper(utf8_encode(addslashes($_POST['city'])));
                            $phone_number=$_POST['phone_number'];
                            $insert = "INSERT INTO `users` VALUES (null,'$name','$first_name','$birth_date','$adress','$postal_code','$city','$email','$password1','$phone_number','$sexe')";
                            $send = $connect->query($insert);
                            if ($send) {
                                echo "Profil enregistré avec succès.";
                            }
                            else {
                                echo "Erreur lors de l'enregistrement !";
                            }
                        }else {
                            echo "Erreur ! les mots de passe ne concordent pas.";
                        }
                    }else {
                        echo "Erreur ! l'E-mail est incorrect.";
                    }
                }
            ?>
        </div>
        <div class="col"></div>
    </div>
</div>
