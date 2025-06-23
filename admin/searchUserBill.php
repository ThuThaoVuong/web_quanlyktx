<?php
session_start();
require_once('../connect.php');
$sql = "select ub.*, u.name as uname from users_bill ub
inner join users u on u.msv=ub.users_id 
where ub.status>0 ";

$type=''; $param=[];

$id=$_POST['id']; 
if($id!='' && $id!=0) {
    $sql.=" and ub.id=? "; $type.='i'; $param[]=$id;
}

$msv=$_POST['msv']; 
if($msv!=0) {
    $msv="%$msv%";
    $sql.=" and ub.users_id like ?"; $type.='s'; $param[]=$msv;
}

$name=$_POST['name'];
if($name!=''){
    $name="%$name%";
    $sql.=' and u.name like ?'; $type.='s'; $param[]=$name;
}

$month=$_POST['month']; 
if($month!=0 && $month!='') {
    $sql.=" and month=?"; $type.='i'; $param[]=$month;
}

$year=$_POST['year'];
if($year!='' && $year!=0){
    $sql.=" and year=?"; $type.='i'; $param[]=$year;
}

$room=$_POST['room'];
if($room==1){
    $sql.=' and room_fee<500000';
}
else if($room==2){
    $sql.=' and room_fee >= 500000';
}

$status=$_POST['status']; 
if($status!=0) {
    $sql.=" and ub.status=?"; $type.='i'; $param[]=$status;
}

$stmt=$conn->prepare($sql);
if($type!='') $stmt->bind_param($type, ...$param);
$stmt->execute();
$res=$stmt->get_result();

$total=ceil($res->num_rows/3);

$sql.=" limit 3 offset ?";
$page=$_POST['page']; $type.='i';
$index=($page-1)*3; $param[]=$index;

$stmt=$conn->prepare($sql);
if($type!='') $stmt->bind_param($type, ...$param);
$stmt->execute();
$res=$stmt->get_result();
$index+=1;
ob_start();
while ($row = $res->fetch_assoc()) {
    $date=DateTime::createFromFormat('Y-m-d H:i:s', $row['created_at']);
    $created=$date->format('d/m/Y');
    $paid='Chưa có';
    if($row['paid_at']!=''){
        $date=DateTime::createFromFormat('Y-m-d H:i:s', $row['paid_at']);
        $paid=$date->format('d/m/Y');
    }
?>
    <tr>
        <td><?php echo $index; ?></td>
        <td><?php echo $row['id'] ?></td>
        <td><?php echo $row['users_id']?></td>
        <td><?php echo $row['uname']?></td>
        <td><?php echo $row['month'] ?></td>
        <td><?php echo $row['year'];?></td>
        <td><?php echo round($row['room_fee']);?></td>
        <td><?php echo $created;?></td>
        <td><?php
            if ($row['status'] == 1) echo 'Chưa trả';
            else if ($row['status'] == 2) echo 'Đã trả';
            ?></td>
        <td><?php echo $paid;?></td>
        <td>
            <a href="./updateuserbill.php?def=update&id=<?php echo $row['id'] ?>"><button class="btn btn-sm">Xem/Sửa</button></a>
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
