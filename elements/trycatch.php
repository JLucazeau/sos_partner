<?php
$host_name = 'db5002624246.hosting-data.io';
$database = 'dbs2084271';
$user_name = 'dbu1615893';
$password = 'sos_partner1';
$dbh = null;

  try {
    $connect = new PDO("mysql:host=$host_name; dbname=$database;", $user_name, $password);
  } catch (PDOException $e) {
    echo "Erreur!: " . $e->getMessage() . "<br/>";
    die();
  }
?>

