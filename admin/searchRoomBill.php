<?php
session_start();
require_once('../connect.php');
$sql = "select rb.*, r.name as rname, k.name as kname from room_bill rb 
inner join room r on r.id=rb.room_id 
inner join ktx k on k.id=r.ktx_id 
where rb.status>0";

$type=''; $param=[];

$id=$_POST['id']; 
if($id!='' && $id!=0) {
    $sql.=" and rb.id=? "; $type.='i'; $param[]=$id;
}

$room=$_POST['room']; 
if($room!=0) {
    $sql.=" and rb.room_id=?"; $type.='i'; $param[]=$room;
}

$month=$_POST['month']; 
if($month!=0 && $month!='') {
    $sql.=" and month=?"; $type.='i'; $param[]=$month;
}

$year=$_POST['year'];
if($year!='' && $year!=0){
    $sql.=" and year=?"; $type.='i'; $param[]=$year;
}

$electricity=$_POST['electricity'];
if($electricity==1){
    $sql.=' and electricity_fee<200000';
}
else if($electricity==2){
    $sql.=' and (electricity_fee between 200000 and 500000)';
}
else if($electricity==3){
    $sql.=' and electricity>500000';
}

$water=$_POST['water'];
if($water==1){
    $sql.=' and water_fee<100000';
}
else if($water==2) $sql.=' and water_fee>=100000';

$total=$_POST['total'];
if($total==1) $sql.=' and total<500000';
else if($total==2) $sql.=' and total>=500000';

$status=$_POST['status']; 
if($status!=0) {
    $sql.=" and rb.status=?"; $type.='i'; $param[]=$status;
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
$stmt->bind_param($type, ...$param);
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
    $user='Chưa có';
    if($row['paid_by_user_id']!=''){
        $sql1='select name from users where msv=?';
        $stmt1=$conn->prepare($sql1);
        $stmt1->bind_param('s', $row['paid_by_user_id']);
        $stmt1->execute();
        $res1=$stmt1->get_result();
        while($r=$res1->fetch_assoc()) $user=$r['name'];
    }
?>
    <tr>
        <td><?php echo $index; ?></td>
        <td><?php echo $row['id'] ?></td>
        <td><?php echo $row['rname'].' - '.$row['kname'] ?></td>
        <td><?php echo $row['month'] ?></td>
        <td><?php echo $row['year'];?></td>
        <td><?php echo round($row['electricity_fee']);?></td>
        <td><?php echo round($row['water_fee']);?></td>
        <td><?php echo round($row['total']);?></td>
        <td><?php echo $created;?></td>
        <td><?php
            if ($row['status'] == 1) echo 'Chưa trả';
            else if ($row['status'] == 2) echo 'Đã trả';
            ?></td>
        <td><?php echo $user;?></td>
        <td><?php echo $paid;?></td>
        <td>
            <a href="./updateroombill.php?def=update&id=<?php echo $row['id'] ?>"><button class="btn btn-sm">Xem/Sửa</button></a>
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
