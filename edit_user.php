<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

require 'db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("معرّف المستخدم غير صالح.");
}

$user_id = $_GET['id'];
$error = '';
$success = '';

// جلب بيانات المستخدم
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$userData = $stmt->get_result()->fetch_assoc();

if (!$userData) {
    die("المستخدم غير موجود.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $role = $_POST['role'];
    $password = trim($_POST['password']); // ممكن يكون فاضي

    if (empty($name) || empty($email)) {
        $error = "يرجى إدخال الاسم والبريد الإلكتروني.";
    } else {
        // تحديث البيانات
        if (!empty($password)) {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, password = ?, role = ? WHERE id = ?");
            $stmt->bind_param("ssssi", $name, $email, $hashed, $role, $user_id);
        } else {
            $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?");
            $stmt->bind_param("sssi", $name, $email, $role, $user_id);
        }

        if ($stmt->execute()) {
            $success = "✅ تم تحديث بيانات المستخدم!";
            // جلب البيانات المحدّثة
            $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $userData = $stmt->get_result()->fetch_assoc();
        } else {
            $error = "حدث خطأ أثناء التحديث.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>تعديل مستخدم</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white min-h-screen flex items-center justify-center p-6">

  <div class="bg-gray-800 p-6 rounded w-full max-w-md">
    <h2 class="text-xl font-bold mb-4">✏️ تعديل مستخدم</h2>

    <?php if ($error): ?>
      <div class="bg-red-500 text-white px-4 py-2 rounded mb-4 text-sm"><?= $error ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
      <div class="bg-green-600 text-white px-4 py-2 rounded mb-4 text-sm"><?= $success ?></div>
      <a href="admin_dashboard.php" class="text-blue-400 underline text-sm">⬅ العودة للوحة التحكم</a>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-3">
        <label class="block text-sm">الاسم الكامل</label>
        <input type="text" name="name" value="<?= htmlspecialchars($userData['name']) ?>" class="w-full p-2 rounded bg-gray-700 text-white" required>
      </div>
      <div class="mb-3">
        <label class="block text-sm">البريد الإلكتروني</label>
        <input type="email" name="email" value="<?= htmlspecialchars($userData['email']) ?>" class="w-full p-2 rounded bg-gray-700 text-white" required>
      </div>
      <div class="mb-3">
        <label class="block text-sm">كلمة مرور جديدة <small class="text-gray-400">(اتركها فارغة إن لم ترد تغييرها)</small></label>
        <input type="password" name="password" class="w-full p-2 rounded bg-gray-700 text-white">
      </div>
      <div class="mb-4">
        <label class="block text-sm">الصلاحية</label>
        <select name="role" class="w-full p-2 rounded bg-gray-700 text-white" required>
          <option value="user" <?= $userData['role'] === 'user' ? 'selected' : '' ?>>مستخدم</option>
          <option value="admin" <?= $userData['role'] === 'admin' ? 'selected' : '' ?>>مشرف</option>
        </select>
      </div>
      <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white w-full py-2 rounded">تحديث</button>
    </form>
  </div>

</body>
</html>
