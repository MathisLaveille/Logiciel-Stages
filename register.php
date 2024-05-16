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
$errorMessage = "";

try {

    if (isset($_POST['password']) && isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['email']) && isset($_POST['phone']) && isset($_POST['RepeatPassword'])) {
        $password = $_POST['password'];
        $password2 = $_POST['RepeatPassword'];
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];

        if ($password != $password2) {
            $errorMessage = "Les mots de passe ne correspondent pas.";
        } else {

            // Connexion à la base de données

            $dbh = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

            // Préparation de la requête
            $stmt = $dbh->prepare("INSERT INTO tbl_user (password_u, nom_u, prenom_u, mail_u, phone_u) VALUES (PASSWORD(CONCAT('*-6',:password)), :nom, :prenom, :email, :phone)");

            // Liaison des paramètres
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':prenom', $prenom);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':phone', $phone);

            // Exécution de la requête
            $stmt->execute();
            header('location: /login.php');
        }
    }
} catch (PDOException $e) {

    $code = $e->getCode();
    if ($code == 23000) {
        $errorMessage = "C'est adresse email existe déjà.";
    }
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

    <title>SB Admin 2 - Register</title>

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

        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">
                 <style>
        .col-lg-6 img {
            width: 90%; /* Ajustez la largeur de l'image à 90% de la colonne parente */
            margin: 0 auto; /* Centre horizontalement l'image */
        }
    </style>

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block text-center">
                                <!-- Ajout de la classe "text-center" pour aligner le contenu au centre -->
                                <img src="../img/NDLP.png" width="75%"> <!-- Largeur de 75% de la colonne parente -->
                            </div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Create un compte!</h1>
                                    </div>
                                    <form class="user" method="post" action="register.php">
                                        <div class="form-group row">
                                            <div class="col-sm-6 mb-3 mb-sm-0">
                                                <input type="text" class="form-control form-control-user" name="nom"
                                                    placeholder="Nom">
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control form-control-user" name="prenom"
                                                    placeholder="Prenom">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user" name="email"
                                                placeholder="Email">
                                        </div>

                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user" name="phone"
                                                placeholder="Numéro De Téléphone">
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-sm-6 mb-3 mb-sm-0">
                                                <input type="password" class="form-control form-control-user"
                                                    name="password" placeholder="Mot De Passe">
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="password" class="form-control form-control-user"
                                                    name="RepeatPassword" placeholder="Confirmation MDP">
                                            </div>
                                        </div>

                                        <?php if (!empty($errorMessage)) { ?>
                                            <div class="alert alert-danger" role="alert">
                                                <?php echo $errorMessage; ?>
                                            </div>
                                        <?php } ?>

                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            Valide le compte
                                        </button>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="forgot-password.php">Mot de passe oublié?</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small" href="login.php">Déjà un compte? Connectez vous!</a>
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