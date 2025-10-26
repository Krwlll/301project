<?php
session_start();
if(!isset($_SESSION['id_account']) || $_SESSION['role_account'] !== 'member'){
    header('Location: ../login.php');
    exit;
}
if(isset($_GET['logout']) && $_GET['logout']==1){
    session_destroy();
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username_account'];
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>LEAGUE OF LEGENDS | LGAME</title>
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
      <a href="../games.php?logout=1">Logout</a>
    </div>
  </div>
</nav>

<header>
    <h1>LEAGUE OF LEGENDS</h1>
    <img src="https://www.lnwtrue.com/_next/image?url=https%3A%2F%2Fmedia.lnwtrue.com%2Fimages%2Fproducts%2Flol-epin%2FTgZDObNPSP9IoY.webp&w=3840&q=75" alt="League of Legends">
</header>

<div class="container">
    <h2>เกี่ยวกับเกม</h2>
    <p>League of Legends (LoL) เป็นเกมแนว MOBA ที่มีชื่อเสียงระดับโลก ผู้เล่นจะแบ่งทีมละ 5 คน เพื่อทำลายฐานของฝ่ายตรงข้ามในแผนที่ Summoner’s Rift ใช้กลยุทธ์ การประสานงาน และการควบคุมตัวละครที่หลากหลาย</p>

    <h2>ระบบการเล่น</h2>
    <p>ผู้เล่นเลือก Champion แต่ละตัวมีสกิลเฉพาะตัว พร้อมทั้งมีระบบแผนที่, ทาวเวอร์, มอนสเตอร์ป่า และมินเนี่ยน การเล่นต้องวางแผนร่วมกับทีมเพื่อชนะฝ่ายตรงข้าม</p>

    <h2>คุณสมบัติเด่น</h2>
    <ul>
        <li>เกม MOBA 5v5 ที่ต้องใช้กลยุทธ์และการสื่อสาร</li>
        <li>ตัวละครหลากหลาย พร้อมสกิลเฉพาะตัว</li>
        <li>แผนที่ Summoner’s Rift และ ARAM ให้เลือก</li>
        <li>ระบบ Ranked และ Tournament แข่งขันระดับโลก</li>
        <li>Skin และไอเทมตกแต่งตัวละครมากมาย</li>
    </ul>

    <h2>ดาวน์โหลดเกม</h2>
    <a href="https://signup.leagueoflegends.com/" class="button" target="_blank">ไปที่หน้าเว็บไซต์ League of Legends</a>
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
