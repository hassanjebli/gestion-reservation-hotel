<?php
session_start();
require_once "../../config/database.php";

if (isset($_POST['ajouter'])) {
    // Retrieve form data
    $numero_chambre = $_POST['numero_chambre'];
    $nombre_adultes_enfants_ch = $_POST['nombre_adultes_enfants_ch'];
    $renfort_chambre = isset($_POST['renfort_chambre']) && $_POST['renfort_chambre'] === 'oui' ? 1 : 0;
    $etage_chambre = $_POST['etage_chambre'];
    $nbr_lits_chambre = $_POST['nbr_lits_chambre'];
    $type = $_POST['type'];
    $capacite = $_POST['capacite'];
    $tarif = $_POST['tarif'];

    // Check if all required fields are not empty
    if (!empty($numero_chambre) && !empty($nombre_adultes_enfants_ch) && !empty($etage_chambre) && !empty($nbr_lits_chambre) && !empty($type) && !empty($capacite) && !empty($tarif) && isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {

        // File upload variables
        $photo_nom = $_FILES['photo']['name'];
        $photo_tmp = $_FILES['photo']['tmp_name'];
        $upload_path = "../../assets/images/chambre/";
        $upload_photo = $upload_path . $photo_nom;

        // Move the uploaded file to desired location
        if (move_uploaded_file($photo_tmp, $upload_photo)) {
            // File uploaded successfully, proceed with database insertion
            $sql = $conn->prepare('INSERT INTO chambre VALUES(NULL,?,?,?,?,?,?,?,?,?)');
            $sql->execute([$numero_chambre, $nombre_adultes_enfants_ch, $renfort_chambre, $etage_chambre, $nbr_lits_chambre, $photo_nom, $type, $capacite, $tarif]);

            $_SESSION['message'] = "Chambre ajoutée avec succès.";
            header('location:list.php');
            exit();
        } else {
            $_SESSION['message'] = "Erreur lors de l'upload du fichier.";
        }
    } else {
        $_SESSION['message'] = "Tous les champs sont obligatoires.";
    }

    // Redirect to list.php regardless of success or failure
    header('location:list.php');
    exit();
}
?>



<?php require_once "../inc/header.php" ?>
<?php require_once "../inc/sidebar.php" ?>

<div id="page-content-wrapper" class="main-content w-100">
    <button class="btn btn-primary mb-4" id="menu-toggle"><i class="fas fa-bars"></i></button>
    <h3 class="text-center mb-5">Ajouter Chambre</h3>
    <div class="row">
        <div class="col-9 mx-auto">
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
                        <input type="number" name="numero_chambre" class="form-control">
                    </div>
                    <div class="form-group col-4">
                        <label class="form-label">Nombre personne</label>
                        <input type="number" name="nombre_adultes_enfants_ch" class="form-control">
                    </div>
                    <div class="form-group col-4">
                        <label>Renfort</label><br>
                        <input type="radio" name="renfort_chambre" value="oui" class="form-check-input">
                        <label class="form-check-label">Oui</label><br>
                        <input type="radio" name="renfort_chambre" value="non" class="form-check-input">
                        <label for="renfort_chambre_non" class="form-check-label">Non</label>
                    </div>
                </div>
                <div class="row my-2">
                    <div class="form-group col-6">
                        <label class="form-label">Etage</label>
                        <input type="number" name="etage_chambre" class="form-control">
                    </div>
                    <div class="form-group col-6">
                        <label class="form-label">Nombre Lits</label>
                        <input type="number" name="nbr_lits_chambre" class="form-control">
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
                                <option value="<?= $type['id_type_ch'] ?>"><?= $type['type_chambre'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group col-4">
                        <?php
                        $s2 = $conn->prepare("SELECT * FROM capacite_chambre");
                        $s2->execute();
                        $capacities = $s2->fetchAll(PDO::FETCH_ASSOC);
                        ?>
                        <label class="form-label">Capacite:</label>
                        <select name="capacite" class="form-control">
                            <option value="">-- Sélectionner --</option>
                            <?php foreach ($capacities as $capacitie): ?>
                                <option value="<?= $capacitie['id_capacite'] ?>"><?= $capacitie['titre_capacite'] ?>
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
                                <option value="<?= $tarif['id_tarif'] ?>"><?= $tarif['prix_base_nuit'] ?> DH </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="row my-2">
                    <div class="col-12">
                        <label class="form-label">Photo</label>
                        <input type="file" name="photo" class="form-control">
                    </div>
                </div>
                <div class="form-group text-center mt-3">
                    <button type="submit" name="ajouter" class="btn btn-success">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once "../inc/footer.php" ?>