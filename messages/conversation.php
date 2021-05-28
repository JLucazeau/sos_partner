<?php
session_start();
require '../elements/trycatch.php';
require '../elements/header.php';
require '../elements/navbar.php';
if (isset($_SESSION['email']) && isset($_SESSION['password'])) {
    $idUser=$_SESSION['id_user'];
    
    ?>
    <body >
        <div class="container">
            <?php
  				if (isset($_GET['idAmi'])) {
                $idAmi=$_GET['idAmi'];
                echo $idAmi;
                $selectConv="SELECT * FROM `convsimple` WHERE (membre1=$idUser and membre2=$idAmi) or (membre1=$idAmi and membre2=$idUser)";
                $sendSelectConv=$connect->query($selectConv);
                $countConv=$sendSelectConv->rowcount();
                $bdd=$sendSelectConv->fetch(PDO::FETCH_ASSOC);
                if ($countConv==0) {
                    $createConv="INSERT INTO `convsimple`(`id_conv`, `membre1`, `membre2`) VALUES (null,$idUser,$idAmi)";
                    $sendCreateConv=$connect->query($createConv);
                    $selectConv="SELECT * FROM `convsimple` WHERE (membre1=$idUser and membre2=$idAmi) or (membre1=$idAmi and membre2=$idUser)";
                    $sendSelectConv=$connect->query($selectConv);
                    $bdd=$sendSelectConv->fetch(PDO::FETCH_ASSOC);
                    $idConv=$bdd['id_conv'];
                    header("location:conversation.php?idConv=$idConv");
                }else {
                    $idConv=$bdd['id_conv'];
                    header("location:conversation.php?idConv=$idConv");
                }
                }elseif (isset($_GET['idConv'])) {
                $idConv=$_GET['idConv'];
                $recup="SELECT c.id_conv, c.membre1, c.membre2, u.name, u.first_name FROM `convsimple` c,`users` u WHERE ((c.membre1=u.id_user and c.membre2=$idUser) or (c.membre2=u.id_user and c.membre1=$idUser)) and c.id_conv=$idConv";
                $sendRecup=$connect->query($recup);
                $bdd=$sendRecup->fetch(PDO::FETCH_ASSOC);?>
                <h2 class="mt-5"><img class="rounded-circle mr-2 float-left" src="http://placehold.it/55x55" alt="profil picture">&nbsp;&nbsp;<?=ucfirst($bdd['first_name'])?>&nbsp;<?=ucfirst($bdd['name'])?></h2>
                <?php
                if (isset($_POST['send'])) {
                    $message=addslashes($_POST['message']);
                    $date=date("Y-m-d H:i:s");
                    $envoiMsg="INSERT INTO `messages` (`id_message`, `id_conv`, `id_user`, `content`, `date`, `statut`) VALUES (NULL, $idConv, $idUser, '$message', '$date', 'non-lu')";
                    $sendEnvoiMsg=$connect->query($envoiMsg);
                }?>
                <div class="border rounded mt-5 p-4 overflow-auto h-50">
                    <?php
                    $recupMsg="SELECT * FROM `messages` WHERE id_conv = $idConv";
                    $sendRecupMsg=$connect->query($recupMsg);
                    while ($bdd=$sendRecupMsg->fetch(PDO::FETCH_ASSOC)) {
                        $idMess=$bdd['id_message'];
                        if ($bdd['id_user']==$idUser) {
                            echo "
                                <small class='form-text text-muted text-right'>".$bdd['date']."</small>
                                <div class='d-flex flex-row-reverse '>
                                    ".$bdd['content']."
                                </div>
                            ";
                        }else {
                            echo "
                                <small class='form-text text-muted text-left'>".$bdd['date']."</small>
                                <div class='d-flex flex-row '>
                                    ".$bdd['content']."
                                </div>
                            ";
                            $setStatut="UPDATE `messages` SET `statut`= 'lu' WHERE id_message=$idMess";
                            $sendSetStatut=$connect->query($setStatut);
                        }
                    }?>               
                </div>
                <form action="conversation.php?idConv=<?=$idConv?>" method="post">
                    <div class="input-group mt-3">
                        <input type="text" class="form-control" placeholder="Search" name="message">
                        <div class="input-group-btn">
                        <button class="btn btn-default text-info" type="submit" name="send">
                            <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="currentColor" class="bi bi-arrow-up-circle" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                <path fill-rule="evenodd" d="M8 12a.5.5 0 0 0 .5-.5V5.707l2.146 2.147a.5.5 0 0 0 .708-.708l-3-3a.5.5 0 0 0-.708 0l-3 3a.5.5 0 1 0 .708.708L7.5 5.707V11.5a.5.5 0 0 0 .5.5z"/>
                            </svg>
                        </button>
                        </div>
                    </div>
                </form>
                <?php
            }else {
                header("location:../messages");
            }
            ?>
        </div>
    </body>    
<?php
} else {
    header("location:../connection/connect.php");
}?>