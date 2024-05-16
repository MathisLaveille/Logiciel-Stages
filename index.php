<?php
session_start();

// // Vérifier si une session est déjà active avant de la démar²rer
// if (session_status() !== PHP_SESSION_ACTIVE) {
//     session_start();
// }

// Récupération de l'email depuis la session
$email = $_SESSION['email'];

// Connexion à la base de données
$connection = mysqli_connect("172.16.136.21", "root", "root", "logiciel_stages");

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


// Libérer la mémoire des résultats
mysqli_free_result($result);

// Fermer la connexion à la base de données
mysqli_close($connection);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Logicel gestion de stages</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <div class="sidebar-brand-icon rotate-n-15">
                </div>
                <div class="sidebar-brand-text mx-3">NDLP Avranches</div>
                <img src="/img/NDLP.png" width="70" height="50">
            </a>

             <!-- Divider -->
             <hr class="sidebar-divider my-0">

<!-- Nav Item - Dashboard -->
<li class="nav-item">
    <a class="nav-link" href="index.php">
    <img src="/img/Acceuil.png" width="25" height="25"> 
        <span>Acceuil</span>
    </a>
</li>

<li class="nav-item">
    <a class="nav-link" href="role.php">
        <img src="/img/role.png" width="35" height="35">
        <span>Role</span>
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
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                 
                   

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">



                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">



                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                    <?php echo $user_firstname; echo '('.$user_role.')'; ?>
                                </span>


                                <img class="img-profile rounded-circle"
                                    src="img/undraw_profile.svg">
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

                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>


                    </ul>
                </nav>

<body>


<div class="card o-hidden border-0 shadow-lg my-5">
    <div class="card-body p-0">
        <!-- Nested Row within Card Body -->
        <div class="row">
            <div class="col-lg-6">
                <div class="p-5">
                    <div class="text-center">
                        <h1 class="h4 text-gray-900 mb-4">Bienvenue sur le site !</h1>
                    </div>
                    <br><br>

                    <a style="font-family: Roboto, sans-serif;">Petite présentation :</a>
                    <br><br>
                    <a style="font-family: Roboto, sans-serif;">Ce site a été conçu par une équipe de trois étudiants en BTS Services Informatiques aux Organisations (SIO) option Solutions Logicielles et Applications Métiers (SLAM).</a>
                    <br><br>
                    <a style="font-family: Roboto, sans-serif;">Explication du projet :</a>
                    <br><br>
                    <a style="font-family: Roboto, sans-serif;">Le projet a débuté le 12 Janvier 2024. Nous avons fait un cahier des charges et la création d'un MCD. Nous avons rencontré le client. Bonne visite !</a>
                    <br><br>
                    <a style="font-family: Roboto, sans-serif;">Stage à venir :</a>
                    <br><br>
                    <table border="2">
                        <tr>
                            <td style="font-family: Roboto, sans-serif;">Classe</td>
                            <td style="font-family: Roboto, sans-serif;">Date</td>
                        </tr>
                        <tr>
                            <td style="font-family: Roboto, sans-serif;">BTS GPME 1ère année</td>
                            <td style="font-family: Roboto, sans-serif;">à définir</td>
                        </tr>
                        <tr>
                            <td style="font-family: Roboto, sans-serif;">BTS SIO 1ère année</td>
                            <td style="font-family: Roboto, sans-serif;">27 mai - 6 juillet</td>
                        </tr>
                        <tr>
                            <td style="font-family: Roboto, sans-serif;">à définir</td>
                            <td style="font-family: Roboto, sans-serif;">à définir</td>
                        </tr>
                        <tr>
                            <td style="font-family: Roboto, sans-serif;">à définir</td>
                            <td style="font-family: Roboto, sans-serif;">à définir</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="p-5">
                    <div class="text-center">
                        <img src="img/NDLP.png" style="max-width: 200px;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>

                <!-- End of Topbar -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Crée par Griffon Dawson, Laveille Mathis, Grall Emeric</span>
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
                    <h5 class="modal-title" id="exampleModalLabel">Prêt à partir ?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"></span>
                    </button>
                </div>
                <div class="modal-body">Sélectionnez « Se déconnecter » ci-dessous si vous êtes prêt à mettre fin à votre session en cours.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Annuler</button>
                    <a class="btn btn-primary" href="login.php">Se déconnecter</a>
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
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>

</body>

</html>
