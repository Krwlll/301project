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
<title>Member Hub</title>
<style>
body { margin:0; font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background:#121212; color:#fff; }

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

nav .user-info { position: relative; }
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
    box-shadow: 0 5px 15px rgba(0,0,0,0.5);
    animation: fadeDown 0.3s ease forwards;
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
    0% { opacity:0; transform: translateY(-10px); }
    100% { opacity:1; transform: translateY(0); }
}

.content { padding:40px; text-align:center; }
</style>
</head>
<body>

<nav>
  <div style="display:flex; align-items:center;">
    <a href="memberhub.php" class="logo">LGAME</a>
    <ul>
      <li><a href="games.php">เกม</a></li>
      <li><a href="news.php">ข่าวสาร</a></li>
      <li><a href="support.php">Support</a></li>
    </ul>
  </div>

  <div class="user-info">
    <button onclick="toggleDropdown()"><?= htmlspecialchars($username) ?> <span>▼</span></button>
    <div class="dropdown" id="userDropdown">
      <a href="?logout=1">Logout</a>
    </div>
  </div>
</nav>

<div class="content">
  <h1>ยินดีต้อนรับสมาชิก <?= htmlspecialchars($username) ?></h1>
  <p>นี่คือหน้า Member Hub ของคุณ</p>
</div>

<script>
function toggleDropdown(){
    const dropdown = document.getElementById('userDropdown');
    dropdown.style.display = (dropdown.style.display === 'block') ? 'none' : 'block';
}

// ปิด dropdown ถ้าคลิกนอก
window.onclick = function(e){
    if(!e.target.closest('.user-info')){
        const dropdown = document.getElementById('userDropdown');
        dropdown.style.display = 'none';
    }
}
</script>

<script>
function reloadPage(){
    window.location.href = 'memberhub.php';
}
</script>

</body>
</html>
