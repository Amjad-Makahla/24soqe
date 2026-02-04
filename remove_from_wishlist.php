<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user']) || !isset($_POST['car_id'])) {
    // جرب تطبع الجلسة لتتأكد منها
    echo json_encode(['error' => 'يجب تسجيل الدخول أولاً']);
    exit;
}

$user_id = $_SESSION['user']['id'];
$car_id = $_POST['car_id'];

$stmt = $conn->prepare("DELETE FROM wish_list WHERE user_id = ? AND car_id = ?");
$stmt->bind_param("ii", $user_id, $car_id);
if ($stmt->execute()) {
    header("Location: my_wishlist.php");
    exit;
} else {
    echo json_encode(['error' => 'فشل في الحذف']);
    exit;
}
?>
