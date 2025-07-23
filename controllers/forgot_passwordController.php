<?php
session_start();
require_once __DIR__ . '/../config/env.php';
require_once BASE_PATH . 'config/database.php';
require_once BASE_PATH . 'function/baseurl.php';
include_once BASE_PATH . 'models/users.php';
require BASE_PATH . 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


if(isset($_POST['forgot_password'])){
    $email = $_POST['email'] ?? null;
    $user = $usersModel->getByEmail($email);

    if(!$user){
        $_SESSION['alert'] = [
            'type' => 'danger',
            'message' => 'Email belum terdaftar!'  
        ];
        header('Location:' . $BaseUrl->getUrlForgotPassword());
        exit();
    }

    // generate token random
    $token = bin2hex(random_bytes(32));
    $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

    // update token di database
    $usersModel->setResetToken($token, $expiry, $email);

    // kirim email php mailer
    $mail = new PHPMailer(true);

    try{
        $mail->isSMTP();
        $mail->Host = MAIL_HOST;
        $mail->SMTPAuth = true;
        $mail->Port = MAIL_PORT;
        $mail->Username = MAIL_USERNAME;
        $mail->Password = MAIL_PASSWORD;

        $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
        $mail->addAddress($email, $user['username']);
        $mail->isHTML(true);
        $mail->Subject = 'Reset Password';
        $mail->Body = '
            <h1>Reset Password</h1>
            <p>Click the link below to reset your password.</p>
            <a href="'. $BaseUrl->getUrlForgotPassword() . '?token=' . urlencode($token) . '">Klik Disini</a>
            ';
        $mail->send();
        $_SESSION['alert'] = [
          'type' => 'success',
          'message' => 'Email berhasil dikirim!'
        ];
        header('Location:' . $BaseUrl->getUrlForgotPassword());
    } catch (Exception $e){
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
else if (isset($_POST['reset_password'])){
    $token = $_POST['token'] ?? null;
    $passwordLama = $_POST['password'] ?? null;
    $passwordBaru = password_hash($passwordLama, PASSWORD_DEFAULT);

    $user = $usersModel->updatePasswordByToken($token, $passwordBaru);

    if(empty($passwordLama) || empty($token) || empty($user)){
        $_SESSION['alert'] = [
            'type' => 'danger',
            'message' => 'Password tidak boleh kosong!'  
        ];
        header('Location:' . $BaseUrl->getUrlForgotPassword() . '?token=' . urlencode($token));
        exit();
    } else {
        $_SESSION['alert'] = [
            'type' => 'success',
            'message' => 'Password berhasil diubah!'  
        ];
        header('Location:' . $BaseUrl->getUrlLogin());
        exit();

    }
}
