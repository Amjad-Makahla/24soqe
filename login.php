<?php
session_start();
require 'db.php';
$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $error = "الرجاء إدخال البريد الإلكتروني وكلمة المرور.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            if ($user['status'] !== 'active') {
                $error = "❌ تم تعطيل حسابك من قبل الإدارة.";
            } else {
                $_SESSION['user'] = $user;

                // توجيه حسب الدور
                if ($user['role'] === 'admin') {
                    $_SESSION['is_admin'] = true;
                    echo "<script>
                        localStorage.setItem('loggedIn', 'true');
                        window.location.href='admin_dashboard.php';
                    </script>";
                } else {
                    echo "<script>
                        localStorage.setItem('loggedIn', 'true');
                        window.location.href='index.html';
                    </script>";
                }

                exit;
            }
        } else {
            $error = "بيانات الدخول غير صحيحة.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>تسجيل الدخول</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

  <div class="bg-white p-8 rounded-xl shadow-md w-full max-w-md text-right">
    <h2 class="text-2xl font-bold mb-6 text-center">تسجيل الدخول</h2>

    <?php if ($error): ?>
      <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4 text-sm"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-4">
        <label class="block text-sm mb-1">البريد الإلكتروني</label>
        <input type="email" name="email" class="w-full px-4 py-2 border rounded" required>
      </div>

      <div class="mb-6">
        <label class="block text-sm mb-1">كلمة المرور</label>
        <input type="password" name="password" class="w-full px-4 py-2 border rounded" required>
      </div>

      <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded">دخول</button>
    </form>
  </div>
</body>
</html>
