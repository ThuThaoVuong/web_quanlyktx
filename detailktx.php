<?php
    session_start();
    require_once('connect.php');
    $a = '1';
    if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['ktx'])) {
        $a = $_GET['ktx'];
    }
    $sql = "select * from ktx where id='$a'";
    $res = mysqli_query($conn, $sql);
    while($row=mysqli_fetch_assoc($res)){
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KTX</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="./style.css">
</head>

<body>
    <!--Header: navigation-->
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <a class="navbar-brand" href="#"><span class="glyphicon glyphicon-home"></span> KTX</a>
            </div>
            <div class="collapse navbar-collapse" id="myNavbar">
                <ul class="nav navbar-nav">
                    <li><a href="./index.php">Trang Chủ</a></li>
                    <?php if(isset($_SESSION['userstatus']) && $_SESSION['userstatus']>1){?>
                        <li><a href="./admin/index.php">Admin</a></li>
                    <?php }?>
                    <li class="active"><a href="./dorm.php">Ký Túc Xá</a></li>
                    <li><a href="./about.php">Về Chúng Tôi</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                        <?php if(isset($_SESSION['userid'])){?>
                            <li><a href="./news.php"><span class="glyphicon glyphicon-bell"></span> Thông báo</a></li>
                            <li><a href="./payment.php"><span class="glyphicon glyphicon-usd"></span> Thanh Toán</a></li>
                            <li><a href="./account.php"><span class="glyphicon glyphicon-user"></span> Tài khoản của tôi</a></li>
                        <?php } else {?>
                            <li><a href="./login.php"><span class="glyphicon glyphicon-log-in"></span> Đăng Nhập</a></li>
                            <li><a href="./register.php"><span class="glyphicon glyphicon-user"></span> Đăng Ký</a></a></li>
                        <?php }?>
                </ul>
            </div>
        </div>
    </nav>


    <!--Icon-->
    <div class="icon">
        <ul class="call">
            <li>
                <a href="https://zalo.me/pc" target="_blank">
                    <span class="glyphicon glyphicon-earphone gly"></span>
                </a>
            </li>
            <li>
                <a href="https://maps.google.com" target="_blank">
                    <span class="glyphicon glyphicon-map-marker gly"></span>
                </a>
            </li>
            <li>
                <a href="#title"><span class="glyphicon glyphicon-arrow-up gly"></span></a>
            </li>
        </ul>
    </div>

    <div id="title" class="jumbotron text-center">
        <h2><?php echo $row['name']?></h2>
        <p class="text-center">
            <?php echo $row['info'];?>
        </p>
    </div>

    <div class="container-fluid row text-center">
        <div class="col-sm-6">
            <img src='./images/<?php echo $row['image'];?>' width="100%" height="500">
        </div>

        <div class="col-sm-6 text-left">
            <h4 style="color: #e90000;"><?php echo $row['name'];?></h4>
            <?php
            $b=$row['id'];
            $sql1 = "SELECT ktx_id,COUNT(room.id) AS sl, 
               COUNT(room.id) - SUM(room.gender) AS nam, 
               SUM(room.gender) AS nu, 
               SUM(room.slot) AS tong,
               MIN(room.price) AS gia
                FROM room
                WHERE room.ktx_id = '$b' 
                AND room.status = 1 
                group by ktx_id";
            $res1=mysqli_query($conn,$sql1);
            if($row1=mysqli_fetch_assoc($res1)) {
            ?>
            <p>
                Có tất cả <?php echo $row1['sl']; ?> phòng.<br>
                Với <?php echo $row1['nam']; ?> phòng dành cho nam, <?php echo $row1['nu']; ?> phòng dành cho nữ
            </p>
            <p>Nơi ở với đầy đủ tiện nghi</p>
            <p>Thuận tiện về giao thông</p>
            <?php }?>
            <?php
            $sql2 = "SELECT COUNT(c.id) AS has
            FROM room r
            INNER JOIN contract c ON c.room_id = r.id
            WHERE r.ktx_id = '$b' AND r.status = 1 AND c.status = 1";
            
            $res2=mysqli_query($conn,$sql2);
            $has=0;
            if($row2=mysqli_fetch_assoc($res2)) $has=$row2['has'];
            ?>
            <p>Đã có <?php echo $has; ?> người ở.</p>
            <p>Chỉ còn lại: <?php echo max(0, $row1['tong'] - $has); ?> suất.</p>
            <h4>Giá chỉ từ: <span class="label label-danger"><?php echo round($row1['gia']);?></span> đồng cho 1 tháng</h4>
        </div>

    </div>
    <div class="container-fluid">
        <div class="row">
            <h2 class="text-center">Danh sách các phòng</h2>
        </div>
        <div class="row">
                <input type="hidden" id="ktx_id" name="ktx_id" value="<?php echo $_GET['ktx'];?>">
                <select id="sortBy" class="form-select" name='order'>
                    <option value="0">Hiển thị theo thứ tự mã phòng</option>
                    <option value="1">Hiển thị theo slot còn lại</option>
                    <option value="2">Hiển thị theo phòng dành cho nam</option>
                    <option value="3">Hiển thị theo phòng dành cho nữ</option>
                </select>
        </div>

        <?php }?>

        <div class="row" id="roomDetail">
        </div>
    </div>

    <!--footer-->
    <footer class="bg-main">
        <div class="container">
            <div class="row">
                <div class="col-md-4 text-center">
                    <div>
                        <h5 class="footer-col">Liên hệ với chúng tôi</h5>
                        <div>
                            <a class="footer-col" target="_blank" href="https://maps.google.com">
                                <i class="fa fa-map-marker" aria-hidden="true"></i>
                                <h5>Địa điểm</h5>
                            </a>
                            <a class="footer-col" target="_blank" href="https://zalo.me/pc">
                                <i class="fa fa-phone" aria-hidden="true"></i>
                                <h5>Gọi +01 1234567890</h5>
                            </a>
                            <a class="footer-col" target="_blank" href="https://gmail.com">
                                <i class="fa fa-envelope" aria-hidden="true"></i>
                                <h5>demo@gmail.com</h5>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <div>
                        <h5 class="footer-col">KTX</h5>
                        <p class="footer-col">Kí túc xá (KTX) dành cho sinh viên,
                            với mục đích cung cấp không gian sinh hoạt chung, tiện nghi cơ bản. KTX giúp tiết kiệm chi
                            phí
                            và tạo môi trường sống thuận lợi cho việc học tập, làm việc, và giao lưu cộng đồng.</p>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <div class="footer-contact">
                        <h5 class="footer-col">Nội quy</h5>
                        <p class="footer-col">Giữ gìn vệ sinh chung</p>
                        <p class="footer-col">Tôn trọng mọi người</p>
                        <p class="footer-col">Bảo vệ tài sản chung</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script>
        $(document).ready(function(){
            let $a=$("#ktx_id").val();
            console.log($a);
            $.ajax({
                url: "searchRoom.php",
                method: 'GET',
                data:{order:0, ktx_id: $a},
                success: function(response){
                    $("#roomDetail").html(response);
                },
                error: function(){
                    alert('Đã có lỗi. Vui lòng thử lại sau');
                }
            });
            $("#sortBy").on('change',function(){
                let $o=$("#sortBy").val();
                $.ajax({
                    url: "searchRoom.php",
                    method: 'GET',
                    data:{order:$o, ktx_id:$a},
                    success: function(response){
                        $("#roomDetail").html(response);
                    },
                    error: function(){
                        alert('Đã có lỗi. Vui lòng thử lại sau');
                    }
                });
            })
        })
    </script>
</body>

</html>