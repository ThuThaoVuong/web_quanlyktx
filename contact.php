<?php
session_start();
require_once('./connect.php');

$email=$_POST['email'];
if(isset($_POST['type'])){
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        echo "Sai định dạng email"; 
    }
    exit;
}

$content=$_POST['content'];
$title='Liên hệ từ người dùng chưa đăng nhập';
$date=date('Y-m-d H:i:s');
$sql="insert into report values (null,?,'NV1',?,?,null,?,1)";
$stmt=$conn->prepare($sql);
$stmt->bind_param('ssss', $email, $title, $content, $date);
$stmt->execute();
?>