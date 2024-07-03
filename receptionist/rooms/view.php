<?php

session_start();
require_once "../../config/database.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sqlState = $conn->prepare('SELECT * FROM chambre 
                                INNER JOIN type_chambre ON type_chambre.id_type_ch=chambre.id_type_ch
                                INNER JOIN capacite_chambre ON capacite_chambre.id_capacite=chambre.id_capacite
                                INNER JOIN tarif_chambre ON tarif_chambre.id_tarif=chambre.id_tarif
                                WHERE id_chambre=?');
    $sqlState->execute([$id]);
    $chambre = $sqlState->fetch(PDO::FETCH_ASSOC);
    // echo "<pre>";
    // print_r($chambre);
    // echo "</pre>";
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
        <a href="list.php" class="btn btn-secondary my-2">Afficher Toutes Les Chmabres</a>
    </div>

    <div class="table-responsive w-100">
        <div class="card w-100 mb-3" style="width: 18rem;">
            <div class="row mx-1">
                <div class="col-6">
                    <img src="../../assets/images/chambre/<?=$chambre['photo']?>" class="card-img-top my-2" alt="Chambre Image"
                        style="height:200px;">
                </div>
                <div class="col-6">
                    <h5 class="card-title">Chambre <?= $chambre['numero_chambre'] ?></h5>
                    <p class="card-text">
                        <strong>Nombre d'adultes et enfants:</strong> <?= $chambre['nombre_adultes_enfants_ch'] ?><br>
                        <strong>Renfort chambre:</strong>
                        <?php echo ($chambre['renfort_chambre'] == 1) ? "Oui" : "Non" ?><br>
                        <strong>Étage:</strong> <?= $chambre['etage_chambre'] ?><br>
                        <strong>Nombre de lits:</strong> <?= $chambre['nbr_lits_chambre'] ?><br>
                        <strong>Type:</strong> <?= $chambre['type_chambre'] ?><br>
                        <strong>Capacité:</strong> <?= $chambre['titre_capacite'] ?><br>
                        <strong>Tarif:</strong> <?= $chambre['prix_base_nuit'] ?><br>
                    </p>
                </div>
            </div>
        </div>
    </div>


    <div class="accordion accordion-flush" id="accordionFlushExample">
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                    Type chambre
                </button>
            </h2>
            <div id="flush-collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">
                    <div class="card w-100 mb-3" style="width: 18rem;">
                        <div class="row mx-1">
                            <div class="col-6">
                                <img src="../../assets/images/type_chambre/<?= $chambre['photo_type'] ?>"
                                    class="card-img-top my-2" alt="Chambre Image" style="height:200px;">
                            </div>
                            <div class="col-6">
                                <h5 class="card-title">Type <?= $chambre['type_chambre'] ?></h5>
                                <p class="card-text">
                                    <strong>Description type:</strong>
                                    <?= $chambre['description_type'] ?><br>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                    Capacité chambre
                </button>
            </h2>
            <div id="flush-collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">
                    <div class="card w-100 mb-3" style="width: 18rem;">
                        <div class="row mx-1">

                            <div class="col-12">
                                <h5 class="card-title">Capacité <?= $chambre['titre_capacite'] ?></h5>
                                <p class="card-text">
                                    <strong>numero capacite:</strong>
                                    <?= $chambre['numero_capacite'] ?><br>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                    Tarif chambre
                </button>
            </h2>
            <div id="flush-collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">
                    <div class="card w-100 mb-3" style="width: 18rem;">
                        <div class="row mx-1">
                            <div class="col-12">
                                <h5 class="card-title">Tarif Chambre :</h5>
                                <p class="card-text">
                                    <strong>Prix Nuit:</strong>
                                    <?= $chambre['prix_base_nuit'] ?> DHs<br>
                                    <strong>Prix Passage:</strong>
                                    <?= $chambre['prix_base_passage'] ?> DHs<br>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- /#page-content-wrapper -->
    <?php require_once "../inc/footer.php" ?>