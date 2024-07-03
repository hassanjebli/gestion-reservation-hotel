<?php

session_start();
require_once "../../config/database.php";

if (!isset($_SESSION["user"])) {
    header("location:../index.php");
}




if (isset($_GET['id'])) {
    $id = $_GET['id'];


    $stmt = $conn->prepare("SELECT * FROM client WHERE id_client=?");
    $stmt->execute([$id]);
    $client = $stmt->fetch(PDO::FETCH_ASSOC);





    if (isset($_POST['modifier'])) {
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


            $state = $conn->prepare("UPDATE client SET nom_complet=? , sexe=? , date_naissance=?,age=?,pays=?,ville=?,adresse=?,telephone=?,email=?,autres_details=? WHERE id_client=?");
            $state->execute([$nom_complet, $sexe, $date_naissance, $age, $pays, $ville, $adresse, $telephone, $email, $autres_details, $id]);
            header("location:list.php");
        } else {
            $messages[] = "Le nom et le pays et le telephone sont obligatoires !!";
        }
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
                <input type="text" placeholder="nom complet" class="form-control" name="nom_complet" value="<?php
                                                                                                            if (isset($_GET['id'])) {
                                                                                                                echo $client['nom_complet'];
                                                                                                            } else {
                                                                                                                echo "";
                                                                                                            }
                                                                                                            ?>">
            </div>
            <div class="row">
                <div class="col-3">
                    <div class="mb-3">
                        <label class="form-label">sexe</label>


                        <select class="form-control" name="sexe">
                            <option value="">Sexe</option>
                            <option value="Homme" <?php if (isset($_GET['id']) && $client['sexe'] === 'Homme') echo ' selected'; ?>>Homme</option>
                            <option value="Femme" <?php if (isset($_GET['id']) && $client['sexe'] === 'Femme') echo ' selected'; ?>>Femme</option>
                        </select>

                    </div>
                </div>
                <div class="col-4">
                    <div class="mb-3">
                        <label class="form-label">Date De Naissance</label>
                        <input type="date" class="form-control" name="date_naissance" value="<?php
                                                                                                if (isset($_GET['id'])) {
                                                                                                    echo $client['date_naissance'];
                                                                                                } else {
                                                                                                    echo "";
                                                                                                }
                                                                                                ?>">
                    </div>
                </div>
                <div class="col-5">
                    <div class="mb-3">
                        <label class="form-label">Pays</label>
                        <select class="form-control" name="pays">
                            <option value="">pays</option>
                            <option value="maroc" <?php echo (isset($_GET['id']) && $client['pays'] === 'maroc') ? 'selected' : ''; ?>>Maroc</option>
                            <option value="algerie" <?= (isset($_GET['id']) && $client['pays'] === 'algerie') ? 'selected' : '' ?>>Algérie</option>
                            <option value="france" <?= (isset($_GET['id']) && $client['pays'] === 'france') ? 'selected' : '' ?>>France</option>
                            <option value="espagne" <?= (isset($_GET['id']) && $client['pays'] === 'espagne') ? 'selected' : '' ?>>Espagne</option>
                            <option value="italie" <?= (isset($_GET['id']) && $client['pays'] === 'italie') ? 'selected' : '' ?>>Italie</option>
                            <option value="egypte" <?= (isset($_GET['id']) && $client['pays'] === 'egypte') ? 'selected' : '' ?>>Égypte</option>
                            <option value="canada" <?= (isset($_GET['id']) && $client['pays'] === 'canada') ? 'selected' : '' ?>>Canada</option>
                            <option value="japon" <?= (isset($_GET['id']) && $client['pays'] === 'japon') ? 'selected' : '' ?>>Japon</option>
                            <option value="chine" <?= (isset($_GET['id']) && $client['pays'] === 'chine') ? 'selected' : '' ?>>Chine</option>
                        </select>


                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                    <div class="mb-3">
                        <label class="form-label">ville</label>
                        <input type="text" placeholder="ville" class="form-control" name="ville" value="<?php
                                                                                                        if (isset($_GET['id'])) {
                                                                                                            echo $client['ville'];
                                                                                                        } else {
                                                                                                            echo "";
                                                                                                        }
                                                                                                        ?>">
                    </div>
                </div>
                <div class="col-8">
                    <div class="mb-3">
                        <label class="form-label">telephone</label>
                        <input type="text" placeholder="telephone" class="form-control" name="telephone" value="<?php
                                                                                                                if (isset($_GET['id'])) {
                                                                                                                    echo $client['telephone'];
                                                                                                                } else {
                                                                                                                    echo "";
                                                                                                                }
                                                                                                                ?>">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="mb-3">
                        <label class="form-label">email</label>
                        <input type="email" placeholder="email" class="form-control" name="email" value="<?php
                                                                                                            if (isset($_GET['id'])) {
                                                                                                                echo $client['email'];
                                                                                                            } else {
                                                                                                                echo "";
                                                                                                            }
                                                                                                            ?>">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="mb-3">
                        <label class="form-label">Adresse</label>
                        <textarea class="form-control" name="adresse"><?php
                                                                        if (isset($_GET['id'])) {
                                                                            echo $client['adresse'];
                                                                        } else {
                                                                            echo "";
                                                                        }
                                                                        ?></textarea>
                    </div>
                </div>
                <div class="col-6">
                    <div class="mb-3">
                        <label class="form-label">Autre Details</label>
                        <textarea class="form-control" name="autres_details"><?php
                                                                                if (isset($_GET['id'])) {
                                                                                    echo $client['autres_details'];
                                                                                } else {
                                                                                    echo "";
                                                                                }
                                                                                ?></textarea>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary" name="modifier">Modifier</button>
            </div>
        </form>
    </div>
</div>
<?php require_once "../inc/footer.php" ?>