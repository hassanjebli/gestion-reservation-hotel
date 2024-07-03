<?php

session_start();
require_once "../../config/database.php";

$sqlState = $conn->prepare("SELECT * FROM type_chambre");
$sqlState->execute();
$types = $sqlState->fetchAll(PDO::FETCH_ASSOC);

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
        <table class="table table-striped table-hover w-100 text-center">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>type_chambre</th>
                    <th>description</th>
                    <th>photo</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($types as $type): ?>
                    <tr>
                        <td><?= $type['id_type_ch'] ?></td>
                        <td><?= $type['type_chambre'] ?></td>
                        <td><?= $type['description_type'] ?></td>
                        <td><img style="width:100px;" src="../../assets/images/type_chambre/<?= $type['photo_type'] ?>"
                                alt=""></td>
                        <td>
                            <a href="edit.php?id=<?= $type['id_type_ch'] ?>" class="btn btn-warning btn-sm"><i
                                    class="fa fa-edit"></i></a>
                            <a href="delete.php?id=<?= $type['id_type_ch'] ?>" class="btn btn-danger btn-sm"><i
                                    class="fa fa-trash"></i></a>
                            <a href="view.php?id=<?= $type['id_type_ch'] ?>" class="btn btn-primary btn-sm"><i
                                    class="fa fa-eye"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- /#page-content-wrapper -->
    <?php require_once "../inc/footer.php" ?>