<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

session_start();
//Load Composer's autoloader (created by composer, not included with PHPMailer)
//require 'vendor/autoload.php';

require_once('./PHPMailer-master/src/PHPMailer.php');
require_once('./PHPMailer-master/src/SMTP.php');
require_once('./PHPMailer-master/src/Exception.php');
require_once ('./connect.php');

$email=$_POST['email'];
$sql="select * from users where email=?";
$stmt=$conn->prepare($sql);
$stmt->bind_param('s',$email);
$stmt->execute();
$res=$stmt->get_result();
if($res->num_rows==0){
    echo "Không tồn tại email này trong hệ thống";
    exit;
}

$sql="update users set password=? where email=?";
$new='123';
$password=password_hash($new, PASSWORD_BCRYPT);
$stmt=$conn->prepare($sql);
$stmt->bind_param('ss',$password, $email);
$stmt->execute();

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'halanhaest2009@gmail.com';                     //SMTP username
    $mail->Password   = 'ymhohvaclmrkpllg';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('halanhaest2009@gmail.com', 'Admin KTX');
    $mail->addAddress($email);     //Add a recipient

    //Content
    $mail->CharSet='UTF-8';
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Khôi phục mật khẩu';
    $mail->Body    = 'Mật khẩu mới là: <b>123</b>';

    $mail->SMTPOptions = array(
        'ssl'=>[
            'verify_peer'=> true, 
            'verify_depth'=>3,
            'allow_self_signed'=>true,
        ],
    );

    $mail->send();
    echo 'Gửi xong';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}