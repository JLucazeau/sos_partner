<?php
session_start();


//include 'modal.php';
if (isset($_SESSION['email']) && isset($_SESSION['password'])) {
  require '../elements/trycatch.php';
require '../elements/header.php';
require '../elements/navbar.php';
  include 'fonction.php';
    $id=$_SESSION['id_user'];
    $sendPref = $connect->query("SELECT * FROM preferences WHERE id_user=$id");
    $count = $sendPref->rowCount();
    if ($count==0) {
      header('Location: http://localhost/cours/Projets/SosPartner/profil/preferences.php');
    }
    $reqAdressA = "SELECT * FROM users WHERE id_user = $id";
    $sendAdressA=$connect->query($reqAdressA);
    $resultAdressA=$sendAdressA->fetch(PDO::FETCH_ASSOC);

    $rue=$resultAdressA['adress'];
    $postal_code=$resultAdressA['postal_code'];
    $city=$resultAdressA['city'];

    $adressA = $rue.' '.$postal_code.' '.$city;
    $adressA = strtolower($adressA);
    getCoordinates($adressA);
    $latitude_longitude = getCoordinates($adressA);
    list($latitude, $longitude) = explode(",", $latitude_longitude);
    $lat1 = $latitude;
    $lng1 = $longitude;

    $sendPref = $connect->query("SELECT * FROM preferences WHERE id_user=$id");
    $i=0;
    while ($ligne=$sendPref->fetch(PDO::FETCH_ASSOC))
    {
      $sport[$i]= $ligne["sport"];
      $i++;
    }

    if(isset($_POST["submit"])) {
      $discipline = $_POST['sport'];
    }else {
      if (isset($_POST['defaut'])) {
        $discipline = $_POST['defaut'];
      }else {
        $discipline = $sport[0];
      }
    }
    if (isset($_POST['confirme']))
    {
      $count = $_POST['count'];
      $reqAjout ="INSERT INTO `demandes`(`iduser`, `idtarget`) VALUES ('$id','$count')";
      $sendReqAjout = $connect->query($reqAjout);
    }
    ?>
    <!DOCTYPE html>
    <html lang="en" dir="ltr">
      <head>
        <meta charset="utf-8">
      </head>
      <body>
        <div class="container">
        <div class="search_area">
          <h1 class="display-4 mt-5">Recherche ton Partner</h1>
        <form method="POST" action="" class="form-group">
          <table>
            <tr>
                <td>
                  <select class="custom-select" name="sport">
                    <?php
                    for ($count=0; $count < $i ; $count++) {?>
                      <option name="sport" value="<?=$sport[$count];?>"><?=$sport[$count];?></option>

                    <?php
                    }
                    ?>
                  </select>
                </td>
                <td>
                  <input class="btn btn-dark" type="submit" name="submit" value="Rechercher">
                </td>
            </tr>
          </table>

        </form>
    <br>


    <?php



        $reqPrefChoisi = $connect->query("SELECT * FROM preferences WHERE sport='$discipline' AND id_user='$id'");

        $resultPrefChoisi = $reqPrefChoisi->fetch(PDO::FETCH_ASSOC);

        $lvl= $resultPrefChoisi["level"];
        $sexe= $resultPrefChoisi["sexe"];
        $tranche= $resultPrefChoisi["tranche-age"];
        $recupAge = preg_split("/[\s,-]+/", $tranche);
        $ageMin= $recupAge[0];
        $ageMax= $recupAge[1];
        $km= $resultPrefChoisi["km"];

        $reqPref = "SELECT * FROM preferences WHERE sport='$discipline' AND level='$lvl' AND sexe='$sexe' AND id_user!='$id'";
        $sendReqPref = $connect->query($reqPref);


        $a = 0;
        while ($resultPref=$sendReqPref->fetch(PDO::FETCH_ASSOC))
          {
            $iduser[$a]= $resultPref['id_user'];
            $a++;
          }

        $z = 0;?>
        <div class="table-responsive">
        <table class="table table-bordered">
        <thead class="thead-dark">
            <tr>
              <th>Nom</th>
              <th>Prénom</th>
              <th>Age</th>
              <th>Sport</th>
              <th>Distance (en km)</th>
              <th></th><th></th><th></th>
            </tr>
        <?php
        for ($count=0; $count <$a ; $count++)
        {

          $reqRecupPref = $connect->query("SELECT * FROM users WHERE id_user='$iduser[$count]'");
          $resultRecupPref=$reqRecupPref->fetch(PDO::FETCH_ASSOC);


          $birth_date[$count] = date("d-m-Y", strtotime($resultRecupPref['birth_date']));
          $phone_number[$count]=$resultRecupPref['phone_number'];
          $nomM[$count] = $resultRecupPref['name'];
          $prenomM[$count] = $resultRecupPref['first_name'];
          $ruep= $resultRecupPref['adress'];
          $code= $resultRecupPref['postal_code'];
          $city= $resultRecupPref['city'];
          $email[$count] = $resultRecupPref['email'];

          if ($sexe == 'femme')
          {
            $prefixe[$count]='Mme';
          }else
          {
            $prefixe[$count]='M.';
          }

          $adressB[$count] =$ruep.' '.$city.' '.$code;
          $adressB[$count] = strtolower($adressB[$count]);

          getCoordinates($adressB[$count]);
          $latitude_longitude = getCoordinates($adressB[$count]);
          list($latitude, $longitude) = explode(",", $latitude_longitude);
          $lat2 = $latitude;
          $lng2 = $longitude;
          $distAB[$count] = distance($lat1,$lng1,$lat2,$lng2);


          $difAge[$count]= age($birth_date[$count]);


          $nb = 0;

          if ($distAB[$count]<=$km && $difAge[$count]<=$ageMax && $difAge[$count]>=$ageMin)
            {?>
            <tr>
              <td><?=$nomM[$count];?></td>
              <td><?=$prenomM[$count];?></td>
              <td><?=age($birth_date[$count])?></td>
              <td><?=$discipline;?></td>
              <td><?=round($distAB[$count],1);?></td>
              <td><button type="button" class="btn btn-primary"  data-toggle="modal" data-target="#detail_<?=$count;?>">Details</button>
              <div class="modal fade" id="detail_<?=$count;?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Detail du profil</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                          <table class="mt-5 mb-5 table">
                              <tr>
                                  <td><i class="fas fa-user mr-2"></i>&nbsp;<?=$prefixe[$count];?>&nbsp;<?=$nomM[$count];?>&nbsp;<?=$prenomM[$count];?></td>
                              </tr>
                              <tr>
                                  <td><i class="fas fa-birthday-cake mr-2"></i>&nbsp;<?=$birth_date[$count]?></td>
                              </tr>
                              <tr>
                                  <td><i class="fas fa-map-marker-alt mr-2"></i>&nbsp;<?=$adressB[$count]?></td>
                              </tr>
                              <tr>
                                  <td><i class="fas fa-envelope-open mr-2"></i>&nbsp;<?=$email[$count]?></td>
                              </tr>
                              <tr>
                                  <td><i class="fas fa-phone mr-2"></i>&nbsp;<?=$phone_number[$count];?></td>
                              </tr>
                          </table>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                  </div>
                </div>
              </div>
              </td>

              <td>
                <?php
                $idSearch=$iduser[$count];
                $checkAmi="SELECT * FROM `amis` WHERE (ami1=$idSearch and ami2=$id) or (ami1=$id and ami2=$idSearch)";
                $sendCheckAmi=$connect->query($checkAmi);
                $countAmi=$sendCheckAmi->rowcount();
                if ($countAmi == 0) {
                  $checkDemAmi="SELECT * FROM `demandes` WHERE (iduser=$idSearch and idtarget=$id) or (iduser=$id and idtarget=$idSearch)";
                  $sendCheckDemAmi=$connect->query($checkDemAmi);
                  $countDemAmi=$sendCheckDemAmi->rowcount();
                  if ($countDemAmi == 0) {?>
                  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ajout_<?=$count;?>">Ajouter</button>
                  <?php
                  }else {?>
                  Demande envoyée &nbsp;
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-hourglass" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M2 1.5a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-1v1a4.5 4.5 0 0 1-2.557 4.06c-.29.139-.443.377-.443.59v.7c0 .213.154.451.443.59A4.5 4.5 0 0 1 12.5 13v1h1a.5.5 0 0 1 0 1h-11a.5.5 0 1 1 0-1h1v-1a4.5 4.5 0 0 1 2.557-4.06c.29-.139.443-.377.443-.59v-.7c0-.213-.154-.451-.443-.59A4.5 4.5 0 0 1 3.5 3V2h-1a.5.5 0 0 1-.5-.5zm2.5.5v1a3.5 3.5 0 0 0 1.989 3.158c.533.256 1.011.791 1.011 1.491v.702c0 .7-.478 1.235-1.011 1.491A3.5 3.5 0 0 0 4.5 13v1h7v-1a3.5 3.5 0 0 0-1.989-3.158C8.978 9.586 8.5 9.052 8.5 8.351v-.702c0-.7.478-1.235 1.011-1.491A3.5 3.5 0 0 0 11.5 3V2h-7z"/>
                  </svg>
                  <?php
                  }
                }else {?>
                  Déja amis &nbsp;
                  <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" fill="currentColor" class="bi bi-check" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M10.97 4.97a.75.75 0 0 1 1.071 1.05l-3.992 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.236.236 0 0 1 .02-.022z"/>
                  </svg>
                  <?php
                }
                ?>

              </td>
              <form action="" method="POST">
              <div class="modal fade" id="ajout_<?=$count;?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Ajouter comme ami</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                      Voulez vous l'ajoutez en tant qu'ami ?
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annulez</button>
                    <input type="hidden" name="count" value="<?=$iduser[$count];?>">
                    <input type="hidden" name="defaut" value="<?=$discipline;?>">
                    <button type="submit" name="confirme" class="btn btn-primary">Confirmez l'ajout</button>
                  </div>
                  </div>
                </div>
              </div>
              </form>
        </tr>

    <?php
            }

        }



    ?>
    </table>
        </div>

        </div>
 </body>
 </html>
 <?php
} else {
    header("location:../connection/connect.php");
}
?>
