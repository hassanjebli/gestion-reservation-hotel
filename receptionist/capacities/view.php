<?php

session_start();
require_once "../../config/database.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sqlState = $conn->prepare('SELECT * FROM capacite_chambre WHERE id_capacite=?');
    $sqlState->execute([$id]);
    $capacite = $sqlState->fetch(PDO::FETCH_ASSOC);
    # code...
}

?>
<?php require_once "../inc/header.php" ?>
<!-- Sidebar -->
<?php require_once "../inc/sidebar.php" ?>
<!-- /#sidebar-wrapper -->

<!-- Page Content -->
<div id="page-content-wrapper" class="main-content w-100">
    <button class="btn btn-primary mb-4" id="menu-toggle"><i class="fas fa-bars"></i></button>
    <div class="text-start">
        <a href="list.php" class="btn btn-success">retour</a>
    </div>
    <div class="container">


        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 ">
            <div class="col mx-auto mb-4 w-75">
                <div class="card h-100 shadow-sm">
                    <div class="row">
                        <div class="col-12">

                            <div class="card-body text-center">
                                <h5 class="card-title"><strong>Titre Capacit : </strong><?= $capacite['titre_capacite'] ?></h5>
                                <p class="card-text"><strong>Numero Capacite : </strong><?= $capacite['numero_capacite'] ?></p>
                            </div>
                            <div class="card-footer bg-transparent">
                                <small class="text-muted">ID: <?= $capacite['id_capacite'] ?></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- /#page-content-wrapper -->
    <?php require_once "../inc/footer.php" ?>