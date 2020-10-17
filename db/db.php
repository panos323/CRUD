<?php
session_start();
$host = "localhost";
$dbName = "droneusers";
$user = "root";
$pass = "";

try {
    $dbh = new PDO("mysql:host={$host};dbname={$dbName}", $user, $pass);
} catch (PDOException $e) {
    print("Error: " . $e->getMessage() . "<br>");
    exit();
}

function dbCredentials() {
    $host = "localhost";
    $dbName = "droneusers";
    $user = "root";
    $pass = "";

    try {
        $dbh = new PDO("mysql:host={$host};dbname={$dbName}", $user, $pass);
    } catch (PDOException $e) {
        print("Error: " . $e->getMessage() . "<br>");
        exit();
    }
}
?>