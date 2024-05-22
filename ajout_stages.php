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

$errorMessage = "";

echo $_FILES['pdf-file']['name'];
echo $_FILES['pdf-file']['tmp_name'];

try {

    // Vérifier si le formulaire a été soumis et que toutes les variables sont présentes
    if (isset($_POST['nom']) && isset($_POST['rue']) && isset($_POST['postal']) && isset($_POST['ville']) && isset($_POST['phone']) && isset($_POST['date_debut']) && isset($_POST['date_fin'])) {
        $upload_dir = 'uploads/';

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        if ($_FILES['pdf-file']['error'] === UPLOAD_ERR_OK) {

            $file_name = $_FILES['pdf-file']['name'];
            $file_tmp = $_FILES['pdf-file']['tmp_name'];
            $file_path = $upload_dir . basename($file_name);


            if (move_uploaded_file($file_tmp, $file_path)) {
                echo "Fichier téléchargé avec succès : " . $file_name;
            } else {
                echo "Erreur lors du déplacement du fichier.";
            }
        } else {
            echo "Erreur lors du téléchargement du fichier.";
        }

        $nom = $_POST['nom'];
        $rue = $_POST['rue'];
        $postal = $_POST['postal'];
        $ville = $_POST['ville'];
        $phone = $_POST['phone'];
        $date_debut = $_POST['date_debut'];
        $date_fin = $_POST['date_fin'];

        // Connexion à la base de données
        $dbh = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

        // Préparation de la requête pour insérer les informations de l'entreprise
        $stmt1 = $dbh->prepare("INSERT INTO tbl_company (nom_e, rue_e, CP_e, city_e, phone_e) VALUES (:nom, :rue, :postal, :ville, :phone)");

        // Liaison des paramètres
        $stmt1->bindParam(':nom', $nom);
        $stmt1->bindParam(':rue', $rue);
        $stmt1->bindParam(':postal', $postal);
        $stmt1->bindParam(':ville', $ville);
        $stmt1->bindParam(':phone', $phone);

        // Exécution de la requête
        $stmt1->execute();

        // Récupérer l'ID de l'entreprise insérée
        $id_s = $dbh->lastInsertId();

        // Préparation de la requête pour insérer les informations du stage
        $stmt2 = $dbh->prepare("INSERT INTO tbl_stage (id_s, period_start, period_end) VALUES (:id_s, :period_start, :period_end)");

        // Liaison des paramètres
        $stmt2->bindParam(':id_s', $id_s);
        $stmt2->bindParam(':period_start', $date_debut);
        $stmt2->bindParam(':period_end', $date_fin);

        // Exécution de la requête
        $stmt2->execute();

        // Redirection vers la page des stages
        header('Location: /tables.php');
        exit();
    }
} catch (PDOException $e) {
    // Annuler la transaction en cas d'erreur
    if ($dbh->inTransaction()) {
        $dbh->rollBack();
    }


    $code = $e->getCode();
    $errorMessage = "Erreur : " . $e->getMessage();

    echo "code = '$code'";
    echo $errorMessage;
} catch (Exception $e) {

    $errorMessage = "Erreur : " . $e->getMessage();

    echo $errorMessage;
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

    <title>Gestion des stages</title>

    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body id="page-top">



    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="acceuil.php">
                <div class="sidebar-brand-icon rotate-n-15">
                </div>
                <div class="sidebar-brand-text mx-3">NDLP Avranches</div>
                <img src="/img/NDLP.png" width="70" height="50">
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="acceuil.php">
                    <img src="/img/Acceuil.png" width="35" height="35">
                    <span>Acceuil</span>
                </a>
            </li>


            <!-- Heading -->
            <div class="sidebar-heading">
                Pages
            </div>

            <!-- Nav Item - Tables -->
            <li class="nav-item active">
                <a class="nav-link" href="tables.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Stages</span></a>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <form class="form-inline">
                        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                            <i class="fa fa-bars"></i>
                        </button>
                    </form>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Search for..." aria-label="Search"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">

                                    <?php
                                    // Vérifier si une session est déjà active avant de la démarrer
                                    if (session_status() !== PHP_SESSION_ACTIVE) {
                                        session_start();
                                    }

                                    // Récupération de l'email depuis la session
                                    $email = $_SESSION['email'];

                                    // Connexion à la base de données
                                    $connection = mysqli_connect($servername, $username, $password, $dbname);

                                    // Vérifier la connexion
                                    if (!$connection) {
                                        die("La connexion a échoué : " . mysqli_connect_error());
                                    }

                                    // Requête SQL
                                    $query = "SELECT prenom_u FROM tbl_user WHERE mail_u='$email'";
                                    $result = mysqli_query($connection, $query);

                                    // Vérifier si la requête a abouti
                                    if (!$result) {
                                        die("Erreur dans la requête : " . mysqli_error($connection));
                                    }

                                    // Affichage des données
                                    $row = mysqli_fetch_assoc($result);
                                    if ($row) {
                                        echo $row['prenom_u'];
                                    } else {
                                        echo "Aucun prénom trouvé.";
                                    }

                                    // Libérer la mémoire des résultats
                                    mysqli_free_result($result);

                                    // Fermer la connexion à la base de données
                                    mysqli_close($connection);
                                    ?>


                                </span>

                                <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Activity Log
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->


                <div class="container">

                    <div class="card o-hidden border-0 shadow-lg my-5">
                        <div class="card-body p-0">
                            <!-- Nested Row within Card Body -->
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="p-5">
                                        <div class="text-center">
                                            <h1 class="h4 text-gray-900 mb-4">Ajout d'un strage : </h1>
                                        </div>
                                        <form class="user" method="post" action="ajout_stages.php" enctype="multipart/form-data">

                                            <div class="form-group row">
                                                <div class="col-sm-6 mb-3 mb-sm-0">
                                                    <input type="text" class="form-control form-control-user" name="nom"
                                                        placeholder="Nom de l'entreprise">
                                                </div>

                                                <div class="col-sm-6">
                                                    <input type="text" class="form-control form-control-user" name="rue"
                                                        placeholder="Rue de l'entreprise">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <input type="text" class="form-control form-control-user" name="postal"
                                                    placeholder="Code postal de l'entreprise">
                                            </div>

                                            <div class="form-group">
                                                <input type="text" class="form-control form-control-user" name="ville"
                                                    placeholder="Ville de l'entreprise">
                                            </div>

                                            <div class="form-group">
                                                <input type="text" class="form-control form-control-user" name="phone"
                                                    placeholder="Téléphone de l'entreprise">
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-sm-6 mb-3 mb-sm-0">
                                                    <input type="date" class="form-control form-control-user"
                                                        name="date_debut" placeholder="Date début de stage : " />
                                                </div>

                                                <div class="col-sm-6">
                                                    <input type="date" class="form-control form-control-user"
                                                        name="date_fin" placeholder="Date début de stage : " />
                                                </div>

                                            </div>

                                            <div class="form-group row">

                                                <label for="pdf-file">Sélectionner un fichier PDF :</label>
                                                <input type="file" id="pdf-file" name="pdf-file"
                                                    accept="application/pdf" required>

                                            </div>

                                            <button type="submit" class="btn btn-primary btn-user btn-block">
                                                Validation du stage
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>


            <br><br><br>
            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2020</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="index.php">Logout</a>
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

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>

</body>

</html>