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


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['role'])) {
    // Connexion à la base de données
    $connection = mysqli_connect($servername, $username, $password, $dbname);

    // Vérifier la connexion
    if (!$connection) {
        die("La connexion a échoué : " . mysqli_connect_error());
    }

    foreach ($_POST['role'] as $user_id => $new_role) {
        // Requête pour obtenir l'id du nouveau rôle
        $query = "SELECT id_r FROM tbl_role WHERE name_r='$new_role'";
        $result = mysqli_query($connection, $query);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $role_id = $row['id_r'];

            // Mettre à jour le rôle de l'utilisateur
            $update_query = "UPDATE tbl_user_role SET id_r_role='$role_id' WHERE id_u_user='$user_id'";
            if (!mysqli_query($connection, $update_query)) {
                echo "Erreur lors de la mise à jour du rôle : " . mysqli_error($connection);
            }
        }
    }

    // Fermer la connexion
    mysqli_close($connection);

    // Redirection pour éviter la resoumission du formulaire
    header("Location: admin.php");
    exit;
}




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

// Vérifier si l'utilisateur est connecté et a le rôle de SUPERADMIN
if ($user_role !== 'SUPER_ADMIN') {
    // Rediriger vers une page d'accès non autorisé si l'utilisateur n'est pas autorisé
    header('Location: /acceuil.php');
    exit;
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
                    <img src="/img/Acceuil.png" width="25" height="25">
                    <span>Acceuil</span>
                </a>
            </li>


            <!-- Nav Item - Tables -->
            <li class="nav-item active">
                <a class="nav-link" href="tables.php">
                    <img src="img/stage.png" width="25" height="25"></img>
                    <span>Stages</span></a>
            </li>


            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">


            <?php
            if ($user_role == 'SUPER_ADMIN') {
                ?>

                <li class="nav-item">
                    <a class="nav-link" href="admin.php">
                        <img src="/img/role.png" width="25" height="25">
                        <span>Administration</span>
                    </a>
                </li>

                <?php
            }


            if ($user_role == 'SUPER_ADMIN' or $user_role == 'ADMIN' or $user_role == 'TEACHER') {
                ?>

                <li class="nav-item">
                    <a class="nav-link" href="validation_stage.php">
                        <img src="/img/role.png" width="25" height="25">
                        <span>Validation stage</span>
                    </a>
                </li>

                <?php
            }  
            ?>
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



                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">

                                <?php echo $user_firstname;
echo '(' . $user_role . ')'; ?>

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


<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Recherche de stages</h1>
<br>

<!-- DataTales Example -->
<div class="card shadow mb-4">

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                    <th>Utilisateur</th>
                    <th>Rôle actuel</th>
                    <th>Nouveau rôle</th>
                    </tr>
                </thead>
                <br><br>

<input type="submit" value="Mettre à jour le rôle">
                <tbody>
                <?php
                // Connexion à la base de données
                $connection = mysqli_connect($servername, $username, $password, $dbname);

                // Vérifier la connexion
                if (!$connection) {
                    die("La connexion a échoué : " . mysqli_connect_error());
                }

                // Requête SQL pour obtenir les utilisateurs et leurs rôles
                $query = "SELECT tbl_user.id_u, tbl_user.prenom_u, tbl_user.mail_u, tbl_role.name_r FROM tbl_user
                          JOIN tbl_user_role ON tbl_user_role.id_u_user = tbl_user.id_u
                          JOIN tbl_role ON tbl_user_role.id_r_role = tbl_role.id_r";

                $result = mysqli_query($connection, $query);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['prenom_u'] . " (" . $row['mail_u'] . ")</td>";
                        echo "<td>" . $row['name_r'] . "</td>";
                        echo "<td>
                                <select name='role[" . $row['id_u'] . "]' class='form-control'>
                                <option value='SUPER_ADMIN'>Super-Admin</option>
                                <option value='ADMIN'>Admin</option>
                                <option value='STUDENT'>Eleve</option>
                                <option value='TEACHER'>Professeur</option>
                                <option value='TUTOR'>Tuteur</option>
                                <option value='GUEST'>Inviter</option>
                                </select>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>Aucun utilisateur trouvé</td></tr>";
                }

                // Fermer la connexion
                mysqli_close($connection);
                ?>

            </tbody>
            </table>


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