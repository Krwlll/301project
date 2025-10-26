<?php
session_start();

$open_connect = 1;
require('connect.php');

if(isset($_POST['email_account']) && isset($_POST['password_account'])){
    $email_account = htmlspecialchars(mysqli_real_escape_string($connect, $_POST['email_account']));
    $password_account = htmlspecialchars(mysqli_real_escape_string($connect, $_POST['password_account']));
    $query_check_account = "SELECT * FROM account WHERE email_account = '$email_account'";
    $call_back_check_account = mysqli_query($connect, $query_check_account);

    if(mysqli_num_rows($call_back_check_account) == 1){
        $result_check_account = mysqli_fetch_assoc($call_back_check_account);
        $hash = $result_check_account['password_account'];
        $password_account = $password_account . $result_check_account['salt_account'];

        if(password_verify($password_account, $hash)){

            $_SESSION['id_account'] = $result_check_account['id_account'];
            $_SESSION['username_account'] = $result_check_account['username_account'];
            $_SESSION['role_account'] = $result_check_account['role_account'];
            $_SESSION['email_account'] = $result_check_account['email_account'];

            if($result_check_account['role_account'] == 'member'){ // role member
                die(header('Location: memberhub.php?login=success'));
            }elseif($result_check_account['role_account'] == 'admin'){ // role admin
                die(header('Location: adminhub.php?login=success'));
            }
        }else{
            die(header('Location: login.php?error=wrong_password')); //รหัสผ่านไม่ถูกต้อง
        }

    }else{
        die(header('Location: login.php?error=email_not_found')); //ไม่มีอีเมลนี้ในระบบ
    }

}else{
    die(header('Location: login.php?error=empty_field')); // กรุณากรอกข้อมูล
}
?>