<?php
session_start();
require_once("./connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $type = $_POST['type'];
    $value = trim($_POST['value']);

    if ($type == 'Msv') {
        if(preg_match('/^[B,N][0-9]{2}[A-Z]{4}[0-9]{3}$/',$value)==0) echo "Sai định dạng MSV";
        else if(preg_match('/^[B,N][0-9]{2}[A-Z]{4}000$/',$value)==1) echo "Sai đinh dạng MSV";
        $stmt = $conn->prepare("SELECT msv FROM users WHERE msv = ?");
    } 
    else if ($type == 'Email') {
        if(!filter_var($value,FILTER_VALIDATE_EMAIL)) {
            echo "Sai định dạng email"; exit;
        }
        $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    }
    else if($type=='phone'){
        if(preg_match('/^0[1-9][0-9]{8}$/',$value)==0) echo "Sai định dạng SDT";
        exit;
    }

    $stmt->bind_param("s", $value);
    $stmt->execute();
    $res=$stmt->get_result();

    if ($res->num_rows > 0) {
        echo "$type đã tồn tại";
    } 

    $stmt->close();
}
?>
