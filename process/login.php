<?php
session_start();
require '../includes/db.php';

$email = trim($_POST['email']);
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT id, name, email, password, role FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// ...existing code...
if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    if (password_verify($password, $row['password'])) {
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['role'] = $row['role'];
        $_SESSION['name'] = $row['name'];
        $_SESSION['email'] = $email;
        if ($row['role'] === 'admin') {
            header("Location: ../admin/dashboard.php");
        } else {
            header("Location: ../user/dashboard.php");
        }
        exit();
    } else {
        $_SESSION['error'] = "Incorrect password.";
        header("Location: ../login.php");
        exit();
    }
} else {
    $_SESSION['error'] = "Account not found.";
    header("Location: ../login.php");
    exit();
}
// ...existing code...
