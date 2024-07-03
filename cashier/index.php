<?php

session_start();
require_once "../config/database.php";

if (!isset($_SESSION["user"])) {
    header("location:../index.php");
}

$stmt = $conn->prepare("SELECT * FROM users_app WHERE id_user=?");
$stmt->execute([$_SESSION['user']['id']]);

$caissier = $stmt->fetch(PDO::FETCH_ASSOC);

if ($caissier['type'] != 'caissier') {
    header('location:../index.php');
    exit();
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>caissier Dashboard</title>
    <link href="../assets/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <!-- Sidebar -->
    <div class="bg-dark border-right sidebar" id="sidebar-wrapper">
        <div class="sidebar-heading">
            <a href="index.php" style="color:white;"><?= $caissier['nom'] ?> <?= $caissier['prenom'] ?><br>
                <span style="color: tomato; font-size:18px;"><i class="fas fa-cash-register"></i> Cachier</span></a>
            <span class="close-sidebar-btn" id="close-sidebar">&times;</span>
        </div>
        <div class="list-group list-group-flush my-3">
            <a href="payments.php"
                class="list-group-item  text-white <?php echo (strpos($_SERVER['PHP_SELF'], 'payments') == true) ? "active" : "" ?>">
                <i class="fas fa-credit-card"></i> Paiement
            </a>

            <a href="../auth/logout.php" class="list-group-item  logout" style="color:red !important;">
                <i class="fas fa-power-off me-2 "></i> Deconnexion
            </a>
        </div>
    </div>
    <!-- /#sidebar-wrapper -->

    <!-- Page Content -->
    <div id="page-content-wrapper" class="main-content">
        <button class="btn btn-primary mb-4" id="menu-toggle"><i class="fas fa-bars"></i></button>
        <div class="container-fluid text-center">
            <div class="row">
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h6 class="card-title">
                                <?php
                                $sqlState1 = $conn->prepare('SELECT SUM(montant_total) AS total_revenue FROM reservation');
                                $sqlState1->execute();
                                $result1 =$sqlState1->fetchColumn();
                                    ?>
                                <i class="bi bi-door-open me-2"></i>Montant total des réservations
                            </h6>
                            <p class="card-text display-4"><?= $result1 ?> DH</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h6 class="card-title">
                                <?php
                                $result2 = 0;
                                ?>
                                <i class="bi bi-door-open me-2"></i>Dépenses et fournitures de l'hôtel
                            </h6>
                            <p class="card-text display-4"><?= $result2 ?> DH</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <h6 class="card-title">
                                <i class="bi bi-door-open me-2"></i>Revenu total de l'hotel
                            </h6>
                            <p class="card-text display-4"><?php echo $result1 - $result2 ?> DH</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /#page-content-wrapper -->
    <!-- /#wrapper -->

    <!-- Bootstrap core JavaScript -->
    <script src="../assets/bootstrap-5.3.3-dist/js/bootstrap.bundle.js"></script>
    <script src="../assets/js/jquery.js"></script>

    <script src="../assets/js/script.js"></script>
</body>

</html>