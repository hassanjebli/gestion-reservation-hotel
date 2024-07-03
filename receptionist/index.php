<?php

session_start();
require_once "../config/database.php";

if (!isset($_SESSION["user"])) {
    header("location:../index.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM users_app WHERE id_user=?");
$stmt->execute([$_SESSION['user']['id']]);

$receptioniste = $stmt->fetch(PDO::FETCH_ASSOC);

if ($receptioniste['type'] != 'receptionniste') {
    header("location:../index.php");
    exit();
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reception</title>
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
            <a href="index.php" style="color:white;"><?= $receptioniste['nom'] ?> <?= $receptioniste['prenom'] ?><br>
                <span style="color: tomato; font-size:18px;"><i class="fas fa-concierge-bell"></i> Reception</span></a>
            <span class="close-sidebar-btn" id="close-sidebar">&times;</span>
        </div>
        <div class="list-group list-group-flush my-3">
            <a href="clients/list.php"
                class="list-group-item text-white <?php echo (strpos($_SERVER['PHP_SELF'], 'clients') == true) ? "active" : "" ?></a>">
                <i class="fas fa-user-friends"></i> Clients
            </a>
            <a href="rooms/list.php"
                class="list-group-item text-white <?php echo (strpos($_SERVER['PHP_SELF'], 'rooms') == true) ? "active" : "" ?></a>">
                <i class="fas fa-bed"></i> Chambres
            </a>
            <a href="tariffs/list.php"
                class="list-group-item text-white <?php echo (strpos($_SERVER['PHP_SELF'], 'tariffs') == true) ? "active" : "" ?></a>">
                <i class="fas fa-dollar-sign"></i> Les Tariifs
            </a>
            <a href="capacities/list.php"
                class="list-group-item text-white <?php echo (strpos($_SERVER['PHP_SELF'], 'capacities') == true) ? "active" : "" ?>">
                <i class="fas fa-users"></i> Capacitie chambre
            </a>
            <a href="types/list.php"
                class="list-group-item text-white <?php echo (strpos($_SERVER['PHP_SELF'], 'types') == true) ? "active" : "" ?>">
                <i class="fas fa-th-list"></i> Type chambre
            </a>
            <a href="reservations/list.php"
                class="list-group-item text-white <?php echo (strpos($_SERVER['PHP_SELF'], 'reservations') == true) ? "active" : "" ?>">
                <i class="fas fa-calendar-alt"></i> Réservations
            </a>
            <a href="../auth/logout.php" class="list-group-item logout" style="color:red !important;">
                <i class="fas fa-power-off me-2"></i> Deconnexion
            </a>
        </div>
    </div>
    <!-- /#sidebar-wrapper -->

    <!-- Page Content -->
    <div id="page-content-wrapper" class="main-content">
        <button class="btn btn-primary mb-4" id="menu-toggle"><i class="fas fa-bars"></i></button>
        <div class="container-fluid text-center">
            <div class="row">
                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="card bg-light text-dark">
                        <div class="card-body">
                            <h6 class="card-title">
                                <?php
                                $sqlState3 = $conn->prepare('SELECT COUNT(*) FROM reservation');
                                $sqlState3->execute();
                                $result3 = $sqlState3->fetchColumn();
                                ?>
                                <i class="bi bi-door-open me-2"></i>Total Reservations
                            </h6>
                            <p class="card-text display-4"><?= $result3 ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h6 class="card-title">
                                <?php
                                $sqlState2 = $conn->prepare('SELECT COUNT(*) AS total_chambres_disponibles
                                                            FROM chambre c
                                                            WHERE NOT EXISTS (
                                                                SELECT 1
                                                                FROM reservation r
                                                                WHERE r.id_chambre = c.id_chambre
                                                                AND CURDATE() BETWEEN r.date_arrivee AND r.date_depart
                                                            )');
                                $sqlState2->execute();
                                $result2 = $sqlState2->fetch(PDO::FETCH_ASSOC);

                                // Accéder au nombre de chambres disponibles
                                $total_chambres_disponibles = $result2['total_chambres_disponibles'];
                                ?>
                                <i class="bi bi-calendar-check me-2"></i>Total Chambres Disponibles
                            </h6>
                            <p class="card-text display-4"><?= $total_chambres_disponibles ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="card bg-warning text-dark">
                        <div class="card-body">
                            <h6 class="card-title">
                                <?php
                                $sqlState1 = $conn->prepare('SELECT COUNT(DISTINCT id_chambre) AS total_reserved_rooms
                                                             FROM reservation
                                                             WHERE CURRENT_DATE BETWEEN date_arrivee AND date_depart');
                                $sqlState1->execute();
                                $result1 = $sqlState1->fetchColumn();
                                ?>
                                <i class="bi bi-door-closed me-2"></i>Total Chambres Reservee
                            </h6>
                            <p class="card-text display-4"><?= $result1 ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h6 class="card-title">
                                <?php
                                $sqlState4 = $conn->prepare('SELECT COUNT(*) FROM client');
                                $sqlState4->execute();
                                $result4 = $sqlState4->fetchColumn();
                                ?>
                                <i class="bi bi-door-open me-2"></i>Total Clients
                            </h6>
                            <p class="card-text display-4"><?= $result4 ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <h6 class="card-title">
                                <?php
                                $sqlState5 = $conn->prepare('SELECT COUNT(*) FROM reservation WHERE etat = "Planifiée"');
                                $sqlState5->execute();
                                $result5 = $sqlState5->fetchColumn();
                                ?>
                                <i class="bi bi-door-open me-2"></i>Total Reservation Planifiée
                            </h6>
                            <p class="card-text display-4"><?= $result5 ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card bg-secondary text-white">
                        <div class="card-body">
                            <h6 class="card-title">
                                <?php
                                $sqlState6 = $conn->prepare('SELECT COUNT(*) FROM reservation WHERE etat = "Terminée"');
                                $sqlState6->execute();
                                $result6 = $sqlState6->fetchColumn();
                                ?>
                                <i class="bi bi-door-open me-2"></i>Total Reservation Terminée
                            </h6>
                            <p class="card-text display-4"><?= $result6 ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card bg-dark text-white">
                        <div class="card-body">
                            <h6 class="card-title">
                                <?php
                                $sqlState7 = $conn->prepare('SELECT COUNT(*) FROM reservation WHERE etat = "En cours"');
                                $sqlState7->execute();
                                $result7 = $sqlState7->fetchColumn();
                                ?>
                                <i class="bi bi-door-open me-2"></i>Total Reservation En cours
                            </h6>
                            <p class="card-text display-4"><?= $result7 ?></p>
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