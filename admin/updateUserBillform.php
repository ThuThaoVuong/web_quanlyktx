<?php 
session_start();
require_once('../connect.php');

$id=$_POST['id-old'];
$user=$_POST['user'];
$month=$_POST['month'];
$year=$_POST['year'];
$status=$_POST['status']; 

$created='';
if(isset($_POST['created'])){
    $created=$_POST['created'];
}

$paid='';
if(isset($_POST['paid'])){
    $paid=$_POST['paid'];
}

if($id!=''){
    $sql='update users_bill set users_id=?, month=?, year=?, status=? where id=?';
    $stmt=$conn->prepare($sql);
    $stmt->bind_param('siiii', $user, $month, $year, $status, $id);
    $stmt->execute();

    if($created!=''){
        $sql1='update users_bill set created_at=? where id=?';
        $stmt1=$conn->prepare($sql1);
        $stmt1->bind_param('si', $created, $id);
        $stmt1->execute();
    }

    if($paid!=''){
        $sql1='update users_bill set paid_at=? where id=?';
        $stmt1=$conn->prepare($sql1);
        $stmt1->bind_param('si', $paid, $id);
        $stmt1->execute();
    }
}
else {  
    $room=0;
    $sql='select r.price from room r 
    inner join contract c on c.room_id=r.id 
    where c.users_id=?';
    $stmt=$conn->prepare($sql);
    $stmt->bind_param('s',$user);
    $stmt->execute();
    $res=$stmt->get_result();
    while($r=$res->fetch_assoc()) $room=$r['price'];

    if($paid!=''){
        $sql2='insert into users_bill values (null,?,?,?,?,?,?,?)';
        $stmt=$conn->prepare($sql2);
        $stmt->bind_param('siidsis',$user, $month, $year, $room, $created, $status, $paid);
        $stmt->execute();
    }
    else{
        $sql2='insert into users_bill values (null,?,?,?,?,?,?,null)';
        $stmt=$conn->prepare($sql2);
        $stmt->bind_param('siidsi',$user, $month, $year, $room, $created, $status);
        $stmt->execute();
    }
    
}

?>

