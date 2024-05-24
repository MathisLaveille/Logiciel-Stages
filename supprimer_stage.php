<?php
// Démarrer la session
session_start();

// Inclure les fichiers nécessaires
require 'vendor/autoload.php';

// Charger les variables d'environnement
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Récupérer les variables d'environnement pour la base de données
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

// Vérifier si l'identifiant du stage est passé en paramètre
if (isset($_GET['id_s'])) {
    $stage_id = intval($_GET['id_s']);

    // Préparer la requête de suppression
    $query = "DELETE FROM tbl_stage WHERE id_s = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param('i', $stage_id);

    // Exécuter la requête
    if ($stmt->execute()) {
        // Rediriger vers la page des stages après la suppression
        header("Location: tables.php");
        exit();
    } else {
        echo "Erreur lors de la suppression du stage : " . $stmt->error;
    }

    // Fermer la requête préparée
    $stmt->close();
} else {
    echo "Identifiant de stage non spécifié.";
}

// Fermer la connexion à la base de données
mysqli_close($connection);
?>
