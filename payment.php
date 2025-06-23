<?php
session_start();
require_once('connect.php');

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
                <a class="navbar-brand" href="#"><span class="glyphicon glyphicon-home"></span> Logo</a>
            </div>
            <div class="collapse navbar-collapse" id="myNavbar">
                <ul class="nav navbar-nav">
                    <li><a href="./index.php">Trang Chủ</a></li>
                    <?php if(isset($_SESSION['userstatus']) && $_SESSION['userstatus']>1){?>
                        <li><a href="./admin/index.php">Admin</a></li>
                    <?php }?>
                    <li><a href="./dorm.php">Ký Túc Xá</a></li>
                    <li><a href="./about.php">Về Chúng Tôi</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <?php if (isset($_SESSION['userid'])) { ?>
                        <li><a href="./news.php"><span class="glyphicon glyphicon-bell"></span> Thông báo</a></li>
                        <li class="active"><a href="./payment.php"><span class="glyphicon glyphicon-usd"></span> Thanh Toán</a></li>
                        <li><a href="./account.php"><span class="glyphicon glyphicon-user"></span> Tài khoản của tôi</a></li>
                    <?php } else { ?>
                        <li><a href="./login.php"><span class="glyphicon glyphicon-log-in"></span> Đăng Nhập</a></li>
                        <li><a href="./register.php"><span class="glyphicon glyphicon-user"></span> Đăng Ký</a></a></li>
                    <?php } ?>
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
        <h2>Thanh toán</h2>
    </div>

    <div class="row container-fluid">
        <div class="col-sm-8 col-xs-12">
            <div class="row">
                <p class="col-sm-3"><a href="./contract.php">Xem hợp đồng (nếu có)</a></p>
            </div>
            <div class="row">
                <p class="col-sm-6"><strong>Hóa đơn của bạn</strong></p>
                <p class="col-sm-6 text-right"><strong>Tổng</strong></p>
            </div>
            <?php
            $sum = 0;
            $sql = "select * from users_bill where users_id='" . $_SESSION['userid'] . "'
             and paid_at is null";

            $res = mysqli_query($conn, $sql);
            if (mysqli_num_rows($res))
                while ($row = mysqli_fetch_assoc($res)) {
                    $sum += $row['room_fee'];
            ?>
                <div class="row">
                    <div class="col-sm-9">
                        <div>
                            <h4>Hóa đơn tiền phòng</h4>
                            <p>Tháng: <?php echo $row['month'] . '/' . $row['year']; ?></p>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <h4 class="text-right"><strong><?php echo round($row['room_fee']); ?></strong></h4>
                    </div>
                </div>
            <?php } ?>
            <?php
            $sql = "SELECT rb.* FROM `room_bill` rb
            inner join contract c on c.room_id=rb.room_id 
            inner join users u on u.msv=c.users_id
            where u.msv=? and paid_at is null AND paid_by_user_id=''";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $_SESSION['userid']);
            $stmt->execute();
            $res = $stmt->get_result();
            if (mysqli_num_rows($res))
                while ($row = mysqli_fetch_assoc($res)) {
                    $total = $row['total'];
                    $sum += $total;
            ?>
                <div class="row">
                    <div class="col-sm-9">
                        <div>
                            <h4 style="color: #e90000;">Hóa đơn tiền điện, nước</h4>
                            <p>Tiền chung của cả phòng (Yêu cầu đóng chung)</p>
                            <p>Tháng: <?php echo $row['month'] . '/' . $row['year']; ?></p>
                            <p>Tiền điện: <?php echo round($row['electricity_fee']); ?></p>
                            <p>Tiền nước: <?php echo round($row['water_fee']); ?></p>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <h4 class="text-right"><strong><?php echo round($row['total']); ?></strong></h4>
                    </div>
                </div>
            <?php } ?>
        </div>
        <div class="col-sm-4 col-xs-12">
            <div class="row">
                <div class="col-sm-12">
                    <p class="col-sm-6"><strong>Thanh toán</strong></p>
                </div>
                <div class="col-sm-12">
                    <p class="col-sm-6"><strong>Tổng cộng</strong></p>
                    <p class="col-sm-6 text-right"><strong>
                            <?php echo round($sum); ?></strong></p>
                </div>
                <div class="col-sm-12">
                    <p class="col-sm-12">Thanh toán qua tài khoản: BIDV 123456789</p>
                </div>
                <div class="col-sm-12">
                    <p class="col-sm-12">Sau khi thanh toán xong hãy nộp minh chứng thanh toán
                        bằng cách tạo 1 thông báo mới gửi đến admin </p>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-main">
        <div class="container">
            <div class="row">
                <div class="col-sm-4 text-center">
                    <div>
                        <h5 class="footer-col">Liên hệ với chúng tôi</h5>
                        <div>
                            <a class="footer-col" target="_blank"
                                href="https://www.google.com/maps/place/H%E1%BB%8Dc+vi%E1%BB%87n+C%C3%B4ng+ngh%E1%BB%87+B%C6%B0u+ch%C3%ADnh+vi%E1%BB%85n+th%C3%B4ng/@20.980918,105.7848416,17z/data=!3m1!4b1!4m6!3m5!1s0x3135accdd8a1ad71:0xa2f9b16036648187!8m2!3d20.980913!4d105.7874165!16s%2Fg%2F12168p16?hl=vi-VN&entry=ttu&g_ep=EgoyMDI1MDIyMy4xIKXMDSoASAFQAw%3D%3D">
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
                <div class="col-sm-4 text-center">
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
    <script src="./script.js"></script>
</body>

</html>