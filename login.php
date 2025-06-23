<?php 
    session_start();
    require_once('connect.php');
        $a=['','']; 
    if($_SERVER['REQUEST_METHOD']=='POST'){
        $error=['email'=>'','password'=>''];
        $value=[];
        if(isset($_POST['email'])){
            $a[0]=$_POST['email'];
            if(empty($_POST['email'])) $error['email']='Email chưa nhập';
            else if(!filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)){
                $error['email']='Sai định dạng email';
            }
            else $value[]=$_POST['email'];
        } 
        if(isset($_POST['password'])){
            $a[1]=$_POST['password'];
            if(empty($_POST['password'])) $error['password']='Mật khẩu chưa nhập';
            else $value[]=$_POST['password'];
        }
        if(count($value)==2){
            $sql="select * from users where email=? and status>0";
            $stmt=$conn->prepare($sql);
            $stmt->bind_param('s', $value[0]);
            $stmt->execute();
            $res=$stmt->get_result(); 
            if($res->num_rows==1){
                $row=$res->fetch_assoc();
                if(password_verify($value[1], $row['password'])==false && $row['password']!=$value[1]){
                    $error['password']='Sai mật khẩu';
                }
                else{
                    $_SESSION['userid']=$row['msv']; $_SESSION['usergender']=$row['gender'];
                    $_SESSION['userstatus']=$row['status'];
                    if($row['status']==1) {
                        header('location: index.php'); exit;
                    }
                    else{
                        header('location:admin/index.php'); exit;
                    }
                }
            }
            else $error['email']='Không tồn tại email trong hệ thống';
        }
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
                    <li><a href="./dorm.php">Ký Túc Xá</a></li>
                    <li><a href="./about.php">Về Chúng Tôi</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                        <?php if(isset($_SESSION['userid'])){?>
                            <li><a href="./news.php"><span class="glyphicon glyphicon-bell"></span> Thông báo</a></li>
                            <li><a href="./payment.php"><span class="glyphicon glyphicon-usd"></span> Thanh Toán</a></li>
                            <li><a href="./account.php"><span class="glyphicon glyphicon-user"></span> Tài khoản của tôi</a></li>
                        <?php } else {?>
                            <li class="active"><a href="./login.php"><span class="glyphicon glyphicon-log-in"></span> Đăng Nhập</a></li>
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
        <h2>Đăng nhập</h2>
    </div>

    <div class="container-fluid row text-center">
        <div class="col-sm-3"></div>
        <div class="col-sm-6">
            <form class="form-horizontal" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>">
                <div class="form-group">
                  <label class="control-label col-sm-2" for="email">Email:</label>
                  <div class="col-sm-10">
                    <input type="email" class="form-control" id="email" placeholder="Nhập email" name="email" value="<?php echo $a[0];?>">
                    <?php if(!empty($error['email'])) echo "<p style='color: #e90000; font-size:15px'>".$error['email']."</p>";?>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-sm-2" for="pwd">Mật khẩu:</label>
                  <div class="col-sm-10">          
                    <input type="password" class="form-control" id="pwd" placeholder="Nhập password" name="password" value="<?php echo $a[1];?>">
                    <?php if(!empty($error['password'])) echo "<p style='color: #e90000; font-size:15px'>".$error['password']."</p>";?>
                    </div>
                </div>
                <div class="form-group">        
                  <div class="col-sm-12">
                    <button type="submit" class="btn btn-lg">Đăng nhập</button>
                  </div>
                </div>
              </form>
              <a id="lost_password">Quên mật khẩu?</a>
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
                            phí và tạo môi trường sống thuận lợi cho việc học tập, làm việc, và giao lưu cộng đồng.</p>
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
            $('#lost_password').on('click',function(e){
                let email=$('#email').val().trim();
                if(email==''){
                    alert('Bạn cần nhập email trước');
                }
                else{
                    e.preventDefault();
                    $.ajax({
                        url: 'lost.php',
                        method:'post', 
                        data: {email:email}, 
                        success: function(r){
                            alert(r);
                        },
                        error: function(){
                            alert('Hệ thống lỗi. Vui lòng quay lại sau');
                        }
                    })
                }
            })
        }) 
    </script>

    <script src="./script.js"></script>
</body>

</html>