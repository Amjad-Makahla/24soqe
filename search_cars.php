<?php
include 'db.php';

$filters = [];
$params = [];

// فلترة حسب الماركة
if (!empty($_GET['brand'])) {
  $filters[] = "brand_id = ?";
  $params[] = $_GET['brand'];
}

// فلترة حسب الموديل
if (!empty($_GET['model'])) {
  $filters[] = "model_id = ?";
  $params[] = $_GET['model'];
}

// المدينة
if (!empty($_GET['city'])) {
  $filters[] = "city_id = ?";
  $params[] = $_GET['city'];
}

// نوع الوقود
if (!empty($_GET['fuel'])) {
  $filters[] = "fuel_type_id = ?";
  $params[] = $_GET['fuel'];
}

// السعر
if (!empty($_GET['price_min'])) {
  $filters[] = "CAST(price AS UNSIGNED) >= ?";
  $params[] = $_GET['price_min'];
}
if (!empty($_GET['price_max'])) {
  $filters[] = "CAST(price AS UNSIGNED) <= ?";
  $params[] = $_GET['price_max'];
}

$where = "";
if (!empty($filters)) {
  $where = "WHERE " . implode(" AND ", $filters);
}

$sql = "SELECT * FROM cars $where ORDER BY created_at DESC LIMIT 50";
$stmt = $conn->prepare($sql);

// ربط القيم
if (!empty($params)) {
  $types = str_repeat("s", count($params)); // كلهم نصوص لأنهم جايين من URL
  $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$cars = [];
while ($row = $result->fetch_assoc()) {
  $cars[] = $row;
}

header('Content-Type: application/json');
echo json_encode($cars);

$stmt->close();
$conn->close();
