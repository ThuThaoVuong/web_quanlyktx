<?php
session_start();
require_once("./connect.php");

$b = 0; $a = 1;
if (isset($_GET['order'])) $b = $_GET['order'];
if (isset ($_GET['ktx_id'])) $a = $_GET['ktx_id'];
$sql = "select room.*, room.slot-sum(CASE WHEN c.status=1 then 1 else 0 end) as other from room 
            LEFT join contract c on c.room_id=room.id   
            where ktx_id=$a and room.status=1";
if ($b == 0) {
    $sql .= ' group by room.id';
} else if ($b == 1) {
    $sql .= " group by room.id order by room.slot-count(c.id) desc";
} else if ($b == 2) {
    $sql .= " and room.gender=0 group by room.id";
} else if ($b == 3) {
    $sql .= " and room.gender=1 group by room.id";
}
$res = mysqli_query($conn, $sql);
if (mysqli_num_rows($res))
    while ($row = mysqli_fetch_assoc($res)) {
?>
    <div class="col-md-3">
        <div class="panel panel-default text-center">
            <div class="panel-heading">
                <h2 style="color: #fff;"><?php echo $row['name'] ?></h2>
            </div>
            <div class="panel-body" style="height: 400px;">
                <div class="thumbnail">
                    <img src="./images/<?php echo $row['image'] ?>" alt="room1" width="400" height="400">
                    <h4>Phòng cho <?php
                                    if ($row['gender'] == 0) echo 'nam';
                                    else echo 'nữ'; ?></h4>
                    <h4>Số lượng người: <?php echo $row['slot'];?></h4>
                    <h4><strong>Slot còn lại: <?php echo $row['other'] ?></strong></h4>
                    <h4>Giá phòng: <?php echo round($row['price'])?> đồng/tháng</h4>
                </div>
            </div>
            <div class="panel-footer">
            <a href="<?php $_SESSION['roomid']=$row['id'];
            if(isset($_SESSION['userid'])){
                if(($_SESSION['usergender']==$row['gender'])) echo './contract.php';
            }
            else echo './login.php'; ?>" >
                    <button class="btn btn-lg" 
                    <?php if(isset($_SESSION['usergender']) && ($_SESSION['usergender']!=$row['gender'])) echo "disabled"?> >
                        Đăng kí ngay
                    </button></a>
            </div>
        </div>
    </div>
<?php } ?>