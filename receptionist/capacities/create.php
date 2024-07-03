<?php

session_start();
require_once "../../config/database.php";

if (isset($_POST['ajouter'])) {
    $titre = $_POST['titre'];
    $numero = $_POST['numero'];
    if (!empty($titre) && !empty($numero)) {
        $sqlState = $conn->prepare('INSERT INTO capacite_chambre VALUES (NULL,?,?)');
        $sqlState->execute([$titre, $numero]);
        $_SESSION['message'] = 'L\'insertion a ete effectue!!';
        header('location:list.php');
        exit();
    } else {
        $message = 'tous les champs sont obligatoires !!';
    }
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
        <a href="list.php" class="btn btn-success">Retour</a>
    </div>
    <?php if (isset($message)): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?= $message ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif;
    unset($message) ?>

    <form method="post" action="">
        <div class="row">
            <div class="col-10 mx-auto">
                <div class="row">
                    <div class="form-group col-6">
                        <label class="form-label">Titre Capacite</label>
                        <input type="text" name="titre" class="form-control">
                    </div>
                    <div class="form-group col-6">
                        <label class="form-label">Numero Capacite</label>
                        <input type="number" name="numero" class="form-control">
                    </div>
                </div>
                <div class="row my-3">
                    <div class="col-12 text-center">
                        <button class="btn btn-primary" type="submit" name="ajouter">Ajouter</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- /#page-content-wrapper -->
    <?php require_once "../inc/footer.php" ?>