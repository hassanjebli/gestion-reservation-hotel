<?php

session_start();
require_once "../../config/database.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sqlState = $conn->prepare("SELECT * FROM type_chambre
                                INNER JOIN chambre ON type_chambre.id_type_ch = chambre.id_type_ch
                                WHERE type_chambre.id_type_ch = ?");
    $sqlState->execute([$id]);

    $chambres = $sqlState->fetchAll(PDO::FETCH_ASSOC);

    $stmt1 = $conn->prepare("SELECT * FROM type_chambre WHERE id_type_ch = ?");
    $stmt1->execute([$id]);
    $type = $stmt1->fetch(PDO::FETCH_ASSOC);
} else {
    header('location:../index.php');
    exit;
}

?>
<?php require_once "../inc/header.php" ?>
<!-- Sidebar -->
<?php require_once "../inc/sidebar.php" ?>
<!-- /#sidebar-wrapper -->

<!-- Page Content -->
<div id="page-content-wrapper" class="main-content w-100">
    <button class="btn btn-primary mb-4" id="menu-toggle"><i class="fas fa-bars"></i></button>
    <h2 class="text-center">Types de Chambres</h2>
    <div class="text-start">
        <a href="list.php" class="btn btn-success my-2">retour</a>
    </div>
    <div class="container">


        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 ">
            <div class="col mx-auto mb-4 w-75">
                <div class="card h-100 shadow-sm">
                    <div class="row">
                        <div class="col-6">
                            <img src="../../assets/images/type_chambre/<?=$type['photo_type']?>" height="230px" class="card-img-top" alt="Chambre Simple">
                        </div>
                        <div class="col-6">

                            <div class="card-body">
                                <h5 class="card-title"><?=$type['type_chambre']?></h5>
                                <p class="card-text"><?=$type['description_type']?></p>
                            </div>
                            <div class="card-footer bg-transparent">
                                <small class="text-muted">ID: <?=$type['id_type_ch']?></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="accordion" id="accordionExample">
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne"
                    aria-expanded="true" aria-controls="collapseOne">
                    La liste des chambres ayant ce type
                </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <ul class="list-group">
                        <?php foreach ($chambres as $chambre): ?>
                            <li class="list-group-item">La Chambre : <?= $chambre['numero_chambre'] ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>


    <!-- /#page-content-wrapper -->
    <?php require_once "../inc/footer.php" ?>