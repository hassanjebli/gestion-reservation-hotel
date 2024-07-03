<?php
session_start();
require_once "../../config/database.php";

// Initialize base SQL query
$sql = "SELECT * FROM chambre
        INNER JOIN type_chambre ON type_chambre.id_type_ch = chambre.id_type_ch
        INNER JOIN capacite_chambre ON capacite_chambre.id_capacite = chambre.id_capacite
        INNER JOIN tarif_chambre ON tarif_chambre.id_tarif = chambre.id_tarif WHERE 1=1"; // Start with a true condition

// Array to hold parameters for binding
$params = array();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Handle room number filter
    if (!empty($_GET['room_number'])) {
        $sql .= " AND chambre.numero_chambre LIKE ?";
        $params[] = '%' . $_GET['room_number'] . '%';
    }

    // Handle room type filter
    if (!empty($_GET['room_type'])) {
        $sql .= " AND type_chambre.type_chambre LIKE ?";
        $params[] = '%' . $_GET['room_type'] . '%';
    }

    // Handle capacity filter
    if (!empty($_GET['capacity'])) {
        $sql .= " AND capacite_chambre.titre_capacite = ?";
        $params[] = $_GET['capacity'];
    }

    // Handle date filters (assuming you want to filter bookings)
    if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
        $sql .= " AND chambre.id_chambre NOT IN (
                    SELECT id_chambre FROM reservation
                    WHERE date_arrivee <= ?
                    AND date_depart >= ?
                )";
        $params[] = $_GET['end_date'];
        $params[] = $_GET['start_date'];
    }

    // Handle price range filter
    if (!empty($_GET['min_price'])) {
        $sql .= " AND tarif_chambre.prix_base_nuit >= ?";
        $params[] = $_GET['min_price'];
    }
    if (!empty($_GET['max_price'])) {
        $sql .= " AND tarif_chambre.prix_base_nuit <= ?";
        $params[] = $_GET['max_price'];
    }
}

// Prepare and execute the SQL statement
$sqlState = $conn->prepare($sql);
$sqlState->execute($params);
$chambres = $sqlState->fetchAll(PDO::FETCH_ASSOC);
?>

<?php require_once "../inc/header.php" ?>
<!-- Sidebar -->
<?php require_once "../inc/sidebar.php" ?>
<!-- /#sidebar-wrapper -->

<!-- Page Content -->
<div id="page-content-wrapper" class="main-content w-100">
    <button class="btn btn-primary mb-4" id="menu-toggle"><i class="fas fa-bars"></i></button>
    <h1 class="text-center">Liste Des Chambres</h1>
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?= $_SESSION['message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif;
    unset($_SESSION['message']) ?>
    <a href="create.php" class="m-3 btn btn-success"><i class="fa fa-plus"></i> Ajouter Chambre</a>

    <div class="container text-center w-100">
        <form class="form-inline my-2" method="GET" action="">
            <div class="row align-items-center">
                <div class="col-md-2">
                    <label for="room_number">Numéro de chambre:</label>
                    <input type="text" class="form-control" id="room_number" name="room_number"
                        value="<?= isset($_GET['room_number']) ? $_GET['room_number'] : '' ?>">
                </div>
                <div class="col-md-2">
                    <label for="room_type">Type de chambre:</label>
                    <select class="form-control" id="room_type" name="room_type">
                        <option value="">-- Sélectionner --</option>
                        <?php
                        // Fetch capacity options from database
                        $sqlType = "SELECT * FROM type_chambre";
                        $stmtType = $conn->prepare($sqlType);
                        $stmtType->execute();
                        $types = $stmtType->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($types as $type) {
                            echo '<option value="' . $type['type_chambre'] . '"' . (isset($_GET['type']) && $_GET['type'] == $type['type_chambre'] ? ' selected' : '') . '>' . $type['type_chambre'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="capacity">Capacité:</label>
                    <select class="form-control" id="capacity" name="capacity">
                        <option value="">-- Sélectionner --</option>
                        <?php
                        // Fetch capacity options from database
                        $sqlCapacity = "SELECT * FROM capacite_chambre";
                        $stmtCapacity = $conn->prepare($sqlCapacity);
                        $stmtCapacity->execute();
                        $capacities = $stmtCapacity->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($capacities as $capacity) {
                            echo '<option value="' . $capacity['titre_capacite'] . '"' . (isset($_GET['capacity']) && $_GET['capacity'] == $capacity['titre_capacite'] ? ' selected' : '') . '>' . $capacity['titre_capacite'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="start_date">Du:</label>
                    <input type="date" class="form-control" id="start_date" name="start_date"
                        value="<?= isset($_GET['start_date']) ? $_GET['start_date'] : '' ?>">
                </div>
                <div class="col-md-3">
                    <label for="end_date">Au:</label>
                    <input type="date" class="form-control" id="end_date" name="end_date"
                        value="<?= isset($_GET['end_date']) ? $_GET['end_date'] : '' ?>">
                </div>
            </div>
            <div class="row align-items-center mt-3">
                <div class="col-md-3">
                    <label for="min_price">Prix Min:</label>
                    <input type="number" class="form-control" id="min_price" name="min_price"
                        value="<?= isset($_GET['min_price']) ? $_GET['min_price'] : '' ?>">
                </div>
                <div class="col-md-3">
                    <label for="max_price">Prix Max:</label>
                    <input type="number" class="form-control" id="max_price" name="max_price"
                        value="<?= isset($_GET['max_price']) ? $_GET['max_price'] : '' ?>">
                </div>
                <div class="col-md-4">
                    <button class="btn btn-outline-secondary mt-2 mt-md-0 w-100" type="submit"><i
                            class="fas fa-search"></i> Rechercher</button>
                </div>
            </div>
        </form>
        <div class="table-responsive w-100">
            <table class="table table-striped table-hover w-100">
                <thead>
                    <tr>
                        <th>Numéro chambre</th>
                        <th>Type</th>
                        <th>Prix Nuit</th>
                        <th>Prix passage</th>
                        <th>Capacité</th>
                        <th>Nombre lits</th>
                        <th>Etage</th>
                        <th>Nbr personnes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($chambres as $chambre): ?>
                        <tr>
                            <td><?= $chambre['numero_chambre'] ?></td>
                            <td><?= $chambre['type_chambre'] ?></td>
                            <td><?= $chambre['prix_base_nuit'] ?></td>
                            <td><?= $chambre['prix_base_passage'] ?></td>
                            <td><?= $chambre['titre_capacite'] ?></td>
                            <td><?= $chambre['nbr_lits_chambre'] ?></td>
                            <td><?= $chambre['etage_chambre'] ?></td>
                            <td><?= $chambre['nombre_adultes_enfants_ch'] ?></td>
                            <td>
                                <a href="view.php?id=<?= $chambre['id_chambre'] ?>" class="btn btn-primary btn-sm"><i
                                        class="fa fa-eye"></i></a>
                                <a href="edit.php?id=<?= $chambre['id_chambre'] ?>" class="btn btn-warning btn-sm"><i
                                        class="fa fa-edit"></i></a>
                                <a href="delete.php?id=<?= $chambre['id_chambre'] ?>" class="btn btn-danger btn-sm"><i
                                        class="fa fa-trash"></i></a>
                                <a href="history.php?id=<?= $chambre['id_chambre'] ?>" class="btn btn-secondary btn-sm"><i
                                        class="fa fa-history"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- /#page-content-wrapper -->

<?php require_once "../inc/footer.php" ?>