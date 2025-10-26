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

// ใช้ฐานข้อมูลและตารางตามที่คุณมีอยู่จริง
define('DB_HOST', 'localhost');
define('DB_NAME', '301_project'); // *** ใช้ DB ที่คุณมีอยู่ ***
define('DB_USER', 'root');       
define('DB_PASS', '');           

$admin_username = $_SESSION['username_account'];
$current_admin_id = $_SESSION['id_account'];
$message = '';

// *** 2. ฟังก์ชันหลักสำหรับเชื่อมต่อและจัดการฐานข้อมูล (ใช้ PDO) ***

function get_pdo_connection() {
    try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
}

// 2.1 ดึงรายการสมาชิกทั้งหมด (ยกเว้น Admin ที่ล็อกอินอยู่)
function fetch_all_members($pdo, $exclude_id) {
    // ดึงเฉพาะ member ที่ไม่ใช่ admin ที่ล็อกอินอยู่ เพื่อป้องกันการลบตัวเอง
    $stmt = $pdo->prepare("SELECT id_account, username_account, role_account, created_at FROM account WHERE id_account != ? ORDER BY role_account DESC, created_at ASC");
    $stmt->execute([$exclude_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// 2.2 จัดการการเปลี่ยนบทบาท (Role)
function update_user_role($pdo, $id, $new_role) {
    if ($new_role !== 'admin' && $new_role !== 'member') return false;

    $stmt = $pdo->prepare("UPDATE account SET role_account = ? WHERE id_account = ?");
    return $stmt->execute([$new_role, $id]);
}

// 2.3 จัดการการลบสมาชิก
function delete_user($pdo, $id) {
    // *** สำคัญ: ถ้าตาราง news มี Foreign Key กับ account ต้องตั้งค่า ON DELETE ให้เป็น CASCADE หรือ SET NULL 
    // ถ้าไม่เช่นนั้น จะลบสมาชิกที่มีข่าวสารที่เขาเคยสร้างไม่ได้
    $stmt = $pdo->prepare("DELETE FROM account WHERE id_account = ?");
    return $stmt->execute([$id]);
}

// สร้างการเชื่อมต่อ PDO
$pdo = get_pdo_connection();


// *** 3. การจัดการคำสั่ง GET (เปลี่ยนบทบาท/ลบ) ***

if(isset($_GET['action']) && isset($_GET['id'])){
    $id = (int)$_GET['id'];
    $action = $_GET['action'];

    if ($id === $current_admin_id) {
        $message = "❌ ไม่สามารถดำเนินการกับบัญชีผู้ดูแลระบบปัจจุบันได้!";
    } elseif ($action === 'delete') {
        if (delete_user($pdo, $id)) {
            $message = "✅ ลบบัญชีผู้ใช้ ID: $id เรียบร้อยแล้ว!";
        } else {
            $message = "❌ เกิดข้อผิดพลาดในการลบบัญชีผู้ใช้! (ตรวจสอบ Foreign Key)";
        }
    } elseif ($action === 'set_admin' || $action === 'set_member') {
        $new_role = ($action === 'set_admin') ? 'admin' : 'member';
        if (update_user_role($pdo, $id, $new_role)) {
            $message = "✅ เปลี่ยนบทบาทผู้ใช้ ID: $id เป็น **$new_role** เรียบร้อยแล้ว!";
        } else {
            $message = "❌ เกิดข้อผิดพลาดในการเปลี่ยนบทบาท!";
        }
    }
    // Redirect เพื่อล้างค่า GET และแสดงข้อความ
    header('Location: manage_users.php?message=' . urlencode($message)); 
    exit;
}

// แสดงข้อความจาก Redirect 
if (isset($_GET['message'])) {
    $message = urldecode($_GET['message']);
}

// ดึงรายการสมาชิกทั้งหมด
$user_items = fetch_all_members($pdo, $current_admin_id);

?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Panel - จัดการสมาชิก</title>
<style>
/* ... (ใช้ CSS จาก admin_panel.php เดิม) ... */
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
    color: #ff4655; 
    text-decoration: none;
    transition: color 0.3s;
}
a:hover {
    color: #e53846;
}


/* ========================================================================= */
/* NAVBAR */
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
/* CONTENT & TABLES */
/* ========================================================================= */
.content { 
    padding:40px 20px; 
    max-width: 1200px;
    margin: 0 auto;
    text-align: left;
}
.content h1 {
    color: #ff4655; 
    text-align: center;
}
.message-success {
    color: #4CAF50; 
    font-weight: bold;
    text-align: center;
    margin: 15px 0;
}
.message-error {
    color: #ff4655; 
    font-weight: bold;
    text-align: center;
    margin: 15px 0;
}

/* ตารางแสดงรายการสมาชิก */
.user-list-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}
.user-list-table th, .user-list-table td {
    border: 1px solid #333;
    padding: 12px 10px;
    text-align: left;
}
.user-list-table th {
    background: #1f1f1f;
    color: #ff4655;
    font-size: 16px;
}
.user-list-table td {
    background: #2a2a2a;
    color: #ccc;
    vertical-align: middle;
}
.user-list-table .role-admin {
    font-weight: bold;
    color: #ff4655; /* สีแดงสำหรับ Admin */
}
.user-list-table .role-member {
    color: #90CAF9; /* สีฟ้าอ่อนสำหรับ Member */
}
.user-list-table .actions a {
    display: inline-block;
    margin-right: 10px;
    padding: 5px 10px;
    border-radius: 4px;
    font-size: 13px;
    font-weight: bold;
    text-align: center;
}
.user-list-table .actions a.delete {
    color: #fff;
    background-color: #ff4655;
}
.user-list-table .actions a.delete:hover {
    background-color: #e53846;
}
.user-list-table .actions a.change-role {
    color: #fff;
    background-color: #4CAF50; /* สีเขียวสำหรับ Promote/Demote */
}
.user-list-table .actions a.change-role:hover {
    background-color: #388E3C;
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
    <h1 style="text-align: center;">จัดการสมาชิก (User Management)</h1>

    <?php if($message): ?>
        <p class="<?= (strpos($message, '❌') !== false) ? 'message-error' : 'message-success' ?>"><?= $message ?></p>
    <?php endif; ?>
    
    <h2>รายการสมาชิกทั้งหมด (<?= count($user_items) ?> บัญชี)</h2>
    
    <?php if(empty($user_items)): ?>
        <p style="text-align: center; color: #ccc;">ไม่พบสมาชิกอื่นในระบบ</p>
    <?php else: ?>
        <table class="user-list-table">
            <thead>
                <tr>
                    <th style="width: 5%;">ID</th>
                    <th style="width: 25%;">ชื่อผู้ใช้</th>
                    <th style="width: 15%;">บทบาท</th>
                    <th style="width: 25%;">วันที่สร้าง</th>
                    <th style="width: 30%;">จัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($user_items as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['id_account']) ?></td>
                    <td><?= htmlspecialchars($user['username_account']) ?></td>
                    <td class="<?= $user['role_account'] === 'admin' ? 'role-admin' : 'role-member' ?>">
                        <?= ucfirst($user['role_account']) ?>
                    </td>
                    <td><?= date('Y-m-d H:i', strtotime($user['created_at'])) ?></td>
                    <td class="actions">
                        
                        <?php if ($user['role_account'] === 'member'): ?>
                            <a href="manage_users.php?action=set_admin&id=<?= $user['id_account'] ?>" class="change-role">
                                ตั้งเป็น Admin
                            </a>
                        <?php else: ?>
                            <a href="manage_users.php?action=set_member&id=<?= $user['id_account'] ?>" class="change-role" onclick="return confirm('ยืนยันการลดบทบาท Admin: <?= htmlspecialchars($user['username_account']) ?>?')">
                                ลดเป็น Member
                            </a>
                        <?php endif; ?>

                        <a href="manage_users.php?action=delete&id=<?= $user['id_account'] ?>" class="delete" 
                           onclick="return confirm('คุณต้องการลบบัญชี <?= htmlspecialchars($user['username_account']) ?> หรือไม่? การลบจะถาวร!')">
                            ลบ
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
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