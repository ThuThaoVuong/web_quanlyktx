<?php 
session_start();
require_once('../connect.php');

$id=$_POST['id-old'];
$room=$_POST['room'];
$month=$_POST['month'];
$year=$_POST['year'];
$electricity=$_POST['electricity'];
$water=$_POST['water'];
$total=$electricity+$water;
$status=$_POST['status']; 

$created='';
if(isset($_POST['created'])){
    $created=$_POST['created'];
}

$user='';
if(isset($_POST['user'])){
    $user=$_POST['user'];
}

$paid='';
if(isset($_POST['paid'])){
    $paid=$_POST['paid'];
}


if($id!=''){
    $sql='update room_bill set room_id=?, month=?, year=?, electricity_fee=?, water_fee=?, total=?, status=? where id=?';
    $stmt=$conn->prepare($sql);
    $stmt->bind_param('ssiidddi', $room, $month, $year, $electricity, $water, $total, $status, $id);
    $stmt->execute();

    if($user!=''){
        $sql1='update room_bill set paid_by_user_id=? where id=?';
        $stmt1=$conn->prepare($sql1);
        $stmt1->bind_param('si', $user, $id);
        $stmt1->execute();
    }
    if($created!=''){
        $sql1='update room_bill set created_at=? where id=?';
        $stmt1=$conn->prepare($sql1);
        $stmt1->bind_param('si', $created, $id);
        $stmt1->execute();
    }
    if($paid!=''){
        $sql1='update room_bill set paid_at=? where id=?';
        $stmt1=$conn->prepare($sql1);
        $stmt1->bind_param('si', $paid, $id);
        $stmt1->execute();
    }
}
else {  
    if($paid!=''){
        $sql2='insert into room_bill values (null,?,?,?,?,?,?,?,?,?,?)';
        $stmt=$conn->prepare($sql2);
        $stmt->bind_param('iiidddsiss',$room, $month, $year, $electricity, $water, $total, $created, $status, $user, $paid);
        $stmt->execute();
    }
    else{
        $sql2='insert into room_bill values (null,?,?,?,?,?,?,?,?,?,null)';
        $stmt=$conn->prepare($sql2);
        $stmt->bind_param('iiidddsis',$room, $month, $year, $electricity, $water, $total, $created, $status, $user);
        $stmt->execute();
    }
    
}

?>

