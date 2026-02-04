<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

require 'db.php';

// الإحصائيات
$adsCount = $conn->query("SELECT COUNT(*) as count FROM cars")->fetch_assoc()['count'];
$usersCount = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];

// جلب البيانات
$cars = $conn->query("SELECT * FROM cars ORDER BY created_at DESC LIMIT 50");
$users = $conn->query("SELECT * FROM users ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>لوحة تحكم المشرف</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .tab-content { display: none; }
    .tab-active { display: block; }
  </style>
</head>
<body class="bg-gray-900 text-white min-h-screen">

  <!-- شريط علوي -->
  <div class="bg-gray-800 p-4 flex justify-between items-center shadow">
    <h1 class="text-2xl font-bold text-red-500">لوحة تحكم المشرف</h1>
    <div>
      <span class="text-sm mr-4">مرحباً، <?= $_SESSION['user']['name'] ?></span>
      <a href="logout.php" class="bg-red-600 text-white px-4 py-1 rounded hover:bg-red-700">تسجيل الخروج</a>
    </div>
  </div>

  <!-- محتوى -->
  <div class="p-6 space-y-6">

    <!-- إحصائيات -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
      <div class="bg-gray-800 p-4 rounded text-center">
        <p class="text-sm text-gray-400">عدد الإعلانات</p>
        <h2 class="text-2xl font-bold text-green-400"><?= $adsCount ?></h2>
      </div>
      <div class="bg-gray-800 p-4 rounded text-center">
        <p class="text-sm text-gray-400">عدد المستخدمين</p>
        <h2 class="text-2xl font-bold text-yellow-400"><?= $usersCount ?></h2>
      </div>
    </div>

    <!-- تبويبات -->
    <div class="flex gap-4 mt-4 border-b border-gray-600 pb-2">
      <button onclick="showTab('ads')" class="tab-button text-blue-400 font-semibold">📢 الإعلانات</button>
      <button onclick="showTab('users')" class="tab-button text-blue-400 font-semibold">👥 إدارة المستخدمين</button>
    </div>

    <!-- تبويب الإعلانات -->
    <div id="tab-ads" class="tab-content tab-active bg-gray-800 p-4 rounded">
      <h2 class="text-xl font-bold mb-4">أحدث الإعلانات</h2>
      <table class="w-full text-right text-sm">
        <thead class="text-gray-300 border-b border-gray-700">
          <tr>
            <th class="py-2">الماركة</th>
            <th>الموديل</th>
            <th>السنة</th>
            <th>السعر</th>
            <th>تاريخ الإضافة</th>
            <th>خيارات</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($car = $cars->fetch_assoc()): ?>
            <tr class="border-b border-gray-700 hover:bg-gray-700/50">
              <td class="py-2"><?= $car['brand'] ?></td>
              <td><?= $car['model'] ?></td>
              <td><?= $car['year'] ?></td>
              <td><?= $car['price'] ?> د.أ</td>
              <td><?= date('Y-m-d', strtotime($car['created_at'])) ?></td>
              <td>
                <a href="delete_car.php?id=<?= $car['id'] ?>" class="text-red-500 hover:underline">حذف</a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

    <!-- تبويب المستخدمين -->
    <div id="tab-users" class="tab-content bg-gray-800 p-4 rounded">
      <h2 class="text-xl font-bold mb-4">قائمة المستخدمين</h2>
      <a href="add_user.php" class="bg-green-600 hover:bg-green-700 text-white text-sm px-4 py-2 rounded inline-block mb-4">
➕ إضافة مستخدم جديد
</a>

      <table class="w-full text-right text-sm">
        <thead class="text-gray-300 border-b border-gray-700">
          <tr>
            <th class="py-2">الاسم</th>
            <th>البريد</th>
            <th>الدور</th>
            <th>تاريخ التسجيل</th>
            <th>خيارات</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($user = $users->fetch_assoc()): ?>
            <tr class="border-b border-gray-700 hover:bg-gray-700/50">
              <td class="py-2"><?= $user['name'] ?></td>
              <td><?= $user['email'] ?></td>
              <td><?= $user['role'] === 'admin' ? 'مشرف' : 'مستخدم' ?></td>
              <td><?= date('Y-m-d', strtotime($user['created_at'])) ?></td>
              <td>
                <?php if ($user['id'] != $_SESSION['user']['id']): ?>
                  <a href="delete_user.php?id=<?= $user['id'] ?>" class="text-red-500 hover:underline">حذف</a>
                <?php else: ?>
                  <span class="text-gray-500 text-xs">(أنت)</span>
                <?php endif; ?>
              </td>
              <td>
  <?php if ($user['id'] != $_SESSION['user']['id']): ?>
    <a href="edit_user.php?id=<?= $user['id'] ?>" class="text-blue-400 hover:underline mr-2">✏️ تعديل</a>
    <a href="delete_user.php?id=<?= $user['id'] ?>" class="text-red-500 hover:underline">🗑️ حذف</a>
  <?php else: ?>
    <span class="text-gray-500 text-xs">(أنت)</span>
  <?php endif; ?>
</td>
<td>
  <?php if ($user['id'] != $_SESSION['user']['id']): ?>
    <a href="edit_user.php?id=<?= $user['id'] ?>" class="text-blue-400 hover:underline mr-2">✏️</a>
    <a href="delete_user.php?id=<?= $user['id'] ?>" class="text-red-500 hover:underline mr-2">🗑️</a>
    <?php if ($user['status'] === 'active'): ?>
      <a href="toggle_user.php?id=<?= $user['id'] ?>&action=disable" class="text-yellow-400 hover:underline">🔒 تعطيل</a>
    <?php else: ?>
      <a href="toggle_user.php?id=<?= $user['id'] ?>&action=enable" class="text-green-400 hover:underline">🔓 تفعيل</a>
    <?php endif; ?>
  <?php else: ?>
    <span class="text-gray-500 text-xs">(أنت)</span>
  <?php endif; ?>
</td>
<th>الحالة</th>
...
<td><?= $user['status'] === 'active' ? '✅ نشط' : '⛔️ معطّل' ?></td>

            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

  </div>

  <!-- جافاسكربت للتبويبات -->
  <script>
    function showTab(tab) {
      document.querySelectorAll('.tab-content').forEach(e => e.classList.remove('tab-active'));
      document.getElementById('tab-' + tab).classList.add('tab-active');
    }
  </script>
</body>
</html>
