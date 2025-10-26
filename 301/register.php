<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0" />
  <title>สร้างบัญชีเกม</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #121212, #1f1f1f);
      color: #f5f5f5;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    .register-box {
      background: rgba(30, 30, 30, 0.9);
      border: 1px solid #333;
      padding: 40px;
      border-radius: 16px;
      width: 100%;
      max-width: 420px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
    }
    .register-box h2 {
      text-align: center;
      margin-bottom: 30px;
      color: #ff4655;
      font-size: 24px;
    }
    label {
      display: block;
      margin: 12px 0 5px;
      font-size: 14px;
      color: #ccc;
    }
    input {
      width: 100%;
      padding: 12px;
      border: none;
      border-radius: 8px;
      background: #2a2a2a;
      color: #fff;
      font-size: 14px;
    }
    input:focus {
      outline: 2px solid #ff4655;
    }
    button {
      width: 100%;
      padding: 12px;
      background: #ff4655;
      border: none;
      border-radius: 10px;
      color: white;
      font-weight: bold;
      font-size: 15px;
      margin-top: 24px;
      cursor: pointer;
      transition: background 0.3s;
    }
    button:hover {
      background: #ff5e6d;
    }
    .message {
      text-align: center;
      margin-top: 16px;
      font-size: 14px;
    }
    .link {
      text-align: center;
      margin-top: 20px;
      font-size: 14px;
      color: #aaa;
    }
    .link a {
      color: #ff4655;
      text-decoration: none;
    }
    .message {
      text-align: center;
      margin-top: 15px;
      font-size: 14px;
    }
    .error { color: #ff7878; }
    .success { color: #6cff9c; }
    .footer {
      text-align: center;
      font-size: 12px;
      color: #777;
      margin-top: 20px;
    }
  </style>
</head>
<body>
  <?php
  if (isset($_GET['error'])) {
    switch ($_GET['error']) {
      case 'empty_field':
        echo "<script>alert('⚠️ กรุณากรอกข้อมูลให้ครบทุกช่อง');</script>";
          break;
      case 'password_mismatch':
        echo "<script>alert('❌ รหัสผ่านไม่ตรงกัน กรุณากรอกใหม่อีกครั้ง');</script>";
          break;
      case 'email_exists':
        echo "<script>alert('📧 อีเมลนี้มีอยู่ในระบบแล้ว');</script>";
          break;
      case 'create_failed':
        echo "<script>alert('❌ สร้างบัญชีไม่สำเร็จ กรุณาลองใหม่');</script>";
          break;
      case 'no_data':
        echo "<script>alert('⚠️ ไม่พบข้อมูลที่ส่งมา');</script>";
          break;
      }
  }elseif (isset($_GET['success'])) {
      echo "<script>alert('✅ สมัครสมาชิกสำเร็จ! กรุณาเข้าสู่ระบบ');</script>";
  }
  ?>

  <div class="register-box">
    <h2>สมัครบัญชีเกม</h2>
    <form action="process-register.php" method="POST">
      <label>ชื่อผู้ใช้</label>
      <input name="username_account" type="text" id="username" placeholder="ชื่อผู้ใช้ (3–20 ตัว)" required minlength="3" maxlength="20">
      
      <label>อีเมล</label>
      <input name="email_account" type="email" id="email" placeholder="example@email.com" required>

      <label>รหัสผ่าน</label>
      <input name="password_account1" type="password" id="password1" placeholder="รหัสผ่าน (อย่างน้อย 6 ตัว)" required minlength="6">

      <label>ยืนยันรหัสผ่าน</label>
      <input name="password_account2" type="password" id="password2" placeholder="ยืนยันรหัสผ่าน" required minlength="6">

      <label>วันเกิด</label>
      <input type="date" id="birthdate" name="birthdate" required>

      <button type="submit">สมัคร</button>
      <div id="message" class="message"></div>
    </form>
    <div class="link">
      มีบัญชีแล้ว? <a href="login.php">เข้าสู่ระบบที่นี่</a>
      </div>
    <div class="footer">
      © 2025 Game ID System
    </div>
  </div>

</body>
</html>
