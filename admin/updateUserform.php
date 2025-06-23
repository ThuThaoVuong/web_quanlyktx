<?php 
session_start();
require_once('../connect.php');

if($_SERVER['REQUEST_METHOD']=='POST'){
    $status=$_POST['status'];
    $id=$_POST['id-old'];
    $sql='update users set status=? where msv=?';
    $stmt=$conn->prepare($sql);
    $stmt->bind_param('is',$status, $id);
    $stmt->execute();
}
?>