<?php
session_start();
require_once "../../config/database.php";

try {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $stmt = $conn->prepare("DELETE FROM client WHERE id_client=?");
        $stmt->execute([$id]);
        header("location:list.php");
        exit();
    }
} catch (PDOException $e) {
    $_SESSION['message']=': Client déjà effectué des réservations';
    header("location:list.php");
        exit();
}
