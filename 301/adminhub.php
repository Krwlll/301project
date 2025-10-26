<?php
session_start();

// 1. ตรวจสอบสิทธิ์การเข้าถึง: ต้องล็อกอินและเป็น 'admin' เท่านั้น
if(!isset($_SESSION['id_account']) || $_SESSION['role_account'] !== 'admin'){
    // ถ้าไม่ใช่ Admin ให้ส่งไปหน้าล็อกอินหรือหน้าที่เหมาะสม
    header('Location: login.php');
    exit;
}

// การจัดการ Logout (เหมือนเดิม)
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
<title>Admin Dashboard</title>
<style>
/* ========================================================================= */
/* BASE & UTILITIES */
/* ========================================================================= */
body { 
    margin:0; 
    font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
    background:#121212; 
    color:#fff; 
    line-height: 1.6;
}
a {
    color: #ff4655; /* ใช้สีเดิม */
    text-decoration: none;
    transition: color 0.3s;
}
a:hover {
    color: #e53846;
}


/* ========================================================================= */
/* NAVBAR (ใช้สไตล์เดิมทั้งหมด) */
/* ========================================================================= */
nav {
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:12px 24px;
    background:#1f1f1f;
    border-bottom:1px solid #333; /* ใช้สีเดิม */
}
nav .logo { 
    font-size:24px; 
    font-weight:bold; 
    color:#ff4655; /* ใช้สีเดิม */
    text-decoration:none; 
    margin-right:30px; 
}
nav ul { list-style:none; display:flex; margin:0; padding:0; }
nav ul li { margin-left:20px; }
nav ul li a { 
    text-decoration:none; 
    color:#fff; 
    font-weight:500; 
    transition:0.3s; 
}
nav ul li a:hover { color:#ff4655; } /* ใช้สีเดิม */

nav .user-info { position: relative; }
nav .user-info button {
    /* ใช้สไตล์เดิม */
    background:#444; 
    border-radius:5px; 
    border:none; 
    color:#fff; 
    font-weight:bold; 
    cursor:pointer; 
    padding:6px 12px; 
    font-size:14px;
    display:flex; align-items:center; gap:4px;
    transition:0.3s;
}
nav .user-info button:hover { color:#ff4655; } /* ใช้สีเดิม */

nav .user-info .dropdown {
    display:none;
    position:absolute;
    right:0;
    top:calc(100% + 5px);
    background:#181818;
    border:1px solid #444; /* ใช้สีเดิม */
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
    background:#ff4655; /* ใช้สีเดิม */
    color:#fff;
}
@keyframes fadeDown {
    0% { opacity:0; transform: translateY(-10px); }
    100% { opacity:1; transform: translateY(0); }
}

/* ========================================================================= */
/* CONTENT & ADMIN CARDS */
/* ========================================================================= */
.content { 
    padding:40px; 
    text-align:center; 
}
.content h1 {
    color: #ff4655; /* ใช้สีเดิม */
    font-size: 32px;
}
.admin-card-container {
    display: flex;
    justify-content: center;
    gap: 30px;
    margin-top: 30px;
}
.admin-card {
    background: #1f1f1f;
    border: 1px solid #333;
    border-top: 4px solid #ff4655; /* เน้นสี Admin ด้วยสีเดิม */
    padding: 25px;
    border-radius: 8px;
    width: 250px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.3);
    transition: transform 0.3s, box-shadow 0.3s;
}
.admin-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 15px rgba(0,0,0,0.5);
}
.admin-card h2 {
    color: #fff;
    font-size: 20px;
    margin-top: 0;
}
.admin-card p {
    color: #ccc;
    margin-bottom: 20px;
}
.admin-card .btn-manage {
    display: inline-block;
    background: #ff4655; /* ใช้สีเดิม */
    color: white;
    padding: 10px 20px;
    border-radius: 4px;
    font-weight: bold;
    transition: background 0.3s;
}
.admin-card .btn-manage:hover {
    background: #e53846; /* ใช้สีเดิมเข้มขึ้น */
    color: white;
}
</style>
</head>
<body>

<nav>
    <div style="display:flex; align-items:center;">
        <a href="adminhub.php" class="logo">LGAME ADMIN</a> 
        <ul>
            <li><a href="adminhub.php">Dashboard</a></li>
            <li><a href="admin_panel.php">จัดการข่าวสาร (Admin)</a></li>
            <li><a href="manage_users.php">จัดการสมาชิก</a></li>
            
            <li style="margin-left: 30px; color: #ccc;">|</li> 
            
            <li><a href="games.php">เกม</a></li> 
            <li><a href="news.php">ข่าวสาร</a></li> 
            <li><a href="support.php">Support</a></li>
        </ul>
    </div>

    <div class="user-info">
        <button onclick="toggleDropdown()">🔑 <?= htmlspecialchars($_SESSION['username_account']) ?> ▼</button>
        <div class="dropdown" id="userDropdown">
            <a href="?logout=1">Logout</a>
        </div>
    </div>
</nav>
<div class="content">
    <h1>ยินดีต้อนรับผู้ดูแลระบบ <?= htmlspecialchars($username) ?></h1>
    <p style="color: #ccc;">นี่คือแผงควบคุมหลักสำหรับการจัดการเว็บไซต์</p>

    <div class="admin-card-container">
        <div class="admin-card">
            <h2>📰 จัดการข่าวสาร</h2>
            <p>เพิ่ม, แก้ไข, หรือลบข่าวสาร</p>
            <a href="admin_panel.php" class="btn-manage">เข้าสู่การจัดการ</a>
        </div>

        <div class="admin-card">
            <h2>👥 จัดการสมาชิก</h2>
            <p>ตรวจสอบและจัดการบัญชีผู้ใช้ (Member/Admin)</p>
            <a href="manage_users.php" class="btn-manage">เข้าสู่การจัดการ</a>
        </div>
    </div>
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

</body>
</html>