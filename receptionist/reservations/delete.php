<?php
session_start();
require_once "../../config/database.php";
if (!isset($_SESSION["user"])) {
    header("location:../index.php");
}


try {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $stmt = $conn->prepare("DELETE FROM reservation WHERE id_reservation=?");
        $stmt->execute([$id]);
        header("location:list.php");
        exit();
    }
} catch (PDOException $e) {
    $_SESSION['message'] = 'One peut pas';
    header("location:list.php");
    exit();
}
