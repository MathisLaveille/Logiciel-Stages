<?php
require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$servername = $_ENV['BD_HOST'];
$username = $_ENV['BD_USER'];
$password = $_ENV['BD_PASS'];
$dbname = $_ENV['BD_NAME'];

try {
    $dbh = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['valider'])) {
            list($companyId, $stageId) = explode('_', $_POST['valider']);

            // Récupérer les données de tbl_verifier_company
            $stmt = $dbh->prepare("SELECT * FROM tbl_verifier_company WHERE id_v = ?");
            $stmt->execute([$companyId]);
            $companyData = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($companyData) {
                // Insérer les données dans tbl_company
                $stmt = $dbh->prepare("INSERT INTO tbl_company (nom_e, rue_e, CP_e, city_e, phone_e) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([
                    $companyData['nom_v'],
                    $companyData['rue_v'],
                    $companyData['CP_v'],
                    $companyData['city_v'],
                    $companyData['phone_v']
                ]);

                // Récupérer les données de tbl_verifier_stage
                $stmt = $dbh->prepare("SELECT * FROM tbl_verifier_stage WHERE id_v = ?");
                $stmt->execute([$stageId]);
                $stageData = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($stageData) {
                    // Insérer les données dans tbl_stage
                    $companyIdNew = $dbh->lastInsertId();
                    $stmt = $dbh->prepare("INSERT INTO tbl_stage (period_start_s, period_end_s, id_s) VALUES (?, ?, ?)");
                    $stmt->execute([
                        $stageData['period_start_v'],
                        $stageData['period_end_v'],
                        $companyIdNew
                    ]);

                    // Supprimer les données des tables tbl_verifier_company et tbl_verifier_stage
                    $stmt = $dbh->prepare("DELETE FROM tbl_verifier_company WHERE id_v = ?");
                    $stmt->execute([$companyId]);

                    $stmt = $dbh->prepare("DELETE FROM tbl_verifier_stage WHERE id_v = ?");
                    $stmt->execute([$stageId]);
                }
            } 
        } elseif (isset($_POST['refuser'])) {
            list($companyId, $stageId) = explode('_', $_POST['refuser']);

            // Supprimer les données des tables tbl_verifier_company et tbl_verifier_stage
            $stmt = $dbh->prepare("DELETE FROM tbl_verifier_company WHERE id_v = ?");
            $stmt->execute([$companyId]);

            $stmt = $dbh->prepare("DELETE FROM tbl_verifier_stage WHERE id_v = ?");
            $stmt->execute([$stageId]);
        }
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}

$dbh = null;

// Rediriger vers la page tables.php après 3 secondes
header("Location: validation_stage.php");
exit();
?>
