<?php
session_start();
require_once "../../config/database.php";

try {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $stmt = $conn->prepare("DELETE FROM capacite_chambre WHERE id_capacite =?");
        $stmt->execute([$id]);
        header("location:list.php");
        exit();
    }
} catch (PDOException $e) {
    $_SESSION['message']=': Capacite déjà lie a une chambre';
    header("location:list.php");
        exit();
}
