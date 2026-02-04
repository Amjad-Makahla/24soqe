
<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>نشر إعلان جديد | 24soqe</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700;900&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Tajawal', sans-serif; }
  </style>
</head>
<body class="bg-gray-900 text-white">

<!-- Navbar -->
<nav class="flex items-center justify-between px-6 py-3 bg-black shadow-lg">
  <a href="index.html" class="text-white hover:text-red-400">🔙 الرجوع للرئيسية</a>
  <h1 class="text-xl font-bold text-red-500">📢 نشر إعلان جديد</h1>
  <img src="assets/24Log.png" class="w-10 h-10 rounded-full border-2 border-white" alt="Logo">
</nav>

<!-- Form -->
<section class="py-10 px-4 max-w-3xl mx-auto">
  <div class="bg-white text-black p-6 rounded-xl shadow-lg">
    <h2 class="text-2xl font-bold mb-6 text-center text-red-600">نموذج إضافة سيارة</h2>
    <form action="add_car.php" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4">

      <!-- صور -->
      <div class="col-span-2">
        <label class="block mb-1 font-bold">صور السيارة 📷</label>
        <input type="file" name="images[]" multiple class="w-full p-2 border rounded" required>
      </div>

      <!-- الماركة -->
      <div>
        <label class="block mb-1">الماركة</label>
        <select name="brand_id" class="w-full p-2 border rounded" required>
          <option value="">اختر</option>
          <?php
          $q = $conn->query("SELECT id, name_ar FROM brands");
          while($r = $q->fetch_assoc()) echo "<option value='{$r['id']}'>{$r['name_ar']}</option>";
          ?>
        </select>
      </div>

      <!-- الموديل -->
      <div>
        <label class="block mb-1">الموديل</label>
        <select name="model_id" class="w-full p-2 border rounded" required>
          <option value="">اختر</option>
          <?php
          $q = $conn->query("SELECT id, name_ar FROM models");
          while($r = $q->fetch_assoc()) echo "<option value='{$r['id']}'>{$r['name_ar']}</option>";
          ?>
        </select>
      </div>

      <!-- المدينة -->
      <div>
        <label class="block mb-1">المدينة</label>
        <select name="city_id" class="w-full p-2 border rounded" required>
          <option value="">اختر</option>
          <?php
          $q = $conn->query("SELECT id, name_ar FROM cities");
          while($r = $q->fetch_assoc()) echo "<option value='{$r['id']}'>{$r['name_ar']}</option>";
          ?>
        </select>
      </div>

      <!-- نوع الوقود -->
      <div>
        <label class="block mb-1">نوع الوقود</label>
        <select name="fuel_type_id" class="w-full p-2 border rounded" required>
          <option value="">اختر</option>
          <?php
          $q = $conn->query("SELECT id, name_ar FROM fuel_types");
          while($r = $q->fetch_assoc()) echo "<option value='{$r['id']}'>{$r['name_ar']}</option>";
          ?>
        </select>
      </div>

      <!-- نوع القير -->
      <div>
        <label class="block mb-1">نوع القير</label>
        <select name="transmission_id" class="w-full p-2 border rounded" required>
          <option value="">اختر</option>
          <?php
          $q = $conn->query("SELECT id, name_ar FROM transmissions");
          while($r = $q->fetch_assoc()) echo "<option value='{$r['id']}'>{$r['name_ar']}</option>";
          ?>
        </select>
      </div>

      <!-- سعة المحرك -->
      <div>
        <label class="block mb-1">سعة المحرك</label>
        <select name="engine_size_id" class="w-full p-2 border rounded" required>
          <option value="">اختر</option>
          <?php
    $q = $conn->query("SELECT id, cc FROM engine_sizes");
while($r = $q->fetch_assoc()) echo "<option value='{$r['id']}'>{$r['cc']} CC</option>";

          ?>
        </select>
      </div>

      <!-- المسافة -->
      <div>
        <label class="block mb-1">المسافة المقطوعة (كم)</label>
        <input type="number" name="mileage" class="w-full p-2 border rounded" required>
      </div>

      <!-- الهيكل -->
      <div>
        <label class="block mb-1">نوع الهيكل</label>
        <select name="body_type_id" class="w-full p-2 border rounded" required>
          <option value="">اختر</option>
          <?php
          $q = $conn->query("SELECT id, name_ar FROM body_types");
          while($r = $q->fetch_assoc()) echo "<option value='{$r['id']}'>{$r['name_ar']}</option>";
          ?>
        </select>
      </div>

      <!-- الدهان -->
      <div>
        <label class="block mb-1">حالة الدهان</label>
        <select name="paint_condition_id" class="w-full p-2 border rounded" required>
          <option value="">اختر</option>
          <?php
          $q = $conn->query("SELECT id, name_ar FROM paint_conditions");
          while($r = $q->fetch_assoc()) echo "<option value='{$r['id']}'>{$r['name_ar']}</option>";
          ?>
        </select>
      </div>

      <!-- سنة الصنع -->
      <div>
        <label class="block mb-1">سنة الصنع</label>
        <input type="number" name="year" class="w-full p-2 border rounded" required>
      </div>

      <!-- السعر -->
      <div>
        <label class="block mb-1">السعر (دينار)</label>
        <input type="number" name="price" class="w-full p-2 border rounded" required>
      </div>

      <!-- وصف -->
      <div class="col-span-2">
        <label class="block mb-1">الوصف</label>
        <textarea name="description" rows="3" class="w-full p-2 border rounded" required></textarea>
      </div>

      <!-- زر النشر -->
      <div class="col-span-2">
        <button type="submit" class="bg-red-600 text-white py-3 px-6 rounded-xl hover:bg-red-700 font-bold w-full">
          🚀 نشر الإعلان
        </button>
      </div>

    </form>
  </div>
</section>

</body>
</html>
