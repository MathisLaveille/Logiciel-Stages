<?php 
print(0);
$errorMessage = "";

try {
    print(1);

    if (isset($_POST['password']) && isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['email']) && isset($_POST['phone'])) {
        print(2);
        $password = $_POST['password'];
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
    
        print("email = '$email'");
        
        // Connexion à la base de données

        $dbh = new PDO('mysql:host=172.16.136.9;dbname=logiciel_stages', 'root', 'root');
    
        // Préparation de la requête
        $stmt = $dbh->prepare("INSERT INTO tbl_user (password_p, nom_p, prenom_p, mail_p, phone_p) VALUES (PASSWORD(CONCAT('*-6',:password)), :nom, :prenom, :email, :phone)");
    
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
} catch (PDOException $e) {
    print(3);
    $code = $e->getCode();
    if ($code == 23000) {
        $errorMessage = "C'est adresse email existe déjà.";
    }

    print("code = '$code'");
    print($e->getMessage());
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
<<<<<<< HEAD

=======
>>>>>>> 0704f64be5bbe1840afe4f3b8716e4c1b8a09167
<body class="bg-gradient-primary">

    <div class="container">

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                            </div>
<<<<<<< HEAD
                            <form class="user" method="post" action="register.php">
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="text" class="form-control form-control-user" name="nom"
                                            placeholder="Nom">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control form-control-user" name="prenom"
                                            placeholder="Prenom">
=======
                            <form class="user">
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="text" class="form-control form-control-user" name="nom"
                                            placeholder="Votre nom ...">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control form-control-user" name="prenom"
                                            placeholder="Votre prenom ...">
>>>>>>> 0704f64be5bbe1840afe4f3b8716e4c1b8a09167
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control form-control-user" name="email"
<<<<<<< HEAD
                                        placeholder="email">
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
                                            name="RepeatPassword" placeholder="Confirmation du Mot De Passe">
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn btn-primary btn-user btn-block">
                                Valide le compte
                                </button>
                            </form>
                            <hr>
                            <div class="text-center">
                                <a class="small" href="forgot-password.html">Mot de passe oublié?</a>
                            </div>
                            <div class="text-center">
                                <a class="small" href="login.php">Déjà un compte? Connectez vous!</a>
=======
                                        placeholder="Votre email ...">
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="password" class="form-control form-control-user"
                                            name="password" placeholder="Votre mot de passe ...">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="password" class="form-control form-control-user"
                                            name="password_two" placeholder="Répéter votre mot de passe ...">
                                    </div>
                                </div>
                                <a href="login.html" class="btn btn-primary btn-user btn-block">
                                    Valider le compte*
                                </a>
                                <hr>

                            </form>
                            <hr>
                            <div class="text-center">
                                <a class="small" href="forgot-password.html">Forgot Password?</a>
                            </div>
                            <div class="text-center">
                                <a class="small" href="login.html">Already have an account? Login!</a>
>>>>>>> 0704f64be5bbe1840afe4f3b8716e4c1b8a09167
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


<<<<<<< HEAD


=======
______________________________________________

    <?php


    // Connexion à la base de données
    $dbh = new PDO('mysql:host=localhost;dbname=test', $user, $pass);

    // Préparation de la requête
    $stmt = $dbh->prepare("INSERT INTO fighters (name, strength, defense) VALUES (:name, :strength, :defense)");

    //Déclaration des variables
    $name = "Miss Fortune";
    $strength = 100;
    $defense = 50;

    // Liaison des paramètres
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':strength', $strength);
    $stmt->bindParam(':defense', $defense);

    // Exécution de la requête
    $stmt->execute();

    ?>
>>>>>>> 0704f64be5bbe1840afe4f3b8716e4c1b8a09167
</body>

</html>