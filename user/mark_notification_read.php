<?php
session_start();
require_once '../includes/db.php';
$user_id = $_SESSION['user_id'];
if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $row = $conn->query("SELECT user_id FROM notifications WHERE id = $id")->fetch_assoc();
    if ($row['user_id'] === null) {
        // Broadcast: insert into notification_reads
        $conn->query("INSERT IGNORE INTO notification_reads (notification_id, user_id) VALUES ($id, $user_id)");
    } else {
        // Personal: update is_read
        $conn->query("UPDATE notifications SET is_read = 1 WHERE id = $id AND user_id = $user_id");
    }
    echo 'ok';
}