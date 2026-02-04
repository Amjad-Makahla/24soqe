<?php
session_start();
require 'db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user']) || !isset($_POST['car_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'يجب تسجيل الدخول أولاً']);
    exit;
}

$user_id = $_SESSION['user']['id'];
$car_id = $_POST['car_id'];

// تحقق من التكرار
$check = $conn->prepare("SELECT * FROM wish_list WHERE user_id = ? AND car_id = ?");
$check->bind_param("ii", $user_id, $car_id);
$check->execute();
$check_result = $check->get_result();

if ($check_result->num_rows > 0) {
    echo json_encode(['error' => '🚫 السيارة موجودة بالفعل في المفضلة']);
    exit;
}

// الإضافة
$stmt = $conn->prepare("INSERT INTO wish_list (user_id, car_id) VALUES (?, ?)");
$stmt->bind_param("ii", $user_id, $car_id);
if (!$stmt->execute()) {
    echo json_encode(['error' => '❌ فشل في الإضافة: ' . $stmt->error]);
    exit;
}

// جلب بيانات السيارة
$car_stmt = $conn->prepare("
    SELECT cars.id, 
           COALESCE(brands.name_ar, cars.brand) AS brand,
           COALESCE(models.name_ar, cars.model) AS model,
           cars.year, cars.price,
           (SELECT image_path FROM car_images WHERE car_id = cars.id LIMIT 1) AS image
    FROM cars 
    LEFT JOIN brands ON cars.brand_id = brands.id
    LEFT JOIN models ON cars.model_id = models.id
    WHERE cars.id = ?
");
$car_stmt->bind_param("i", $car_id);
$car_stmt->execute();
$car_result = $car_stmt->get_result();

if ($car_result->num_rows > 0) {
    $car_data = $car_result->fetch_assoc();
    echo json_encode(['success' => true, 'car' => $car_data]);
} else {
    echo json_encode(['success' => true, 'car' => null]);
}
?>
