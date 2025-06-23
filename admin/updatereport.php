<?php
session_start();
require_once('../connect.php');
if($_SERVER['REQUEST_METHOD']=='GET' && isset($_GET['def'])){
    $def=$_GET['def'];
    $id=0;
    if($def=='del'){
        $id=$_GET['id'];
        $sql='update report set status=0 where id=?';
        $stmt=$conn->prepare($sql);
        $stmt->bind_param('i',$id);
        $stmt->execute();
        header('location: managereport.php');
        exit;
    }
    else if($def=='update'){
        $id=$_GET['id'];
    }
    $reportid=''; $reportsender=''; $reportreceiver=''; $reporttitle=''; $reportcontent=''; 
    $reportcreated=''; $reportstatus=0; $reportimage='';
    $sql='select * from report where id=?';
    $stmt=$conn->prepare($sql);
    $stmt->bind_param('i',$id);
    $stmt->execute();
    $res=$stmt->get_result();
    while($row=$res->fetch_assoc()){
        $reportid=$row['id']; $reportsender=$row['sender_id'];
        $reportreceiver=$row['receiver_id'];
        $reporttitle=$row['title']; $reportcontent=$row['content'];
        $reportimage=$row['image']; $reportstatus=$row['status'];

        $date=DateTime::createFromFormat('Y-m-d H:i:s', $row['created_at']);
        $reportcreated=$date->format('d/m/Y');
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
            <li><a href="./manageuser.php"><span class="glyphicon glyphicon-education"></span> Quản lý tài khoản</a></li>
            <li><a href="./manageuserbill.php"><span class="glyphicon glyphicon-education"></span> Quản lý hóa đơn người dùng</a></li>
            <li><a href="./managecontract.php"><span class="glyphicon glyphicon-certificate"></span> Quản lý hợp đồng</a></li>
            <li class="active"><a href="./managereport.php"><span class="glyphicon glyphicon-bell"></span> Thông báo</a></li>
            <li><a href="../index.php"><span class="glyphicon glyphicon-user"></span> Trang chủ chính </a></li>
            <li><a href="../logout.php"><span class="glyphicon glyphicon-log-out"></span> Đăng xuất</a></li>
        </ul>
    </div>
    
    <div class="main-content" style="padding-left: 0px;">
        <h2 class="text-center">Thông tin thông báo</h2>
        <div id="result" class="text-center" style="height: 80px;"></div>
        <div class="container-fluid row text-center">
            <div class="col-sm-3"></div>
            <div class="col-sm-6">
                <form class="form-horizontal" id="reportform" method="post" enctype="multipart/form-data"
                action="updateReportform.php">
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="id">Mã thông báo:</label>
                        <div class="col-sm-8">
                            <input class="form-control" id="id" name="id" value="<?php echo $reportid; ?>" disabled>
                            <input class="form-control" type="hidden" id="id-old" name="id-old" value="<?php echo $reportid; ?>">               
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="sender">Mã người gửi:</label>
                        <div class="col-sm-8">
                            <input class="form-control" id="sender" name="sender" value="<?php echo $reportsender?>" disabled> 
                            <input class="form-control" id="sender-old" type="hidden" name="sender-old" value="<?php echo $reportsender?>">
                            <p style='color:#e90000; font-size: 15px' id='error-reportsender' class="error-message"></p>           
                        </div>
                    </div>
                    <div>
                        <label class="control-label col-sm-4" for="senderinfo">Thông tin người gửi:</label>
                        <div class="col-sm-8">
                            <table class="table table-bordered table-hover table-striped text-center">
                                <thead>
                                    <tr>
                                        <th class="text-center">STT</th>
                                        <th class="text-center">MSV</th>
                                        <th class="text-center">Tên</th>
                                        <th class="text-center">Giới tính</th>
                                        <th class="text-center">Ngày sinh</th>
                                        <th class="text-center">SDT</th>
                                        <th class="text-center">Địa chỉ</th>
                                        <th class="text-center">Email</th>
                                        <th class="text-center">Vai trò</th>
                                        <th class="text-center">Tác vụ</th>
                                    </tr>
                                </thead>
                                <tbody id='senderinfo'></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="receiver">Mã người nhận:</label>
                        <div class="col-sm-8">
                            <select id="receiver" style="width: 100%;" name="receiver[]" class="text-center" multiple 
                            <?php if($reportreceiver!='') echo "disabled"?> >
                                <option value="0">...</option>
                                <?php
                                $sql1 = 'select msv from users';
                                $res1 = mysqli_query($conn, $sql1);
                                while ($row1 = mysqli_fetch_assoc($res1)) {
                                ?>
                                    <option value="<?php echo $row1['msv'] ?>"
                                        <?php if ($reportreceiver == $row1['msv']) echo "selected"; ?>>
                                        <?php echo $row1['msv'] ?>
                                    </option>
                                <?php } ?>
                            </select>
                            <p style='color:#e90000; font-size: 15px' id='error-reportreceiver' class="error-message"></p>
                        </div>
                    </div>

                    <div>
                        <label class="control-label col-sm-4" for="receiverinfo">Thông tin người nhận (nếu có):</label>
                        <div class="col-sm-8">
                            <table class="table table-bordered table-hover table-striped text-center">
                                <thead>
                                    <tr>
                                        <th class="text-center">STT</th>
                                        <th class="text-center">MSV</th>
                                        <th class="text-center">Tên</th>
                                        <th class="text-center">Giới tính</th>
                                        <th class="text-center">Ngày sinh</th>
                                        <th class="text-center">SDT</th>
                                        <th class="text-center">Địa chỉ</th>
                                        <th class="text-center">Email</th>
                                        <th class="text-center">Vai trò</th>
                                        <th class="text-center">Tác vụ</th>
                                    </tr>
                                </thead>
                                <tbody id='receiverinfo'></tbody>
                            </table>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-4" for="title">Tiêu đề:</label>
                        <div class="col-sm-8">
                            <input class="form-control" id="title" placeholder="Nhập tiêu đề" name="title" 
                            value="<?php echo $reporttitle ?>" <?php if($_GET['def']=='update') echo 'disabled'?>>
                            <p style='color:#e90000; font-size: 15px' id='error-reporttitle' class="error-message"></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="content">Nội dung:</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" id='content' name="content" rows="3" value="" 
                            <?php if($_GET['def']=='update') echo 'disabled'?>><?php echo $reportcontent ?></textarea>
                            <p style='color:#e90000; font-size: 15px' id='error-reportcontent' class="error-message"></p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-4" for="created">Ngày gửi:</label>
                        <div class="col-sm-8">
                            <input class="form-control" id="created" name="created" type="date" 
                            value="<?php echo ($reportcreated != '' ? DateTime::createFromFormat('d/m/Y', $reportcreated)->format('Y-m-d') : ''); ?>"
                            <?php if($_GET['def']=='update') echo 'disabled'?>>
                            
                            <p style='color:#e90000; font-size: 15px' id='error-reportcreated' class="error-message"></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="status">Trạng thái hoạt động:</label>
                        <div class="col-sm-8">
                            <select class="form-control" id="status" name="status">
                                <option value="0" <?php if ($reportstatus == 0) echo "selected" ?>>...</option>
                                <option value="1" <?php if ($reportstatus == 1) echo "selected" ?>>Chưa xem</option>
                                <option value="2" <?php if ($reportstatus == 2) echo "selected"; ?>>Đã xem</option>
                            </select>
                        <p style='color:#e90000; font-size: 15px' id='error-reportstatus' class="error-message"></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="image">Ảnh :</label>
                        <div class="col-sm-8">
                            <input type="file" class="form-control" id="image" name="image" accept="image/png, image/jpeg">
                            <input type="hidden" id="image-old" name="image-old" value="<?php echo $reportimage;?>">
                            <img width="200px" height="100px" src="../images/<?php echo $reportimage ?>">
                            <p style='color:#e90000; font-size: 15px' id='error-reportimage' class="error-message"></p>
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
    
        function info($userid, $who) {
            $.ajax({
                url: 'searchUser.php',
                method: 'POST',
                data: {id: $userid, name: '', gender:0, phone:'',  
                    address: '', email:'', status: 0, lim:0, page: 1
                },
                dataType: 'json',
                success: function(response){
                    if($who==2){
                        $('#receiverinfo').html(response.table);
                        let row=$('#receiverinfo tr').length; console.log(row);
                    }
                    else{
                        $('#senderinfo').html(response.table);
                    }
                },
                error: function(){
                    $('#error-reportreceiver').text('Mã người nhận không tồn tại');
                }
            });
        }
        if($('#receiver').val()!='') info($("#receiver").val(),2);
        if($('#sender-old').val()!='') info($('#sender-old').val(),1);

        $('#receiver').on('change', function(){
            $('#error-reportreceiver').text('');
            let id=$('#receiver').val();
            info(id,2);
        });


        $('#reportform').on('submit', function(e) {
            let ok = 1; 
            $('.error-message').text('');
            if ($('#receiver').val() == 0) {
                ok = 0;
                $('#error-reportreceiver').text('Bạn cần chọn phòng');
            }

            if($('#title').val()==''){
                ok=0; $('#error-reporttitle').text('Bạn cần nhập tiêu đề');
            }
            if($('#content').val()==''){
                ok=0; $('#error-reportcontent').text('Bạn cần nhập nội dung');
            }

            if ($('#created').val() == '') {
                ok = 0;
                $('#error-reportcreated').text('Bạn cần nhập ngày tạo');
            }

            if ($('#status').val() == '0') {
                ok = 0;
                $('#error-reportstatus').text('Bạn cần nhập trạng thái hoạt động');
            }

            if($('#error-reportreceiver').val()!=''){
                ok=0;
            }

            if (ok == 0) e.preventDefault();
            else {
                let formData = new FormData($('#reportform')[0]);
                e.preventDefault();
                $.ajax({
                    url: 'updateReportform.php',
                    method: 'post',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(res) {
                        $('#result').html('<span class="alert alert-success">Cập nhật xong</span>');
                        $('html, body').animate({
                            scrollTop: 0
                        }, 'slow');
                    },
                    error: function() {
                        alert('Lỗi');
                    }
                });
            }
        })
})
</script>
<script src="./script.js"></script>
</html>