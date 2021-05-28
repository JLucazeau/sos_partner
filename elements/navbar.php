<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <a class="navbar-brand align-middle" href="../">
        <img src="../medias/logo-ballons.png" alt="" width="" height="50" class="d-inline-block align-middle">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarColor01">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="../recherche">Recherche</a>
            </li>
            <li class="nav-item">
                <?php
                if (isset($_SESSION['id_user'])) {
                    $idUser=$_SESSION['id_user'];
                    $reqFriends="Select d.id_fav, d.iduser, d.idtarget, u.name, u.first_name from demandes d,users u where idtarget=$idUser and d.iduser=u.id_user";
                    $send=$connect->query($reqFriends);
                    $count=$send->rowcount();
                    $selectConv="SELECT * FROM `convsimple` WHERE membre1=$idUser or membre2=$idUser";
                    $sendSelectConv=$connect->query($selectConv);
                    $tabConv = array();
                    $total=0;
                    while ($bdd=$sendSelectConv->fetch(PDO::FETCH_ASSOC)) {
                        array_push($tabConv, $bdd['id_conv']);
                    }
                    foreach ($tabConv as $value) {
                        $messAtt="SELECT * FROM `messages` WHERE id_user!=$idUser and statut='non-lu' and id_conv=$value";
                        $sendMessAtt=$connect->query($messAtt);
                        $countMess=$sendMessAtt->rowcount();
                        $total=$total+$countMess;
                    }
                }?>
                <a class="nav-link" href="../messages">
                    Messages
                    <?php
                    if (isset($total)) {
                        if ($total>0) {?>
                            <span class="badge badge-danger"><?=$total?></span>
                            <?php
                        }
                    }?>
                    / Amis
                    <?php
                    if (isset($count)) {
                        if ($count>0) {?>
                            <span class="badge badge-danger"><?=$count?></span>
                            <?php
                        }
                    }?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../profil">Profil</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../contact">Contact</a>
            </li>
        </ul>
        <?php
        if (isset($_SESSION['email']) && isset($_SESSION['password'])) {
            echo "<span class='navbar-text text-white'>",$_SESSION['first_name']," ",$_SESSION['name'],", connecté(e).&nbsp;&nbsp;&nbsp;</span>";
            echo "<a class='btn btn-primary' href='../connection/disconnect.php'>Déconnexion</a>";
        }else {
            echo "<a class='btn btn-primary' href='../connection/connect.php'>Connexion</i></a>";
        }
    ?>
    </div>
</nav>
