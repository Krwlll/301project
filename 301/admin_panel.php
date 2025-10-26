<?php
session_start();

// *** 1. การตรวจสอบสิทธิ์ และ ข้อมูลการเชื่อมต่อฐานข้อมูล ***

// ตรวจสอบสิทธิ์: ต้องเป็น 'admin' เท่านั้นถึงเข้าได้
if(!isset($_SESSION['id_account']) || $_SESSION['role_account'] !== 'admin'){
    header('Location: login.php');
    exit;
}
if(isset($_GET['logout']) && $_GET['logout']==1){
    session_destroy();
    header('Location: login.php');
    exit;
}

// ข้อมูลการเชื่อมต่อฐานข้อมูล (ต้องตรงกับ news.php)
define('DB_HOST', 'localhost');
define('DB_NAME', '301_project'); 
define('DB_USER', 'root');       
define('DB_PASS', '');           

$admin_username = $_SESSION['username_account'];
$message = '';
$edit_news = null; // เก็บข้อมูลข่าวที่จะแก้ไข

// *** 2. ฟังก์ชันหลักสำหรับเชื่อมต่อและจัดการฐานข้อมูล (ใช้ PDO) ***

function get_pdo_connection() {
    try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        // อาจ redirect ไปหน้า error หรือแสดงข้อความที่กำหนด
        die("Database connection failed: " . $e->getMessage());
    }
}

// 2.1 ดึงรายการข่าวทั้งหมด
function fetch_all_news($pdo) {
    $stmt = $pdo->prepare("SELECT id, title, content, created_at FROM news ORDER BY created_at DESC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// 2.2 ดึงข่าวตาม ID
function get_news_by_id($pdo, $id) {
    $stmt = $pdo->prepare("SELECT id, title, content FROM news WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// 2.3 จัดการ (เพิ่ม/แก้ไข/ลบ)
function manage_news($pdo, $action, $data = []) {
    $admin_id = $_SESSION['id_account']; // ใช้ ID ของ Admin ที่ล็อกอิน

    if ($action === 'add') {
        $stmt = $pdo->prepare("INSERT INTO news (title, content, admin_id) VALUES (?, ?, ?)");
        return $stmt->execute([$data['title'], $data['content'], $admin_id]);
    } 
    
    if ($action === 'update') {
        $stmt = $pdo->prepare("UPDATE news SET title = ?, content = ?, updated_at = NOW() WHERE id = ?");
        return $stmt->execute([$data['title'], $data['content'], $data['id']]);
    }
    
    if ($action === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM news WHERE id = ?");
        return $stmt->execute([$data['id']]);
    }
    
    return false;
}

// สร้างการเชื่อมต่อ PDO
$pdo = get_pdo_connection();


// *** 3. การจัดการคำสั่ง POST (เพิ่ม/แก้ไข) ***
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $id = (int)($_POST['news_id'] ?? 0);

    if (empty($title) || empty($content)) {
        $message = "❌ กรุณากรอกหัวข้อและเนื้อหาให้ครบถ้วน!";
    } elseif ($id > 0) {
        // โหมดแก้ไข
        if (manage_news($pdo, 'update', ['id' => $id, 'title' => $title, 'content' => $content])) {
            $message = "✅ แก้ไขข่าวสาร ID: $id เรียบร้อยแล้ว!";
        } else {
            $message = "❌ เกิดข้อผิดพลาดในการแก้ไขข้อมูล!";
        }
    } else {
        // โหมดเพิ่ม
        if (manage_news($pdo, 'add', ['title' => $title, 'content' => $content])) {
            $message = "✅ เพิ่มข่าวสารใหม่เรียบร้อยแล้ว!";
        } else {
            $message = "❌ เกิดข้อผิดพลาดในการบันทึกข้อมูล!";
        }
    }
}

// *** 4. การจัดการคำสั่ง GET (ลบ/เตรียมแก้ไข) ***

// การลบ
if(isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])){
    $id_to_delete = (int)$_GET['id'];
    if (manage_news($pdo, 'delete', ['id' => $id_to_delete])) {
        $message = "✅ ลบข่าวสาร ID: $id_to_delete เรียบร้อยแล้ว!";
    } else {
        $message = "❌ เกิดข้อผิดพลาดในการลบข้อมูล!";
    }
    header('Location: admin_panel.php?message=' . urlencode($message)); 
    exit;
}

// การเตรียมแก้ไข
if(isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])){
    $edit_news = get_news_by_id($pdo, (int)$_GET['id']);
    if (!$edit_news) {
        $message = "❌ ไม่พบข่าวสารที่ต้องการแก้ไข!";
    }
}

// แสดงข้อความจาก Redirect หลังจากการลบ
if (isset($_GET['message'])) {
    $message = urldecode($_GET['message']);
}

// ดึงรายการข่าวสารล่าสุดเพื่อแสดง
$news_items = fetch_all_news($pdo);

?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Panel - จัดการข่าวสาร</title>
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
    color: #ff4655; /* สีลิงก์หลัก */
    text-decoration: none;
    transition: color 0.3s;
}
a:hover {
    color: #e53846;
}


