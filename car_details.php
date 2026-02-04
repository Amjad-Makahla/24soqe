<?php
include 'db.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("الإعلان غير موجود.");
}

$car_id = intval($_GET['id']);

// جلب تفاصيل السيارة
$stmt = $conn->prepare("
    SELECT cars.*, 
           brands.name_ar AS brand_name,
           models.name_ar AS model_name,
           cities.name_ar AS city_name,
           fuel_types.name_ar AS fuel_type_name,
           transmissions.name_ar AS transmission_name,
           engine_sizes.cc AS engine_cc,
           body_types.name_ar AS body_type_name,
           paint_conditions.name_ar AS paint_condition_name
    FROM cars
    LEFT JOIN brands ON cars.brand_id = brands.id
    LEFT JOIN models ON cars.model_id = models.id
    LEFT JOIN cities ON cars.city_id = cities.id
    LEFT JOIN fuel_types ON cars.fuel_type_id = fuel_types.id
    LEFT JOIN transmissions ON cars.transmission_id = transmissions.id
    LEFT JOIN engine_sizes ON cars.engine_size_id = engine_sizes.id
    LEFT JOIN body_types ON cars.body_type_id = body_types.id
    LEFT JOIN paint_conditions ON cars.paint_condition_id = paint_conditions.id
    WHERE cars.id = ?
");
$stmt->bind_param("i", $car_id);
$stmt->execute();
$car = $stmt->get_result()->fetch_assoc();

if (!$car) {
    die("السيارة غير موجودة.");
}

// جلب الصور
$images = $conn->query("SELECT image_path FROM car_images WHERE car_id = $car_id");
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تفاصيل السيارة | 24soqe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700;900&display=swap" rel="stylesheet">
    <style>body { font-family: 'Tajawal', sans-serif; }</style>
</head>
<body class="bg-gray-900 text-white">

<!-- العنوان -->
<div class="text-center py-8">
    <h1 class="text-3xl font-bold text-red-500">🚘 تفاصيل السيارة</h1>
</div>

<!-- معلومات السيارة -->
<div class="max-w-4xl mx-auto bg-white text-black rounded-xl p-6 shadow-lg">
    <!-- صور -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-6">
        <?php while ($img = $images->fetch_assoc()): ?>
            <img src="<?= $img['image_path'] ?>" class="rounded-xl w-full h-48 object-cover border" alt="صورة السيارة">
        <?php endwhile; ?>
    </div>

    <!-- التفاصيل -->
    <table class="w-full text-right rtl">
        <tr><td class="font-bold w-40">الماركة:</td><td><?= $car['brand_name'] ?></td></tr>
        <tr><td class="font-bold">الموديل:</td><td><?= $car['model_name'] ?></td></tr>
        <tr><td class="font-bold">السنة:</td><td><?= $car['year'] ?></td></tr>
        <tr><td class="font-bold">المدينة:</td><td><?= $car['city_name'] ?></td></tr>
        <tr><td class="font-bold">نوع الوقود:</td><td><?= $car['fuel_type_name'] ?></td></tr>
        <tr><td class="font-bold">نوع القير:</td><td><?= $car['transmission_name'] ?></td></tr>
        <tr><td class="font-bold">سعة المحرك:</td><td><?= $car['engine_cc'] ?> CC</td></tr>
        <tr><td class="font-bold">المسافة:</td><td><?= number_format($car['mileage']) ?> كم</td></tr>
        <tr><td class="font-bold">الهيكل:</td><td><?= $car['body_type_name'] ?></td></tr>
        <tr><td class="font-bold">حالة الدهان:</td><td><?= $car['paint_condition_name'] ?></td></tr>
        <tr><td class="font-bold">السعر:</td><td class="text-red-600 font-bold"><?= number_format($car['price']) ?> دينار</td></tr>
        <tr><td class="font-bold">الوصف:</td><td><?= nl2br($car['description']) ?></td></tr>
    </table>
</div>

<!-- زر الرجوع -->
<div class="text-center mt-6">
    <a href="index.html" class="bg-red-600 text-white py-2 px-4 rounded hover:bg-red-700">🔙 الرجوع للرئيسية</a>
</div>

</body>
</html>
