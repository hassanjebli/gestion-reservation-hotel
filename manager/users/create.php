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

if (isset($_POST['ajouter'])) {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $type = $_POST['type'];
    $etat = $_POST['etat'];
    if (!empty($nom) && !empty($prenom) && !empty($username) && !empty($password) && !empty($type) && !empty($etat)) {
        $stmt = $conn->prepare("SELECT * FROM users_app WHERE username=?");
        $stmt->execute([$username]);
        if ($stmt->rowCount() > 0) {
            $messages[] = "username deja existe";
        } else {
            $statement = $conn->prepare("INSERT INTO users_app VALUES (NULL,?,?,?,?,?,?)");
            $statement->execute([$nom, $prenom, $username, $password, $type, $etat]);
            header("location:list.php");
        }
    } else {
        $messages[] = "tous les champs sont obligatoires";
    }
}

?>


<?php include "../inc/header.php" ?>
<?php include "../inc/sidebar.php" ?>

<!-- Page Content -->
<div id="page-content-wrapper" class="main-content w-100">
    <button class="btn btn-primary mb-4" id="menu-toggle"><i class="fas fa-bars"></i></button>
    <h1 class="text-center">Liste Des Utilisateurs</h1>
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
                <a href="list.php" class="btn btn-success my-2">View All Users </a>
            </div>

            <form method="post" action="">
                <div class="row">
                    <div class="col-6">
                        <div class="mb-3">
                            <label class="form-label">Nom</label>
                            <input type="text" placeholder="nom" class="form-control" name="nom">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label class="form-label">Prenom</label>
                            <input type="text" placeholder="prenom" class="form-control" name="prenom">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" placeholder="username" class="form-control" name="username">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" placeholder="password" class="form-control" name="password">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="mb-3">
                            <label class="form-label">Type</label>
                            <select name="type" class="form-control">
                                <option value="">Type</option>
                                <option value="manager">Manager</option>
                                <option value="receptionniste">Réceptionniste</option>
                                <option value="caissier">Caissier</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label class="form-label">Etat</label>
                            <select name="etat" class="form-control">
                                <option value="">Etat</option>
                                <option value="Activé">Activé</option>
                                <option value="Bloqué">Bloqué</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary" name="ajouter">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /#page-content-wrapper -->
<?php include "../inc/footer.php" ?>