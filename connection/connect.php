<?php
session_start();
 if (isset($_POST['connexion']) && !isset($_SESSION['email']) && !isset($_SESSION['password'])) {
	header('location:../index.php');
  }
if (isset($_POST['connexion'])) {
  if (!empty($_POST['email'])) {
    if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
      if (!empty($_POST['password'])) {
        $formEmail=$_POST['email'];
        $formPsw=$_POST['password'];
        require '../elements/trycatch.php';
        $select = "SELECT * FROM users WHERE email = '$formEmail'";
        $send = $connect->query($select);
        $count = $send->rowCount();
        if ($count>0) {
          $bdd=$send->fetch(PDO::FETCH_ASSOC);
          $bddPsw=$bdd['password'];
          if ($bddPsw===$formPsw) {
            $_SESSION['email']=$formEmail;
            $_SESSION['password']=$formPsw;
            $_SESSION['id_user']=$bdd['id_user'];
            $_SESSION['name']=ucfirst($bdd['name']);
            $_SESSION['first_name']=ucfirst($bdd['first_name']);
          }else {
            $pswError="Désolé, le mot de passe est incorrect. Veuillez réessayer.";
          }
        }else {
          $mailError="Désolé, nous n'avons pas trouvé de compte avec cette adresse e-mail. Veuillez réessayer ou <a href='signin.php'>créez un nouveau compte</a>.";
        }
      }else {
        $pswError="Mot de passe manquant !";
      }
    }else {
      $mailError="Adresse e-mail incorrecte !";
    }
  }else {
    $mailError="Adresse e-mail manquante !";
  }
  if (isset($mailError)) {
    echo $mailError;
  }elseif (isset($pswError)) {
    echo $pswError;
  }
}

require '../elements/header.php';
require '../elements/navbar.php';
?>
<!-- notif'
<p class="notification text-danger" data-close="self">Le site comporte des problèmes de redirection. Une mise à jour devrais arriver.. </p>
<script src="https://unpkg.com/simple-notifications-solution/dist/Notifications.js"></script>
<script>
  var notifications = new Notifications();
  notifications.init();
</script>-->
<div class="container">
    <div class="row mt-5">
        <div class="col"></div>
        <div class="col-5">
            <form class="" action="" method="post">
                <div class="form-group">
                    <label for="inputEmail">Email address</label>
                    <input type="email" class="form-control" id="inputEmail" aria-describedby="emailHelp" name="email">
                </div>
                <div class="form-group">
                    <label for="inputPassword">Password</label>
                    <input type="password" class="form-control" id="inputPassword" name="password">
                </div>
                <input type="submit" class="btn btn-primary" name="connexion" value="Connexion">
            </form>
          <?php
          	
          ?>
        <br>
        <a href="signup.php">Créer un compte</a>
        </div>
        <div class="col"></div>
    </div>
</div>
