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

// Récupérer l'ID du stage à modifier depuis l'URL
$id_stage = $_GET['id_s'];

// Récupérer les données actuelles du stage
$query = "SELECT * FROM tbl_stage JOIN tbl_company ON tbl_stage.id_s = tbl_company.id_e WHERE id_s = '$id_stage'";
$result = mysqli_query($connection, $query);

// Vérifier si la requête a abouti
if (!$result) {
    die("Erreur dans la requête : " . mysqli_error($connection));
}

// Récupérer les données du stage
$stage = mysqli_fetch_assoc($result);

// Fermer la connexion à la base de données
mysqli_close($connection);
?>


<?php

// Récupération de l'email depuis la session
$email = $_SESSION['email'];

// Connexion à la base de données
$connection = mysqli_connect($servername, $username, $password, $dbname);

// Vérifier la connexion
if (!$connection) {
    die("La connexion a échoué : " . mysqli_connect_error());
}

// Requête SQL pour obtenir les infos sur l'utilisateur
$query = "SELECT prenom_u FROM tbl_user WHERE mail_u='$email'";
$result = mysqli_query($connection, $query);

// Vérifier si la requête a abouti
if (!$result) {
    die("Erreur dans la requête : " . mysqli_error($connection));
}

// Stockage des données
$row = mysqli_fetch_assoc($result);
if ($row) {
    $user_firstname = $row['prenom_u'];
} else {
    $user_firstname = "Aucun prénom trouvé.";
}



// Requête SQL pour obtenir les infos sur le rôle
$query = "SELECT tbl_role.name_r FROM tbl_role 
JOIN tbl_user_role ON tbl_user_role.id_r_role = tbl_role.id_r
JOIN tbl_user ON tbl_user_role.id_u_user = tbl_user.id_u
WHERE tbl_user.mail_u = '$email';";

$result = mysqli_query($connection, $query);

// Vérifier si la requête a abouti
if (!$result) {
    die("Erreur dans la requête : " . mysqli_error($connection));
}

// Stockage des données
$row = mysqli_fetch_assoc($result);
if ($row) {
    $user_role = $row['name_r'];
} else {
    $user_role = "Aucun rôle.";
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

    <title>Modification de Stage</title>

    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

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
                    <img src="/img/Acceuil.png" width="25" height="25">
                    <span>Acceuil</span>
                </a>
            </li>

            <!-- Nav Item - Tables -->
            <li class="nav-item active">
                <a class="nav-link" href="tables.php">
                    <img src="img/stage.png" width="25" height="25"></img>
                    <span>Stages</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <?php
            if ($user_role == 'SUPER_ADMIN' || $user_role == 'ADMIN' || $user_role == 'TEACHER') {
                ?>
                <!-- Nav Item - Dropdown Menu -->
                <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseMenu"
                        aria-expanded="true" aria-controls="collapseMenu">
                        <img src="/img/role.png" width="25" height="25">
                        <span>Administration</span>
                    </a>
                    <div id="collapseMenu" class="collapse" aria-labelledby="headingMenu" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <?php if ($user_role == 'SUPER_ADMIN') { ?>
                                <a class="collapse-item" href="admin.php">
                                    <img src="/img/role2.png" width="25" height="25">
                                    <span>Gestion Roles</span>
                                </a>
                            <?php } ?>
                            <a class="collapse-item" href="validation_stage.php">
                                <img src="/img/role2.png" width="25" height="25">
                                <span>Validation stage</span>
                            </a>
                        </div>
                    </div>
                </li>
                <?php
            }
            ?>

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

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
<<<<<<< HEAD
        <?php echo $user_firstname;
        echo '(' . $user_role . ')'; ?>
    </span>
=======
                                    <?php echo $user_firstname;
                                    echo '(' . $user_role . ')'; ?>
                                </span>
>>>>>>> d1fb1e5eca3a5de694637eff1949516621e93192

                                <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">

                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Déconnexion
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">Modification du Stage</h1>

                    <!-- Formulaire de modification -->
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <form action="update_stage.php" method="post">
                                <input type="hidden" name="id_s" value="<?php echo $stage['id_s']; ?>">

                                <div class="form-group">
                                    <label for="nom_e">Nom de l'entreprise</label>
                                    <input type="text" class="form-control" id="nom_e" name="nom_e"
                                        value="<?php echo $stage['nom_e']; ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="rue_e">Rue de l'entreprise</label>
                                    <input type="text" class="form-control" id="rue_e" name="rue_e"
                                        value="<?php echo $stage['rue_e']; ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="CP_e">Code postal de l'entreprise</label>
                                    <input type="text" class="form-control" id="CP_e" name="CP_e"
                                        value="<?php echo $stage['CP_e']; ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="city_e">Ville de l'entreprise</label>
                                    <input type="text" class="form-control" id="city_e" name="city_e"
                                        value="<?php echo $stage['city_e']; ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="phone_e">Téléphone de l'entreprise</label>
                                    <input type="text" class="form-control" id="phone_e" name="phone_e"
                                        value="<?php echo $stage['phone_e']; ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="period_start_s">Début du stage</label>
                                    <input type="date" class="form-control" id="period_start_s" name="period_start_s"
                                        value="<?php echo $stage['period_start_s']; ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="period_end_s">Fin du stage</label>
                                    <input type="date" class="form-control" id="period_end_s" name="period_end_s"
                                        value="<?php echo $stage['period_end_s']; ?>" required>
                                </div>

                                <button type="submit" class="btn btn-primary btn-user btn-block">Enregistrer les
                                    modifications</button>
                            </form>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span> Crée par Laveille Mathis et Grall Emeric </span>
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