<?php

$servername = "192.168.0.109";
$username = "root";
$password = "root";
$dbname = "logiciel_stages";

// Créer une connexion à la base de données
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
}

// Récupérer l'identifiant du fichier depuis les paramètres de l'URL
if(isset($_GET['id'])) {
    $file_id = $_GET['id'];

    // Récupérer le fichier depuis la base de données
    $sql = "SELECT nom, contenu FROM fichiers_php WHERE id = $file_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Afficher le contenu du fichier
        $row = $result->fetch_assoc();
        $file_name = $row["nom"];
        $file_content = $row["contenu"];

        // Envoyer les en-têtes appropriés pour indiquer que c'est un fichier à télécharger
        header("Content-type: application/pdf");
        header("Content-Disposition: inline; filename='$file_name'");
        echo base64_decode($file_content);
    } else {
        echo "Le fichier demandé n'existe pas.";
    }
} else {
    echo "Identifiant du fichier non spécifié.";
}
?>
