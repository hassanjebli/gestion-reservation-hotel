<?php
session_start();
require_once "config/database.php";

if (isset($_SESSION['user'])) {
  $statement = $conn->prepare("SELECT type FROM users_app WHERE id_user=?");
  $statement->execute([$_SESSION['user']['id']]);
  $type = $statement->fetch(PDO::FETCH_ASSOC)['type'] ?? "";

  switch ($type) {
    case 'manager':
      header("location:manager/index.php");
      break;
    case 'receptionniste':
      header("location:receptionist/index.php");
      break;
    case 'caissier':
      header("location:cashier/index.php");
      break;
  }
}

if (isset($_POST['login'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  if (!empty($username) && !empty($password)) {
    $sql = "SELECT * FROM users_app WHERE username=? AND password=?";
    $values = [$username, $password];
    $stmt = $conn->prepare($sql);
    $stmt->execute($values);

    if ($stmt->rowCount() > 0) {
      $user = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($user['etat'] == "Activé") {
        if ($user['type'] == "manager") {
          $_SESSION['user']['id'] = $user['id_user'];
          header("location:manager/index.php");
        } elseif ($user['type'] == "receptionniste") {
          $_SESSION['user']['id'] = $user['id_user'];
          header("location:receptionist/index.php");
        } elseif ($user['type'] == "caissier") {
          $_SESSION['user']['id'] = $user['id_user'];
          header("location:cashier/index.php");
        } else {
          $messages[] = "L'utilisateur n est pas trouve";
        }
      } else {
        $messages[] = $user['nom'] . " votre etat est Bloqué";
      }
    } else {
      $messages[] = "username ou password sont incorrects";
    }
  } else {
    $messages[] = "tous les champs sont obligatoires";
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Page</title>
  <link rel="stylesheet" href="assets/css/index.css">
  <link rel="stylesheet" href="assets/bootstrap-5.3.3-dist/css/bootstrap.min.css">
  <style>
    .hidden {
      display: none;
    }
  </style>
</head>

<body>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div id="demo">
          <div class="mt-3">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="showDemoAccount">
              <label class="form-check-label" style="color:white;">
                Show Demo Account
              </label>
            </div>
          </div>
          <div id="demoAccount" class="text-center bg-info rounded mt-2 hidden p-1">
            <p><strong class="text-danger">Demo Compte :</strong> <br> Username : <strong
                class="text-success">manager</strong> <br>Password : <strong class="text-success">123</strong></p>
          </div>
        </div>
        <div class="card">
          <div class="card-header bg-dark-blue text-white text-center">
            <h4>Login</h4>
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
              <div class="form-group mt-2">
                <label for="username" class="text-dark-blue">Username</label>
                <input type="text" class="form-control" name="username" placeholder="Enter username">
              </div>
              <div class="form-group mt-2">
                <label for="password" class="text-dark-blue">Password</label>
                <input type="password" class="form-control" name="password" placeholder="Enter password">
              </div>
              <div class="text-center mt-3">
                <button type="submit" class="btn btn-dark-blue btn-block" name="login">Login</button>
              </div>
            </form>
          </div>
        </div>

      </div>
    </div>
  </div>

  <script src="assets/bootstrap-5.3.3-dist/js/bootstrap.bundle.js"></script>
  <script>
    document.getElementById('showDemoAccount').addEventListener('change', function () {
      var demoAccount = document.getElementById('demoAccount');
      if (this.checked) {
        demoAccount.classList.remove('hidden');
      } else {
        demoAccount.classList.add('hidden');
      }
    });
  </script>
</body>

</html>