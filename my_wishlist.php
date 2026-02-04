<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user']['id'];

$stmt = $conn->prepare("
    SELECT cars.id, cars.year, cars.price,
           COALESCE(brands.name_ar, 'ماركة غير معروفة') AS brand,
           COALESCE(models.name_ar, 'موديل غير معروف') AS model,
           (SELECT image_path FROM car_images WHERE car_id = cars.id LIMIT 1) AS image
    FROM wish_list 
    JOIN cars ON cars.id = wish_list.car_id 
    LEFT JOIN brands ON cars.brand_id = brands.id
    LEFT JOIN models ON cars.model_id = models.id
    WHERE wish_list.user_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>قائمة المفضلة</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Tajawal', sans-serif; }
  </style>
</head>
<body class="bg-gradient-to-b from-gray-900 to-gray-800 text-white min-h-screen p-6">

  <div class="max-w-7xl mx-auto">

    <!-- رأس الصفحة -->
    <div class="flex items-center justify-between mb-6">
      <h2 class="text-3xl font-bold text-red-500">❤️ سياراتك المفضلة</h2>
      <a href="index.html" class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-xl shadow text-white font-bold">🔙 العودة للرئيسية</a>
    </div>

    <!-- عرض السيارات -->
    <?php if ($result->num_rows === 0): ?>
      <p class="text-center text-gray-300 mt-12 text-lg">🚫 لا توجد سيارات في المفضلة حتى الآن.</p>
    <?php else: ?>
      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php while ($car = $result->fetch_assoc()): ?>
          <?php $image = !empty($car['image']) ? $car['image'] : 'assets/default_car.png'; ?>
          <div class="bg-white text-black rounded-xl shadow-lg overflow-hidden transition duration-300 relative group">
            <a href="car_details.php?id=<?= $car['id'] ?>" class="block hover:opacity-90">
              <img src="<?= htmlspecialchars($image) ?>" class="w-full h-52 object-cover" alt="Car">
              <div class="p-4">
                <h3 class="text-xl font-bold mb-1">
                  <?= htmlspecialchars($car['brand']) ?> <?= htmlspecialchars($car['model']) ?> <?= $car['year'] ?>
                </h3>
                <p class="text-sm text-gray-700">💰 <?= number_format($car['price']) ?> د.أ</p>
              </div>
            </a>
            <form method="POST" action="remove_from_wishlist.php" class="absolute bottom-2 left-4">
              <input type="hidden" name="car_id" value="<?= $car['id'] ?>">
              <button type="submit" class="text-red-500 hover:text-red-700 text-sm bg-white px-3 py-1 rounded-xl shadow">🗑️ إزالة</button>
            </form>
          </div>
        <?php endwhile; ?>
      </div>
    <?php endif; ?>

  </div>

</body>
</html>
