<?php
session_start();
if(!isset($_SESSION['id_account']) || $_SESSION['role_account'] !== 'member'){
    header('Location: login.php');
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
<title>ELDEN RING | LGAME</title>
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
    <h1>ELDEN RING</h1>
    <img src="https://www.impericon.com/cdn/shop/collections/20220204_the_elden_ring__header.jpg?v=1715766544" alt="Elden Ring">
</header>

<div class="container">
    <h2>เกี่ยวกับเกม</h2>
    <p>Elden Ring เป็นเกม Action RPG โลกเปิดที่เต็มไปด้วยความลึกลับและอันตราย ผู้เล่นจะสำรวจดินแดนกว้างใหญ่, ต่อสู้กับบอสที่ท้าทาย และพัฒนาตัวละครตามสไตล์การเล่นของตนเอง</p>

    <h2>ระบบการเล่น</h2>
    <p>ผู้เล่นสามารถสร้างตัวละคร ปรับแต่งอาวุธและสกิล มีระบบ Combat ที่ซับซ้อนและต้องวางกลยุทธ์ในการต่อสู้กับมอนสเตอร์และบอสในโลกกว้าง มีโหมด Multiplayer Co-op ให้เล่นร่วมกับเพื่อน</p>

    <h2>คุณสมบัติเด่น</h2>
    <ul>
        <li>โลกเปิดขนาดใหญ่ สำรวจและค้นพบความลับ</li>
        <li>ต่อสู้กับบอสสุดท้าทาย</li>
        <li>ระบบ RPG ปรับแต่งตัวละครและอาวุธได้</li>
        <li>กราฟิกและเสียงสมจริงระดับ AAA</li>
        <li>รองรับโหมด Multiplayer Co-op</li>
    </ul>

    <h2>ดาวน์โหลดเกม</h2>
    <a href="https://en.bandainamcoent.eu/elden-ring/elden-ring" class="button" target="_blank">ไปที่หน้าเว็บไซต์ Elden Ring</a>
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