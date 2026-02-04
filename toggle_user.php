<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    die("Unauthorized");
}

require 'db.php';

if (isset($_GET['id'], $_GET['action']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    $action = $_GET['action'];

    $newStatus = ($action === 'disable') ? 'disabled' : 'active';

    $stmt = $conn->prepare("UPDATE users SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $newStatus, $id);
    $stmt->execute();
}

header("Location: admin_dashboard.php");
exit;
?>
