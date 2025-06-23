<?php
session_start();
require_once('../connect.php');
if($_SERVER['REQUEST_METHOD']=='GET' && isset($_GET['def'])){
    $def=$_GET['def'];
    $id=0;
    if($def=='del'){
        $id=$_GET['id'];
        $sql='update ktx set status=0 where id=?';
        $stmt=$conn->prepare($sql);
        $stmt->bind_param('i',$id);
        $stmt->execute();
        header('location: manageroom.php');
        exit;
    }
    else if($def=='update'){
        $id=$_GET['id'];
    }
    $ktxid=''; $ktxname=''; $ktxaddress=''; $ktxinfo=''; $ktximage=''; $ktxstatus=0;
    $sql='select * from ktx where id=?';
    $stmt=$conn->prepare($sql);
    $stmt->bind_param('i',$id);
    $stmt->execute();
    $res=$stmt->get_result();
    while($row=$res->fetch_assoc()){
        $ktxid=$row['id']; $ktxname=$row['name'];
        $ktxaddress=$row['address']; $ktxinfo=$row['info'];
        $ktximage=$row['image']; $ktxstatus=$row['status'];
    }


}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Trang quản lý KTX</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="./style.css">
</head>

<body>
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" id="list">
                    <span class="glyphicon glyphicon-align-justify"></span>
                </button>
                <a class="navbar-brand" href="#"><span class="glyphicon glyphicon-home"></span> KTX</a>
            </div>
            
        </div>
    </nav>

    <div class="sidebar collapse" id="sidebar">
        <ul class="nav nav-pills nav-stacked">
            <li><a href="./index.php">Tổng quan</a></li>
            <li class="active"><a href="./managektx.php"><span class="glyphicon glyphicon-edit"></span> Quản lý KTX</a></li>
            <li><a href="./manageroom.php"><span class='glyphicon glyphicon-tent'></span> Quản lý phòng</a></li>
            <li><a href="./manageroombill.php"><span class='glyphicon glyphicon-tent'></span> Quản lý hóa đơn phòng</a></li>
            <li><a href="./manageuser.php"><span class="glyphicon glyphicon-education"></span> Quản lý tài khoản</a></li>
            <li><a href="./manageuserbill.php"><span class="glyphicon glyphicon-education"></span> Quản lý hóa đơn người dùng</a></li>
            <li><a href="./managecontract.php"><span class="glyphicon glyphicon-certificate"></span> Quản lý hợp đồng</a></li>
            <li><a href="./managereport.php"><span class="glyphicon glyphicon-bell"></span> Thông báo</a></li>
            <li><a href="../index.php"><span class="glyphicon glyphicon-user"></span> Trang chủ chính </a></li>
            <li><a href="../logout.php"><span class="glyphicon glyphicon-log-out"></span> Đăng xuất</a></li>
        </ul>
    </div>
    
    <div class="main-content">
        <h2 class="text-center">Thông tin ktx</h2>
        <div id="result" class="text-center" style="height: 80px;"></div>
        <div class="container-fluid row text-center">
            <div class="col-sm-3"></div>
            <div class="col-sm-6">
                <form class="form-horizontal" id="ktxform" method="post" enctype="multipart/form-data"
                action="./updateKTXform.php">
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="id">Mã ktx:</label>
                        <div class="col-sm-8">
                            <input class="form-control" id="id" name="id" value="<?php echo $ktxid; ?>" disabled>
                            <input class="form-control" type="hidden" id="id-old" name="id-old" value="<?php echo $ktxid; ?>">               
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="name">Tên ktx:</label>
                        <div class="col-sm-8">
                            <input class="form-control" id="name" placeholder="Nhập tên" name="name" value="<?php echo $ktxname ?>">
                            <p style='color:#e90000; font-size: 15px' id='error-ktxname' class="error-message"></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="address">Địa chỉ:</label>
                        <div class="col-sm-8">
                            <input class="form-control" id="address" placeholder="Nhập địa chỉ" name="address" value="<?php echo $ktxaddress ?>">
                            <p style='color:#e90000; font-size: 15px' id='error-ktxaddress' class="error-message"></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="info">Mô tả ngắn:</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" id='info' name="info" rows="3" value=""><?php echo $ktxinfo ?></textarea>
                            <p style='color:#e90000; font-size: 15px' id='error-ktxinfo' class="error-message"></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="status">Trạng thái hoạt động:</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="status">
                                <option value="0" <?php if ($ktxstatus == 0) echo "selected" ?>>...</option>
                                <option value="1" <?php if ($ktxstatus == 1) echo "selected" ?>>Đang hoạt động</option>
                                <option value="2" <?php if ($ktxstatus == 2) echo "selected"; ?>>Chưa hoạt động</option>
                                <option value="3" <?php if ($ktxstatus == 3) echo "selected"; ?>>Tạm dừng hoạt động</option>
                            </select>
                        <p style='color:#e90000; font-size: 15px' id='error-ktxstatus' class="error-message"></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="image">Ảnh :</label>
                        <div class="col-sm-8">
                            <input type="file" class="form-control" id="image" name="image" accept="image/png, image/jpeg">
                            <input type="hidden" id="image-old" name="image-old" value="<?php echo $ktximage;?>">
                            <img width="200px" height="100px" src="../images/<?php echo $ktximage ?>">
                            <p style='color:#e90000; font-size: 15px' id='error-ktximage' class="error-message"></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-lg">Cập nhật mới</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-sm-3"></div>
        </div>
    </div>

</body>
<script>
$(document).ready(function(){
    $('#ktxform').on('submit', function(e){
        let ok=1; 
        $('.error-message').text('');
        if($('#name').val().trim()=='') {
            ok=0; $('#error-ktxname').text('Bạn cần nhập tên ktx');
        }
        if($('#address').val().trim()=='') {
            ok=0; $('#error-ktxaddress').text('Bạn cần nhập địa chỉ ktx');
        }
        if($('#info').val().trim()=='') {
            ok=0; $('#error-ktxinfo').text('Bạn cần nhập mô tả ngắn ktx');
        }
        if($('#status').val()=='0') {
            ok=0; $('#error-ktxstatus').text('Bạn cần nhập trạng thái hoạt động ktx');
        }
        if($('#image').val().trim()=='' && $('#image-old').val().trim()=='') {
            ok=0; $('#error-ktximage').text('Bạn cần tải ảnh ktx lên');
        }
        if(ok==0) e.preventDefault();
        else{
            let formData=new FormData(this); e.preventDefault();
            $.ajax({
                url: 'updateKTXform.php',
                method: 'post',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res){
                    $('#result').html('<span class="alert alert-success">Cập nhật xong</span>');
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
<script src="./script.js"></script>
</html>