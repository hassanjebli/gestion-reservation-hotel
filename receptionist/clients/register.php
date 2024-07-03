<?php

session_start();
require_once "../../config/database.php";

if (!isset($_SESSION["user"])) {
    header("location:../index.php");
}

$stmt = $conn->prepare("SELECT * FROM users_app WHERE id_user=?");
$stmt->execute([$_SESSION['user']['id']]);

$reception = $stmt->fetch(PDO::FETCH_ASSOC);

// Requête de base
$query = "SELECT * FROM reservation
          JOIN client ON reservation.id_client = client.id_client
          JOIN chambre ON reservation.id_chambre = chambre.id_chambre
          JOIN tarif_chambre ON tarif_chambre.id_tarif = chambre.id_tarif";

// Filtres de recherche
$filters = [];
if (isset($_GET['query']) && !empty($_GET['query'])) {
    $filters[] = "client.nom_complet LIKE '%{$_GET['query']}%'";
}
if (isset($_GET['date_debut']) && !empty($_GET['date_debut']) && isset($_GET['date_fin']) && !empty($_GET['date_fin'])) {
    $filters[] = "reservation.date_arrivee BETWEEN '{$_GET['date_debut']}' AND '{$_GET['date_fin']}'";
}

// Ajouter les filtres à la requête
if (!empty($filters)) {
    $query .= " WHERE " . implode(" AND ", $filters);
}

// Exécuter la requête
$stmt = $conn->prepare($query);
$stmt->execute();
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
// echo "<pre>";
// print_r($reservations);
// echo "</pre>";



?>
<?php require_once "../inc/header.php" ?>
<!-- Sidebar -->
<?php require_once "../inc/sidebar.php" ?>
<!-- /#sidebar-wrapper -->

<!-- Page Content -->
<div id="page-content-wrapper" class="main-content w-100">
    <button class="btn btn-primary mb-4" id="menu-toggle"><i class="fas fa-bars"></i></button>
    <h1 class="text-center">Liste Des Clients</h1>
    <a href="list.php" class="m-3 btn btn-success"><i class="fa fa-back"></i> retour</a>
    <a href="print-pdf.php" class="m-3 btn btn-secondary"><i class="fa fa-print"></i> Print</a>
    <div class="container text-center w-100">
        <form class="form-inline my-2" method="GET" action="">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="nom complet..." name="query">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" name="rechercher" type="submit"><i
                            class="fas fa-search"></i></button>
                </div>
            </div>
            <div class="input-group my-2">
                <input type="date" class="form-control" placeholder="Du" name="date_debut">
                <input type="date" class="form-control" placeholder="Au" name="date_fin">
            </div>
        </form>
        <div class="table-responsive w-100">
            <table class="table table-striped table-hover w-100">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom Complet</th>
                        <th>Sexe</th>
                        <th>Age</th>
                        <th>Date d’arrivée</th>
                        <th>Date de départ</th>
                        <th>N de Chambre</th>
                        <th>Prix</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservations as $reservation): ?>
                        <tr>
                            <td><?=$reservation['id_client']?></td>
                            <td><?=$reservation['nom_complet']?></td>
                            <td><?=$reservation['sexe']?></td>
                            <td><?=$reservation['age']?></td>
                            <td><?=$reservation['date_arrivee']?></td>
                            <td><?=$reservation['date_depart']?></td>
                            <td><?=$reservation['numero_chambre']?></td>
                            <td><?=$reservation['n_prix_nuit']?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- /#page-content-wrapper -->
<?php require_once "../inc/footer.php" ?>