<?php
session_start();
require_once '../../config/database.php';


try {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $sqlState = $conn->prepare('DELETE FROM chambre WHERE id_chambre=?');
        $sqlState->execute([$id]);
        $_SESSION['message'] = 'La suppression a ete effectue.';
        header('location:list.php');
        exit();
    }
} catch (PDOException $e) {
    $_SESSION['message'] = 'chambre déjà un objet de réservation.';
    header('location:list.php');
    exit();
}