<?php

session_start();
require_once "../../config/database.php";

if (!isset($_SESSION["user"])) {
    header("location:../../index.php");
}

$stmt = $conn->prepare("SELECT * FROM users_app WHERE id_user=?");
$stmt->execute([$_SESSION['user']['id']]);

$manager = $stmt->fetch(PDO::FETCH_ASSOC);


?>


<?php

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $statement = $conn->prepare("SELECT * FROM users_app WHERE id_user=?");
    $statement->execute([$id]);
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    if (isset($_POST['modifier'])) {
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $username = $_POST['username'];
        $type = $_POST['type'];
        $etat = $_POST['etat'];
        if (!empty($nom) && !empty($prenom) && !empty($username) && !empty($type) && !empty($etat)) {
            $stmt = $conn->prepare("SELECT * FROM users_app WHERE username=?");
            $stmt->execute([$username]);
            if ($stmt->rowCount() > 1) {
                $messages[] = "username deja existe";
            } else {
                $statement = $conn->prepare("UPDATE users_app SET nom=?,prenom=?,username=?,type=?,etat=? WHERE id_user=?");
                $statement->execute([$nom, $prenom, $username, $type, $etat, $id]);
                header("location:list.php");
            }
        } else {
            $messages[] = "tous les champs sont obligatoires";
        }
    }
}

?>


<?php include "../inc/header.php" ?>
<?php include "../inc/sidebar.php" ?>

<!-- Page Content -->
<div id="page-content-wrapper" class="main-content w-100">
    <button class="btn btn-primary mb-4" id="menu-toggle"><i class="fas fa-bars"></i></button>
    <h1 class="text-center">Modifier Utilisateur</h1>
    <div class="container text-center w-100">
        <div class="form-container w-75 mx-auto">
            <div class="text-center w-75 mx-auto">
                <?php

                if (isset($messages)) {
                    foreach ($messages as $message):
                        ?>
                        <div class="alert alert-danger" role="alert"><?= $message ?></div>
                        <?php
                    endforeach;
                }

                ?>
            </div>
            <div class="text-start">
                <a href="list.php" class="btn btn-success my-2">retourne </a>
            </div>

            <form method="post" action="">
                <div class="row">
                    <div class="col-6">
                        <div class="mb-3">
                            <label class="form-label">Nom</label>
                            <input type="text" placeholder="nom" class="form-control" name="nom" value="<?php
                            if (isset($_GET['id'])) {
                                echo $user['nom'];
                            }
                            ?>">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label class="form-label">Prenom</label>
                            <input type="text" placeholder="prenom" class="form-control" name="prenom" value="<?php
                            if (isset($_GET['id'])) {
                                echo $user['prenom'];
                            }
                            ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" placeholder="username" class="form-control" name="username"
                                value="<?php
                                if (isset($_GET['id'])) {
                                    echo $user['username'];
                                }
                                ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="mb-3">
                            <label class="form-label">Type</label>
                            <select name="type" class="form-control">
                                <option value="">Type</option>
                                <option value="manager" <?php echo (isset($_GET['id']) && $user['type'] == "manager") ? 'selected' : ''; ?>>Manager</option>
                                <option value="receptionniste" <?php echo (isset($_GET['id']) && $user['type'] == "receptionniste") ? 'selected' : ''; ?>>Réceptionniste</option>
                                <option value="caissier" <?php echo (isset($_GET['id']) && $user['type'] == "caissier") ? 'selected' : ''; ?>>Caissier</option>
                            </select>

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label class="form-label">Etat</label>
                            <select name="etat" class="form-control">
                                <option value="">Etat</option>
                                <option value="Activé" <?php echo (isset($_GET['id']) && $user['etat'] == "Activé") ? 'selected' : ''; ?>>Activé</option>
                                <option value="Bloqué" <?php echo (isset($_GET['id']) && $user['etat'] == "Bloqué") ? 'selected' : ''; ?>>Bloqué</option>
                            </select>

                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary" name="modifier">Modifier</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /#page-content-wrapper -->
<?php include "../inc/footer.php" ?>