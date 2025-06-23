<?php
session_start();
require_once('../connect.php');
$sql = "select r.*, k.name as kname from room r inner join ktx k on k.id=r.ktx_id 
where r.status>0 and r.name like ? and area like ? ";

$type=''; $param=[];
$name=$_POST['name']; $name="%$name%"; $type.='s'; $param[]=$name;
$area=$_POST['area']; $area="%$area%"; $type.='s'; $param[]=$area;

$id=$_POST['id']; 
if($id!='') {
    $sql.=" and r.id=? "; $type.='i'; $param[]=$id;
}

$gender=$_POST['gender']; 
if($gender!=0) {
    $sql.=" and gender=?"; $type.='i'; $param[]=$gender-1;
}

$slot=$_POST['slot']; 
if($slot!=0) {
    $sql.=" and slot=?"; $type.='i'; $param[]=$slot;
}

$price=$_POST['price'];
if($price==1){
    $sql.=" and price<500000";
}
else if($price==2) $sql.=' and price >=500000';

$status=$_POST['status']; 
if($status!=0) {
    $sql.=" and r.status=?"; $type.='i'; $param[]=$status;
}

$ktx=$_POST['ktx']; 
if($ktx!=0) {
    $sql.=" and ktx_id=?"; $type.='i'; $param[]=$ktx;
}

$stmt=$conn->prepare($sql);
$stmt->bind_param($type, ...$param);
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
    $row['has']=0;
    $sql1='select count(id) as has from contract where status=1 and room_id=?';
    $i=$row['id'];
    $stmt1=$conn->prepare($sql1);
    $stmt1->bind_param('i',$i);
    $stmt1->execute();
    $res1=$stmt1->get_result();
    while($r=$res1->fetch_assoc()) $row['has']=$r['has'];
?>
    <tr>
        <td><?php echo $index; ?></td>
        <td><?php echo $row['id'] ?></td>
        <td><?php echo $row['kname'] ?></td>
        <td><?php echo $row['name'] ?></td>
        <td><?php echo $row['area'] ?></td>
        <td><?php
            if ($row['gender'] == 0) echo 'Nam';
            else if ($row['gender'] == 1) echo 'Nữ';
            ?></td>
        <td><?php echo $row['slot'];?></td>
        <td><?php echo $row['has'];?></td>
        <td><?php echo round($row['price']);?></td>
        <td><?php
            if ($row['status'] == 1) echo 'Đang hoạt động';
            else if ($row['status'] == 2) echo 'Tạm dừng hoạt động';
            ?></td>
        <td>
            <a href="./updateroom.php?def=update&id=<?php echo $row['id'] ?>"><button class="btn btn-sm">Xem/Sửa</button></a>
            <a href="./updateroom.php?def=del&id=<?php echo $row['id'] ?>"
            onclick="return confirm('Bạn có chắc chắn muốn xóa không?')">
            <button class="btn btn-sm">Xóa</button></a>
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
