<?php
session_start();
if(!isset($_SESSION['id_account']) || !in_array($_SESSION['role_account'], ['member', 'admin'])){
    header('Location: login.php');
    exit;
}

if(isset($_GET['logout']) && $_GET['logout']==1){
    session_destroy();
    header('Location: login.php');
    exit;
}
$username = $_SESSION['username_account'];
$user_role = $_SESSION['role_account']; // ดึง role มาใช้ในการแสดงผล

?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>MINECRAFT | LGAME</title>
<style>
body {
    margin:0;
    font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background:#121212;
    color:#fff;
}

/* Navbar */
nav {
  display:flex;
  justify-content:space-between;
  align-items:center;
  padding:12px 24px;
  background:#1f1f1f;
  border-bottom:1px solid #333;
}
nav .logo { font-size:24px; font-weight:bold; color:#ff4655; text-decoration:none; margin-right:30px; }
nav ul { list-style:none; display:flex; margin:0; padding:0; }
nav ul li { margin-left:20px; }
nav ul li a { text-decoration:none; color:#fff; font-weight:500; transition:0.3s; }
nav ul li a:hover { color:#ff4655; }

/* User dropdown */
nav .user-info { position:relative; }
nav .user-info button {
    background:#444; border-radius:5px; border:none; color:#fff; font-weight:bold; cursor:pointer; padding:6px 12px; font-size:14px;
    display:flex; align-items:center; gap:4px;
    transition:0.3s;
}
nav .user-info button:hover { color:#ff4655; }
nav .user-info .dropdown {
    display:none;
    position:absolute;
    right:0;
    top:calc(100% + 5px);
    background:#181818;
    border:1px solid #444;
    border-radius:6px;
    min-width:150px;
    overflow:hidden;
    box-shadow:0 5px 15px rgba(0,0,0,0.5);
    animation:fadeDown 0.3s ease forwards;
    z-index:100;
}
nav .user-info .dropdown a {
    display:block;
    padding:10px 16px;
    color:#fff;
    text-decoration:none;
    font-weight:500;
    transition:0.2s;
}
nav .user-info .dropdown a:hover {
    background:#ff4655;
    color:#fff;
}
@keyframes fadeDown {
    0% { opacity:0; transform:translateY(-10px); }
    100% { opacity:1; transform:translateY(0); }
}

/* Header */
header {
    text-align:center;
    padding:30px 20px;
}
header img { width:100%; max-width:800px; border-radius:10px; }

/* Container */
.container {
    max-width:1000px;
    margin:0 auto;
    padding:20px;
}
h1 { color:#ff4655; }
h2 { margin-top:30px; color:#ff4655; }
p { color:#ccc; line-height:1.6; }
ul li { color:#ccc; line-height:1.6; }
a.button {
    display:inline-block;
    padding:10px 20px;
    background:#ff4655;
    color:#fff;
    text-decoration:none;
    border-radius:6px;
    margin-top:20px;
    transition:0.3s;
}
a.button:hover { background:#e03e4d; }

</style>
</head>
<body>

<nav>
  <div style="display:flex; align-items:center;">
    <a href="../memberhub.php" class="logo">LGAME</a>
    <ul>
      <li><a href="../games.php">เกม</a></li>
      <li><a href="../news.php">ข่าวสาร</a></li>
      <li><a href="../support.php">Support</a></li>
    </ul>
  </div>

  <div class="user-info">
    <button onclick="toggleDropdown()"><?= htmlspecialchars($username) ?> ▼</button>
    <div class="dropdown" id="userDropdown">
      <a href="../settings.php">Setting</a>
      <a href="../logout.php">Logout</a>
    </div>
  </div>
</nav>

<header>
    <h1>MINECRAFT</h1>
    <img src="https://play-lh.googleusercontent.com/gfNz1N2GNi5piz24IB08RQ4ZGfUnN_kOH8Edhh7uCiotI2P7IBWBXdHzR8gC01ppNnU=w720-h405-rw" alt="Minecraft">
</header>

<div class="container">
    <h2>เกี่ยวกับเกม</h2>
    <p>Minecraft เป็นเกม sandbox ที่ผู้เล่นสามารถสร้างโลกในแบบของตัวเอง วางบล็อก ปลูกพืช ทำเหมือง หรือสร้างสิ่งก่อสร้างได้ตามจินตนาการ มีโหมด Survival และ Creative ให้เลือกเล่น</p>

    <h2>ระบบการเล่น</h2>
    <p>ผู้เล่นสามารถเก็บทรัพยากร, ต่อสู้กับมอนสเตอร์, สร้างสิ่งก่อสร้าง, และสำรวจโลกกว้าง มีระบบ Crafting และ Redstone สำหรับสร้างกลไกต่างๆ สามารถเล่นแบบ Multiplayer กับเพื่อนๆ ได้</p>

    <h2>คุณสมบัติเด่น</h2>
    <ul>
        <li>โลกเปิดไม่มีที่สิ้นสุด สำรวจและสร้างได้ตามใจ</li>
        <li>โหมด Survival และ Creative</li>
        <li>ระบบ Crafting และ Redstone</li>
        <li>รองรับ Multiplayer เล่นกับเพื่อน</li>
        <li>สนับสนุน Mod และ Skin หลายรูปแบบ</li>
    </ul>

    <h2>ดาวน์โหลดเกม</h2>
    <a href="https://www.minecraft.net/" class="button" target="_blank">ไปที่หน้าเว็บไซต์ Minecraft</a>
    <br>
    <a href="../games.php" class="button">กลับไปหน้ารวมเกม</a>
</div>

<script>
function toggleDropdown(){
    const dropdown = document.getElementById('userDropdown');
    dropdown.style.display = (dropdown.style.display === 'block') ? 'none' : 'block';
}
window.onclick = function(e){
    if(!e.target.closest('.user-info')){
        document.getElementById('userDropdown').style.display = 'none';
    }
}
</script>

</body>
</html>
