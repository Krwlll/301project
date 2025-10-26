<?php
session_start();

// อนุญาตให้เข้าได้ถ้ามีการล็อกอิน และ role เป็น 'member' หรือ 'admin'
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

// *** โค้ดสำหรับกำหนดลิงก์ Hub ***
if ($user_role === 'admin') {
    $hub_link = 'adminhub.php';
} else {
    $hub_link = 'memberhub.php';
}
// ------------------------------------------
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>เกมทั้งหมด | LGAME</title>
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

/* Game cards */
.games-container {
  display:grid;
  grid-template-columns:repeat(auto-fit, minmax(250px, 1fr));
  gap:24px;
  padding:40px;
}

.game-card {
  background:#1f1f1f;
  border:1px solid #333;
  border-radius:10px;
  overflow:hidden;
  transition:transform 0.3s, box-shadow 0.3s;
}
.game-card:hover {
  transform:translateY(-5px);
  box-shadow:0 6px 20px rgba(0,0,0,0.5);
}
.game-card img {
  width:100%;
  height:150px;
  object-fit:cover;
}
.game-card .info {
  padding:16px;
}
.game-card .info h3 {
  margin:0;
  font-size:20px;
  color:#ff4655;
}
.game-card .info p {
  font-size:14px;
  color:#ccc;
  margin-top:6px;
}
.game-card {
    text-decoration: none;
    color: inherit;
    display: block;
}
</style>
</head>
<body>

<nav>
  <div style="display:flex; align-items:center;">
    <a href="memberhub.php" class="logo">LGAME</a>
    <ul>
      <li><a href="games.php" style="color:#ff4655;">เกม</a></li>
      <li><a href="news.php">ข่าวสาร</a></li>
      <li><a href="support.php">Support</a></li>
    </ul>
  </div>

  <div class="user-info">
    <button onclick="toggleDropdown()"><?= htmlspecialchars($username) ?> ▼</button>
    <div class="dropdown" id="userDropdown">
      <a href="?logout=1">Logout</a>
    </div>
  </div>
</nav>

<div class="games-container">
  <a href = "gameinfo/game-valo.php" class="game-card">
    <img src="https://www.riotgames.com/darkroom/1440/d0807e131a84f2e42c7a303bda672789:3d02afa7e0bfb75f645d97467765b24c/valorant-offwhitelaunch-keyart.jpg" alt="Valorant">
    <div class="info">
      <h3>VALORANT</h3>
      <p>เกมยิงปืนเชิงกลยุทธ์ 5v5 จาก Riot Games ที่ต้องอาศัยฝีมือและทีมเวิร์ก</p>
    </div>
  </a>

  <a href ="gameinfo/game-lol.php" class="game-card">
    <img src="https://www.lnwtrue.com/_next/image?url=https%3A%2F%2Fmedia.lnwtrue.com%2Fimages%2Fproducts%2Flol-epin%2FTgZDObNPSP9IoY.webp&w=3840&q=75" alt="League of Legends">
    <div class="info">
      <h3>LEAGUE OF LEGENDS</h3>
      <p>เกม MOBA ระดับตำนาน สู้กันในสนามรบแห่ง Summoner’s Rift เพื่อทำลายฐานของศัตรู</p>
    </div>
  </a>

  <a href = "gameinfo/game-bf.php" class="game-card">
    <img src="https://image.api.playstation.com/vulcan/ap/rnd/202507/2917/aa972ae00b4f52514faa64d6626c43fe92ca880b250fa485.jpg" alt="Battlefield">
    <div class="info">
      <h3>BATTLEFIELD 6</h3>
      <p>สงครามขนาดใหญ่พร้อมยานพาหนะและทีมขนาดใหญ่ สนามรบที่สมจริงที่สุด</p>
    </div>
  </a>
</div>

<div class="games-container">
  <a href ="gameinfo/game-mhwild.php" class="game-card">
    <img src="https://cdn.oneesports.co.th/cdn-data/sites/3/2024/10/monster-hunter-wilds-pc-game-ste-2.jpg" alt="Monster Hunter Wilds">
    <div class="info">
      <h3>MONSTER HUNTER WILDS</h3>
      <p>ออกล่ามอนสเตอร์สุดโหดใน Monster Hunter Wilds โลกที่กว้างใหญ่กำลังรอให้เหล่าฮันเตอร์มาพิชิต</p>
    </div>
  </a>

  <a href ="gameinfo/game-mc.php" class="game-card">
    <img src="https://play-lh.googleusercontent.com/gfNz1N2GNi5piz24IB08RQ4ZGfUnN_kOH8Edhh7uCiotI2P7IBWBXdHzR8gC01ppNnU=w720-h405-rw" alt="Minecraft">
    <div class="info">
      <h3>MINECRAFT</h3>
      <p>ปลดปล่อยจินตนาการ สร้างสิ่งที่คุณคิด ฝึกทักษะ วางบล็อกทุกชิ้นให้โลกของคุณเต็มไปด้วยความมหัศจรรย์</p>
    </div>
  </a>

  <a href="gameinfo/game-elden.php" class="game-card">
    <img src="https://www.impericon.com/cdn/shop/collections/20220204_the_elden_ring__header.jpg?v=1715766544" alt="Elden Ring">
    <div class="info">
      <h3>ELDEN RING</h3>
      <p>สำรวจโลกกว้างที่เต็มไปด้วยความลึกลับและอันตราย Elden Ring รอให้เหล่าผู้มัวหมองมาพิชิตทุกความท้าทาย</p>
    </div>
  </a>

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