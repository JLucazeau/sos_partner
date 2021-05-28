<?php
session_start();
$idUser=$_SESSION['id_user'];
require '../elements/trycatch.php';
require '../elements/header.php';
require '../elements/navbar.php';
if (isset($_SESSION['email']) && isset($_SESSION['password'])) {
?>
    <div class="container">
        <?php
            if (isset($_POST['save-pref'])) {
                $sport=$_POST['sport'];
        		$level=$_POST['level'];
                $sexe=$_POST['sexe'];  
                $tranche_age=$_POST['tranche-age']; 
                $km=$_POST['km'];  
                $insert="INSERT INTO `preferences`(`id_preference`, `id_user`, `sport`, `level`, `sexe`, `tranche-age`, `km`) VALUES (NULL,$idUser,'$sport','$level','$sexe','$tranche_age',$km)";
                $send = $connect->query($insert);
                if ($send) {
                    echo "
                    <div class='alert alert-success alert-dismissible fade show mt-5' role='alert'>
                        Préférence enregistrée avec succès !
                        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                            <span aria-hidden='true'>&times;</span>
                        </button>
                    </div>
                    ";
                }
            }
        ?>
        <h1 class="display-4 mt-5">Préférences</h1>
        <div class="liste-preferences mt-5">
            <?php 
            if (isset($_POST['close'])) {
                $idResa=$_POST['idResa'];
                $delete="DELETE FROM preferences WHERE id_preference=$idResa";
                $send = $connect->query($delete);
            }
            $cpt=0;
            $sportUser=[];
            $select="SELECT * FROM preferences where id_user = '$idUser'";
            $send=$connect->query($select);
            while ($bdd=$send->fetch(PDO::FETCH_ASSOC)) {
                echo "
                <div class='alert alert-dark alert-dismissible fade show text-center' role='alert'>
                <h4>".strtoupper($bdd['sport'])."</h4><h5>Niveau : ".$bdd['level']." | Sexe : ".$bdd['sexe']." | Tranche d'âge : ".$bdd['tranche-age']." | Rayon (Km) : ".$bdd['km']." </h5>
                <form action='' method='post'>
                    <button type='submit' class='close' name='close'>
                        <input type='hidden' name='idResa' value='".$bdd['id_preference']."'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                </form>
                </div>";
                $sportUser[$cpt]=$bdd['sport'];
                $cpt++;
            }
            if ($cpt<10) {
                echo "
                <div class='alert alert-dark alert-dismissible fade show text-center pt-4' role='alert'>
                    <button type='button' class='btn' data-toggle='modal' data-target='#exampleModalCenter'>
                        <h3><i class='fas fa-plus-circle'></i></h3>
                    </button>
                </div>";
            }
            ?>
            <!-- Modal -->
            <form action="" method="post">
                <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Ajout de préférence</h5>
                        </div>
                        <div class="modal-body">
                            <?php
                                $sportList=['Football','Tennis','Basketball','Handball','Rugby','Golf','Pétanque','Badminton','Arts martiaux','Equitation'];
                            ?>
                            <div class="form-group row">
                                <label for="selectSport" class="col-sm-4 col-form-label">Sport</label>
                                <div class="col-sm-5">
                                    <select class="custom-select mr-sm-4" id="selectSport" name="sport" >
                                        <?php
                                            foreach ($sportList as $key => $value) {
                                                if (!in_array($value, $sportUser)) {
                                                    echo "<option value='".$value."'>".$value."</option>";
                                                }
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="selectLvl" class="col-sm-4 col-form-label">Niveau</label>
                                <div class="col-sm-5">
                                    <select class="custom-select mr-sm-4" id="selectLvl" name="level" >
                                        <option value="Debutant">Débutant</option>
                                        <option value="Confirme">Confirmé</option>
                                        <option value="Expert">Expert</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="selectSexe" class="col-sm-4 col-form-label">Sexe</label>
                                <div class="col-sm-5">
                                    <select class="custom-select mr-sm-4" id="selectSexe" name="sexe" >
                                        <option value="Homme">Homme</option>
                                        <option value="Femme">Femme</option>
                                        <option value="Homme-Femme">Peu importe</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <script>
                                    $( function() {
                                        $( "#slider-range" ).slider({
                                        range: true,
                                        min: 16,
                                        max: 77,
                                        values: [ 75, 300 ],
                                        slide: function( event, ui ) {
                                            $( "#age" ).val(ui.values[ 0 ] + " - " + ui.values[ 1 ] );
                                        }
                                        });
                                        $( "#age" ).val($( "#slider-range" ).slider( "values", 0 ) +
                                        " - " + $( "#slider-range" ).slider( "values", 1 ) );
                                    } );
                                </script>
                                <p>
                                    <label for="age">Tranche d'âge :</label>
                                    <input type="text" id="age" class="text-primary" readonly style="border:0; font-weight:bold;" name="tranche-age">
                                </p>
                                <div id="slider-range"></div>
                            </div>
                            <div class="form-group">
                                <script>
                                    $( function() {
                                        $( "#range-min" ).slider({
                                        range: "min",
                                        value: 37,
                                        min: 5,
                                        max: 100,
                                        slide: function( event, ui ) {
                                            $( "#distance" ).val(ui.value);
                                        }
                                        });
                                        $( "#distance" ).val($( "#range-min").slider( "value" ) );
                                    } );
                                </script>
                                <p>
                                    <label for="distance">Rayon (km) :</label>
                                    <input type="text" id="distance" class="text-primary" readonly style="border:0; font-weight:bold;" name="km">
                                </p>
                                <div id="range-min"></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                            <input type="submit" class="btn btn-primary" name="save-pref" value="Enregistrer">
                        </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php 
} else {
    header("location:../connection/connect.php");
}
?>