/* ========================================================================= */
/* NAVBAR (ใช้สไตล์เดียวกับ memberhub.php) */
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
/* CONTENT & ADMIN FORM */
/* ========================================================================= */
.content { 
    padding:40px 20px; 
    max-width: 1000px;
    margin: 0 auto;
    text-align: left;
}
.content h1 {
    color: #ff4655; 
    text-align: center;
}
.admin-form-container {
    background: #1f1f1f; 
    padding: 30px;
    border-radius: 8px;
    margin-top: 20px;
    margin-bottom: 40px;
    border: 1px solid #333;
}
.admin-form-container h2 {
    color: #fff;
    margin-top: 0;
    padding-bottom: 10px;
    border-bottom: 1px solid #444;
}
.admin-form-container input[type="text"], 
.admin-form-container textarea {
    width: 100%;
    padding: 12px;
    margin-bottom: 15px;
    border: 1px solid #555;
    border-radius: 4px;
    background: #121212; 
    color: #fff;
    box-sizing: border-box; 
    font-size: 16px;
}
.admin-form-container textarea {
    resize: vertical;
}
.admin-form-container button {
    background: #ff4655;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background 0.3s;
    font-weight: bold;
    font-size: 16px;
}
.admin-form-container button:hover {
    background: #e53846;
}

.message-success {
    color: #4CAF50; /* ใช้สีเขียวสำหรับ Success */
    font-weight: bold;
    text-align: center;
    margin: 15px 0;
}
.message-error {
    color: #ff4655; /* ใช้สีแดงสำหรับ Error */
    font-weight: bold;
    text-align: center;
    margin: 15px 0;
}

/* ตารางแสดงรายการข่าว */
.news-list-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}
.news-list-table th, .news-list-table td {
    border: 1px solid #333;
    padding: 10px;
    text-align: left;
}
.news-list-table th {
    background: #1f1f1f;
    color: #ff4655;
    font-size: 16px;
}
.news-list-table td {
    background: #2a2a2a;
    color: #ccc;
    vertical-align: top;
}
.news-list-table .actions a {
    margin-right: 10px;
    font-weight: bold;
}
.news-list-table .actions a.delete {
    color: #ff4655;
}
.news-list-table .actions a.edit {
    color: #90CAF9; /* สีฟ้าสำหรับแก้ไข */
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
    <h1 style="text-align: center;">จัดการข่าวสาร (News Management)</h1>

    <?php if($message): ?>
        <p class="<?= (strpos($message, '❌') !== false) ? 'message-error' : 'message-success' ?>"><?= $message ?></p>
    <?php endif; ?>

    <div class="admin-form-container">
        <h2><?= $edit_news ? '✏️ แก้ไขข่าวสาร ID: ' . htmlspecialchars($edit_news['id']) : '➕ เพิ่มข่าวสารใหม่' ?></h2>
        <form method="POST" action="admin_panel.php">
            <?php if($edit_news): ?>
                <input type="hidden" name="news_id" value="<?= htmlspecialchars($edit_news['id']) ?>">
            <?php endif; ?>
            <input type="text" name="title" placeholder="หัวข้อข่าวสาร" required 
                   value="<?= htmlspecialchars($edit_news['title'] ?? '') ?>">
            
            <textarea name="content" placeholder="เนื้อหาข่าวสาร" rows="10" required
            ><?= htmlspecialchars($edit_news['content'] ?? '') ?></textarea>
            
            <button type="submit"><?= $edit_news ? '💾 บันทึกการแก้ไข' : '📢 เผยแพร่ข่าวสาร' ?></button>
            <?php if($edit_news): ?>
                <a href="admin_panel.php" style="color: #ccc; margin-left: 15px;">ยกเลิกการแก้ไข</a>
            <?php endif; ?>
        </form>
    </div>
    
    <hr style="border-color: #333; margin: 40px 0;">

    <h2>รายการข่าวสารทั้งหมด (<?= count($news_items) ?> รายการ)</h2>
    
    <?php if(empty($news_items)): ?>
        <p style="text-align: center; color: #ccc;">ยังไม่มีข่าวสารในระบบ</p>
    <?php else: ?>
        <table class="news-list-table">
            <thead>
                <tr>
                    <th style="width: 5%;">ID</th>
                    <th style="width: 50%;">หัวข้อ</th>
                    <th style="width: 20%;">วันที่เผยแพร่</th>
                    <th style="width: 25%;">จัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($news_items as $news): ?>
                <tr>
                    <td><?= htmlspecialchars($news['id']) ?></td>
                    <td><?= htmlspecialchars($news['title']) ?></td>
                    <td><?= date('Y-m-d H:i', strtotime($news['created_at'])) ?></td>
                    <td class="actions">
                        <a href="admin_panel.php?action=edit&id=<?= $news['id'] ?>" class="edit">แก้ไข</a>
                        <a href="admin_panel.php?action=delete&id=<?= $news['id'] ?>" class="delete" 
                           onclick="return confirm('คุณต้องการลบข่าวสาร: <?= htmlspecialchars($news['title']) ?> หรือไม่?')">ลบ</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <p style="margin-top: 30px; text-align: center;"><a href="news.php">[ดูหน้าข่าวสารสำหรับสมาชิก]</a></p>
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