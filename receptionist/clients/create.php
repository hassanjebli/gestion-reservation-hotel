<?php

session_start();
require_once "../../config/database.php";

if (!isset($_SESSION["user"])) {
    header("location:../index.php");
}


$stmt = $conn->prepare("SELECT * FROM users_app WHERE id_user=?");
$stmt->execute([$_SESSION['user']['id']]);

$reception = $stmt->fetch(PDO::FETCH_ASSOC);


$statement = $conn->prepare("SELECT * FROM client");
$statement->execute();
$clients = $statement->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST['ajouter'])) {
    $nom_complet = $_POST['nom_complet'];
    $sexe = $_POST['sexe'];
    $date_naissance = $_POST['date_naissance'];
    $pays = $_POST['pays'];
    $ville = $_POST['ville'];
    $adresse = $_POST['adresse'];
    $telephone = $_POST['telephone'];
    $email = $_POST['email'];
    $autres_details = $_POST['autres_details'];
    if (!empty($nom_complet) && !empty($pays) && !empty($ville) && !empty($telephone)) {
        //calculating age based on the birth date
        $birthDateObj = new DateTime($date_naissance);
        $currentDate = new DateTime();
        $interval = $currentDate->diff($birthDateObj);
        $age = $interval->y;


        $state = $conn->prepare("INSERT INTO client VALUES (NULL,?,?,?,?,?,?,?,?,?,?)");
        $state->execute([$nom_complet, $sexe, $date_naissance, $age, $pays, $ville, $adresse, $telephone, $email, $autres_details]);
        header("location:list.php");
    } else {
        $messages[] = "Le nom et le pays et le telephone sont obligatoires !!";
    }
}





?>
<?php require_once "../inc/header.php"; ?>
<!-- Sidebar -->
<?php require_once "../inc/sidebar.php" ?>

<!-- /#sidebar-wrapper -->

<!-- Page Content -->
<div id="page-content-wrapper" class="main-content w-100">
    <button class="btn btn-primary mb-4" id="menu-toggle"><i class="fas fa-bars"></i></button>
    <div class="form-container w-75 mx-auto">
        <div class="text-center w-75 mx-auto">
            <?php

            if (isset($messages)) {
                foreach ($messages as $message) :
            ?>
                    <div class="alert alert-danger" role="alert"><?= $message ?></div>
            <?php
                endforeach;
            }

            ?>
        </div>
        <a href="list.php" class="btn btn-success my-2">View All Clients </a>

        <form method="post" action="">
            <div class="mb-3">
                <label class="form-label">nom complet</label>
                <input type="text" placeholder="nom complet" class="form-control" name="nom_complet">
            </div>
            <div class="row">
                <div class="col-3">
                    <div class="mb-3">
                        <label class="form-label">sexe</label>
                        <select name="sexe" class="form-control" id="sexe">
                        <option value="">Sexe</option>
                        <option value="Homme">Homme</option>
                        <option value="Femme">Femme</option>
                        </select>
                    </div>
                </div>
                <div class="col-4">
                    <div class="mb-3">
                        <label class="form-label">Date De Naissance</label>
                        <input type="date" class="form-control" name="date_naissance">
                    </div>
                </div>
                <div class="col-5">
                    <div class="mb-3">
                        <label class="form-label">Pays</label>
                        <select class="form-control" name="pays">
                            <option value="">pays</option>
                            <option value="maroc">Maroc</option>
                            <option value="algerie">Algérie</option>
                            <option value="france">France</option>
                            <option value="espagne">Espagne</option>
                            <option value="italie">Italie</option>
                            <option value="egypte">Égypte</option>
                            <option value="canada">Canada</option>
                            <option value="japon">Japon</option>
                            <option value="chine">Chine</option>
                        </select>

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                    <div class="mb-3">
                        <label class="form-label">ville</label>
                        <input type="text" placeholder="ville" class="form-control" name="ville">
                    </div>
                </div>
                <div class="col-8">
                    <div class="mb-3">
                        <label class="form-label">telephone</label>
                        <input type="text" placeholder="telephone" class="form-control" name="telephone">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="mb-3">
                        <label class="form-label">email</label>
                        <input type="email" placeholder="email" class="form-control" name="email">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="mb-3">
                        <label class="form-label">Adresse</label>
                        <textarea class="form-control" name="adresse"></textarea>
                    </div>
                </div>
                <div class="col-6">
                    <div class="mb-3">
                        <label class="form-label">Autre Details</label>
                        <textarea class="form-control" name="autres_details"></textarea>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary" name="ajouter">Ajoter</button>
            </div>
        </form>
    </div>
</div>
<?php require_once "../inc/footer.php" ?>