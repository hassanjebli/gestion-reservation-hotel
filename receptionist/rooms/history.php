<?php
session_start();
require_once "../../config/database.php";

// Initialize variables
$params = [];
$sql = "SELECT *
        FROM reservation
        INNER JOIN chambre ON chambre.id_chambre = reservation.id_chambre
        INNER JOIN client ON client.id_client = reservation.id_client";

// Handle ID parameter safely (if provided)
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql .= " WHERE chambre.id_chambre = ?";
    $params[] = $id;
}else {
    header("location:list.php");
    exit();
}

// Handle form submission (GET method)
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Check if date filters are provided
    if (!empty($_GET['date_arrivee']) && !empty($_GET['date_depart'])) {
        // Add conditions to SQL query
        $sql .= " AND (reservation.date_arrivee BETWEEN ? AND ? OR reservation.date_depart BETWEEN ? AND ?)";
        $params[] = $_GET['date_arrivee'];
        $params[] = $_GET['date_depart'];
        $params[] = $_GET['date_arrivee'];
        $params[] = $_GET['date_depart'];
    }
}

// Prepare and execute SQL query
$sqlStmt = $conn->prepare($sql);
$sqlStmt->execute($params);
$histories = $sqlStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php require_once "../inc/header.php"; ?>
<!-- Sidebar -->
<?php require_once "../inc/sidebar.php"; ?>
<!-- /#sidebar-wrapper -->

<!-- Page Content -->
<div id="page-content-wrapper" class="main-content w-100">
    <button class="btn btn-primary mb-4" id="menu-toggle"><i class="fas fa-bars"></i></button>

    <div class="table-responsive w-100">
        <a href="list.php" class="btn btn-success m-2">Retour</a>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" onchange="this.submit()">
            <?php if (isset($_GET['id'])): ?>
                <input type="hidden" name="id" value="<?= $_GET['id'] ?>">
            <?php endif; ?>
            <div class="row mx-2 my-2">
                <div class="col-6">
                    <label>Du:</label>
                    <input type="date" class="form-control" name="date_arrivee"
                        value="<?= $_GET['date_arrivee'] ?? '' ?>">
                </div>
                <div class="col-6">
                    <label>Au:</label>
                    <input type="date" class="form-control" name="date_depart"
                        value="<?= $_GET['date_depart'] ?? '' ?>">
                </div>
            </div>
        </form>
        <table class="table table-striped table-hover w-100">
            <thead>
                <tr>
                    <th>Date de réservation</th>
                    <th>Client</th>
                    <th>Téléphone</th>
                    <th>Date d’arrivée</th>
                    <th>Date de départ</th>
                    <th>Prix</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($histories as $history): ?>
                    <tr>
                        <td><?= $history['date_heure_reservation'] ?></td>
                        <td><?= $history['nom_complet'] ?></td>
                        <td><?= $history['telephone'] ?></td>
                        <td><?= $history['date_arrivee'] ?></td>
                        <td><?= $history['date_depart'] ?></td>
                        <td><?= $history['montant_total'] ?> DH</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<!-- /#page-content-wrapper -->

<?php require_once "../inc/footer.php"; ?>