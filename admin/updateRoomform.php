<?php 
session_start();
require_once('../connect.php');

$id=$_POST['id-old'];
$ktx=$_POST['ktx'];
$name=$_POST['name'];
$area=$_POST['area'];
$gender=$_POST['gender']-1;
$slot=$_POST['slot'];
$price=$_POST['price'];
$status=$_POST['status']; 


if($id!=''){
    $sql='update room set name=?, area=?, gender=?, slot=?, price=?, ktx_id=?, status=? where id=?';
    $stmt=$conn->prepare($sql);
    $stmt->bind_param('ssiidiii', $name, $area, $gender, $slot, $price, $ktx, $status, $id);
    $stmt->execute();

    if(!empty($_FILES['image']['name'])){
        //$uploadPath='C:\xampp\htdocs\quanly2\images\\';
        $uploadPath=__DIR__.'/../images/';
        $image_name=time().'_'.basename($_FILES['image']['name']);

        $upload=$uploadPath.$image_name;
        if(move_uploaded_file($_FILES['image']['tmp_name'], $upload)){
            $a=1;
        }
        $sql1='update room set image=? where id=?';
        $stmt=$conn->prepare($sql1);
        $stmt->bind_param('si', $image_name, $id);
        $stmt->execute();
    }
}
else {
    $sql2='insert into room values (null,?,?,?,?,?,?,?,?)';
    if(!empty($_FILES['image']['name'])){
        //$uploadPath='C:\xampp\htdocs\quanly2\images\\';
        $uploadPath=__DIR__.'/../images/';
        $image_name=time().'_'.basename($_FILES['image']['name']);

        $upload=$uploadPath.$image_name;
        if(move_uploaded_file($_FILES['image']['tmp_name'], $upload)){
            $image=$image_name;
        }
    }
    else $image=null;
    $stmt=$conn->prepare($sql2);
    $stmt->bind_param('ssiidsii',$name, $area, $gender, $slot, $price, $image, $ktx, $status);
    $stmt->execute();
}

?>

