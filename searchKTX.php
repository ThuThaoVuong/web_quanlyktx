<?php
session_start();
require_once("./connect.php");
// Lấy từ khóa tìm kiếm
if(isset($_POST['ktx'])) $keyword=$_POST['ktx'];
else $keyword="";

$keyword = "%{$keyword}%";

// Câu truy vấn chính
$sql = "SELECT ktx.*, COUNT(room.id) AS sl, 
               COUNT(room.id) - SUM(room.gender) AS nam, 
               SUM(room.gender) AS nu, 
               SUM(room.slot) AS tong,
               MIN(room.price) AS gia
        FROM ktx 
        INNER JOIN room ON room.ktx_id = ktx.id 
        WHERE ktx.status = 1 
          AND room.status = 1 
          AND (ktx.name LIKE ? OR ktx.address LIKE ?)
        GROUP BY ktx.id";

$stmt = mysqli_prepare($conn,$sql);
$stmt->bind_param('ss', $keyword, $keyword);
$stmt->execute();
$res = $stmt->get_result();

while ($row = $res->fetch_assoc()) {
    // Truy vấn số người đang ở
    $sql1 = "SELECT COUNT(c.id) AS has
             FROM room r
             INNER JOIN contract c ON c.room_id = r.id
             WHERE r.ktx_id = ? AND r.status = 1 AND c.status = 1";
    $stmt1 = $conn->prepare($sql1);
    $stmt1->bind_param('i', $row['id']);
    $stmt1->execute();
    $res1 = $stmt1->get_result();
    $row1 = $res1->fetch_assoc();
    $has = $row1['has'] ?? 0;

    // In ra HTML
    ?>
    <div class="col-md-4">
        <div class="panel panel-default text-center">
            <div class="panel-heading">
                <h2 style="color: #fff;"><?php echo htmlspecialchars($row['name']); ?></h2>
            </div>
            <div class="panel-body">
                <p>
                    Có tất cả <?php echo $row['sl']; ?> phòng.<br>
                    Với <?php echo $row['nam']; ?> phòng dành cho nam, <?php echo $row['nu']; ?> phòng dành cho nữ
                </p>
                <p>Nơi ở với đầy đủ tiện nghi</p>
                <p>Thuận tiện về giao thông</p>
                <p>Đã có <?php echo $has; ?> người ở.</p>
                <p>Chỉ còn lại: <?php echo max(0, $row['tong'] - $has); ?> suất.</p>
                <div class="thumbnail">
                    <img src="./images/<?php echo htmlspecialchars($row['image']); ?>" alt="Ảnh KTX" class="img-responsive">
                </div>
            </div>
            <div class="panel-footer">
                <h4>Giá chỉ từ</h4>
                <h3><?php echo round($row['gia']); ?> đồng</h3>
                <h4>cho 1 tháng</h4>
                <a href="./detailktx.php?ktx=<?php echo $row['id'] ?>">
                    <button class="btn btn-lg">Xem chi tiết</button>
                </a>
            </div>
        </div>
    </div>
<?php
}
?>
