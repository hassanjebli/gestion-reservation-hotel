<?php
session_start();
require_once "../../config/database.php";

if (!isset($_SESSION["user"])) {
    header("location:../index.php");
    exit; // Ensure to stop further execution if not logged in
}

$stmt = $conn->prepare("SELECT * FROM users_app WHERE id_user=?");
$stmt->execute([$_SESSION['user']['id']]);
$reception = $stmt->fetch(PDO::FETCH_ASSOC);

$les_clients = [];

if (isset($_GET['rechercher']) && isset($_GET['query'])) {
    $query = '%' . $_GET['query'] . '%';
    $stmt = $conn->prepare("SELECT * FROM client 
                            WHERE nom_complet LIKE ? 
                            OR pays LIKE ? 
                            OR ville LIKE ?");
    $stmt->execute([$query, $query, $query]);
    $les_clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Default query to fetch all clients if no search query is provided
    $statement = $conn->prepare("SELECT * FROM client");
    $statement->execute();
    $les_clients = $statement->fetchAll(PDO::FETCH_ASSOC);
}

?>
<?php require_once "../inc/header.php"; ?>
<!-- Sidebar -->
<?php require_once "../inc/sidebar.php"; ?>
<!-- /#sidebar-wrapper -->

<!-- Page Content -->
<div id="page-content-wrapper" class="main-content w-100">
    <button class="btn btn-primary mb-4" id="menu-toggle"><i class="fas fa-bars"></i></button>
    <h1 class="text-center">Liste Des Clients</h1>
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?= $_SESSION['message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif;
    unset($_SESSION['message']) ?>
    <a href="create.php" class="m-3 btn btn-success"><i class="fa fa-plus"></i> Ajouter Client</a>
    <a href="register.php" class="m-3 btn btn-warning"><i class="fa fa-file"></i> Registre Client</a>

    <div class="container text-center w-100">
        <form class="form-inline my-2" method="GET" action="">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Rechercher..." name="query">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" name="rechercher" type="submit"><i
                            class="fas fa-search"></i></button>
                </div>
            </div>
        </form>
        <div class="table-responsive w-100">
            <table class="table table-striped table-hover w-100">
                <thead>
                    <tr>
                        <th>Nom Complet</th>
                        <th>Pays</th>
                        <th>Ville</th>
                        <th>Téléphone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($les_clients as $client): ?>
                        <tr>
                            <td><?= $client['nom_complet'] ?></td>
                            <td><?= $client['pays'] ?></td>
                            <td><?= $client['ville'] ?></td>
                            <td><?= $client['telephone'] ?></td>
                            <td>
                                <a href="view.php?id=<?= $client['id_client'] ?>" class="btn btn-primary btn-sm"><i
                                        class="fa fa-eye"></i></a>
                                <a href="edit.php?id=<?= $client['id_client'] ?>" class="btn btn-warning btn-sm"><i
                                        class="fa fa-edit"></i></a>
                                <a href="delete.php?id=<?= $client['id_client'] ?>" class="btn btn-danger btn-sm"><i
                                        class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- /#page-content-wrapper -->
<?php require_once "../inc/footer.php"; ?>