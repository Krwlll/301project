<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>เข้าสู่ระบบ</title>
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
    .box {
      background: rgba(30, 30, 30, 0.9);
      padding: 40px;
      border-radius: 16px;
      border: 1px solid #333;
      width: 100%;
      max-width: 420px;
      box-shadow: 0 0 15px rgba(0,0,0,0.5);
    }
    h2 {
      text-align: center;
      color: #ff4655;
      margin-bottom: 30px;
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
    input:focus { outline: 2px solid #ff4655; }
    button {
      width: 100%;
      padding: 12px;
      margin-top: 20px;
      background: #ff4655;
      border: none;
      border-radius: 10px;
      color: #fff;
      font-weight: bold;
      cursor: pointer;
      transition: background 0.3s;
    }
    button:hover { background: #ff5e6d; }
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
  </style>
</head>
<body>

<?php if (isset($_GET['error'])): ?>
<script>
<?php if ($_GET['error'] == 'wrong_password'): ?>
alert('❌ รหัสผ่านไม่ถูกต้อง');
<?php elseif ($_GET['error'] == 'email_not_found'): ?>
alert('❌ ไม่พบอีเมลนี้ในระบบ');
<?php elseif ($_GET['error'] == 'empty_field'): ?>
alert('⚠️ กรุณากรอกข้อมูลให้ครบ');
<?php endif; ?>
</script>
<?php endif; ?>

<?php if (isset($_GET['login']) && $_GET['login'] == 'success'): ?>
<script>
alert('✅ เข้าสู่ระบบสำเร็จ!');
</script>
<?php endif; ?>

<div class="box">
  <h2>เข้าสู่ระบบ</h2>
  <form action="process-login.php" method="POST">
    <label>อีเมล</label>
    <input name="email_account" type="email" id="email" placeholder="example@email.com" required>
    <label>รหัสผ่าน</label>
    <input name="password_account" type="password" id="password" placeholder="รหัสผ่าน" required>
    <button type="submit">เข้าสู่ระบบ</button>
    <div id="message" class="message"></div>
  </form>
  <div class="link">
    ยังไม่มีบัญชี? <a href="register.php">สมัครที่นี่</a>
  </div>
</div>
  
</body>
</html>
