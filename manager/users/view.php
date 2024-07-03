<?php

session_start();
require_once "../../config/database.php";

if (!isset($_SESSION["user"])) {
    header("location:../../index.php");
}

$stmt = $conn->prepare("SELECT * FROM users_app WHERE id_user=?");
$stmt->execute([$_SESSION['user']['id']]);

$manager = $stmt->fetch(PDO::FETCH_ASSOC);


if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $statement = $conn->prepare("SELECT * FROM users_app WHERE id_user=?");
    $statement->execute([$id]);
    $user = $statement->fetch(PDO::FETCH_ASSOC);
}


?>

<?php include "../inc/header.php" ?>
<?php include "../inc/sidebar.php" ?>

<div id="page-content-wrapper" class="main-content w-100">
    <button class="btn btn-primary mb-4" id="menu-toggle"><i class="fas fa-bars"></i></button>
    <h1 class="text-center">View User</h1>
    <div class="container text-center">



        <div class="card-body">

            <div class="row">

                <div class="col-8 mx-auto">
                    <div class="text-start">
                        <a href="list.php" class="btn btn-success">Retourner</a>
                    </div>
                    <div class="row mx-auto">
                        <div class="form-group">
                            <label for="id" class="form-label">ID:</label>
                            <p class="form-control"><?php echo $user['id_user']; ?></p>
                        </div>
                        <div class="row  mx-auto">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="nom_complet" class="form-label">Nom:</label>
                                    <p class="form-control"><?php echo $user['nom']; ?></p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="nom_complet" class="form-label">Prenom:</label>
                                    <p class="form-control"><?php echo $user['prenom']; ?></p>
                                </div>
                            </div>

                        </div>
                        <div class="form-group">
                            <label for="nom_complet" class="form-label">Username:</label>
                            <p class="form-control"><?php echo $user['username']; ?></p>
                        </div>
                        <div class="row  mx-auto">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="nom_complet" class="form-label">Type:</label>
                                    <p class="form-control"><?php echo $user['type']; ?></p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="nom_complet" class="form-label">Etat:</label>
                                    <p class="form-control"><?php echo $user['etat']; ?></p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>



    </div>
</div>
<?php include "../inc/footer.php" ?>