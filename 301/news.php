<?php
session_start();

// *** 1. ข้อมูลการเชื่อมต่อฐานข้อมูล (กรุณาแก้ไขตามข้อมูลจริงของคุณ) ***
define('DB_HOST', 'localhost');
define('DB_NAME', '301_project'); // ชื่อฐานข้อมูลที่คุณสร้างไว้
define('DB_USER', 'root');       // ชื่อผู้ใช้ MySQL (มักเป็น root ใน localhost)
define('DB_PASS', '');           // รหัสผ่าน MySQL (มักเป็นค่าว่างใน XAMPP/WAMP)

// ตรวจสอบสถานะการเข้าสู่ระบบ
$is_logged_in = isset($_SESSION['id_account']);
$is_admin = $is_logged_in && $_SESSION['role_account'] === 'admin';
$username = $_SESSION['username_account'] ?? 'Guest'; // กำหนดชื่อผู้ใช้สำหรับ Navbar

// *** 2. การจัดการ Logout (เหมือนเดิม) ***
if(isset($_GET['logout']) && $_GET['logout']==1){
    session_destroy();
    header('Location: login.php');
    exit;
}

// *** 3. ฟังก์ชันดึงข่าวสารจากฐานข้อมูลจริง ***
function fetch_all_news_from_db(){
    try {
        // สร้างการเชื่อมต่อ PDO
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Query ดึงข่าวทั้งหมด เรียงจาก created_at ล่าสุดไปเก่าสุด
        $stmt = $pdo->prepare("SELECT id, title, content, created_at FROM news ORDER BY created_at DESC");
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        // ในโลกจริงควรบันทึก Error ลง log file ไม่ใช่แสดงบนหน้าจอ
        // error_log("Database Error: " . $e->getMessage());
        return []; // คืนค่าเป็น Array ว่างถ้ามีข้อผิดพลาด
    }
}

// ดึงรายการข่าวสารล่าสุด
$news_items = fetch_all_news_from_db();
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ข่าวสาร - LGAME</title>
<style>
/* ... (ส่วน CSS ทั้งหมดใช้โค้ดเดิม) ... */
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
    color: #ff4655; /* สีลิงก์หลัก */
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
    border-bottom:1px solid #333; 
}
nav .logo { 
    font-size:24px; 
    font-weight:bold; 
    color:#ff4655; 
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
nav ul li a:hover { color:#ff4655; } 

nav .user-info { position: relative; }
nav .user-info button {
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

/* ========================================================================= */
/* NEWS CONTENT */
/* ========================================================================= */
.content { 
    padding:40px 20px; 
    text-align: center;
}
.news-container {
    text-align: left;
    max-width: 900px;
    margin: 0 auto; 
}
.news-container h1 {
    color: #ff4655;
    margin-bottom: 25px;
}
.news-item {
    border: 1px solid #333;
    padding: 20px;
    margin-bottom: 25px;
    border-radius: 8px;
    background: #1f1f1f;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
}
.news-item h2 {
    color: #fff;
    margin-top: 0;
    border-bottom: 2px solid #ff4655; /* ขีดเส้นใต้หัวข้อข่าว */
    padding-bottom: 8px;
    margin-bottom: 15px;
    font-size: 24px;
}
.news-item p {
    color: #ccc;
    white-space: pre-wrap; /* รักษารูปแบบขึ้นบรรทัดใหม่จาก nl2br */
}
.news-meta {
    font-size: 12px;
    color: #888;
    margin-top: 15px;
    border-top: 1px solid #333;
    padding-top: 10px;
}
.news-actions a {
    font-weight: bold;
    margin-right: 15px;
    color: #90CAF9; /* สีฟ้าสำหรับแก้ไข */
}
.news-actions a.delete {
    color: #ff4655; /* สีแดงสำหรับลบ */
}
.admin-link-bar {
    text-align: center;
    margin-bottom: 20px;
}
.admin-link-bar a {
    color: #4CAF50; /* ใช้สีเขียวสำหรับลิงก์จัดการ (เพื่อให้ Admin สังเกตเห็น) */
    font-weight: bold;
    padding: 8px 15px;
    border: 1px solid #4CAF50;
    border-radius: 4px;
    transition: 0.3s;
}
.admin-link-bar a:hover {
    background: #4CAF50;
    color: #fff;
}
</style>
</head>
<body>

<nav>
    <div style="display:flex; align-items:center;">
        <a href="<?= $is_admin ? 'adminhub.php' : 'memberhub.php' ?>" class="logo">LGAME</a>
        <ul>
            <li><a href="games.php">เกม</a></li>
            <li><a href="news.php">ข่าวสาร</a></li>
            <li><a href="support.php">Support</a></li>
        </ul>
    </div>

    <div class="user-info">
        <button onclick="toggleDropdown()"><?= htmlspecialchars($username) ?> <span>▼</span></button>
        <div class="dropdown" id="userDropdown">
            <?php if ($is_logged_in): ?>
                <?php if ($is_admin): ?>
                    <a href="adminhub.php">Admin Hub</a>
                <?php endif; ?>
                <a href="?logout=1">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="content">
    <div class="news-container">
        <h1>📢 ข่าวสารล่าสุด</h1>
        
        <?php if($is_admin): ?>
            <div class="admin-link-bar">
                <a href="admin_panel.php">🔑 จัดการข่าวสาร (Admin)</a>
            </div>
        <?php endif; ?>

        <?php if(empty($news_items)): ?>
            <p style="text-align: center; color: #ccc;">ยังไม่มีข่าวสารในขณะนี้</p>
        <?php else: ?>
            <?php foreach ($news_items as $news): ?>
                <div class="news-item">
                    <h2><?= htmlspecialchars($news['title']) ?></h2>
                    <p><?= nl2br(htmlspecialchars($news['content'])) ?></p>
                    
                    <div class="news-meta">
                        เผยแพร่: <?= date('d M Y H:i', strtotime($news['created_at'])) ?> 
                    </div>
                    
                    <?php if($is_admin): ?>
                        <hr style="border-color: #333; margin: 15px 0;">
                        <div class="news-actions">
                            <a href="admin_panel.php?action=edit&id=<?= $news['id'] ?>">แก้ไข</a>
                            <a href="admin_panel.php?action=delete&id=<?= $news['id'] ?>" class="delete" onclick="return confirm('ยืนยันการลบข่าวสาร: <?= htmlspecialchars($news['title']) ?>?')">ลบ</a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script>
function toggleDropdown(){
    const dropdown = document.getElementById('userDropdown');
    dropdown.style.display = (window.getComputedStyle(dropdown).display === 'block') ? 'none' : 'block';
}

// ปิด dropdown ถ้าคลิกนอก
window.onclick = function(e){
    if(!e.target.closest('.user-info')){
        const dropdown = document.getElementById('userDropdown');
        if (dropdown.style.display === 'block') {
             dropdown.style.display = 'none';
        }
    }
}
</script>

</body>
</html>