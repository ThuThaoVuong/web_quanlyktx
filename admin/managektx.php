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
        <h2 class="text-center">Quản lý KTX</h2>
        <a href="./updatektx.php?def=add"><button class="btn btn-lg">Thêm KTX mới</button></a>
        <table class="table table-bordered table-hover table-striped text-center">
            <thead>
                <tr>
                    <th class="text-center">STT</th>
                    <th class="text-center">Mã ktx</th>
                    <th class="text-center">Tên ktx</th>
                    <th class="text-center">Địa chỉ</th>
                    <th class="text-center">Mô tả ngắn</th>
                    <th class="text-center">Trạng thái KTX</th>
                    <th class="text-center">Tác vụ</th>
                </tr>
                <tr>
                    <form id="ktxsearch" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>">
                    <th class="text-center"><span class="glyphicon glyphicon-search"></span></th>
                    <th><input id="ktxid" style="width: 100%" name="id" value=""></th>
                    <th><input id="ktxname" style="width: 100%" name="name" value=""></th>
                    <th><input id="ktxaddress" style="width: 100%;" name="address" value=""></th>
                    <th><input id="ktxinfo" style="width: 100%;" name="info" value=""></th>
                    <th>
                        <select style="width: 100%;" id="ktxstatus" class="text-center" name="status">
                            <option value="0">...</option>
                            <option value="1">Đang hoạt động</option>
                            <option value="2">Chưa hoạt động</option>
                            <option value="3">Tạm dừng hoạt động</option>
                        </select>
                    </th>
                    <th class="text-center"><button type='submit' class="btn btn-sm">Tìm</button></th>
                    </form>
                </tr>
            </thead>
            <tbody id="ktxresult">
            </tbody>
        </table>
        <div id="ktxpage">

        </div>
    </div>

</body>
<script>
    $(document).ready(function(){
        function load(p){
            let id=$('#ktxid').val();
            let name=$('#ktxname').val().trim();
            let address=$('#ktxaddress').val().trim();
            let info=$('#ktxinfo').val().trim();
            let status=$('#ktxstatus').val();
            let page=p;
            $.ajax({
                url: 'searchKTX.php',
                method: 'POST',
                data: {id: id, name: name, address: address,
                    info: info, status: status, page: p
                },
                dataType: 'json',
                success: function(response){
                    $('#ktxresult').html(response.table);
                    $('#ktxpage').html(response.pagination)
                },
                error: function(){
                    alert('Lỗi');
                }
            });
        }

        $('#ktxid').on('keyup', function(e){
            e.preventDefault();load(1);
        });
        $('#ktxname').on('keyup', function(e){
            e.preventDefault();load(1);
        });
        $('#ktxaddress').on('keyup', function(e){
            e.preventDefault();load(1);
        });
        $('#ktxinfo').on('keyup', function(e){
            e.preventDefault();load(1);
        });
        $('#ktxstatus').on('change', function(e){
            e.preventDefault();load(1);
        });
        $('#ktxsearch').on('submit', function(e){
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