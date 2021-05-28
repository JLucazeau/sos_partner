<?php
session_start();
if (isset($_SESSION['email']) && isset($_SESSION['password'])) {
  	require '../elements/trycatch.php';
	require '../elements/header.php';
  	require '../elements/navbar.php';
    $idUser=$_SESSION['id_user'];?>
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-sm-8">
                <div class="request_area">
                    <?php
                    if (isset($_POST['ajouter'])) {
                    $idAskUser=$_POST['idAskUser'];
                    $idFav=$_POST['idFav'];
                    $del="DELETE FROM `demandes` WHERE `demandes`.`id_fav` = $idFav";
                    $send=$connect->query($del);
                    $add="INSERT INTO `amis` (`id_amitie`, `ami1`, `ami2`) VALUES (NULL, '$idUser', '$idAskUser')";
                    $send=$connect->query($add);
                    }
                    if (isset($_POST['supprimer'])) {
                        $idFav=$_POST['idFav'];
                        $del="DELETE FROM `demandes` WHERE `demandes`.`id_fav` = $idFav";
                        $send=$connect->query($del);
                    }
                    $reqFriends="Select d.id_fav, d.iduser, d.idtarget, u.name, u.first_name from demandes d,users u where idtarget=$idUser and d.iduser=u.id_user";
                    $send=$connect->query($reqFriends);
                    $count=$send->rowcount();
                    if ($count>0) {
                        echo "<h3 class='mt-5'>Demandes d'amis</h3> <hr>";
                    }
                    while ($bdd=$send->fetch(PDO::FETCH_ASSOC)) {
                        $idAskUser=$bdd['iduser'];
                        $idFav=$bdd['id_fav'];?>
                        <div class="media">
                            <img src="http://placehold.it/100x100" class="mr-3" alt="...">
                            <div class="media-body">
                                <h5 class="mt-0"><?=ucfirst($bdd['first_name'])?>&nbsp;<?=ucfirst($bdd['name'])?></h5>
                                Souhaite devenir votre ami(e).
                            </div>
                            <form action="" method="post">
                                <button class="btn btn-success m-2" type="submit" name="ajouter"><i class="fas fa-plus"></i>&nbsp;&nbsp;Ajouter</button>
                                <input type='hidden' name='idFav' value='<?=$idFav?>'>
                                <input type='hidden' name='idAskUser' value='<?=$idAskUser?>'>
                            </form>
                            <form action="" method="post">
                                <input class="btn btn-light m-2" type="submit" name="supprimer" value="Supprimer">
                                <input type='hidden' name='idFav' value='<?=$idFav?>'>
                            </form>
                        </div>
                        <hr>
                        <?php
                    }
                    ?>  
                </div>
                <h1 class="display-4 mt-5">Messages</h1>
                <ul class="list-group list-group-flush">
                    <?php
                        $dateNow=new DateTime(date("Y-m-d H:i:s"));
                        $selectConv="SELECT c.id_conv, c.membre1, c.membre2, u.name, u.first_name FROM `convsimple` c,`users` u WHERE (c.membre1=u.id_user and c.membre2=$idUser) or (c.membre2=u.id_user and c.membre1=$idUser)";
                        $sendConv=$connect->query($selectConv);
                        $count=$sendConv->rowcount();
                        if ($count==0) {
                            echo "<h3 class='mt-5'>Aucun message :(</h3> <hr>";
                        }else {
                            while ($bdd=$sendConv->fetch(PDO::FETCH_ASSOC)) {
                                $name=ucfirst($bdd['name']);
                                $first_name=ucfirst($bdd['first_name']);
                                $idConv=$bdd['id_conv'];
                                if ($bdd['membre1']==$idUser) {
                                    $idAmi=$bdd['membre2'];
                                }else {
                                    $idAmi=$bdd['membre1'];
                                }
                                $selectMess="SELECT * FROM `messages` WHERE id_conv=$idConv ORDER BY date DESC limit 1";
                                $sendMess=$connect->query($selectMess);
                                $bdd=$sendMess->fetch(PDO::FETCH_ASSOC);
                                $dateMess=new DateTime($bdd['date']);
                                $interval = $dateNow->diff($dateMess);
                                $interval=$interval->format('%a');
                                $selectNonLu="SELECT * FROM `messages` WHERE statut='non-lu' and id_user=$idAmi and id_conv=$idConv";
                                $sendCountNonLu=$connect->query($selectNonLu);
                                $countNonLu=$sendCountNonLu->rowcount();
                                ?>
                                <a href="conversation.php?idConv=<?=$idConv?>" class="text-primary nounderline">
                                    <li class="list-group-item">
                                        <div class="media">
                                            <div class="media-body">
                                                <h5 class="mt-0"><?=ucfirst($first_name)?>&nbsp;<?=ucfirst($name)?>&nbsp;
                                                    <?php
                                                    if (isset($countNonLu)) {
                                                        if ($countNonLu>0) {?>
                                                            <span class="badge badge-danger"><?=$countNonLu?></span>
                                                            <?php
                                                        }
                                                    }?>
                                                </h5>
                                                <?php
                                                if ($bdd['statut']=='non-lu'and$bdd['id_user']!=$idUser) {
                                                    echo "<strong>".$bdd['content']."</strong>";
                                                }else {
                                                    echo $bdd['content'];
                                                }?>
                                            </div>
                                            <span>
                                            <?php
                                                if ($interval==0) {
                                                    echo $dateMess->format('H:i:s');
                                                }else {
                                                    echo $dateMess->format('d/m/Y');
                                                }
                                            ?>
                                            </span>
                                        </div>  
                                    </li>
                                </a>
                            <?php
                            }
                        }
                    ?>
                </ul>
            </div>
            <div class="col-lg-4 col-sm-4">
                <h4 class="display-6 mt-5 ml-3">Amis</h4>
                <?php
                    if (isset($_POST['friendDel'])) {
                        $id_amitieDel=$_POST['hidden'];
                        $deleteFriend="DELETE FROM `amis` WHERE `amis`.`id_amitie` = $id_amitieDel";
                        $sendDeleteFriend=$connect->query($deleteFriend);
                    }
                ?>
                <div class="accordion md-accordion" id="accordionEx" role="tablist" aria-multiselectable="false">
                    <ul class="list-group list-group-flush btn text-left">
                        <?php
                        $selectAllFriends="SELECT a.id_amitie, a.ami1, a.ami2, u.name, u.first_name FROM `amis` a,`users` u WHERE (a.ami2=u.id_user and a.ami1=$idUser) or (a.ami1=u.id_user and a.ami2=$idUser)";
                        $sendAllFriends=$connect->query($selectAllFriends);
                        $count=$sendAllFriends->rowcount();
                        if ($count==0) {
                            echo "<h3 class='mt-5'>Aucun ami :(</h3> <hr>";
                        }else {
                            while ($bdd=$sendAllFriends->fetch(PDO::FETCH_ASSOC)) {
                                $id_amitie=$bdd['id_amitie'];
                                $name=ucfirst($bdd['name']);
                                $first_name=ucfirst($bdd['first_name']);
                                if ($bdd['ami1']==$idUser) {
                                    $idAmi=$bdd['ami2'];
                                }else {
                                    $idAmi=$bdd['ami1'];
                                }?>
                            <li class="list-group-item" data-toggle="collapse" data-parent="#accordionEx" href="#collapseText<?=$idAmi?>" aria-expanded="false" aria-controls="collapseText<?=$idAmi?>">
                                <h5 class="mt-1"><img class="rounded-circle mr-2 float-left" src="http://placehold.it/30x30" alt="profil picture"><?=$first_name?>&nbsp;<?=$name?></h5>               
                            </li>
                            <div id="collapseText<?=$idAmi?>" class="collapse" role="tabpanel" data-parent="#accordionEx">
                                <form action="" method="post">
                                    <a class="btn btn-link m-2" href="profil.php?idAmi=<?=$idAmi?>">
                                    <svg width="1.5em" height="1.5em" viewBox="0 0 16 16" class="bi bi-person-lines-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm7 1.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5zm-2-3a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5zm0-3a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5zm2 9a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5z"/>
                                    </svg>
                                    </a>
                                    <a class="btn btn-link m-2" href="conversation.php?idAmi=<?=$idAmi?>">
                                    <svg width="1.5em" height="1.5em" viewBox="0 0 16 16" class="bi bi-chat-left-text" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M14 1H2a1 1 0 0 0-1 1v11.586l2-2A2 2 0 0 1 4.414 11H14a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 0a2 2 0 0 0-2 2v12.793a.5.5 0 0 0 .854.353l2.853-2.853A1 1 0 0 1 4.414 12H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
                                        <path fill-rule="evenodd" d="M3 3.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zM3 6a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 6zm0 2.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z"/>
                                    </svg>
                                    </a>
                                    <input type="hidden" name="hidden" value="<?=$id_amitie?>">
                                    <button class="btn btn-link m-2" onclick="friendDel()" type="submit" name="friendDel">
                                    <svg width="1.5em" height="1.5em" viewBox="0 0 16 16" class="bi bi-trash" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                        <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                    </svg>
                                    </button>
                                </form>
                                <script>
                                    function friendDel() {
                                        confirm("Souhaitez vous vraiment supprimer <?=$first_name?> <?=$name?> de vos amis ?");
                                    }
                                </script>                                
                            </div>
                            <?php
                            }
                        }
                        ?>
                    </ul>
                </div> 
            </div> 
        </div>
    </div>
<?php
} else {
    header("location:../connection/connect.php");
}?>