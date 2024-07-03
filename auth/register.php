<?php
require_once "../config/database.php";

if (isset($_POST['register'])) {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $username = $_POST['username'];
    $type = $_POST['type'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];

    if (!empty($nom) && !empty($prenom) && !empty($username) && !empty($type) && !empty($password) && !empty($confirm_password)) {

        if ($password != $confirm_password) {
            $messages[] = "Les passwords pas identiques";
        } else {

            $sql0 = "SELECT * FROM users_app WHERE username=?";
            $value0 = [$username];
            $stnmt0 = $conn->prepare($sql0);
            $stnmt0->execute($value0);
            if ($stnmt0->rowCount() > 0) {
                $messages[] = "username deja existe";
            } else {
                $etat_user = "active";

                $sql = "INSERT INTO users_app VALUES (NULL,?,?,?,?,?,?)";
                $values = [$nom, $prenom, $username, $password, $type, $etat_user];

                $stmt = $conn->prepare($sql);
                $stmt->execute($values);
                header("location:../index.php");
            }
        }
    } else {
        $messages[] = "Tous les champs sont obligatoires";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>
    <link rel="stylesheet" href="../assets/css/index.css">

    <link rel="stylesheet" href="../assets/bootstrap-5.3.3-dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 mt-4">
                <div class="card">
                    <div class="card-header bg-dark-blue text-white text-center">
                        <h4>Register</h4>
                    </div>
                    <div class="card-body">
                        <?php
                        if (isset($messages)) {
                            foreach ($messages as $message) {
                        ?>
                                <div class="alert alert-danger text-center" role="alert"><?= $message ?></div>
                        <?php
                            }
                        }
                        ?>
                        <form action="" method="post" autocomplete="off">
                            <div class="row">
                                <div class="form-group mt-3 col-6">
                                    <label for="nom" class="text-dark-blue">Nom</label>
                                    <input type="text" class="form-control" name="nom" placeholder="Enter votre nom">
                                </div>

                                <div class="form-group mt-3 col-6">
                                    <label for="prenom" class="text-dark-blue">Prenom</label>
                                    <input type="text" class="form-control" name="prenom" placeholder="Enter votre prenom">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group mt-3 col-7">
                                    <label for="username" class="text-dark-blue">Username</label>
                                    <input type="text" class="form-control" name="username" placeholder="Enter a username">
                                </div>
                                <div class="form-group mt-3 col-5">
                                    <label for="type" class="text-dark-blue">Type</label>
                                    <select name="type" class="form-control">
                                        <option value="">Type</option>
                                        <option value="manager">Manager</option>
                                        <option value="receptionniste">Réceptionniste</option>
                                        <option value="caissier">Caissier</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group mt-3">
                                <label for="password" class="text-dark-blue">Password</label>
                                <input type="password" class="form-control" name="password" placeholder="Enter a password">
                            </div>
                            <div class="form-group mt-3">
                                <label for="confirm-password" class="text-dark-blue">Confirm Password</label>
                                <input type="password" class="form-control" name="confirm-password" placeholder="Confirm your password">
                            </div>
                            <div class="text-center mt-2">
                            <button type="submit" class="btn btn-dark-blue btn-block" name="register">Register</button>
                            </div>
                        </form>
                        <div class="text-center mt-1">
                            <p class="text-muted">
                                Vous avez déjà un compte ?
                                <span>
                                    <a href="../index.php" class="text-dark-blue font-weight-bold">Se connecter</a>
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/bootstrap-5.3.3-dist/js/bootstrap.bundle.js"></script>
</body>

</html>