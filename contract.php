<?php
session_start();
require_once('connect.php');

if($_SERVER['REQUEST_METHOD']=='POST'){
    $start=$_POST['start'];
    $month_living=$_POST['month'];

    $start_date=new DateTime($start);
    $start_date->modify("+ $month_living months");
    $end= $start_date -> format('Y-m-d');

    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $created_at = Date('Y-m-d H:i:s');
    $sql='insert into contract (users_id, room_id, start_date, month_living, end_date, created_at, status)
    values (?,?,?,?,?,?,3)';

    $stmt=$conn->prepare($sql);
    $stmt->bind_param("sisiss", $_SESSION['userid'], $_SESSION['roomid'], $start, $month_living, $end, $created_at);
    $stmt->execute();
}
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
                    <li><a href="./dorm.php">Ký Túc Xá</a></li>
                    <li><a href="./about.php">Về Chúng Tôi</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                        <?php if(isset($_SESSION['userid'])){?>
                            <li><a href="./news.php"><span class="glyphicon glyphicon-bell"></span> Thông báo</a></li>
                            <li class="active"><a href="./payment.php"><span class="glyphicon glyphicon-usd"></span> Thanh Toán</a></li>
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
        <h2>Hợp đồng</h2>
        <div id="result"></div>
    </div>

    <div class="container-fluid row text-center">
        <div class="col-sm-3"></div>
        <div class="col-sm-6">
            <?php
            $sql="select c.*, c.status as cs, r.id, r.name, r.area, r.price,
            k.name as kname, k.address as kaddress from contract c
            inner join room r on r.id=c.room_id 
            inner join ktx k on k.id=r.ktx_id
            where users_id='".$_SESSION['userid']."' and (c.status=1 or c.status=3 or c.status=4)";

            $roomName=''; $area='';$kname=''; $kaddress=''; $price='';
            $start=''; $month=''; $end=''; $loai=1;

            $res=mysqli_query($conn,$sql);
            if(mysqli_num_rows($res)>0){
                echo "<p>Hợp đồng phòng đã được tạo nên không thể đăng ký phòng mới</p>";
                while($row=mysqli_fetch_assoc($res)){
                    $roomName=$row['name'];
                    $area=$row['area']; $price=$row['price'];
                    $kname=$row['kname']; $kaddress=$row['kaddress'];
                    $start=$row['start_date']; $end=$row['end_date'];
                    $month=$row['month_living']; $status=$row['cs'];
                }
            }
            else{
                echo "<p>Hãy tạo hợp đồng với phòng đã đăng kí</p>";
                if(isset($_SESSION['roomid'])==false) {
                    header('location: dorm.php'); exit;
                }
                $sql1="select room.*, k.address as kaddress, k.name as kname from room 
                inner join ktx k on room.ktx_id=k.id where room.id='".$_SESSION['roomid']."'";
                $res1=mysqli_query($conn,$sql1);
                while($row1=mysqli_fetch_assoc($res1)){
                    $roomName=$row1['name'];
                    $area=$row1['area']; $price=$row1['price'];
                    $kname=$row1['kname']; $kaddress=$row1['kaddress']; $loai=2;
                }
            }
            ?>
            <form class="form-horizontal" id='contract' method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>">
                <div class="form-group">
                  <label class="control-label col-sm-2" for="roomName">Phòng: </label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="roomName" name="roomName" value="<?php echo $roomName;?>" 
                    <?php if($loai==1) echo "disabled";?>>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-sm-2" for="address">Địa chỉ:</label>
                  <div class="col-sm-10">          
                    <input type="text" class="form-control" id="address" name="address" value="<?php echo $area.', '.$kname.', '.$kaddress;?>" 
                    <?php if($loai==1) echo "disabled";?>>
                    </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-sm-2" for="start">Bắt đầu từ ngày:</label>
                  <div class="col-sm-10">          
                    <input type="date" class="form-control" id="start" name="start" value="<?php echo $start;?>" 
                    <?php if($loai==1) echo "disabled";?>>
                    <p id='start-result' class="error" style="color: red;"></p>
                    </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-sm-2" for="month">Số tháng ở:</label>
                  <div class="col-sm-10">          
                    <input type="number" class="form-control" id="month" name="month" value="<?php echo $month;?>" 
                    <?php if($loai==1) echo "disabled";?>>
                    <p id='month_living-result' class="error" style="color: red;"></p>
                    </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-sm-2" for="end">Kết thúc ngày:</label>
                  <div class="col-sm-10">          
                    <input type="date" class="form-control" id="start" name="start" value="<?php echo $end;?>" disabled>
                    </div>
                </div>
                <div class="form-group">        
                  <div class="col-sm-12">
                    <?php if($end=='') {?>
                    <button type="submit" class="btn btn-lg" >
                        Gửi hợp đồng
                    </button>
                    <?php } else {?>
                    <button type="submit" class="btn btn-lg" disabled>
                       <?php if($status==1) echo "Đang có hiệu lực";
                       else if($status==3) echo "Chờ duyệt";
                       else if($status==4) echo "Sắp hết hạn";?>
                    </button>
                    <?php }?>
                  </div>
                </div>
              </form>
        </div>
        <div class="col-sm-3"></div>
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
<script>
    $(document).ready(function(){
        $("#contract").on('submit', function(e){
            let ok=1; e.preventDefault();
            $('.error').text('');
            if($('#start').val()==''){
                $('#start-result').text('Bạn cần chọn ngày bắt đầu ở'); ok=0;
            }
            if($('#month').val()==''){
                $('#month_living-result').text('Bạn cần chọn số tháng ở'); ok=0;
            }
            if(ok==1) {
                let formData=new FormData(this);
                $.ajax({
                    url:'contract.php',
                    method: 'post',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data){
                        $('#result').html("<span class='alert alert-success'>Tạo xong</span>");
                        $('html, body').animate({ scrollTop: 0 }, 'slow');
                    }, 
                    error: function(){
                        alert('Lỗi');
                    }

                });
            }
        })
    })
</script>
</html>