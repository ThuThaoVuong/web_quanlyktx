<?php 
session_start();
require_once('../connect.php');
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

        <h2 class="text-center">Quản lý hóa đơn theo người dùng</h2>
        <a href="./updateuserbill.php?def=add"><button class="btn btn-lg">Thêm hóa đơn người dùng mới</button></a>
        <table class="table table-bordered table-hover table-striped text-center">
            <thead>
                <tr>
                    <th class="text-center">STT</th>
                    <th class="text-center">Mã</th>
                    <th class="text-center">MSV</th>
                    <th class="text-center">Tên</th>
                    <th class="text-center">Tháng</th>
                    <th class="text-center">Năm</th>
                    <th class="text-center">Tiền phòng</th>
                    <th class="text-center">Ngày tạo</th>
                    <th class="text-center">Trạng thái</th>
                    <th class="text-center">Ngày trả</th>
                    <th class="text-center">Tác vụ</th>
                </tr>
                <tr>
                    <th class="text-center"><span class="glyphicon glyphicon-search"></span></th>
                    <th><input id='billid' name="id" style="width:100%"></th>
                    <th><input id="billmsv" name="msv" style="width:100%"></th>
                    <th><input id="billname" name="name" style="width:100%"></th>
                    <th><input id="billmonth" name="month" type="number" max="12" style="width: 100%;"></th>
                    <th><input id='billyear' name="year" type="number" style="width:100%;"></th>
                    <th>
                        <select id="billroom" style="width: 100%;" name="room" class="text-center">
                            <option value="0">...</option>
                            <option value="1">Dưới 500k</option>
                            <option value="2">Từ 500k trở lên</option>
                        </select>
                    </th>
                    <th></th>
                    <th>
                        <select id="billstatus" style="width: 100%;" name="billstatus" class="text-center">
                            <option value="0">...</option>
                            <option value="1">Chưa trả</option>
                            <option value="2">Đã trả</option>
                        </select>
                    </th>
                    <th></th>
                    <th class="text-center"><button class="btn btn-sm">Tìm</button></th>
                </tr>
            </thead>
            <tbody id="billresult">

            </tbody>
        </table>
        <div id="billpage">

        </div>
    </div>

</body>
<script>
    $(document).ready(function(){
        function load(p){
            let id=$('#billid').val();
            let msv=$('#billmsv').val().trim(); 
            let name=$('#billname').val().trim();
            let room=$('#billroom').val(); 
            let month=$('#billmonth').val();
            let year=$('#billyear').val();
            let status=$('#billstatus').val();
            let page=p;
            $.ajax({
                url: 'searchUserBill.php',
                method: 'POST',
                data: {id: id, msv:msv, name:name, room:room, month: month,
                    year: year, status: status, page: p
                },
                dataType: 'json',
                success: function(response){
                    $('#billresult').html(response.table);
                    $('#billpage').html(response.pagination)
                },
                error: function(){
                    alert('Lỗi');
                }
            });
        }

        $('#billid').on('change', function(e){
            e.preventDefault();load(1);
        });
        $('#billmsv').on('keyup', function(e){
            e.preventDefault(); load(1);
        });
        $('#billname').on('keyup', function(e){
            e.preventDefault(); load(1);
        })
        $('#billroom').on('change', function(e){
            e.preventDefault();load(1);
        });
        $('#billmonth').on('keyup change', function(e){
            e.preventDefault();load(1);
        });
        $('#billyear').on('keyup change', function(e){
            e.preventDefault();load(1);
        });
        $('#billstatus').on('change', function(e){
            e.preventDefault();load(1);
        });

        $(document).on('click', 'a.changePage', function(e){
            e.preventDefault();
            let p = $(this).text().trim(); // Lấy số trang
            console.log("Trang được chọn:", p);
            load(p);
        });

        load(1);
    });
</script>
<script src="./script.js"></script>
</html>