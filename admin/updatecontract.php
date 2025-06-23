<?php
session_start();
require_once('../connect.php');
if($_SERVER['REQUEST_METHOD']=='GET' && isset($_GET['def'])){
    $def=$_GET['def'];
    $id=0;
    if($def=='update'){
        $id=$_GET['id'];
    }
    $contractid=''; $contractuser=''; $contractroom=''; $contractstart=''; 
    $contractend=''; $contractmonth=''; $contractadmin='Chưa được xem';
    $contractcreate=''; $contractstatus=0;
    $sql='select c.*, u.name as uname, k.name as kname, r.name as rname from contract c
    inner join users u on c.users_id=u.msv
    inner join room r on r.id=c.room_id 
    inner join ktx k on k.id=r.ktx_id 
    where c.id=? ';
    $stmt=$conn->prepare($sql);
    $stmt->bind_param('i',$id);
    $stmt->execute();
    $res=$stmt->get_result();
    while($row=$res->fetch_assoc()){
        $contractid=$row['id']; $contractuser=$row['uname']; 
        $contractroom=$row['rname'].' - '.$row['kname']; 

        $date=DateTime::createFromFormat('Y-m-d', $row['start_date']);
        $contractstart=$date->format('d/m/Y');
        $date=DateTime::createFromFormat('Y-m-d', $row['end_date']);
        $contractend=$date->format('d/m/Y');
        $date=DateTime::createFromFormat('Y-m-d H:i:s', $row['created_at']);
        $contractcreate=$date->format('d/m/Y');

        $contractmonth=$row['month_living'];
        $contractstatus=$row['status'];

        $sql1='select name from users where msv=?';
        $stmt1=$conn->prepare($sql1);
        $stmt1->bind_param('s', $row['admin_id']);
        $stmt1->execute();
        $res1=$stmt1->get_result();
        while($r=$res1->fetch_assoc()) $contractadmin=$r['name'];
        
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
            <li class="active"><a href="./managecontract.php"><span class="glyphicon glyphicon-certificate"></span> Quản lý hợp đồng</a></li>
            <li><a href="./managereport.php"><span class="glyphicon glyphicon-bell"></span> Thông báo</a></li>
            <li><a href="../index.php"><span class="glyphicon glyphicon-user"></span> Trang chủ chính </a></li>
            <li><a href="../logout.php"><span class="glyphicon glyphicon-log-out"></span> Đăng xuất</a></li>
        </ul>
    </div>
    
    <div class="main-content">
        <h2 class="text-center">Thông tin hợp đồng</h2>
        <div id="result" class="text-center" style="height: 80px;"></div>
        <div class="container-fluid row text-center">
            <div class="col-sm-3"></div>
            <div class="col-sm-6">
                <form class="form-horizontal" id="contractform" method="post"
                action="updateContractform.php">
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="id">Mã hợp đồng:</label>
                        <div class="col-sm-8">
                            <input class="form-control" id="id" name="id" value="<?php echo $contractid; ?>" disabled>
                            <input class="form-control" type="hidden" id="id-old" name="id-old" value="<?php echo $contractid; ?>">               
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="user">Người thuê:</label>
                        <div class="col-sm-8">
                            <input class="form-control" id="user" placeholder="Nhập tên" name="user" value="<?php echo $contractuser ?>" disabled>
                            <p style='color:#e90000; font-size: 15px' id='error-contractuser' class="error-message"></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="room">Phòng thuê:</label>
                        <div class="col-sm-8">
                            <input class="form-control" id="room" placeholder="Nhập tên" name="room" value="<?php echo $contractroom ?>" disabled>
                            <p style='color:#e90000; font-size: 15px' id='error-contractroom' class="error-message"></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="start">Ngày bắt đầu thuê:</label>
                        <div class="col-sm-8">
                            <input class="form-control" id="start" placeholder="Nhập ngày" name="start" value="<?php echo $contractstart ?>" disabled>
                            <p style='color:#e90000; font-size: 15px' id='error-contractstart' class="error-message"></p>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="month">Số tháng thuê:</label>
                        <div class="col-sm-8">
                            <input class="form-control" id="month" placeholder="Nhập số" name="start" value="<?php echo $contractmonth ?>" disabled>
                            <p style='color:#e90000; font-size: 15px' id='error-contractmonth' class="error-message"></p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-4" for="end">Ngày kết thúc thuê:</label>
                        <div class="col-sm-8">
                            <input class="form-control" id="end" placeholder="Nhập ngày" name="end" value="<?php echo $contractend ?>" disabled>
                            <p style='color:#e90000; font-size: 15px' id='error-contractend' class="error-message"></p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-4" for="admin">Admin đã kí:</label>
                        <div class="col-sm-8">
                            <input class="form-control" id="admin" placeholder="Nhập admin" name="admin" value="<?php echo $contractadmin ?>" disabled>
                            <p style='color:#e90000; font-size: 15px' id='error-contractadmin' class="error-message"></p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-4" for="create">Thời gian tạo:</label>
                        <div class="col-sm-8">
                            <input class="form-control" id="create" placeholder="Nhập ngày" name="create" value="<?php echo $contractcreate ?>" disabled>
                            <p style='color:#e90000; font-size: 15px' id='error-contractcreate' class="error-message"></p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-4" for="status">Trạng thái:</label>
                        <div class="col-sm-8">
                            <select class="form-control" id="status" name="status">
                                <option value="0" <?php if ($contractstatus == 0) echo "selected" ?>>...</option>
                                <option value="1" <?php if ($contractstatus == 1) echo "selected" ?>>Có hiệu lực</option>
                                <option value="2" <?php if ($contractstatus == 2) echo "selected"; ?>>Hết hiệu lực</option>
                                <option value="3" <?php if ($contractstatus == 3) echo "selected"; ?>>Chưa có hiệu lực</option>
                                <option value="4" <?php if ($contractstatus == 4) echo "selected"; ?>>Sắp hết hạn</option>
                            </select>
                        <p style='color:#e90000; font-size: 15px' id='error-contractstatus' class="error-message"></p>
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
    $('#contractform').on('submit', function(e){
        let ok=1; 
        if($('#status').val()=='0') {
            ok=0; $('#error-contractstatus').text('Bạn cần nhập trạng thái của hợp đồng');
        }
        if(ok==0) e.preventDefault();
        else{
            let status=$('#status').val(); 
            let id=$('#id-old').val();
            e.preventDefault();
            $.ajax({
                url: 'updateContractform.php',
                method: 'post',
                data: {status: status, id: id},
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