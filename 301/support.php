<?php
session_start();

// ================================
// 1. ตรวจสอบสถานะผู้ใช้
// ================================
$is_logged_in = isset($_SESSION['id_account']);
$username = $_SESSION['username_account'] ?? 'Guest';
$role = $_SESSION['role_account'] ?? 'guest';

// ================================
// 2. กำหนดลิงก์หน้าหลักและหน้าตั้งค่า
// ================================
if ($role === 'admin') {
    $hub_link = 'adminhub.php';
    $settings_link = 'admin_settings.php';
} elseif ($role === 'member') {
    $hub_link = 'memberhub.php';
    $settings_link = 'member_settings.php';
} else {
    $hub_link = 'index.php';
    $settings_link = 'login.php';
}

// ================================
// 3. ตรวจสอบการ Logout
// ================================
if (isset($_GET['logout']) && $is_logged_in) {
    session_destroy();
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Support | ศูนย์ช่วยเหลือ</title>
<style>
/* ======================== */
/* โครงสร้างพื้นฐาน */
/* ======================== */
body {
  margin: 0;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background: #121212;
  color: #fff;
  line-height: 1.6;
}
a {
  color: #ff4655;
  text-decoration: none;
  transition: color 0.3s;
}
a:hover { color: #e53846; }

/* ======================== */
/* Navbar */
/* ======================== */
nav {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 12px 24px;
  background: #1f1f1f;
  border-bottom: 1px solid #333;
}
nav .logo {
  font-size: 24px;
  font-weight: bold;
  color: #ff4655;
  text-decoration: none;
  margin-right: 30px;
}
nav ul { list-style: none; display: flex; margin: 0; padding: 0; }
nav ul li { margin-left: 20px; }
nav ul li a {
  text-decoration: none;
  color: #fff;
  font-weight: 500;
  transition: 0.3s;
}
nav ul li a:hover { color: #ff4655; }

nav .user-info { position: relative; }
nav .user-info button {
  background: #444;
  border-radius: 5px;
  border: none;
  color: #fff;
  font-weight: bold;
  cursor: pointer;
  padding: 6px 12px;
  font-size: 14px;
  display: flex;
  align-items: center;
  gap: 4px;
  transition: 0.3s;
}
nav .user-info button:hover { color: #ff4655; }

nav .user-info .dropdown {
  display: none;
  position: absolute;
  right: 0;
  top: calc(100% + 5px);
  background: #181818;
  border: 1px solid #444;
  border-radius: 6px;
  min-width: 150px;
  overflow: hidden;
  box-shadow: 0 5px 15px rgba(0,0,0,0.5);
  animation: fadeDown 0.3s ease forwards;
  z-index: 100;
}
nav .user-info .dropdown a {
  display: block;
  padding: 10px 16px;
  color: #fff;
  text-decoration: none;
  font-weight: 500;
  transition: 0.2s;
}
nav .user-info .dropdown a:hover {
  background: #ff4655;
  color: #fff;
}

@keyframes fadeDown {
  0% { opacity: 0; transform: translateY(-10px); }
  100% { opacity: 1; transform: translateY(0); }
}

/* ======================== */
/* ส่วนเนื้อหา Support */
/* ======================== */
.content {
  padding: 40px;
  text-align: center;
  max-width: 700px;
  margin: 0 auto;
}
.content h1 {
  color: #ff4655;
  font-size: 36px;
  border-bottom: 2px solid #333;
  padding-bottom: 15px;
  margin-bottom: 30px;
}
.contact-card {
  background: #1f1f1f;
  border: 1px solid #333;
  border-radius: 10px;
  overflow: hidden;
  padding: 30px;
  margin-top: 30px;
  box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}
.contact-card h2 {
  color: #fff;
  margin-top: 0;
  font-size: 24px;
}
.contact-card p {
  font-size: 18px;
  color: #ccc;
  margin-bottom: 10px;
}
.contact-card strong {
  color: #ff4655;
}
</style>
</head>
<body>

<nav>
  <div style="display:flex; align-items:center;">
    <a href="<?= htmlspecialchars($hub_link) ?>" class="logo">LGAME</a>
    <ul>
      <li><a href="games.php">เกม</a></li>
      <li><a href="news.php">ข่าวสาร</a></li>
      <li><a href="support.php" style="color:#ff4655;">Support</a></li>
    </ul>
  </div>

  <?php if ($is_logged_in): ?>
    <div class="user-info">
      <button onclick="toggleDropdown()"><?= htmlspecialchars($username) ?> ▼</button>
      <div class="dropdown" id="userDropdown">
        <a href="<?= htmlspecialchars($settings_link) ?>">Setting</a>
        <a href="?logout=1">Logout</a>
      </div>
    </div>
  <?php else: ?>
    <a href="login.php" style="font-weight: bold; background: #ff4655; padding: 6px 12px; border-radius: 4px;">Login</a>
  <?php endif; ?>
</nav>

<div class="content">
  <h1>หน้าช่วยเหลือ (Support Center)</h1>
  <p>เราพร้อมช่วยเหลือคุณในทุกปัญหาที่เกี่ยวข้องกับการใช้งานระบบและบริการของเรา</p>

  <div class="contact-card">
    <h2>ช่องทางการติดต่อ</h2>
    <p>หากมีข้อสงสัยหรือปัญหาใด ๆ กรุณาติดต่อผู้ดูแลระบบโดยตรง:</p>
    <p><strong>อีเมล:</strong> support@lgame.com</p>
    <p><strong>โทรศัพท์:</strong> 08x-xxx-xxxx</p>
    <p style="margin-top: 25px; font-size: 16px;">(คุณยังสามารถส่งข้อความถึงแอดมินผ่านช่องทาง Social Media หลักของเราได้)</p>
  </div>
</div>

<script>
function toggleDropdown(){
  const dropdown = document.getElementById('userDropdown');
  if (dropdown) { 
    dropdown.style.display = (window.getComputedStyle(dropdown).display === 'block') ? 'none' : 'block';
  }
}
window.onclick = function(e){
  if(!e.target.closest('.user-info')){
    const dropdown = document.getElementById('userDropdown');
    if (dropdown && window.getComputedStyle(dropdown).display === 'block') {
      dropdown.style.display = 'none';
    }
  }
}
</script>

</body>
</html>
