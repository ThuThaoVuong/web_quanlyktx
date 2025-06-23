<?php
session_start();
require_once('../connect.php');
$sql = "select * from users 
where status>0 ";

$type=''; $param=[];

$msv=$_POST['id']; 
if($msv!='' && is_array($msv)==false){
    $sql.=' and msv like ? ';
    $msv="%$msv%"; $type.='s'; $param[]=$msv;
}
else if($msv!='' && is_array($msv)){
    $a=implode(',',array_fill(0,count($msv),'?'));
    $sql.=" and msv in ($a) ";
    foreach($msv as $i){
        $type.='s'; $param[]=$i;
    }
}

$name=$_POST['name']; 
if($name!=''){
    $sql.=' and name like ? ';
    $name="%$name%"; $type.='s'; $param[]=$name;
}

$phone=$_POST['phone']; 
if($phone!=''){
    $sql.=' and phone like ? ';
    $phone="%$phone%"; $type.='s'; $param[]=$phone;
}

$address=$_POST['address']; 
if($address!=''){
    $sql.=' and address like ? ';
    $address="%$address%"; $type.='s'; $param[]=$address;
}

$email=$_POST['email']; 
if($email!=''){
    $sql.=' and email like ? ';
    $email="%$email%"; $type.='s'; $param[]=$email;
}


$status=$_POST['status']; 
if($status!=0) {
    $sql.=" and status=? "; $type.='i'; $param[]=$status;
}
$gender=$_POST['gender']; 
if($gender!=0) {
    $gender-=1;
    $sql.=" and gender = ? "; $type.='i'; $param[]=$gender;
}

$stmt=$conn->prepare($sql);
if($type!='') $stmt->bind_param($type, ...$param);

$stmt->execute();
$res=$stmt->get_result();

$total=ceil($res->num_rows/3);
$index=0;
if(!isset($_POST['lim'])){
    $sql.=" limit 3 offset ?";
$page=$_POST['page'];
$index=($page-1)*3;
$type.='i'; $param[]=$index;
$stmt=$conn->prepare($sql);
if($type!='') $stmt->bind_param($type, ...$param);

$stmt->execute();
$res=$stmt->get_result();
}

$index+=1;
ob_start();
while ($row = $res->fetch_assoc()) {
    $date= DateTime::createFromFormat('Y-m-d', $row['dob']);
    $dob= $date->format('d/m/Y');
?>
    <tr>
        <td><?php echo $index; ?></td>
        <td><?php echo $row['msv'] ?></td>
        <td><?php echo $row['name'] ?></td>
        <td><?php 
            if ($row['gender'] == 0) echo 'Nam';
            else echo 'Nữ';     
        ?></td>
        <td><?php echo $dob ?></td>
        <td><?php echo $row['phone'] ?></td>
        <td><?php echo $row['address'] ?></td>
        <td><?php echo $row['email'] ?></td>
        <td><?php
            if ($row['status'] == 1) echo 'Sinh viên';
            else if ($row['status'] == 2) echo 'Admin';
            ?></td>
        <td>
            <a href="./updateuser.php?def=update&id=<?php echo $row['msv'] ?>">
                <button class="btn btn-sm">Xem/Sửa</button></a>
        </td>
    </tr>
<?php $index++;
} ?>
<?php $table=ob_get_clean();
ob_start();?>
<ul class="pagination pagination-sm">
    <?php for ($i = 1; $i <= $total; $i++){
        $a='';
        if($i==$page) $a='active';
        echo "<li class='$a' ><a class='changePage'>" . $i . "</a></li>"; 
    }
    ?>
</ul>
<?php
$p=ob_get_clean();

echo json_encode([
    'table' => $table,
    'pagination' => $p
]);
?>
