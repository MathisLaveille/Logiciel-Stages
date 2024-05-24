<?php
session_start();

require 'vendor/autoload.php';

// Charger les variables d'environnement à partir du fichier .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Récupérer les variables d'environnement
$servername = $_ENV['BD_HOST'];
$username = $_ENV['BD_USER'];
$password = $_ENV['BD_PASS'];
$dbname = $_ENV['BD_NAME'];

// Connexion à la base de données
$connection = mysqli_connect($servername, $username, $password, $dbname);

// Vérifier la connexion
if (!$connection) {
    die("La connexion a échoué : " . mysqli_connect_error());
}

// Récupérer les données du formulaire
$id_stage = $_POST['id_s'];
$nom_e = $_POST['nom_e'];
$rue_e = $_POST['rue_e'];
$CP_e = $_POST['CP_e'];
$city_e = $_POST['city_e'];
$phone_e = $_POST['phone_e'];
$period_start_s = $_POST['period_start_s'];
$period_end_s = $_POST['period_end_s'];

// Mettre à jour les informations dans la base de données
$query = "UPDATE tbl_stage
          JOIN tbl_company ON tbl_stage.id_s = tbl_company.id_e
          SET tbl_company.nom_e = '$nom_e', tbl_company.rue_e = '$rue_e', tbl_company.CP_e = '$CP_e',
              tbl_company.city_e = '$city_e', tbl_company.phone_e = '$phone_e',
              tbl_stage.period_start_s = '$period_start_s', tbl_stage.period_end_s = '$period_end_s'
          WHERE tbl_stage.id_s = '$id_stage'";

if (mysqli_query($connection, $query)) {
    // Rediriger vers la page des stages après la mise à jour
    header("Location: tables.php");
} else {
    echo "Erreur de mise à jour : " . mysqli_error($connection);
}

// Fermer la connexion à la base de données
mysqli_close($connection);
?>
