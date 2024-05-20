<?php
// Configuration de la connexion à la base de données
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

// Vérifier si le formulaire a été soumis
if(isset($_POST["submit"])) {
    // Vérifier si un fichier a été téléchargé
    if(isset($_FILES['fileToUpload'])) {
        $file_name = $_FILES['fileToUpload']['name'];
        $file_tmp = $_FILES['fileToUpload']['tmp_name'];

        // Lire le contenu du fichier et l'encoder en base64
        $file_content = base64_encode(file_get_contents($file_tmp));

        // Insérer le fichier dans la base de données
        $sql = "INSERT INTO fichiers_php (nom, contenu) VALUES ('$file_name', '$file_content')";

        if ($conn->query($sql) === TRUE) {
            echo "Le fichier $file_name a été téléchargé et enregistré dans la base de données avec succès.";
        } else {
            echo "Une erreur s'est produite lors de l'enregistrement du fichier dans la base de données : " . $conn->error;
        }
    } else {
        echo "Aucun fichier n'a été téléchargé.";
    }
}

// Sélectionner tous les fichiers de la base de données
$sql = "SELECT id, nom FROM fichiers_php";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Afficher la liste des fichiers
    echo "<h2>Liste des fichiers téléchargés :</h2>";
    echo "<ul>";
    while($row = $result->fetch_assoc()) {
        $file_id = $row["id"];
        $file_name = $row["nom"];
        echo "<li><a href='view_file.php?id=$file_id'>$file_name</a></li>";
    }
    echo "</ul>";
} else {
    echo "Aucun fichier n'a été téléchargé.";
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Formulaire de téléchargement de fichiers PHP</title>
</head>
<body>

  <h2>Télécharger un fichier PHP</h2>

  <form action="formulaire.php" method="post" enctype="multipart/form-data">
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Télécharger" name="submit">
  </form>

</body>
</html>
