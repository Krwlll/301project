<?php
session_start();

if(!isset($_SESSION['id_account']) || $_SESSION['role_account'] !== 'member'){
    header('Location: ../login.php'); // ปรับ path ให้ถูกต้อง
    exit;
}

if(isset($_GET['logout']) && $_GET['logout'] == 1){
    session_destroy(); // ล้าง session ทั้งหมด
    header('Location: ../login.php'); // กลับไปหน้า login
    exit;
}

$username = $_SESSION['username_account'];
?>