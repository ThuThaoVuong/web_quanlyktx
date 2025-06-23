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
            <li class="active"><a href="./manageuser.php"><span class="glyphicon glyphicon-education"></span> Quản lý tài khoản</a></li>
            <li><a href="./manageuserbill.php"><span class="glyphicon glyphicon-education"></span> Quản lý hóa đơn người dùng</a></li>
            <li><a href="./managecontract.php"><span class="glyphicon glyphicon-certificate"></span> Quản lý hợp đồng</a></li>
            <li><a href="./managereport.php"><span class="glyphicon glyphicon-bell"></span> Thông báo</a></li>
            <li><a href="../index.php"><span class="glyphicon glyphicon-user"></span> Trang chủ chính </a></li>
            <li><a href="../logout.php"><span class="glyphicon glyphicon-log-out"></span> Đăng xuất</a></li>
        </ul>
    </div>
    <div class="main-content">
        <h2 class="text-center">Quản lý người dùng</h2>
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
                <tr>
                    <form id="svsearch" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>">
                    <th class="text-center"><span class="glyphicon glyphicon-search"></span></th>
                    <th><input id="svid" style="width: 100%" name="id" value=""></th>
                    <th><input id="svname" style="width: 100%" name="name" value=""></th>
                    <th>
                        <select style="width: 100%;" id="svgender" class="text-center" name="gender">
                            <option value="0">...</option>
                            <option value="1">Nam</option>
                            <option value="2">Nữ</option>
                        </select>
                    </th>
                    <th></th>
                    <th><input id="svphone" style="width:100%;" name="phone" ></th>
                    <th><input id="svaddress" style="width: 100%;" name="address" value=""></th>
                    <th><input id="svemail" style="width:100%;" name="email"></th>
                    <th>
                        <select style="width: 100%;" id="svstatus" class="text-center" name="status">
                            <option value="0">...</option>
                            <option value="1">Sinh viên</option>
                            <option value="2">Admin</option>
                        </select>
                    </th>
                    <th class="text-center"><button type='submit' class="btn btn-sm">Tìm</button></th>
                    </form>
                </tr>
            </thead>
            <tbody id="svresult">
            </tbody>
        </table>
        <div id="svpage">

        </div>
    </div>

</body>
<script>
    $(document).ready(function(){
        function load(p){
            let id=$('#svid').val().trim();
            let name=$('#svname').val().trim();
            let gender=$('#svgender').val();
            let phone=$('#svphone').val();
            let address=$('#svaddress').val().trim();
            let email=$('#svemail').val().trim();
            let status=$('#svstatus').val();
            let page=p;
            $.ajax({
                url: 'searchUser.php',
                method: 'POST',
                data: {id: id, name: name, gender:gender, phone:phone,  
                    address: address, email:email, status: status, page: p
                },
                dataType: 'json',
                success: function(response){
                    $('#svresult').html(response.table);
                    $('#svpage').html(response.pagination)
                },
                error: function(){
                    alert('Lỗi');
                }
            });
        }

        $('#svid').on('keyup', function(e){
            e.preventDefault();load(1);
        });
        $('#svname').on('keyup', function(e){
            e.preventDefault();load(1);
        });
        $('#svgender').on('change', function(e){
            e.preventDefault();load(1);
        });
        $('#svphone').on('keyup', function(e){
            e.preventDefault();load(1);
        });
        $('#svaddress').on('keyup', function(e){
            e.preventDefault();load(1);
        });
        $('#svstatus').on('change', function(e){
            e.preventDefault();load(1);
        });
        $('#svsearch').on('submit', function(e){
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