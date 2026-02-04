<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
?>

<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // بيانات الإعلان
    $brand_id = $_POST['brand_id'];
    $model_id = $_POST['model_id'];
    $city_id = $_POST['city_id'];
    $fuel_type_id = $_POST['fuel_type_id'];
    $transmission_id = $_POST['transmission_id'];
    $engine_size_id = $_POST['engine_size_id'];
    $mileage = $_POST['mileage'];
    $body_type_id = $_POST['body_type_id'];
    $paint_condition_id = $_POST['paint_condition_id'];
    $year = $_POST['year'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    // إدخال الإعلان إلى جدول cars
    $stmt = $conn->prepare("INSERT INTO cars (brand_id, model_id, city_id, fuel_type_id, transmission_id, engine_size_id, mileage, body_type_id, paint_condition_id, year, price, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiiiiiiiiis", $brand_id, $model_id, $city_id, $fuel_type_id, $transmission_id, $engine_size_id, $mileage, $body_type_id, $paint_condition_id, $year, $price, $description);
    $stmt->execute();

    $car_id = $stmt->insert_id;

    // حفظ الصور المتعددة في جدول car_images
    $targetDir = "uploads/";
    foreach ($_FILES['images']['tmp_name'] as $index => $tmpName) {
        $fileName = basename($_FILES['images']['name'][$index]);
        $targetFilePath = $targetDir . time() . "_" . $fileName;
        if (move_uploaded_file($tmpName, $targetFilePath)) {
            $stmtImg = $conn->prepare("INSERT INTO car_images (car_id, image_path) VALUES (?, ?)");
            $stmtImg->bind_param("is", $car_id, $targetFilePath);
            $stmtImg->execute();
        }
    }

    echo "<script>alert('تم نشر الإعلان بنجاح!'); window.location.href = 'index.html';</script>";
}
?>
