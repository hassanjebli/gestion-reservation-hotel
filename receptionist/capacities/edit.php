<?php

session_start();
require_once "../../config/database.php";

if (isset($_GET['id'])) {

    $id = $_GET['id'];
    $sql = $conn->prepare('SELECT * FROM capacite_chambre WHERE id_capacite=?');
    $sql->execute([$id]);
    $capacite = $sql->fetch(PDO::FETCH_ASSOC);
    if (isset($_POST['modifier'])) {
        $titre = $_POST['titre'];
        $numero = $_POST['numero'];
        if (!empty($titre) && !empty($numero)) {
            $sqlState = $conn->prepare('UPDATE capacite_chambre SET titre_capacite=? , numero_capacite=? WHERE id_capacite=?');
            $sqlState->execute([$titre, $numero,$id]);
            $_SESSION['message'] = 'La modification a ete effectue!!';
            header('location:list.php');
            exit();
        } else {
            $message = 'tous les champs sont obligatoires !!';
        }
    }

} else {
    $_SESSION['message'] = 'aucune capacite avec cette id !!';
    header('location:list.php');
    exit();
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
                        <input type="text" name="titre" class="form-control"
                            value="<?= $capacite['titre_capacite'] ?? "" ?>">
                    </div>
                    <div class="form-group col-6">
                        <label class="form-label">Numero Capacite</label>
                        <input type="number" name="numero" class="form-control"
                            value="<?= $capacite['numero_capacite'] ?? "" ?>">
                    </div>
                </div>
                <div class="row my-3">
                    <div class="col-12 text-center">
                        <button class="btn btn-primary" type="submit" name="modifier">Modifier</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- /#page-content-wrapper -->
    <?php require_once "../inc/footer.php" ?>