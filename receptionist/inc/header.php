<?php



if (!isset($_SESSION["user"])) {
    header("location:../index.php");
}

$stmt = $conn->prepare("SELECT * FROM users_app WHERE id_user=?");
$stmt->execute([$_SESSION['user']['id']]);

$reception = $stmt->fetch(PDO::FETCH_ASSOC);

if ($reception['type']!='receptionniste') {
    header('location:../../index.php');
    exit();
}


$statement = $conn->prepare("SELECT * FROM client");
$statement->execute();
$clients = $statement->fetchAll(PDO::FETCH_ASSOC);


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reception</title>
    <link href="../../assets/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
</head>

<body>
    <div class="d-flex" id="wrapper">