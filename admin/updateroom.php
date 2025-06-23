<?php
session_start();
require_once('../connect.php');
if($_SERVER['REQUEST_METHOD']=='GET' && isset($_GET['def'])){
    $def=$_GET['def'];
    $id=0;
    if($def=='del'){
        $id=$_GET['id'];
        $sql='update room set status=0 where id=?';
        $stmt=$conn->prepare($sql);
        $stmt->bind_param('i',$id);
        $stmt->execute();
        header('location: manageroom.php');
        exit;
    }
    else if($def=='update'){
        $id=$_GET['id'];
    }
    $roomid=''; $roomktx=''; $roomname=''; $roomarea=''; $roomslot=''; 
    $roomimage=''; $roomstatus=0; $roomgender=0; $roomprice='';
    $sql='select r.*, k.name as kname from room r
    inner join ktx k on k.id=r.ktx_id 
    where r.id=?';
    $stmt=$conn->prepare($sql);
    $stmt->bind_param('i',$id);
    $stmt->execute();
    $res=$stmt->get_result();
    while($row=$res->fetch_assoc()){
        $roomid=$row['id']; $roomname=$row['name']; $roomktx=$row['kname'];
        $roomarea=$row['area']; $roomslot=$row['slot'];
        $roomimage=$row['image']; $roomstatus=$row['status'];
        $roomprice=$row['price']; $roomgender=$row['gender']+1;
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
            <li class="active"><a href="./manageroom.php"><span class='glyphicon glyphicon-tent'></span> Quản lý phòng</a></li>
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
        <h2 class="text-center">Thông tin phòng</h2>
        <div id="result" class="text-center" style="height: 80px;"></div>
        <div class="container-fluid row text-center">
            <div class="col-sm-3"></div>
            <div class="col-sm-6">
                <form class="form-horizontal" id="roomform" method="post" enctype="multipart/form-data"
                action="updateRoomform.php">
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="id">Mã phòng:</label>
                        <div class="col-sm-8">
                            <input class="form-control" id="id" name="id" value="<?php echo $roomid; ?>" disabled>
                            <input class="form-control" type="hidden" id="id-old" name="id-old" value="<?php echo $roomid; ?>">               
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="ktx">KTX:</label>
                        <div class="col-sm-8">
                            <select id="ktx" style="width: 100%;" name="ktx" class="text-center">
                                <option value="0">...</option>
                                <?php 
                                $sql1='select id,name from ktx where status>0';
                                $res1=mysqli_query($conn, $sql1);
                                while($row1=mysqli_fetch_assoc($res1)){
                                ?>
                                <option value="<?php echo $row1['id']?>" 
                                <?php if($roomktx==$row1['name']) echo "selected";?>>
                                    <?php echo $row1['name']?>
                                </option>
                                <?php }?>
                            </select>    
                            <p style='color:#e90000; font-size: 15px' id='error-roomktx' class="error-message"></p>           
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="name">Tên phòng:</label>
                        <div class="col-sm-8">
                            <input class="form-control" id="name" placeholder="Nhập tên vd: Phòng 101" name="name" value="<?php echo $roomname ?>">
                            <p style='color:#e90000; font-size: 15px' id='error-roomname' class="error-message"></p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-4" for="area">Vị trí:</label>
                        <div class="col-sm-8">
                            <input class="form-control" id="area" placeholder="Nhập vị trí vd: Tầng 1" name="area" value="<?php echo $roomarea ?>">
                            <p style='color:#e90000; font-size: 15px' id='error-roomarea' class="error-message"></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="gender">Phòng cho giới tính:</label>
                        <div class="col-sm-8">
                            <select class="form-control" id="gender" name="gender">
                                <option value="0" <?php if ($roomgender == 0) echo "selected" ?>>...</option>
                                <option value="1" <?php if ($roomgender == 1) echo "selected" ?>>Nam</option>
                                <option value="2" <?php if ($roomgender == 2) echo "selected"; ?>>Nữ</option>
                            </select>
                        <p style='color:#e90000; font-size: 15px' id='error-roomstatus' class="error-message"></p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-4" for="slot">Số người ở:</label>
                        <div class="col-sm-8">
                            <input class="form-control" id='slot' name="slot" placeholder="Nhập số vd:1" value="<?php echo $roomslot ?>">
                            <p style='color:#e90000; font-size: 15px' id='error-roomslot' class="error-message"></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="price">Giá phòng:</label>
                        <div class="col-sm-8">
                            <input class="form-control" id='price' name="price" placeholder="Nhập số vd:1" value="<?php echo $roomprice ?>">
                            <p style='color:#e90000; font-size: 15px' id='error-roomprice' class="error-message"></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="status">Trạng thái hoạt động:</label>
                        <div class="col-sm-8">
                            <select class="form-control" id="status" name="status">
                                <option value="0" <?php if ($roomstatus == 0) echo "selected" ?>>...</option>
                                <option value="1" <?php if ($roomstatus == 1) echo "selected" ?>>Đang hoạt động</option>
                                <option value="2" <?php if ($roomstatus == 2) echo "selected"; ?>>Chưa hoạt động</option>
                            </select>
                        <p style='color:#e90000; font-size: 15px' id='error-roomstatus' class="error-message"></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="image">Ảnh :</label>
                        <div class="col-sm-8">
                            <input type="file" class="form-control" id="image" name="image" accept="image/png, image/jpeg">
                            <input type="hidden" id="image-old" name="image-old" value="<?php echo $roomimage;?>">
                            <img width="200px" height="100px" src="../images/<?php echo $roomimage ?>">
                            <p style='color:#e90000; font-size: 15px' id='error-roomimage' class="error-message"></p>
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
    $('#roomform').on('submit', function(e){
        let ok=1; 
        $('.error-message').text('');
        if($('#ktx').val()==0){
            ok=0; $('#error-roomktx').text('Bạn cần chọn ktx');
        }

        if($('#name').val().trim()=='') {
            ok=0; $('#error-roomname').text('Bạn cần nhập tên phòng');
        }

        if($('#area').val().trim()=='') {
            ok=0; $('#error-roomarea').text('Bạn cần nhập vị trí phòng');
        }

        if($('#gender').val()==0) {
            ok=0; $('#error-roomgender').text('Bạn cần chọn giới tính');
        }
        if($('#slot').val().trim()==0 || !/^\d+$/.test($('#slot').val().trim())){
            ok=0; $('#error-roomslot').text('Bạn cần nhập số người ở bằng số');
        }
        if($('#price').val().trim()==0 || !/^\d+(\.\d{1,2})?$/.test($('#price').val().trim())){
            ok=0; $('#error-roomprice').text('Bạn cần giá phòng bằng số');
        }
        if($('#status').val()=='0') {
            ok=0; $('#error-roomstatus').text('Bạn cần nhập trạng thái hoạt động phòng');
        }
    
        if($('#image').val().trim()=='' && $('#image-old').val().trim()=='') {
            ok=0; $('#error-roomimage').text('Bạn cần tải ảnh phòng lên');
        }
        if(ok==0) e.preventDefault();
        else{
            let formData=new FormData(this); e.preventDefault();
            $.ajax({
                url: 'updateRoomform.php',
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