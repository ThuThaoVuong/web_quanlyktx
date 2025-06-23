<?php
session_start();
require_once('../connect.php');
if($_SERVER['REQUEST_METHOD']=='GET' && isset($_GET['def'])){
    $def=$_GET['def'];
    $id=0;
    if($def=='update'){
        $id=$_GET['id'];
    }
    $userid=''; $username=''; $useraddress=''; $usergender=''; 
    $userdob=''; $userphone=''; $useremail='';
    $userimage=''; $userstatus=0;
    $sql='select * from users where msv=?';
    $stmt=$conn->prepare($sql);
    $stmt->bind_param('s',$id);
    $stmt->execute();
    $res=$stmt->get_result();
    while($row=$res->fetch_assoc()){
        $userid=$row['msv']; $username=$row['name']; 
        $useraddress=$row['address']; $usergender=$row['gender'];
        $userphone=$row['phone']; $useremail=$row['email'];
        $userimage=$row['avatar']; $userstatus=$row['status'];
        $date=DateTime::createFromFormat('Y-m-d', $row['dob']);
        $userdob=$date->format('d/m/Y');
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
            <li><a href="./managektx.php"><span class="glyphicon glyphicon-edit"></span> Quản lý KTX</a></li>
            <li><a href="./manageroom.php"><span class='glyphicon glyphicon-tent'></span> Quản lý phòng</a></li>
            <li><a href="./manageroombill.php"><span class='glyphicon glyphicon-tent'></span> Quản lý hóa đơn phòng</a></li>
            <li class="active"><a href="./manageuser.php"><span class="glyphicon glyphicon-education"></span> Quản lý tài khoản</a></li>
            <li><a href="./manageuserbill.php"><span class="glyphicon glyphicon-education"></span> Quản lý hóa đơn người dùng</a></li>
            <li><a href="./managecontract.php"><span class="glyphicon glyphicon-certificate"></span> Quản lý hợp đồng</a></li>
            <li><a href="./managereport.php"><span class="glyphicon glyphicon-bell"></span> Thông báo</a></li>
            <li><a href="../index.php"><span class="glyphicon glyphicon-user"></span> Trang chủ chính </a></li>
            <li><a href="../logout.php"><span class="glyphicon glyphicon-log-out"></span> Đăng xuất</a></li>
        </ul>
    </div>
    
    <div class="main-content">
        <h2 class="text-center">Thông tin người dùng</h2>
        <div id="result" class="text-center" style="height: 80px;"></div>
        <div class="container-fluid row text-center">
            <div class="col-sm-3"></div>
            <div class="col-sm-6">
                <form class="form-horizontal" id="userform" method="post" enctype="multipart/form-data"
                action="updateUserform.php">
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="id">MSV:</label>
                        <div class="col-sm-8">
                            <input class="form-control" id="id" name="id" value="<?php echo $userid; ?>" disabled>
                            <input class="form-control" type="hidden" id="id-old" name="id-old" value="<?php echo $userid; ?>">               
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="name">Tên:</label>
                        <div class="col-sm-8">
                            <input class="form-control" id="name" placeholder="Nhập tên" name="name" value="<?php echo $username ?>" disabled>
                            <p style='color:#e90000; font-size: 15px' id='error-username' class="error-message"></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="gender">Giới tính:</label>
                        <div class="col-sm-8">
                            <select class="form-control"  name="gender" disabled>
                                <option value="1" <?php if ($usergender+1 == 1) echo "selected" ?>>Nam</option>
                                <option value="2" <?php if ($userstatus+1 == 2) echo "selected"; ?>>Nữ</option>
                            </select>
                        <p style='color:#e90000; font-size: 15px' id='error-usergender' class="error-message"></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="dob">Ngày sinh:</label>
                        <div class="col-sm-8">
                            <input class="form-control" id="dob" placeholder="Nhập ngày sinh" name="dob" value="<?php echo $userdob ?>" disabled>
                            <p style='color:#e90000; font-size: 15px' id='error-userdob' class="error-message"></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="phone">SDT:</label>
                        <div class="col-sm-8">
                            <input class="form-control" id="phone" placeholder="Nhập sdt" name="phone" value="<?php echo $userphone ?>" disabled>
                            <p style='color:#e90000; font-size: 15px' id='error-userphone' class="error-message"></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="address">Địa chỉ:</label>
                        <div class="col-sm-8">
                            <input class="form-control" id="address" placeholder="Nhập địa chỉ" name="address" value="<?php echo $useraddress ?>" disabled>
                            <p style='color:#e90000; font-size: 15px' id='error-useraddress' class="error-message"></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="email">Email:</label>
                        <div class="col-sm-8">
                            <input class="form-control" id="email" placeholder="Nhập email" name="email" value="<?php echo $useremail?>" disabled>
                            <p style='color:#e90000; font-size: 15px' id='error-useremail' class="error-message"></p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-4" for="status">Vai trò:</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="status">
                                <option value="0" <?php if ($userstatus == 0) echo "selected" ?>>...</option>
                                <option value="1" <?php if ($userstatus == 1) echo "selected" ?>>Sinh viên</option>
                                <option value="2" <?php if ($userstatus == 2) echo "selected"; ?>>Admin</option>
                            </select>
                        <p style='color:#e90000; font-size: 15px' id='error-userstatus' class="error-message"></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="image">Ảnh :</label>
                        <div class="col-sm-8">
                            <input type="hidden" id="image-old" name="image-old" value="<?php echo $userimage;?>">
                            <img width="200px" height="100px" src="../images/<?php echo $userimage ?>">
                            <p style='color:#e90000; font-size: 15px' id='error-userimage' class="error-message"></p>
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
    $('#userform').on('submit', function(e){
        let ok=1; 
        if($('#status').val()=='0') {
            ok=0; $('#error-userstatus').text('Bạn cần nhập vai trò của người dùng');
        }
        if(ok==0) e.preventDefault();
        else{
            let status=$('#status').val(); 
            let id=$('#id-old').val(); 
            e.preventDefault();
            $.ajax({
                url: 'updateUserform.php',
                method: 'post',
                data: {status: status, id:id},
                processData:false,
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