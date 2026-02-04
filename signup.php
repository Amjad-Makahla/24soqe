<?php
require 'db.php';
$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($name) || empty($email) || empty($password)) {
        $error = "الرجاء ملء جميع الحقول.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $check = $stmt->get_result();

        if ($check->num_rows > 0) {
            $error = "البريد الإلكتروني مستخدم بالفعل.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $hash);
            if ($stmt->execute()) {
                $success = "تم التسجيل بنجاح! يمكنك الآن تسجيل الدخول.";
            } else {
                $error = "حدث خطأ أثناء التسجيل.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>تسجيل جديد</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

  <div class="bg-white p-8 rounded-xl shadow-md w-full max-w-md text-right">
    <h2 class="text-2xl font-bold mb-6 text-center">تسجيل جديد</h2>

    <?php if ($error): ?>
      <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4 text-sm"><?= $error ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
      <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4 text-sm"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-4">
        <label class="block text-sm mb-1">الاسم</label>
        <input type="text" name="username" class="w-full px-4 py-2 border rounded" required>
      </div>
      <div class="mb-4">
        <label class="block text-sm mb-1">البريد الإلكتروني</label>
        <input type="email" name="email" class="w-full px-4 py-2 border rounded" required>
      </div>
      <div class="mb-6">
        <label class="block text-sm mb-1">كلمة المرور</label>
        <input type="password" name="password" class="w-full px-4 py-2 border rounded" required>
      </div>
      <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded">تسجيل</button>
    </form>
  </div>
</body>
</html>
