<?php

session_start();
require_once "../config/database.php";

if (!isset($_SESSION["user"])) {
    header("location:../index.php");
}

$stmt = $conn->prepare("SELECT * FROM users_app WHERE id_user=?");
$stmt->execute([$_SESSION['user']['id']]);

$manager = $stmt->fetch(PDO::FETCH_ASSOC);

if ($manager['type']!='manager') {
    header('location:../index.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager</title>
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
            <a href="index.php" style="color:white;"><?= $manager['nom'] ?> <?= $manager['prenom'] ?><br><span
                    style="color: tomato; font-size:18px;"><i class="fas fa-briefcase"></i> Manager</span></a>
            <span class="close-sidebar-btn" id="close-sidebar">&times;</span>
        </div>
        <div class="list-group list-group-flush my-3 ">
            <a href="reservations.php" class="list-group-item  text-white <?php echo (strpos($_SERVER['PHP_SELF'],'reservations')==true)?"active":"" ?>" >
                <i class="fas fa-calendar-alt"></i> Réservations
            </a>
            <a href="users/list.php" class="list-group-item  text-white <?php echo (strpos($_SERVER['PHP_SELF'],'list')==true)?"active":"" ?>">
                <i class="fas fa-users"></i> Utilisateurs
            </a>
            <a href="planning.php" class="list-group-item  text-white <?php echo (strpos($_SERVER['PHP_SELF'],'planning')==true)?"active":"" ?>">
                <i class="fas fa-calendar-check"></i> Consultation de planning
            </a>

            <a href="../auth/logout.php" class="list-group-item  logout" style="color:red !important;">
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
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h5 class="card-title">
                                <?php
                                $stmt0 = $conn->prepare('SELECT COUNT(*) FROM reservation');
                                $stmt0->execute();
                                $count0 = $stmt0->fetchColumn();
                                ?>
                                <i class="bi bi-door-open me-2"></i>Total Reservations
                            </h5>
                            <p class="card-text display-4"><?=$count0?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="bi bi-calendar-check me-2"></i>Total Managers
                            </h5>
                            <?php
                            $stmt1 = $conn->prepare('SELECT COUNT(*) FROM users_app WHERE type=?');
                            $stmt1->execute(['manager']);
                            $count1 = $stmt1->fetchColumn();
                            ?>
                            <p class="card-text display-4"><?= $count1 ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="card bg-warning text-dark">
                        <div class="card-body">
                            <h5 class="card-title">
                                <?php
                                $stmt2 = $conn->prepare('SELECT COUNT(*) FROM users_app WHERE type=?');
                                $stmt2->execute(['receptionniste']);
                                $count2 = $stmt2->fetchColumn();
                                ?>
                                <i class="bi bi-door-closed me-2"></i>Total Receptionist
                            </h5>
                            <p class="card-text display-4"><?= $count2 ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h5 class="card-title">
                                <?php
                                $stmt3 = $conn->prepare('SELECT COUNT(*) FROM users_app WHERE type=?');
                                $stmt3->execute(['caissier']);
                                $count3 = $stmt3->fetchColumn();
                                ?>
                                <i class="bi bi-door-open me-2"></i>Total Caissier
                            </h5>
                            <p class="card-text display-4"><?= $count3 ?></p>
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