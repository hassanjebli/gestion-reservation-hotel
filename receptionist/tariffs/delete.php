<?php
session_start();
require_once "../../config/database.php";

try {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $stmt = $conn->prepare("DELETE FROM tarif_chambre WHERE id_tarif  =?");
        $stmt->execute([$id]);
        header("location:list.php");
        exit();
    }
} catch (PDOException $e) {
    $_SESSION['message']=': tarif déjà lie a une chambre';
    header("location:list.php");
        exit();
}
