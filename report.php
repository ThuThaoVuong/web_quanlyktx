<?php
    session_start(); require_once('./connect.php');
    $title=''; $content=''; $image='';
    if($_SERVER['REQUEST_METHOD']=='POST'){
        $title=$_POST['title'];
        $content=$_POST['content'];
        
        if(!empty($_FILES['image']['name'])){
            $uploadPath = __DIR__.'/images/';
            $image=time().'_'.basename($_FILES['image']['name']);
            $imagePath=$uploadPath.$image;
            move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
            $sql="insert into report values (null,?,'NV1',?,?,?,?,1)";
            $stmt=$conn->prepare($sql);
            $stmt->bind_param('sssss', $_SESSION['userid'], $title, $content, $image, date('Y-m-d H:i:s'));
        }

        else{
            $sql="insert into report values (null,?,'NV1',?,?,null,?,1)";
            $stmt=$conn->prepare($sql);
            $stmt->bind_param('ssss', $_SESSION['userid'], $title, $content, date('Y-m-d H:i:s'));
        }
        $stmt->execute();
        $mes='Tạo xong';

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
                            <li class="active"><a href="./news.php"><span class="glyphicon glyphicon-bell"></span> Thông báo</a></li>
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
        <h2>Báo cáo</h2>
    </div>

    <div class="container-fluid row text-center">
        <div class="col-sm-3"></div>
        <div class="col-sm-6">
            <?php if($mes=='Tạo xong'){?>
                <p class="alert alert-success">Tạo xong</p>
            <?php }?>
            <form class="form-horizontal" id="reportform" method="post" enctype="multipart/form-data"
                action="">
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="title">Tiêu đề:</label>
                        <div class="col-sm-8">
                            <input class="form-control" id="title" placeholder="Nhập tiêu đề" name="title" value="<?php echo $title?>" >
                            <p style='color:#e90000; font-size: 15px' id='error-reporttitle' class="error-message"></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="content">Nội dung:</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" id='content' name="content" rows="3"><?php echo $content;?></textarea>
                            <p style='color:#e90000; font-size: 15px' id='error-reportcontent' class="error-message"></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="image">Ảnh (Nếu có):</label>
                        <div class="col-sm-8">
                            <input type="file" class="form-control" id="image" name="image" accept="image/png, image/jpeg">
                            <img width="200px" height="100px" src="../images/<?php echo $image ?>">
                            <p style='color:#e90000; font-size: 15px' id='error-reportimage' class="error-message"></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-lg">Gửi báo cáo</button>
                        </div>
                    </div>
                </form>
        </div>
        <div class="col-sm-3"></div>
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
            $('#reportForm').on('submit', function(e){
                let ok=1; $('#error-message').text('');
                if($('#title').val().trim()==''){
                    ok=0; $('#error-reporttitle').text('Bạn cần nhập tiêu đề');
                }
                if($('#content').val().trim()==''){
                    ok=0; $('#error-reportcontent').text('Bạn cần nhập nội dung');
                }
                if(ok==0) e.preventDefault();
                else{
                    let formData=new FormData(this);
                    e.preventDefault();
                    $.ajax({
                        url:'',
                        method: 'post',
                        processData: false,
                        contentType: false, 
                        
                    });
                }
            })
        })
    </script>
</body>

</html>