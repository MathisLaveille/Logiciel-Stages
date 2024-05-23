<?php

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
try {
    $dbh = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_POST['password']) && isset($_POST['RepeatPassword']) && isset($_POST['email'])) {
        $password = $_POST['password'];
        $password2 = $_POST['RepeatPassword'];
        $email = $_POST['email'];

        if ($password != $password2) {
            $errorMessage = "Les mots de passe ne correspondent pas.";
        } else {

            // Préparation de la requête
            $stmt = $dbh->prepare("UPDATE tbl_user
                       SET password_u = PASSWORD(CONCAT('*-6', :password))
                       WHERE mail_u = :email");

            // Liaison des paramètres
            $stmt->bindParam(':password', $password2);
            $stmt->bindParam(':email', $email);

            // Exécution de la requête
            $stmt->execute();
            header('location: /index.php');
        }
    }
} catch (PDOException $e) {
    $errorMessage = $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Forgot Password</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block text-center">
                                <!-- Ajout de la classe "text-center" pour aligner le contenu au centre -->
                                <img src="../img/NDLP.png" width="90%"> <!-- Largeur de 75% de la colonne parente -->
                            </div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-2">Mot de passe oublié ?</h1>
                                        <p class="mb-4"></p>
                                    </div>
                                    <form class="user" method="POST" action="forgot-password.php">

                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user" name="email"
                                                placeholder="Email">
                                        </div>

                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user"
                                                name="password" placeholder="Nouveau mot de passe">
                                        </div>

                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user"
                                                name="RepeatPassword" placeholder="Confirmation nouveau MDP">
                                        </div>

                                        <?php if (!empty($errorMessage)) { ?>
                                            <div class="alert alert-danger" role="alert">
                                                <?php echo $errorMessage; ?>
                                            </div>
                                        <?php } ?>

                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            Réinitialiser le mot de passe
                                        </button>

                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="register.php">Créer un compte !</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small" href="index.php">Déjà un compte ? Connectez vous !</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>