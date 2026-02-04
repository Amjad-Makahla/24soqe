<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
require 'db.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = $_POST['role'];

    if (empty($name) || empty($email) || empty($password)) {
        $error = "يرجى تعبئة جميع الحقول.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "البريد الإلكتروني مستخدم مسبقًا.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $insert = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
            $insert->bind_param("ssss", $name, $email, $hashedPassword, $role);
            $insert->execute();
            $success = "✅ تم إنشاء المستخدم بنجاح!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>إضافة مستخدم</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white min-h-screen flex items-center justify-center p-6">

  <div class="bg-gray-800 p-6 rounded w-full max-w-md">
    <h2 class="text-xl font-bold mb-4">➕ إضافة مستخدم جديد</h2>

    <?php if ($error): ?>
      <div class="bg-red-500 text-white px-4 py-2 rounded mb-4 text-sm"><?= $error ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
      <div class="bg-green-600 text-white px-4 py-2 rounded mb-4 text-sm"><?= $success ?></div>
      <a href="admin_dashboard.php" class="text-blue-400 underline text-sm">⬅ العودة للوحة التحكم</a>
    <?php else: ?>
    <form method="POST">
      <div class="mb-3">
        <label class="block text-sm">الاسم الكامل</label>
        <input type="text" name="name" class="w-full p-2 rounded bg-gray-700 text-white" required>
      </div>
      <div class="mb-3">
        <label class="block text-sm">البريد الإلكتروني</label>
        <input type="email" name="email" class="w-full p-2 rounded bg-gray-700 text-white" required>
      </div>
      <div class="mb-3">
        <label class="block text-sm">كلمة المرور</label>
        <input type="password" name="password" class="w-full p-2 rounded bg-gray-700 text-white" required>
      </div>
      <div class="mb-4">
        <label class="block text-sm">الصلاحية</label>
        <select name="role" class="w-full p-2 rounded bg-gray-700 text-white" required>
          <option value="user">مستخدم</option>
          <option value="admin">مشرف</option>
        </select>
      </div>
      <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white w-full py-2 rounded">حفظ</button>
    </form>
    <?php endif; ?>
  </div>

</body>
</html>
