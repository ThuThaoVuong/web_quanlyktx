<?php
session_start();
require_once('../connect.php');
$sql = "select * from report where status>0 ";

$type=''; $param=[];
$id=$_POST['id']; 
if($id!='') {
    $sql.=" and id=? "; $type.='i'; $param[]=$id;
}

$sender=$_POST['sender']; 
if($sender!='') {
    $sql.=" and sender_id like ? "; $type.='s'; $param[]="%$sender%";
}

$receiver=$_POST['receiver']; 
if($receiver!='') {
    $sql.=" and receiver_id like ?"; $type.='s'; $param[]="%$receiver%";
}

$title=$_POST['title']; 
if($title!='') {
    $sql.=" and title like ?"; $type.='s'; $param[]="%$title%";
}

$content=$_POST['content'];
if($content!=''){
    $sql.=" and content like ?"; $type.='s'; $param[]="%$content%";
}

$status=$_POST['status']; 
if($status!=0) {
    $sql.=" and status=?"; $type.='i'; $param[]=$status;
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
    $date=DateTime::createFromFormat('Y-m-d H:i:s',$row['created_at']);
    $created=$date->format('d/m/Y');
?>
    <tr>
        <td><?php echo $index; ?></td>
        <td><?php echo $row['id'] ?></td>
        <td><?php echo $row['sender_id'] ?></td>
        <td><?php echo $row['receiver_id'] ?></td>
        <td><?php echo $row['title'] ?></td>
        <td><?php echo $row['content'];?></td>
        <td><?php echo $created;?></td>
        <td><?php
            if ($row['status'] == 1) echo 'Chưa xem';
            else if ($row['status'] == 2) echo 'Đã xem';
            ?></td>
        <td>
            <a href="./updatereport.php?def=update&id=<?php echo $row['id'] ?>"><button class="btn btn-sm">Xem/Sửa</button></a>
            <a href="./updatereport.php?def=del&id=<?php echo $row['id'] ?>"
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
