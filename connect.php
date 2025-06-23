<?php 
$host='localhost';
$user='root';
$pass='';
$db='quanly2';

$conn=mysqli_connect($host,$user,$pass,$db);

if($conn==false){
    die('Lỗi kết nối database'.mysqli_connect_error());
}
mysqli_set_charset($conn,'utf8');
