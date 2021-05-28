<?php
session_start();
require '../elements/trycatch.php';
require '../elements/header.php';
require '../elements/navbar.php';
?>
<div class="container">
    <h1 class="display-4 my-5">Contact</h1>
    <div class="row">
        <div class="col"></div>
        <div class="col-6">
            <form action="" method="post">
                <div class="mb-3">
                    <label for="inputName" class="form-label">Nom</label>
                    <input type="text" class="form-control" id="inputName" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="inputMail" class="form-label">Email</label>
                    <input type="email" class="form-control" id="inputMail" name="mail" required>
                </div>
                <div class="mb-3">
                    <label for="inputObjet" class="form-label">Objet</label>
                    <input type="text" class="form-control" id="inputObjet" name="objet" required>
                </div>
                <div class="mb-3">
                    <label for="textMessage" class="form-label">Message</label>
                    <textarea class="form-control" id="textMessage" rows="3" name="message" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary" name="send">Envoyer</button>
            </form>
            <?php
            if (isset($_POST['send'])) {
                $header="MIME-Version: 1.0\r\n";
                $header.='From:"Josselin-Lucazeau.fr"<contact@josselin-lucazeau.fr>'."\n";
                $header.='Content-Type:text/html; charset="uft-8"'."\n";
                $header.='Content-Transfer-Encoding: 8bit';

                $message='
                    <html>
                        <body>
                        <div align="center">
                            <br />
                            <u>Nom de l\'expéditeur :</u>'.$_POST['name'].'<br />
                            <u>Mail de l\'expéditeur :</u>'.$_POST['mail'].'<br />
                            <u>Sujet :</u>'.$_POST['objet'].'<br />
                            <br />
                            '.nl2br($_POST['message']).'
                            <br />
                        </div>
                        </body>
                    </html>
                ';
                $sujet=$_POST['objet'];
                $send=mail("contact@josselin-lucazeau.fr",$sujet, $message, $header);
                if ($send) {
                    echo '
                    <div class="alert alert-success" role="alert">
                        Message envoyé ! Vous recevrez une réponse dans les plus brefs délais.
                    </div>
                    ';
                }else {
                    echo '
                    <div class="alert alert-danger" role="alert">
                        Erreur lors de l\'envoi
                    </div>
                    ';
                }
            }
            ?>
        </div>
        <div class="col"></div>
    </div>
</div>