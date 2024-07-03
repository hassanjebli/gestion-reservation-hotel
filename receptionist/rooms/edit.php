<?php

session_start();
require_once "../../config/database.php";

// Fetch room data for editing
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare('SELECT * FROM chambre WHERE id_chambre=?');
    $stmt->execute([$id]);
    $room = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    header('Location: list.php');
    exit;
}

// Process form submission for editing
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero_chambre = $_POST['numero_chambre'];
    $nombre_adultes_enfants_ch = $_POST['nombre_adultes_enfants_ch'];
    $renfort_chambre = $_POST['renfort_chambre'] === 'oui' ? 1 : 0;
    $etage_chambre = $_POST['etage_chambre'];
    $nbr_lits_chambre = $_POST['nbr_lits_chambre'];
    $type = $_POST['type'];
    $capacite = $_POST['capacite'];
    $tarif = $_POST['tarif'];

    if (!empty($numero_chambre) && !empty($nombre_adultes_enfants_ch) && !empty($renfort_chambre) && !empty($etage_chambre) && !empty($nbr_lits_chambre) && !empty($type) && !empty($capacite) && !empty($tarif)) {
        // Check if a new image has been uploaded
        if ($_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $photo = $_FILES['photo']['name'];
            $photo_tmp = $_FILES['photo']['tmp_name'];
            move_uploaded_file($photo_tmp, "../../assets/images/chambre/$photo");
        } else {
            // No new image uploaded, keep the existing one
            $photo = $room['photo'];
        }

        // Update the room in the database
        $stmt = $conn->prepare('UPDATE chambre SET numero_chambre=?, nombre_adultes_enfants_ch=?, renfort_chambre=?, etage_chambre=?, nbr_lits_chambre=?, id_type_ch=?, id_capacite=?, id_tarif=?, photo=? WHERE id_chambre=?');
        $stmt->execute([$numero_chambre, $nombre_adultes_enfants_ch, $renfort_chambre, $etage_chambre, $nbr_lits_chambre, $type, $capacite, $tarif, $photo, $id]);

        $_SESSION['message'] = "Chambre mise à jour avec succès!";
        header('Location: list.php');
        exit;
    } else {
        $_SESSION['message'] = 'tous les champs sont obligatoires !!';
    }
}

?>
<?php require_once "../inc/header.php"; ?>
<?php require_once "../inc/sidebar.php"; ?>

<div id="page-content-wrapper" class="main-content w-100">
    <button class="btn btn-primary mb-4" id="menu-toggle"><i class="fas fa-bars"></i></button>
    <h3 class="text-center mb-5">Editer Chambre</h3>
    <div class="row">

        <div class="col-9 mx-auto">
            <a href="list.php" class="btn btn-success">Retour</a>
            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <?= $_SESSION['message'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="row my-2">
                    <div class="form-group col-4">
                        <label class="form-label">Numero:</label>
                        <input type="number" name="numero_chambre" class="form-control"
                            value="<?= $room['numero_chambre'] ?>">
                    </div>
                    <div class="form-group col-4">
                        <label class="form-label">Nombre de personnes</label>
                        <input type="number" name="nombre_adultes_enfants_ch" class="form-control"
                            value="<?= $room['nombre_adultes_enfants_ch'] ?>">
                    </div>
                    <div class="form-group col-4">
                        <label>Renfort</label><br>
                        <input type="radio" name="renfort_chambre" value="oui" class="form-check-input"
                            <?= $room['renfort_chambre'] == 1 ? 'checked' : '' ?>>
                        <label class="form-check-label">Oui</label><br>
                        <input type="radio" name="renfort_chambre" value="non" class="form-check-input"
                            <?= $room['renfort_chambre'] == 0 ? 'checked' : '' ?>>
                        <label class="form-check-label">Non</label>
                    </div>
                </div>
                <div class="row my-2">
                    <div class="form-group col-6">
                        <label class="form-label">Etage</label>
                        <input type="number" name="etage_chambre" class="form-control"
                            value="<?= $room['etage_chambre'] ?>">
                    </div>
                    <div class="form-group col-6">
                        <label class="form-label">Nombre de lits</label>
                        <input type="number" name="nbr_lits_chambre" class="form-control"
                            value="<?= $room['nbr_lits_chambre'] ?>">
                    </div>
                </div>
                <div class="row my-2">
                    <div class="form-group col-4">
                        <?php
                        $s1 = $conn->prepare("SELECT * FROM type_chambre");
                        $s1->execute();
                        $types = $s1->fetchAll(PDO::FETCH_ASSOC);
                        ?>
                        <label class="form-label">Type:</label>
                        <select name="type" class="form-control">
                            <option value="">-- Sélectionner --</option>
                            <?php foreach ($types as $type): ?>
                                <option value="<?= $type['id_type_ch'] ?>" <?= $room['id_type_ch'] == $type['id_type_ch'] ? 'selected' : '' ?>>
                                    <?= $type['type_chambre'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group col-4">
                        <?php
                        $s2 = $conn->prepare("SELECT * FROM capacite_chambre");
                        $s2->execute();
                        $capacities = $s2->fetchAll(PDO::FETCH_ASSOC);
                        ?>
                        <label class="form-label">Capacité:</label>
                        <select name="capacite" class="form-control">
                            <option value="">-- Sélectionner --</option>
                            <?php foreach ($capacities as $capacitie): ?>
                                <option value="<?= $capacitie['id_capacite'] ?>"
                                    <?= $room['id_capacite'] == $capacitie['id_capacite'] ? 'selected' : '' ?>>
                                    <?= $capacitie['titre_capacite'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group col-4">
                        <?php
                        $s3 = $conn->prepare("SELECT * FROM tarif_chambre");
                        $s3->execute();
                        $tarifs = $s3->fetchAll(PDO::FETCH_ASSOC);
                        ?>
                        <label class="form-label">Tarif:</label>
                        <select name="tarif" class="form-control">
                            <option value="">-- Sélectionner --</option>
                            <?php foreach ($tarifs as $tarif): ?>
                                <option value="<?= $tarif['id_tarif'] ?>" <?= $room['id_tarif'] == $tarif['id_tarif'] ? 'selected' : '' ?>>
                                    <?= $tarif['prix_base_nuit'] ?> DH
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="row text-center">
                    <div class="col-12">
                        <img src="../../assets/images/chambre/<?= $room['photo'] ?>" style="width:320px;">
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <label class="form-label">Photo</label>
                        <input type="file" name="photo" class="form-control">
                    </div>
                </div>
                <div class="form-group text-center mt-3">
                    <button type="submit" name="editer" class="btn btn-primary">Enregistrer les modifications</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once "../inc/footer.php"; ?>