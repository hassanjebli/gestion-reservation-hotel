<?php

session_start();
require_once "../../config/database.php";

$sqlState = $conn->prepare("SELECT * FROM capacite_chambre");
$sqlState->execute();
$capacities = $sqlState->fetchAll(PDO::FETCH_ASSOC);

?>
<?php require_once "../inc/header.php" ?>
<!-- Sidebar -->
<?php require_once "../inc/sidebar.php" ?>
<!-- /#sidebar-wrapper -->

<!-- Page Content -->
<div id="page-content-wrapper" class="main-content w-100">
    <button class="btn btn-primary mb-4" id="menu-toggle"><i class="fas fa-bars"></i></button>
    <div class="text-start">
        <a href="create.php" class="btn btn-success my-2">Ajouter</a>
    </div>
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?= $_SESSION['message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif;
    unset($_SESSION['message']) ?>
    <div class="table-responsive w-100">
        <table class="table table-striped table-hover text-center w-100">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titre Capacite</th>
                    <th>Numero Capacite</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($capacities as $capacite): ?>
                    <tr>
                        <td><?= $capacite['id_capacite'] ?></td>
                        <td><?= $capacite['titre_capacite'] ?></td>
                        <td><?= $capacite['numero_capacite'] ?></td>
                        <td>
                            <a href="edit.php?id=<?= $capacite['id_capacite'] ?>" class="btn btn-warning btn-sm"><i
                                    class="fa fa-edit"></i></a>
                            <a href="delete.php?id=<?= $capacite['id_capacite'] ?>" class="btn btn-danger btn-sm"><i
                                    class="fa fa-trash"></i></a>
                            <a href="view.php?id=<?= $capacite['id_capacite'] ?>" class="btn btn-primary btn-sm"><i
                                    class="fa fa-eye"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- /#page-content-wrapper -->
    <?php require_once "../inc/footer.php" ?>