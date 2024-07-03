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


$sqlState = $conn->prepare('SELECT * FROM reservation 
                            INNER JOIN client ON reservation.id_client=client.id_client 
                            INNER JOIN chambre ON chambre.id_chambre=reservation.id_chambre
                            WHERE reservation.etat=?');
$sqlState->execute(['Terminée']);
$reservations = $sqlState->fetchAll(PDO::FETCH_ASSOC);

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
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">Client</th>
                            <th scope="col">Telephone</th>
                            <th scope="col">Code Reservation</th>
                            <th scope="col">Date Arrivee</th>
                            <th scope="col">Date Depart</th>
                            <th scope="col">Montant Total</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reservations as $reservation): ?>
                            <tr>
                                <td><?=$reservation['nom_complet']?></td>
                                <td><?=$reservation['telephone']?></td>
                                <td><?=$reservation['code_reservation']?></td>
                                <td><?=$reservation['date_arrivee']?></td>
                                <td><?=$reservation['date_depart']?></td>
                                <td><?=$reservation['montant_total']?> DH</td>
                                <td>
                                    <a href="print.php?id=<?=$reservation['id_reservation']?>" class="btn btn-primary"><i class="fa fa-print"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
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