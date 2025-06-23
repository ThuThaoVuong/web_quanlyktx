<?php 
session_start();
require_once('../connect.php');

if($_SERVER['REQUEST_METHOD']=='POST'){
    $status=$_POST['status'];
    $id=$_POST['id'];
    $admin=$_SESSION['userid'];
    $sql='update contract set status=?, admin_id=? where id=?';
    $stmt=$conn->prepare($sql);
    $stmt->bind_param('isi',$status, $admin, $id);
    $stmt->execute();
}
?>