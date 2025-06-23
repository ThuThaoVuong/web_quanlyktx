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
            <li><a href="./manageuserbill.php"><span class="glyphicon glyphicon-education"></span> Quản lý hóa đơn người dùng</a></li>
            <li><a href="./managecontract.php"><span class="glyphicon glyphicon-certificate"></span> Quản lý hợp đồng</a></li>
            <li class="active"><a href="./managereport.php"><span class="glyphicon glyphicon-bell"></span> Thông báo</a></li>
            <li><a href="../index.php"><span class="glyphicon glyphicon-user"></span> Trang chủ chính </a></li>
            <li><a href="../logout.php"><span class="glyphicon glyphicon-log-out"></span> Đăng xuất</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h2 class="text-center">Quản lý thông báo, báo cáo</h2>
        <a href="./updatereport.php?def=add"><button class="btn btn-lg">Thêm thông báo mới</button></a>
        <table class="table table-bordered table-hover table-striped text-center">
            <thead>
                <tr>
                    <th class="text-center">STT</th>
                    <th class="text-center">Mã</th>
                    <th class="text-center">Mã người gửi</th>
                    <th class="text-center">Mã người nhận</th>
                    <th class="text-center">Tiêu đề</th>
                    <th class="text-center">Nội dung</th>
                    <th class="text-center">Thời gian gửi</th>
                    <th class="text-center">Trạng thái</th>
                    <th class="text-center">Tác vụ</th>
                </tr>
                <tr>
                    <th class="text-center"><span class="glyphicon glyphicon-search"></span></th>
                    <th><input id="reportid" style="width: 100%" name="id" value=""></th>
                    <th><input id="reportsender" style="width:100%" name="sender" ></th>
                    <th><input id='reportreceiver' style="width:100%" name="receiver"></th>
                    <th><input id="reporttitle" style="width: 100%;" name="title" value=""></th>
                    <th><input id="reportcontent" style="width:100%" name="content"></th>
                    <th></th>
                    <th>
                        <select style="width: 100%;" id="reportstatus" class="text-center" name="status">
                            <option value="0">...</option>
                            <option value="1">Chưa xem</option>
                            <option value="2">Đã xem</option>
                        </select>
                    </th>
                    <th class="text-center"><button type='submit' class="btn btn-sm">Tìm</button></th>
                </tr>
            </thead>
            <tbody id="reportresult">
            </tbody>
        </table>
        <div id="reportpage">

        </div>
    </div>

</body>
<script>
    $(document).ready(function(){
        function load(p){
            let id=$('#reportid').val().trim();
            let sender=$('#reportsender').val().trim();
            let receiver=$('#reportreceiver').val().trim();
            let title=$('#reporttitle').val().trim();
            let content=$('#reportcontent').val().trim();
            let status=$('#reportstatus').val();
            let page=p;
            $.ajax({
                url: 'searchReport.php',
                method: 'POST',
                data: {id: id, sender: sender, receiver: receiver,
                    title: title, content: content, status: status, page: p
                },
                dataType: 'json',
                success: function(response){
                    $('#reportresult').html(response.table);
                    $('#reportpage').html(response.pagination)
                },
                error: function(){
                    alert('Lỗi');
                }
            });
        }

        $('#reportid').on('keyup', function(e){
            e.preventDefault();load(1);
        });
        $('#reportsender').on('keyup', function(e){
            e.preventDefault();load(1);
        });
        $('#reportreceiver').on('keyup', function(e){
            e.preventDefault();load(1);
        });
        $('#reporttitle').on('keyup', function(e){
            e.preventDefault();load(1);
        });
        $('#reportcontent').on('keyup', function(e){
            e.preventDefault();load(1);
        });
        $('#reportstatus').on('change', function(e){
            e.preventDefault(); load(1);
        })
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