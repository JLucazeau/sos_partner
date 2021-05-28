<?php
session_start();
require 'elements/trycatch.php';
?>
<html lang="en" dir="ltr">
    <head>
        <title>SOS Partner</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/bootstrap.flatly.min.css">
        <link rel="stylesheet" href="css/mystyles.css">
        <script src="js/jquery-3.5.1.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script defer src="https://use.fontawesome.com/releases/v5.14.0/js/all.js"></script>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <link rel="stylesheet" href="/resources/demos/style.css">
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <link rel="stylesheet" href="https://unpkg.com/simple-notifications-solution/dist/Notifications.css">
    </head>
    <body><!--notif'
        <p class="notification text-danger" data-close="self">Le site comporte des problèmes de redirection. Une mise à jour devrais arriver.. </p>
        <script src="https://unpkg.com/simple-notifications-solution/dist/Notifications.js"></script>
        <script>
          var notifications = new Notifications();
          notifications.init();
        </script>-->
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <a class="navbar-brand align-middle" href="/">
                <img src="medias/logo-ballons.png" alt="" width="" height="50" class="d-inline-block align-middle">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarColor01">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="recherche">Recherche</a>
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
                <a class="nav-link" href="messages">
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
                        <a class="nav-link" href="profil">Profil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact">Contact</a>
                    </li>
                </ul>
                <?php
                if (isset($_SESSION['email']) && isset($_SESSION['password'])) {
                    echo "<span class='navbar-text text-white'>",$_SESSION['first_name']," ",$_SESSION['name'],", connecté(e).&nbsp;&nbsp;&nbsp;</span>";
                    echo "<a class='btn btn-primary' href='connection/disconnect.php'>Déconnexion</a>";
                }else {
                    echo "<a class='btn btn-primary' href='connection/connect.php'>Connexion</i></a>";
                }
            ?>
            </div>
        </nav>
        <div class="wallpaper">
            <div class="container">
                <div class="row">
                    <div class="col-sm ">
                        <div class="jumbotron mt-5">
                            <h1 class="display-4">SOS Partner</h1>
                            <p class="lead">Grâce à notre site, vous ne serez plus jamais seul(e) pour vos activités physique ou sportives !</p>
                            <hr class="my-4">
                            <p>SOS Partner vous propose de recontrer, grâce à vos préférences, d'autres sportif en quête de partenaires. Ce site est un projet proposé par la Maison des Ligues de Lorraine, et a pour objectif de dynamiser les echanges sportif dans la région. </p>
                            <a class="btn btn-primary btn-lg" href="#" role="button">En savoir plus</a>
                        </div>
                        <div id="carouselExampleCaptions" class="carousel slide mt-5" data-ride="carousel">
                            <ol class="carousel-indicators">
                                <li data-target="#carouselExampleCaptions" data-slide-to="0" class="active"></li>
                                <li data-target="#carouselExampleCaptions" data-slide-to="1"></li>
                                <li data-target="#carouselExampleCaptions" data-slide-to="2"></li>
                            </ol>
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img src="medias/Marathon-de-Paris-ils-l-ont-couru-ils-nous-racontent (2).jpg" class="d-block w-100" alt="marathon paris">
                                    <div class="carousel-caption d-none d-md-block">
                                        <div class="img-desc text-primary p-4">
                                            <h1>Marathon de paris</h1>
                                            <p>le semi-marathon et le marathon de Paris prévus les 5 septembre et 17 octobre 2021</p>
                                            <hr>
                                            <a class="btn btn-primary " href="https://www.leparisien.fr/sports/running-on-connait-les-dates-du-semi-marathon-et-marathon-de-paris-2021-17-11-2020-8408768.php">En savoir plus</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="carousel-item">
                                    <img src="medias/991014 (2).jpg" class="d-block w-100" alt="meeting indoor">
                                    <div class="carousel-caption d-none d-md-block">
                                        <div class="img-desc text-primary p-4">
                                            <h1>MEETING DE PARIS INDOOR 2020</h1>
                                            <p>Le meeting de Paris indoor s'est déroulé à l'Accor Hotel Arena le 2 février 2020, avec un objectif de show d'avant-saison, à 6 mois des Jeux Olympiques de Tokyo.</p>
                                            <hr>
                                            <a class="btn btn-primary " href="https://www.accorhotelsarena.com/fr/sports-a-Paris/meeting-de-paris-indoor-2020">En savoir plus</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="carousel-item">
                                    <img src="medias/sport-maison.jpg" class="d-block w-100" alt="sport maison">
                                    <div class="carousel-caption d-none d-md-block">
                                        <div class="img-desc text-primary p-4">
                                            <h1>Le Tabata</h1>
                                            <p>Tout au long de ce confinement, sur 28 jours, suivez notre programme de sport à la maison de sport à la maison (une séance par jour, sauf le week-end). Objectif : perdre de la masse grasse et se tonifier chez soi sans matériel.</p>
                                            <hr>
                                            <a class="btn btn-primary " href="https://www.lequipe.fr/Coaching/Sport-a-la-maison/Actualites/Programme-sport-a-la-maison-n-11-le-tabata/1197679">En savoir plus</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a class="carousel-control-prev" href="#carouselExampleCaptions" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#carouselExampleCaptions" role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
