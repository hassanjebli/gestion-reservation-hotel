<?php
$host = 'localhost';
$dbname = 'php_project';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
} catch (PDOException $e) {
    die("Could not connect to the database $dbname :" . $e->getMessage());
}

function afficher($content){
    echo "<pre>";
    print_r($content);
    echo "</pre>";
}
