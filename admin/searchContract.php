<?php
session_start();
require_once('../connect.php');
$update="update contract set status=4 where status=1 and adddate(now(), INTERVAL 10 day)>= end_date";
$stmt=$conn->prepare($update);
$stmt->execute();

$update2="update contract set status=2 where status=1 and now()= end_date";
$stmt=$conn->prepare($update2);
$stmt->execute();


$sql = "select c.*, u.name as uname, r.name as rname, k.name as kname from contract c
inner join users u on c.users_id=u.msv 
inner join room r on r.id=c.room_id 
inner join ktx k on k.id=r.ktx_id 
where c.status>0 ";

$type=''; $param=[];

$id=$_POST['id']; 
if($id!='' && $id!=0) {
    $sql.=" and c.id=? "; $type.='i'; $param[]=$id;
}

$user=$_POST['user']; 
if($user!=0) {
    $sql.=" and c.users_id=? "; $type.='s'; $param[]=$user;
}

$room=$_POST['room']; 
if($room!=0) {
    $sql.=" and c.room_id=? "; $type.='i'; $param[]=$room;
}

$month=$_POST['month'];
if($month!='' && $month!=0){
    $sql.=" and month = ? "; $type.='i'; $param[]=$month;
}

$admin=$_POST['admin'];
if($admin!=0){
    $sql=" and c.admin_id=? "; $type.='s'; $param[]=$admin;
}

$status=$_POST['status']; 
if($status!=0) {
    $sql.=" and c.status=? "; $type.='i'; $param[]=$status;
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
    $date=DateTime::createFromFormat('Y-m-d', $row['start_date']);
    $start=$date->format('d/m/Y');
    $date=DateTime::createFromFormat('Y-m-d', $row['end_date']);
    $end=$date->format('d/m/Y');
    $date=DateTime::createFromFormat('Y-m-d H:i:s', $row['created_at']);
    $created_at=$date->format('d/m/Y');

    $row['admin']='Chưa được xem';
    $i=$row['admin_id'];
    $sql1='select name from users where msv=?';
    $stmt1=$conn->prepare($sql1);
    $stmt1->bind_param('s',$i);
    $stmt1->execute();
    $res1=$stmt1->get_result();
    while($r=$res1->fetch_assoc()) $row['admin']=$r['name'];
?>
    <tr>
        <td><?php echo $index; ?></td>
        <td><?php echo $row['id'] ?></td>
        <td><?php echo $row['uname'] ?></td>
        <td><?php echo $row['kname'] ?></td>
        <td><?php echo $start ?></td>
        <td><?php echo $row['month_living'];?></td>
        <td><?php echo $end;?></td>
        <td><?php echo $row['admin'];?></td>
        <td><?php echo $created_at;?></td>
        <td><?php
            if ($row['status'] == 1) echo 'Có hiệu lực';
            else if ($row['status'] == 2) echo 'Hết hiệu lực';
            else if($row['status']==3) echo "Chưa có hiệu lực";
            else if($row['status']==4) echo "Sắp hết hạn";
            ?></td>
        <td>
            <a href="./updatecontract.php?def=update&id=<?php echo $row['id'] ?>"><button class="btn btn-sm">Xem/Sửa</button></a>
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
