<?php

session_start();
require_once "../config/database.php";

if (!isset($_SESSION["user"])) {
    header("location:../index.php");
}

$stmt = $conn->prepare("SELECT * FROM users_app WHERE id_user=?");
$stmt->execute([$_SESSION['user']['id']]);

$manager = $stmt->fetch(PDO::FETCH_ASSOC);
if ($manager['type'] != 'manager') {
    header('location:../index.php');
    exit();
}

$params = [];
$sql = "SELECT * FROM reservation
        INNER JOIN client ON reservation.id_client=client.id_client
        INNER JOIN chambre ON reservation.id_chambre=chambre.id_chambre WHERE 1=1";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (!empty($_GET['etat'])) {
        $etat = $_GET['etat'];
        $sql .= ' AND etat=?';
        $params[] = $etat;
    }

    if (!empty($_GET['client'])) {
        $client = $_GET['client'];
        $sql .= ' AND reservation.id_client=?';
        $params[] = $client;
    }

    if (!empty($_GET['chambre'])) {
        $chambre = $_GET['chambre'];
        $sql .= ' AND reservation.id_chambre=?';
        $params[] = $chambre;
    }

    if (!empty($_GET['du']) && !empty($_GET['au'])) {
        $du = $_GET['du'];
        $au = $_GET['au'];
        $sql .= ' AND date_arrivee >= ? AND date_depart <= ?';
        $params[] = $du;
        $params[] = $au;
    }
}

$sqlState = $conn->prepare($sql);
$sqlState->execute($params);
$reservations = $sqlState->fetchAll(PDO::FETCH_ASSOC);



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
            <a href="reservations.php"
                class="list-group-item  text-white <?php echo (strpos($_SERVER['PHP_SELF'], 'reservations') == true) ? "active" : "" ?>">
                <i class="fas fa-calendar-alt"></i> Réservations
            </a>
            <a href="users/list.php"
                class="list-group-item  text-white <?php echo (strpos($_SERVER['PHP_SELF'], 'list') == true) ? "active" : "" ?>">
                <i class="fas fa-users"></i> Utilisateurs
            </a>
            <a href="planning.php"
                class="list-group-item  text-white <?php echo (strpos($_SERVER['PHP_SELF'], 'planning') == true) ? "active" : "" ?>">
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
            <h1 class="text-center">Reservation</h1>
            <div class="container mt-4">
                <div class="table-responsive w-100">
                    <form action="" method="get" onchange="this.submit()">
                        <div class="row">
                            <div class="col-2">
                                <input type="date" name="du" class="form-control my-2 text-center"
                                    value="<?= isset($_GET['du']) ? $_GET['du'] : '' ?>" placeholder="Du">
                            </div>
                            <div class="col-2">
                                <input type="date" name="au" class="form-control my-2 text-center"
                                    value="<?= isset($_GET['au']) ? $_GET['au'] : '' ?>" placeholder="Au">
                            </div>
                            <div class="col-2">
                                <select name="etat" class="form-control my-2 text-center">
                                    <option value="">-- Selectionnez l'etat de reservation --</option>
                                    <option value="Planifiée" <?= (isset($_GET['etat']) && $_GET['etat'] == 'Planifiée') ? "selected" : "" ?>>Planifiée</option>
                                    <option value="En cours" <?= (isset($_GET['etat']) && $_GET['etat'] == 'En cours') ? "selected" : "" ?>>En cours</option>
                                    <option value="Terminée" <?= (isset($_GET['etat']) && $_GET['etat'] == 'Terminée') ? "selected" : "" ?>>Terminée</option>
                                </select>
                            </div>
                            <div class="col-3">
                                <?php
                                $stmt1 = $conn->prepare("SELECT * FROM client");
                                $stmt1->execute();
                                $clients = $stmt1->fetchAll(PDO::FETCH_ASSOC);
                                ?>
                                <select name="client" class="form-control my-2 text-center">
                                    <option value="">-- Selectionnez le client --</option>
                                    <?php foreach ($clients as $client): ?>
                                        <option value="<?= $client['id_client'] ?>" <?= (isset($_GET['client']) && $_GET['client'] == $client['id_client']) ? "selected" : "" ?>>
                                            <?= $client['nom_complet'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-3">
                                <?php
                                $stmt2 = $conn->prepare("SELECT * FROM chambre");
                                $stmt2->execute();
                                $chambres = $stmt2->fetchAll(PDO::FETCH_ASSOC);
                                ?>
                                <select name="chambre" class="form-control my-2 text-center">
                                    <option value="">-- Selectionnez la chambre --</option>
                                    <?php foreach ($chambres as $chambre): ?>
                                        <option value="<?= $chambre['id_chambre'] ?>" <?= (isset($_GET['chambre']) && $_GET['chambre'] == $chambre['id_chambre']) ? "selected" : "" ?>>
                                            <?= $chambre['numero_chambre'] ?>
                                        </option>
                                    <?php endforeach; ?>

                                </select>
                            </div>
                        </div>
                    </form>
                    <table class="table table-hover w-100" style="font-size:14px !important;">
                        <thead>
                            <tr>
                                <th>Code réservation</th>
                                <th>Date réservation</th>
                                <th>Date d’arrivée</th>
                                <th>Date de départ</th>
                                <th>Nbr jours</th>
                                <th>Client</th>
                                <th>Téléphone</th>
                                <th>Chambre</th>
                                <th>Prix</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reservations as $reservation): ?>
                                <tr class="table-<?php
                                if ($reservation['etat'] == 'Planifiée') {
                                    echo "warning";
                                } elseif ($reservation['etat'] == 'En cours') {
                                    echo "success";
                                } elseif ($reservation['etat'] == 'Terminée') {
                                    echo "danger";
                                } else {
                                    echo "secondary";
                                }
                                ?>">
                                    <td><?= $reservation['code_reservation'] ?></td>
                                    <td><?= $reservation['date_heure_reservation'] ?></td>
                                    <td><?= $reservation['date_arrivee'] ?></td>
                                    <td><?= $reservation['date_depart'] ?></td>
                                    <td><?= $reservation['nbr_jours'] ?></td>
                                    <td><?= $reservation['nom_complet'] ?></td>
                                    <td><?= $reservation['telephone'] ?></td>
                                    <td><?= $reservation['numero_chambre'] ?></td>
                                    <td><?= $reservation['montant_total'] ?></td>
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