<?php
session_start();
require_once('../connect.php');
$sql = "select * from ktx 
where status>0 and name like ? and address like ? and info like ? ";

$id=$_POST['id']; if($id!='') $sql.=" and id=? ";
$name=$_POST['name']; $name="%$name%";
$address=$_POST['address']; $address="%$address%";
$info=$_POST['info']; $info="%$info%";
$status=$_POST['status']; 
if($status!=0) $sql.=" and status=? ";

$stmt=$conn->prepare($sql);
if($id!='' && $status!=0) $stmt->bind_param("sssii",$name, $address, $info, $id, $status);
else if($id!='') $stmt->bind_param("sssi",$name, $address, $info, $id);
else if($status!=0) $stmt->bind_param("sssi",$name, $address, $info, $status);
else $stmt->bind_param("sss",$name, $address, $info);

$stmt->execute();
$res=$stmt->get_result();

$total=ceil($res->num_rows/3);

$sql.=" limit 3 offset ?";
$page=$_POST['page'];
$index=($page-1)*3;

$stmt=$conn->prepare($sql);
if($id!='' && $status!=0) $stmt->bind_param("sssiii",$name, $address, $info, $id, $status, $index);
else if($id!='') $stmt->bind_param("sssii",$name, $address, $info, $id, $index);
else if($status!=0) $stmt->bind_param("sssii",$name, $address, $info, $status, $index);
else $stmt->bind_param("sssi",$name, $address, $info, $index);

$stmt->execute();
$res=$stmt->get_result();
$index+=1;
ob_start();
while ($row = $res->fetch_assoc()) {
?>
    <tr>
        <td><?php echo $index; ?></td>
        <td><?php echo $row['id'] ?></td>
        <td><?php echo $row['name'] ?></td>
        <td><?php echo $row['address'] ?></td>
        <td><?php echo $row['info'] ?></td>
        <td><?php
            if ($row['status'] == 1) echo 'Đang hoạt động';
            else if ($row['status'] == 2) echo 'Chưa hoạt động';
            else echo 'Tạm dừng hoạt động';
            ?></td>
        <td>
            <a href="./updatektx.php?def=update&id=<?php echo $row['id'] ?>"><button class="btn btn-sm">Xem/Sửa</button></a>
            <a href="./updatektx.php?def=del&id=<?php echo $row['id'] ?>"
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
