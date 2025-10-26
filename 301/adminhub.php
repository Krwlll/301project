<?php
session_start();
if(!isset($_SESSION['id_account']) || $_SESSION['role_account'] !== 'admin'){
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
<title>Admin Hub</title>
<style>
body{margin:0; font-family:'Segoe UI',sans-serif; background:#121212; color:#fff;}
nav{display:flex;justify-content:space-between;align-items:center;padding:12px 24px;background:#1f1f1f;border-bottom:1px solid #333;}
nav .logo{font-size:24px;font-weight:bold;color:#ff4655;text-decoration:none;margin-right:30px;}
nav ul{list-style:none;display:flex;margin:0;padding:0;}
nav ul li{margin-left:20px;}
nav ul li a{text-decoration:none;color:#fff;font-weight:500;transition:0.3s;}
nav ul li a:hover{color:#ff4655;}
nav .user-info{position: relative;}
nav .user-info button{background:#444;border-radius:5px;border:none;color:#fff;font-weight:bold;cursor:pointer;padding:6px 12px;display:flex;align-items:center;gap:4px;transition:0.3s;}
nav .user-info button:hover{color:#ff4655;}
nav .user-info .dropdown{display:none;position:absolute;right:0;top:calc(100% + 5px);background:#181818;border:1px solid #444;border-radius:6px;min-width:150px;overflow:hidden;box-shadow:0 5px 15px rgba(0,0,0,0.5);z-index:100;}
nav .user-info .dropdown a{display:block;padding:10px 16px;color:#fff;text-decoration:none;font-weight:500;transition:0.2s;}
nav .user-info .dropdown a:hover{background:#ff4655;color:#fff;}
.content{padding:40px;text-align:center;}
</style>
</head>
<body>

<nav>
  <div style="display:flex;align-items:center;">
    <a href="adminhub.php" class="logo">LGAME</a>
    <ul>
      <li><a href="adminhub.php">Dashboard</a></li>
      <li><a href="manage-users.php">จัดการผู้ใช้</a></li>
      <li><a href="manage-games.php">จัดการเกม</a></li>
      <li><a href="reports.php">Reports</a></li>
    </ul>
  </div>

  <div class="user-info">
    <button onclick="toggleDropdown()"><?= htmlspecialchars($username) ?> <span>▼</span></button>
    <div class="dropdown" id="userDropdown">
      <a href="settings.php">Setting</a>
      <a href="?logout=1">Logout</a>
    </div>
  </div>
</nav>

<div class="content">
<h1>ยินดีต้อนรับ Admin <?= htmlspecialchars($username) ?></h1>
<p>คุณสามารถจัดการสมาชิกและเกมได้ที่นี่</p>
</div>

<script>
function toggleDropdown(){
    const dropdown = document.getElementById('userDropdown');
    dropdown.style.display = (dropdown.style.display==='block')?'none':'block';
}
window.onclick=function(e){
    if(!e.target.closest('.user-info')){
        document.getElementById('userDropdown').style.display='none';
    }
}
</script>
<script>
function reloadPage(){
    window.location.href = 'adminhub.php';
}
</script>

</body>
</html>
