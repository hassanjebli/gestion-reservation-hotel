<?php

session_start();
require_once "../../config/database.php";

if (!isset($_SESSION["user"])) {
    header("location:../../index.php");
}

if (isset($_GET['id'])) {
    if ($_GET['id'] == $_SESSION['user']['id']) {
        $_SESSION['message'] = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Attention!</strong> vous etes en train de utiliser ce compte...donc tu peux pas le supprimer
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>';
        header('location:list.php');
        exit;
    } else {
        $id = $_GET['id'];
        $statement = $conn->prepare("DELETE FROM users_app WHERE id_user=?");
        $statement->execute([$id]);
        header("location:list.php");
        exit();
    }

}
