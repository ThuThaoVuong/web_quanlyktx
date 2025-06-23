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

        <h2 class="text-center">Quản lý phòng</h2>
        <a href="./updateroom.php?def=add"><button class="btn btn-lg">Thêm phòng mới</button></a>
        <table class="table table-bordered table-hover table-striped text-center">
            <thead>
                <tr>
                    <th class="text-center">STT</th>
                    <th class="text-center">Mã</th>
                    <th class="text-center">KTX</th>
                    <th class="text-center">Phòng</th>
                    <th class="text-center">Vị trí</th>
                    <th class="text-center">Phòng cho giới tính</th>
                    <th class="text-center">Số người ở</th>
                    <th class="text-center">Hiện có</th>
                    <th class="text-center">Giá phòng</th>
                    <th class="text-center">Trạng thái phòng</th>
                    <th class="text-center">Tác vụ</th>
                </tr>
                <tr>
                    <th class="text-center"><span class="glyphicon glyphicon-search"></span></th>
                    <th><input id='roomid' type="number" style="width:100%"></th>
                    <th>
                        <select id="roomktx" style="width: 100%;" name="ktx" class="text-center">
                            <option value="0">...</option>
                            <?php 
                            $sql='select id,name from ktx where status>0';
                            $res=mysqli_query($conn, $sql);
                            while($row=mysqli_fetch_assoc($res)){
                            ?>
                            <option  value="<?php echo $row['id']?>"><?php echo $row['name'];?></option>
                            <?php }?>
                        </select>
                    </th>
                    <th><input id="roomname" name="name" style="width: 100%;"></th>
                    <th><input id='roomarea' name="area" style="width:100%;"></th>
                    <th>
                        <select id="roomgender" style="width: 100%;" name="gender" class="text-center">
                            <option value="0">...</option>
                            <option value="1">Nam</option>
                            <option value="2">Nữ</option>
                        </select>
                    </th>
                    <th>
                        <select id="roomslot" style="width: 100%;" name="slot" class="text-center">
                            <option value="0">...</option>
                            <option value="4">4</option>
                            <option value="6">6</option>
                            <option value="8">8</option>
                        </select>
                    </th>
                    <th></th>
                    <th>
                        <select id="roomprice" style="width: 100%;" name="roomprice" class="text-center">
                            <option value="0">...</option>
                            <option value="1">Dưới 500k</option>
                            <option value="2">Từ 500k trở lên</option>
                        </select>
                    </th>
                    <th>
                        <select id="roomstatus" style="width: 100%;" name="roomstatus" class="text-center">
                            <option value="0">...</option>
                            <option value="1">Đang hoạt động</option>
                            <option value="2">Tạm dừng hoạt động</option>
                        </select>
                    </th>
                    <th class="text-center"><button class="btn btn-sm">Tìm</button></th>
                </tr>
            </thead>
            <tbody id="roomresult">

            </tbody>
        </table>
        <div id="roompage">

        </div>
    </div>

</body>
<script>
    $(document).ready(function(){
        function load(p){
            let id=$('#roomid').val(); 
            let ktx=$('#roomktx').val(); 
            let name=$('#roomname').val().trim();
            let area=$('#roomarea').val().trim();
            let gender=$('#roomgender').val();
            let slot=$('#roomslot').val();
            let price=$('#roomprice').val();
            let status=$('#roomstatus').val();
            let page=p;
            $.ajax({
                url: 'searchRoom.php',
                method: 'POST',
                data: {id: id, ktx:ktx, name: name, area: area, gender: gender, 
                    slot: slot, price: price, status: status, page: p
                },
                dataType: 'json',
                success: function(response){
                    $('#roomresult').html(response.table);
                    $('#roompage').html(response.pagination)
                },
                error: function(){
                    alert('Lỗi');
                }
            });
        }

        $('#roomid').on('keyup change', function(e){
            e.preventDefault();load(1);
        });
        $('#roomktx').on('change', function(e){
            e.preventDefault();load(1);
        });
        $('#roomname').on('keyup', function(e){
            e.preventDefault();load(1);
        });
        $('#roomarea').on('key up', function(e){
            e.preventDefault();load(1);
        });
        $('#roomgender').on('change', function(e){
            e.preventDefault();load(1);
        });
        $('#roomslot').on('change', function(e){
            e.preventDefault();load(1);
        });
        $('#roomprice').on('change', function(e){
            e.preventDefault();load(1);
        });
        $('#roomstatus').on('change', function(e){
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