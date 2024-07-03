<?php

session_start();
require_once "../../config/database.php";

// Fetch room data for editing
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare('SELECT * FROM type_chambre WHERE id_type_ch=?');
    $stmt->execute([$id]);
    $type = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    header('Location: list.php');
    exit;
}

// Process form submission for editing
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type_chambre = $_POST['type_chambre'];
    $description_type = $_POST['description_type'];

    // Check if a new image has been uploaded
    if ($_FILES['photo_type']['error'] === UPLOAD_ERR_OK) {
        $photo = $_FILES['photo_type']['name'];
        $photo_tmp = $_FILES['photo_type']['tmp_name'];
        move_uploaded_file($photo_tmp, "../../assets/images/type_chambre/$photo");
    } else {
        // No new image uploaded, keep the existing one
        $photo = $type['photo_type'];
    }

    // Update the room in the database
    $stmt = $conn->prepare('UPDATE type_chambre SET type_chambre=? , description_type=?,photo_type=?  WHERE id_type_ch=?');
    $stmt->execute([$type_chambre, $description_type, $photo, $id]);

    $_SESSION['message'] = "type chambre mise à jour avec succès!";
    header('Location: list.php');
    exit;
}

?>
<?php require_once "../inc/header.php"; ?>
<?php require_once "../inc/sidebar.php"; ?>

<div id="page-content-wrapper" class="main-content w-100">
    <button class="btn btn-primary mb-4" id="menu-toggle"><i class="fas fa-bars"></i></button>
    <h3 class="text-center mb-5">Editer type chambre</h3>
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
                    <div class="form-group col-6">
                        <label class="form-label">type_chambre:</label>
                        <input type="text" name="type_chambre" class="form-control"
                            value="<?= $type['type_chambre'] ?? "" ?>">
                    </div>
                    <div class="form-group col-6">
                        <label class="form-label">photo:</label>
                        <input type="file" name="photo_type" class="form-control">

                    </div>
                </div>
                <div class="row my-2">
                    <div class="col-12 text-center">
                        <img src="../../assets/images/type_chambre/<?= $type['photo_type'] ?>" style="width:320px;">
                    </div>
                </div>
                <div class="row my-2">
                    <div class="form-group col-12">
                        <label class="form-label">description_type</label>
                        <textarea name="description_type"
                            class="form-control"><?= $type['description_type'] ?? "" ?></textarea>

                    </div>
                </div>
                <div class="form-group text-center mt-3">
                    <button type="submit" name="modifier" class="btn btn-success">Modifier</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once "../inc/footer.php"; ?>