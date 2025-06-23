<?php 
session_start();
require_once('../connect.php');

$id=$_POST['id-old'];
$sender=$_POST['sender-old'];

$status=$_POST['status']; 

$created='';
if(isset($_POST['created'])){
    $created=$_POST['created'];
}

if($id!=''){
    $sql='update report set status=? where id=?';
    $stmt=$conn->prepare($sql);
    $stmt->bind_param('ii', $status, $id);
    $stmt->execute();

}
else {  
    $receiver=$_POST['receiver'];

    $title=$_POST['title'];
    $content=$_POST['content'];
        
    $sender=$_SESSION['userid'];
    if(!empty($_FILES['image']['name'])){
        //$uploadPath='C:\xampp\htdocs\quanly2\images\\';
        $uploadPath=__DIR__.'/../images/';
        $image_name=time().'_'.basename($_FILES['image']['name']);

        $upload=$uploadPath.$image_name;
        if(move_uploaded_file($_FILES['image']['tmp_name'], $upload)){
            $image=$image_name;
        }
        $sql='insert into report values (null,?,?,?,?,?,?,?)';
        $stmt=$conn->prepare($sql);
        if(is_array($receiver)){
            foreach($receiver as $i){
                $stmt->bind_param('ssssssi',$sender, $i, $title, $content, $image, $created, $status);
                $stmt->execute();
            }
        }
        else{
            $stmt->bind_param('ssssssi',$sender, $receiver, $title, $content, $image, $created, $status);
            $stmt->execute();
        }
    }
    else{
        $sql='insert into report values (null,?,?,?,?,null,?,?)';
        $stmt=$conn->prepare($sql);
        if(is_array($receiver)){
            foreach($receiver as $i){
                $stmt->bind_param('sssssi',$sender, $i, $title, $content, $created, $status);
                $stmt->execute();
            }
        }
        else{
            $stmt->bind_param('sssssi',$sender, $receiver, $title, $content, $created, $status);
            $stmt->execute();
        }
    }

}

?>

