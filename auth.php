<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>تسجيل الدخول / تسجيل حساب - 24soqe</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Tajawal', sans-serif; }
    .active-tab { background-color: #dc2626; color: white; }
  </style>
</head>
<body class="bg-gray-900 text-white min-h-screen flex items-center justify-center">

<div class="w-full max-w-md bg-gray-800 p-8 rounded-xl shadow-lg">
  <div class="flex justify-around mb-6">
    <button onclick="showTab('login')" id="tab-login" class="w-1/2 py-2 font-bold rounded-r-xl active-tab">تسجيل الدخول</button>
    <button onclick="showTab('signup')" id="tab-signup" class="w-1/2 py-2 font-bold rounded-l-xl bg-gray-700">إنشاء حساب</button>
  </div>

  <!-- Login Form -->
  <form id="form-login" method="POST" action="login.php" class="space-y-4">
    <input type="email" name="email" placeholder="البريد الإلكتروني" class="w-full p-3 rounded bg-gray-700 text-white" required>
    <input type="password" name="password" placeholder="كلمة المرور" class="w-full p-3 rounded bg-gray-700 text-white" required>
    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 py-2 rounded font-bold">دخول</button>
  </form>

  <!-- Signup Form -->
  <form id="form-signup" method="POST" action="signup.php" class="space-y-4 hidden">
    <input type="text" name="name" placeholder="الاسم الكامل" class="w-full p-3 rounded bg-gray-700 text-white" required>
    <input type="email" name="email" placeholder="البريد الإلكتروني" class="w-full p-3 rounded bg-gray-700 text-white" required>
    <input type="password" name="password" placeholder="كلمة المرور" class="w-full p-3 rounded bg-gray-700 text-white" required>
    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 py-2 rounded font-bold">تسجيل</button>
  </form>
</div>

<script>
function showTab(tab) {
  const loginForm = document.getElementById('form-login');
  const signupForm = document.getElementById('form-signup');
  const loginTab = document.getElementById('tab-login');
  const signupTab = document.getElementById('tab-signup');

  if (tab === 'login') {
    loginForm.classList.remove('hidden');
    signupForm.classList.add('hidden');
    loginTab.classList.add('active-tab');
    signupTab.classList.remove('active-tab');
    echo "<script>localStorage.setItem('loggedIn', 'true'); window.location.href='index.html';</script>";
  } else {
    loginForm.classList.add('hidden');
    signupForm.classList.remove('hidden');
    loginTab.classList.remove('active-tab');
    signupTab.classList.add('active-tab');
  }

}
</script>

</body>
</html>
