<?php
session_start();
require_once('connect.php');
$page = $_GET['page'];
$search = $_GET['search'];
$search = "%" . $search . "%";

$sql = "select * from report where receiver_id=? and status>0 and (title like ? or content like ?) 
                order by created_at desc";
$stmt = $conn->prepare($sql);
$stmt->bind_param('sss', $_SESSION['userid'], $search, $search);
$stmt->execute();
$res = $stmt->get_result();
$sl = 3;
$tong_so_bai = $res->num_rows;
$tong_so_trang = ceil($tong_so_bai / $sl);


$index = ($page - 1) * 3;
$sql = 'select * from report where receiver_id=? and status>0 and (title like ? or content like ?) 
        order by created_at desc limit 3 offset ?';
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssi", $_SESSION['userid'], $search, $search, $index);
$stmt->execute();
$res = $stmt->get_result();

?>
<table class="table table-bordered table-hover table-striped text-center">
    <thead>
        <th class="text-center">
            <h4>Thông báo mới nhất</h4>
        </th>
    </thead>
    <tbody>
        <?php
        if ($res->num_rows > 0)
            while ($row = $res->fetch_assoc()) {
                $date = DateTime::createFromFormat('Y-m-d H:i:s', $row['created_at']);
                $created_at = $date->format('d/m/Y');
        ?>
            <tr style="border: 1px solid black;">
                <td style="border: 1px solid black;">
                    <div class="text-left">
                        <a href="./detailNews.php?id=<?php echo $row['id']; ?>">
                            <?php echo $row['title']; ?></a>
                        <p><?php echo $row['content'] . substr(0, 20); ?></p>
                        <p style="color: gray;">Ngày đăng: <?php echo $created_at; ?></p>
                    </div>
                </td>
            </tr>
        <?php } ?>
    <tbody>
</table>

<ul class="pagination pagination-sm">
    <?php for ($i = 1; $i <= $tong_so_trang; $i++){
        $a='';
        if($i==$page) $a='active';
        echo "<li class='$a'><a id='changePage'>" . $i . "</a></li>";
    }?>
</ul>