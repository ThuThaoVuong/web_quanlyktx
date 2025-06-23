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
            <li class="active"><a href="./managecontract.php"><span class="glyphicon glyphicon-certificate"></span> Quản lý hợp đồng</a></li>
            <li><a href="./managereport.php"><span class="glyphicon glyphicon-bell"></span> Thông báo</a></li>
            <li><a href="../index.php"><span class="glyphicon glyphicon-user"></span> Trang chủ chính </a></li>
            <li><a href="../logout.php"><span class="glyphicon glyphicon-log-out"></span> Đăng xuất</a></li>
        </ul>
    </div>

    <div class="main-content">

        <h2 class="text-center">Quản lý hợp đồng</h2>
        <table class="table table-bordered table-hover table-striped text-center">
            <thead>
                <tr>
                    <th class="text-center">STT</th>
                    <th class="text-center">Mã</th>
                    <th class="text-center">Người thuê</th>
                    <th class="text-center">Phòng thuê</th>
                    <th class="text-center">Ngày bắt đầu thuê</th>
                    <th class="text-center">Số tháng thuê</th>
                    <th class="text-center">Ngày kết thúc thuê</th>
                    <th class="text-center">Admin đã kí</th>
                    <th class="text-center">Thời gian tạo</th>
                    <th class="text-center">Trạng thái hợp đồng</th>
                    <th class="text-center">Tác vụ</th>
                </tr>
                <tr>
                    <th class="text-center"><span class="glyphicon glyphicon-search"></span></th>
                    <th><input id='contractid' name="id" type="number" style="width:100%"></th>
                    <th>
                        <select id="contractuser" style="width: 100%;" name="user" class="text-center">
                            <option value="0">...</option>
                            <?php 
                            $sql='select msv,name from users where status>0';
                            $res=mysqli_query($conn, $sql);
                            while($row=mysqli_fetch_assoc($res)){
                            ?>
                            <option  value="<?php echo $row['msv']?>"><?php echo $row['name']?></option>
                            <?php }?>
                        </select>
                    </th>
                    <th>
                        <select id="contractroom" style="width: 100%;" name="room" class="text-center">
                            <option value="0">...</option>
                            <?php 
                            $sql='select r.id,r.name, k.name as kname from room r 
                            inner join ktx k on k.id=r.ktx_id
                            where r.status>0';
                            $res=mysqli_query($conn, $sql);
                            while($row=mysqli_fetch_assoc($res)){
                            ?>
                            <option  value="<?php echo $row['id']?>">
                                <?php echo $row['name'].' - '.$row['kname'];?>
                            </option>
                            <?php }?>
                        </select>
                    </th>
                    <th></th>
                    <th><input type="number" id="contractmonth" name="month"></th>
                    <th></th>
                    <th>
                        <select id="contractadmin" style="width: 100%;" name="admin" class="text-center">
                            <option value="0">...</option>
                            <?php 
                            $sql='select msv,name from users where status>1';
                            $res=mysqli_query($conn, $sql);
                            while($row=mysqli_fetch_assoc($res)){
                            ?>
                            <option  value="<?php echo $row['msv']?>">
                                <?php echo $row['name'];?>
                            </option>
                            <?php }?>
                        </select>
                    </th>
                    <th></th>
                    <th>
                        <select id="contractstatus" style="width: 100%;" name="status" class="text-center">
                            <option value="0">...</option>
                            <option value="1">Có hiệu lực</option>
                            <option value="2">Hết hiệu lực</option>
                            <option value="3">Chưa có hiệu lực</option>
                            <option value="4">Sắp hết hạn</option>
                        </select>
                    </th>
                    <th class="text-center"><button class="btn btn-sm">Tìm</button></th>
                </tr>
            </thead>
            <tbody id="contractresult">

            </tbody>
        </table>
        <div id="contractpage">

        </div>
    </div>

</body>
<script>
    $(document).ready(function(){
        function load(p){
            let id=$('#contractid').val(); 
            let user=$('#contractuser').val(); 
            let room=$('#contractroom').val();
            let month=$('#contractmonth').val();
            let admin=$('#contractadmin').val();
            let status=$('#contractstatus').val();
            let page=p;
            $.ajax({
                url: 'searchContract.php',
                method: 'POST',
                data: {id: id, user: user, room: room, month: month,
                    admin:admin, status: status, page: p
                },
                dataType: 'json',
                success: function(response){
                    $('#contractresult').html(response.table);
                    $('#contractpage').html(response.pagination)
                },
                error: function(){
                    alert('Lỗi');
                }
            });
        }

        $('#contractid').on('keyup change', function(e){
            e.preventDefault();load(1);
        });
        $('#contractuser').on('change', function(e){
            e.preventDefault();load(1);
        });
        $('#contractroom').on('change', function(e){
            e.preventDefault();load(1);
        });
        $('#contractmonth').on('keyup change', function(e){
            e.preventDefault();load(1);
        });
        $('#contractadmin').on('change', function(e){
            e.preventDefault();load(1);
        });
        $('#contractstatus').on('change', function(e){
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