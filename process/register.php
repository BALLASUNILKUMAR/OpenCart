<?php
session_start();
require '../includes/db.php';

$name = trim($_POST['name']);
$email = trim($_POST['email']);
$password = $_POST['password'];

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = "Invalid email address.";
    header("Location: ../register.php");
    exit();
}

$hashed = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $email, $hashed);

if ($stmt->execute()) {
    $_SESSION['user_id'] = $stmt->insert_id;
    $_SESSION['role'] = 'customer';
    header("Location: ../index.php");
} else {
    $_SESSION['error'] = "Email already exists.";
    header("Location: ../register.php");
}
