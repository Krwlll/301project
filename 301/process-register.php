<?php

$open_connect = 1;
require('connect.php');

if (isset($_POST['username_account']) && isset($_POST['email_account']) && isset($_POST['password_account1']) && isset($_POST['password_account2']) && isset($_POST['birthdate'])){

    $username_account = htmlspecialchars(mysqli_real_escape_string($connect, $_POST['username_account']));
    $email_account = htmlspecialchars(mysqli_real_escape_string($connect, $_POST['email_account']));
    $password_account1 = mysqli_real_escape_string($connect, $_POST['password_account1']);
    $password_account2 = mysqli_real_escape_string($connect, $_POST['password_account2']);
    $birthdate = htmlspecialchars(mysqli_real_escape_string($connect, $_POST['birthdate']));

    if (empty($username_account) || empty($email_account) || empty($password_account1) || empty($password_account2) || empty($birthdate)){
        header('Location: register.php?error=empty_field');
        exit;
    } elseif ($password_account1 !== $password_account2) {
        header('Location: register.php?error=password_mismatch');
        exit;
    } else {
        // ตรวจสอบอีเมลซ้ำ
        $query_check_email_account = "SELECT * FROM account WHERE email_account = '$email_account'";
        $call_back_query_check_email_account = mysqli_query($connect, $query_check_email_account);

        if (mysqli_num_rows($call_back_query_check_email_account) > 0) {
            header('Location: register.php?error=email_exists');
            exit;
        } else {
            // สร้างค่า salt และเข้ารหัสรหัสผ่าน
            $salt_account = bin2hex(random_bytes(16));
            $password_account1 = $password_account1 . $salt_account;
            $algo = PASSWORD_ARGON2ID;
            $options = [
                'memory_cost' => 1 << 17, // 128 MB
                'time_cost' => 4,
                'threads' => 3
            ];
            $password_account = password_hash($password_account1, $algo, $options);

            // เพิ่มข้อมูลในฐานข้อมูล
            $query_create_account = "
                INSERT INTO account 
                VALUES ('', '$username_account', '$email_account', '$password_account', '$birthdate', '$salt_account', 'member')
            ";
            $call_back_create_account = mysqli_query($connect, $query_create_account);

            if ($call_back_create_account) {
                echo "
                <script>
                    alert('✅ สมัครสมาชิกสำเร็จ! กรุณาเข้าสู่ระบบ');
                    window.location.href = 'login.php';
                </script>";
                exit;
            } else {
                header('Location: register.php?error=create_failed');
                exit;
            }
        }
    }

} else {
    header('Location: register.php?error=no_data');
    exit;
}

?>