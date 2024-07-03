<?php

session_start();
require_once "../../config/database.php";

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
<?php require_once "../inc/header.php" ?>
<!-- Sidebar -->
<?php require_once "../inc/sidebar.php" ?>
<!-- /#sidebar-wrapper -->

<!-- Page Content -->
<div id="page-content-wrapper" class="main-content w-100">
    <button class="btn btn-primary mb-4" id="menu-toggle"><i class="fas fa-bars"></i></button>
    <div class="text-start">
        <a href="create.php" class="btn btn-success">Ajouter Reservation</a>
    </div>
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?= $_SESSION['message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif;
    unset($_SESSION['message']) ?>
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
        <table class="table w-100 text-center" style="font-size:14px;">
            <thead>
                <tr>
                    <th>Code réservation</th>
                    <th>Date réservation</th>
                    <th>Date d’arrivée</th>
                    <th>Date de départ</th>
                    <th>Jours</th>
                    <th>Client</th>
                    <th>Téléphone</th>
                    <th>Chambre</th>
                    <th>Prix</th>
                    <th>Actions</th>
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


                        <td style="width: 200px;"><?= $reservation['code_reservation'] ?></td>
                        <td style="width: 200px;"><?= $reservation['date_heure_reservation'] ?></td>
                        <td style="width: 200px;"><?= $reservation['date_arrivee'] ?></td>
                        <td style="width: 200px;"><?= $reservation['date_depart'] ?></td>
                        <td><?= $reservation['nbr_jours'] ?></td>
                        <td><?= $reservation['nom_complet'] ?></td>
                        <td><?= $reservation['telephone'] ?></td>
                        <td><?= $reservation['numero_chambre'] ?></td>
                        <td><?= $reservation['montant_total'] ?></td>
                        <td style="width: 150px;">
                            <a href="view.php?id=<?= $reservation['id_reservation'] ?>" class="btn btn-primary btn-sm"><i
                                    class="fa fa-eye" style="width: 10px;"></i></a>
                            <a href="edit.php?id=<?= $reservation['id_reservation'] ?>" class="btn btn-warning btn-sm"><i
                                    class="fa fa-edit" style="width: 10px;"></i></a>
                            <a href="delete.php?id=<?= $reservation['id_reservation'] ?>" class="btn btn-danger btn-sm"><i
                                    class="fa fa-trash" style="width: 10px;"></i></a>
                        </td>
                    </tr>

                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- /#page-content-wrapper -->
    <?php require_once "../inc/footer.php" ?>