<?php

session_start();
require_once "../../config/database.php";

if (!isset($_SESSION["user"])) {
    header("location:../index.php");
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM client WHERE id_client=?");
    $stmt->execute([$id]);
    $client = $stmt->fetch(PDO::FETCH_ASSOC);
}

?>
<?php require_once "../inc/header.php"; ?>

<!-- Sidebar -->
<?php require_once "../inc/sidebar.php"; ?>
<!-- /#sidebar-wrapper -->

<!-- Page Content -->
<div id="page-content-wrapper" class="main-content w-100">
    <div class="container-fluid">
        <button class="btn btn-primary mb-4" id="menu-toggle"><i class="fas fa-bars"></i></button>

        <div class="card w-75 mx-auto">

            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0 text-center">Client Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="id" class="form-label">ID:</label>
                                    <p class="form-control"><?php echo $client['id_client']; ?></p>
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="form-group">
                                    <label for="nom_complet" class="form-label">Nom complet:</label>
                                    <p class="form-control"><?php echo $client['nom_complet']; ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="sexe" class="form-label">Sexe:</label>
                            <p class="form-control"><?php echo $client['sexe']; ?></p>
                        </div>
                        <div class="form-group">
                            <label for="date_naissance" class="form-label">Date de Naissance:</label>
                            <p class="form-control"><?php echo $client['date_naissance']; ?></p>
                        </div>
                        <div class="form-group">
                            <label for="age" class="form-label">Age:</label>
                            <p class="form-control"><?php echo $client['age']; ?></p>
                        </div>
                        <div class="form-group">
                            <label for="pays" class="form-label">Pays:</label>
                            <p class="form-control"><?php echo $client['pays']; ?></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="ville" class="form-label">Ville:</label>
                            <p class="form-control"><?php echo $client['ville']; ?></p>
                        </div>
                        <div class="form-group">
                            <label for="telephone" class="form-label">Téléphone:</label>
                            <p class="form-control"><?php echo $client['telephone']; ?></p>
                        </div>
                        <div class="form-group">
                            <label for="email" class="form-label">Email:</label>
                            <p class="form-control"><?php echo $client['email']; ?></p>
                        </div>
                        <div class="form-group">
                            <label for="adresse" class="form-label">Adresse:</label>
                            <p class="form-control"><?php echo $client['adresse']; ?></p>
                        </div>
                        <div class="form-group">
                            <label for="autres_details" class="form-label">Autres Détails:</label>
                            <p class="form-control"><?php echo $client['autres_details']; ?></p>
                        </div>

                    </div>
                </div>
            </div>
            <div class="text-center">
                <a href="list.php" class="btn btn-success mb-2">View All Clients </a>
            </div>
        </div>

    </div>
</div>

<?php require_once "../inc/footer.php"; ?>