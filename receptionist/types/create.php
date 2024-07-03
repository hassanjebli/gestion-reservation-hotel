<?php
session_start();
require_once "../../config/database.php";

if (isset($_POST['ajouter'])) {
    // Retrieve form data
    $type_chambre = $_POST['type_chambre'];
    $description_type = $_POST['description_type'];

    // Check if all required fields are not empty
    if (!empty($type_chambre) && !empty($description_type) && $_FILES['photo_type']['error'] === UPLOAD_ERR_OK) {

        // File upload variables
        $photo_nom = $_FILES['photo_type']['name'];
        $photo_tmp = $_FILES['photo_type']['tmp_name'];
        $upload_path = "../../assets/images/type_chambre/";
        $upload_photo = $upload_path . $photo_nom;

        // Move the uploaded file to desired location
        if (move_uploaded_file($photo_tmp, $upload_photo)) {
            // File uploaded successfully, proceed with database insertion
            $sql = $conn->prepare('INSERT INTO type_chambre VALUES (NULL,?,?,?)');
            $sql->execute([$type_chambre, $description_type, $photo_nom]);

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
                    <div class="form-group col-6">
                        <label class="form-label">type_chambre:</label>
                        <input type="text" name="type_chambre" class="form-control">
                    </div>
                    <div class="form-group col-6">
                        <label class="form-label">photo:</label>
                        <input type="file" name="photo_type" class="form-control">
                    </div>
                </div>
                <div class="row my-2">
                    <div class="form-group col-12">
                        <label class="form-label">description_type</label>
                        <textarea name="description_type" class="form-control"></textarea>
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