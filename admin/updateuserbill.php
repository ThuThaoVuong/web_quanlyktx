<?php
session_start();
require_once('../connect.php');
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['def'])) {
    $def = $_GET['def'];
    $id = 0;
    if ($def == 'update') {
        $id = $_GET['id'];
    }
    $billid = '';$billuser = ''; $billroom = '';
    $billmonth = '';$billyear = ''; $billcreated= '';
    $billstatus = 0;$billpaid = '';

    $sql = 'select ub.* from users_bill ub where ub.id=? ';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        $billid = $row['id'];
        $billuser = $row['users_id'];
        $billmonth = $row['month'];
        $billyear=$row['year'];
        $billroom = $row['room_fee'];
        $billstatus = $row['status'];
        $billpaid = $row['paid_at'];

        $date = DateTime::createFromFormat('Y-m-d H:i:s', $row['created_at']);
        $billcreated = $date->format('d/m/Y');
        if ($row['paid_at'] != '') {
            $date = DateTime::createFromFormat('Y-m-d H:i:s', $row['paid_at']);
            $billpaid = $date->format('d/m/Y');
        }
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
            <li class="active"><a href="./manageuserbill.php"><span class="glyphicon glyphicon-education"></span> Quản lý hóa đơn người dùng</a></li>
            <li><a href="./managecontract.php"><span class="glyphicon glyphicon-certificate"></span> Quản lý hợp đồng</a></li>
            <li><a href="./managereport.php"><span class="glyphicon glyphicon-bell"></span> Thông báo</a></li>
            <li><a href="../index.php"><span class="glyphicon glyphicon-user"></span> Trang chủ chính </a></li>
            <li><a href="../logout.php"><span class="glyphicon glyphicon-log-out"></span> Đăng xuất</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h2 class="text-center">Thông tin hóa đơn theo phòng</h2>
        <div id="result" class="text-center" style="height: 80px;"></div>
        <div class="container-fluid row text-center">
            <div class="col-sm-3"></div>
            <div class="col-sm-6">
                <form class="form-horizontal" id="userbillform" method="post" enctype="multipart/form-data"
                    action="updateUserBillform.php">
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="id">Mã:</label>
                        <div class="col-sm-8">
                            <input class="form-control" id="id-new" name="id-new" value="<?php echo $billid; ?>" disabled>
                            <input class="form-control" type="hidden" id="id-old" name="id-old" value="<?php echo $billid; ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="user">Mã người dùng :</label>
                        <div class="col-sm-8">
                            <input class="form-control" id='user' name="user" placeholder="Nhập mã người dùng" value="<?php echo $billuser ?>">
                            <p style='color:#e90000; font-size: 15px' id='error-billuser'></p>
                        </div>
                    </div>
                    <div>
                        <label class="control-label col-sm-4" for="userinfo">Thông tin người trả (nếu có):</label>
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
                                <tbody id='userinfo'></tbody>
                            </table>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-4" for="month">Tháng:</label>
                        <div class="col-sm-8">
                            <input class="form-control" type="number" max="12" id="month" placeholder="Nhập tháng" name="month" value="<?php echo $billmonth ?>">
                            <p style='color:#e90000; font-size: 15px' id='error-billmonth' class="error-message"></p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-4" for="year">Năm:</label>
                        <div class="col-sm-8">
                            <input class="form-control" type="number" id="year" placeholder="Nhập năm" name="year" value="<?php echo $billyear ?>">
                            <p style='color:#e90000; font-size: 15px' id='error-billyear' class="error-message"></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="room">Tiền phòng:</label>
                        <div class="col-sm-8">
                            <input class="form-control" id='room' name="room" placeholder="hệ thống tự tính theo phòng đã đăng kí" value="<?php echo $billroom ?>" disabled>
                            <p style='color:#e90000; font-size: 15px' id='error-billroom' class="error-message"></p>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="created">Ngày tạo:</label>
                        <div class="col-sm-8">
                            <input class="form-control" id="created" name="created" type="date" 
value="<?php echo ($billcreated != '' ? DateTime::createFromFormat('d/m/Y', $billcreated)->format('Y-m-d') : ''); ?>">
                            <p style='color:#e90000; font-size: 15px' id='error-billcreated' class="error-message"></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="status">Trạng thái hóa đơn:</label>
                        <div class="col-sm-8">
                            <select class="form-control" id="status" name="status">
                                <option value="0" <?php if ($billstatus == 0) echo "selected" ?>>...</option>
                                <option value="1" <?php if ($billstatus == 1) echo "selected" ?>>Chưa trả</option>
                                <option value="2" <?php if ($billstatus == 2) echo "selected"; ?>>Đã trả</option>
                            </select>
                            <p style='color:#e90000; font-size: 15px' id='error-billstatus' class="error-message"></p>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="paid">Ngày trả :</label>
                        <div class="col-sm-8">
                            <input class="form-control" id="paid" name="paid" type="date" 
                            value="<?php echo ($billpaid != '' ? DateTime::createFromFormat('d/m/Y', $billpaid)->format('Y-m-d') : ''); ?>">
                            
                            <p style='color:#e90000; font-size: 15px' id='error-billpaid' class="error-message"></p>
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
    $(document).ready(function() {
        $('#month').on('change keyup', function() {
            if ($('#month').val() > 12) $('#error-billmonth').text('Tháng chỉ từ 1 đến 12');
            else $('#error-billmonth').text('');
        });
        $('#year').on('change keyup', function() {
            if ($('#year').val() < 2024 || $('#year').val() > 2025)
                $('#error-billyear').text('Chỉ được tạo hóa đơn cho năm 2024-2025');
            else $('#error-billyear').text('');
        });
        function info($userid) {
            $.ajax({
                url: 'searchUser.php',
                method: 'POST',
                data: {id: $userid, name: '', gender:0, phone:'',  
                    address: '', email:'', status: 0, page: 1
                },
                dataType: 'json',
                success: function(response){
                    $('#userinfo').html(response.table);
                    let row=$('#userinfo tr').length; console.log(row);
                    if(row>1 || row==0) $('#error-billuser').text('Mã người dùng không tồn tại');
                },
                error: function(){
                    $('#error-billuser').text('Mã người dùng không tồn tại');
                }
            });
        }
        if($('#user').val()!='') info($("#user").val());

        $('#user').on('change', function(){
            $('#error-billuser').text('');
            let id=$('#user').val();
            info(id);
        });

        $('#userbillform').on('submit', function(e) {
            let ok = 1; 
            $('.error-message').text('');
            if ($('#user').val() == 0) {
                ok = 0;
                $('#error-billuser').text('Bạn cần chọn người dùng');
            }

            if ($('#month').val().trim() == '' || $('#month').val() > 12) {
                ok = 0;
                $('#error-billmonth').text('Bạn cần chọn tháng từ 1 đến 12');
            }

            if ($('#year').val().trim() == '' || $('#year').val() < 2024 || $('#year').val() > 2025) {
                ok = 0;
                $('#error-billyear').text('Bạn cần chọn năm 2024 hoặc 2025');
            }

            if ($('#created').val() == '') {
                ok = 0;
                $('#error-billcreated').text('Bạn cần nhập ngày tạo');
            }

            if ($('#status').val() == '0') {
                ok = 0;
                $('#error-billstatus').text('Bạn cần nhập trạng thái hoạt động');
            }

            if($('#error-billuser').val()!=''){
                ok=0;
            }

            if (ok == 0) e.preventDefault();
            else {
                let formData = new FormData($('#userbillform')[0]);
                e.preventDefault();
                $.ajax({
                    url: 'updateUserBillform.php',
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