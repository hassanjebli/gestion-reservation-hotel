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

$statement = $conn->prepare("SELECT * FROM users_app");

$statement->execute();
$users = $statement->fetchAll(PDO::FETCH_ASSOC);

?>


<?php include "../inc/header.php" ?>
<?php include "../inc/sidebar.php" ?>

<!-- Page Content -->
<div id="page-content-wrapper" class="main-content w-100">
    <button class="btn btn-primary mb-4" id="menu-toggle"><i class="fas fa-bars"></i></button>
    <h1 class="text-center">Liste Des Utilisateurs</h1>
    <div class="text-start">
        <a href="create.php" class="m-3 btn btn-success"><i class="fa fa-add"></i> Add User</a>
    </div>
    <div class="container text-center w-100">
        <?php echo $_SESSION['message'] ?? "";
        unset($_SESSION['message']) ?>
        <div class="table-responsive w-100">
            <table class="table table-striped table-hover w-100">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prenom</th>
                        <th>Type</th>
                        <th>Etat</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= $user['nom'] ?></td>
                            <td><?= $user['prenom'] ?></td>
                            <td><?= $user['type'] ?></td>
                            <td>
                                <span
                                    class="<?php echo ($user['etat'] == 'Activé') ? 'badge bg-success' : 'badge bg-danger'; ?>">
                                    <?php echo $user['etat']; ?>
                                </span>
                            </td>

                            <td>
                                <a href="view.php?id=<?= $user['id_user'] ?>" class="btn btn-primary btn-sm"><i
                                        class="fa fa-eye"></i></a>
                                <a href="delete.php?id=<?= $user['id_user'] ?>" class="btn btn-danger btn-sm"><i
                                        class="fa fa-trash"></i></a>
                                <a href="edit.php?id=<?= $user['id_user'] ?>" class="btn btn-warning btn-sm"><i
                                        class="fa fa-edit"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- /#page-content-wrapper -->
<?php include "../inc/footer.php" ?>