<?php 
session_start();
require_once('../connect.php');

$id=$_POST['id-old'];
$name=$_POST['name'];
$address=$_POST['address'];
$info=$_POST['info'];
$status=$_POST['status'];

if($id!=''){
    $sql='update ktx set name=?, address=?, info=?, status=? where id=?';
    $stmt=$conn->prepare($sql);
    $stmt->bind_param('sssii', $name, $address, $info, $status, $id);
    $stmt->execute();

    if(!empty($_FILES['image']['name'])){
        //$uploadPath='C:\xampp\htdocs\quanly2\images\\';
        $uploadPath=__DIR__.'/../images/';
        $image_name=time().'_'.basename($_FILES['image']['name']);

        $upload=$uploadPath.$image_name;
        if(move_uploaded_file($_FILES['image']['tmp_name'], $upload)){
            $a=1;
        }
        $sql1='update ktx set image=? where id=?';
        $stmt=$conn->prepare($sql1);
        $stmt->bind_param('si', $image_name, $id);
        $stmt->execute();
    }
}
else {
    $sql2='insert into ktx values (null,?,?,?,?,?)';
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
    $stmt->bind_param('ssssi',$name, $address, $info, $image ,$status);
    $stmt->execute();
}

?>

