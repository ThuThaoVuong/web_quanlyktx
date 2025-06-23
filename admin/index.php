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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            <li class="active"><a href="./index.php">Tổng quan</a></li>
            <li><a href="./managektx.php"><span class="glyphicon glyphicon-edit"></span> Quản lý KTX</a></li>
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
        <h2 class="text-center">Hệ thống quản lý KTX</h2>
        <?php
        function getCount($conn, $table, $condition = '1')
        {
            $sql = "SELECT COUNT(*) AS sl FROM $table WHERE $condition";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $res = $stmt->get_result();
            $row = $res->fetch_assoc();
            return $row['sl'];
        }
        ?>
        <h4 >Thống kê chung</h4>

        <div class="row">
            <!-- Tòa KTX -->
            <div class="col-sm-4">
                <div class="panel panel-default">
                    <div class="panel-body text-center">
                        <h4><?php echo getCount($conn, 'ktx', 'status>0'); ?> tòa KTX</h4>
                    </div>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="panel panel-default">
                    <div class="panel-body text-center">
                        <h4><?php echo getCount($conn, 'room', 'status>0'); ?> phòng</h4>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="panel panel-default">
                    <div class="panel-body text-center">
                        <h4><?php echo getCount($conn, 'users', 'status>0'); ?> người dùng</h4>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="panel panel-warning">
                    <div class="panel-body text-center">
                        <h4><?php echo getCount($conn, 'contract', '(status=1 and adddate(now(), INTERVAL 10 day)>= end_date) or status=4'); ?> hợp đồng sắp hết hạn</h4>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="panel panel-warning">
                    <div class="panel-body text-center">
                        <h4><?php echo getCount($conn, 'contract', 'status=3'); ?> hợp đồng chờ duyệt</h4>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="panel panel-info">
                    <div class="panel-body text-center">
                        <h4><?php echo getCount($conn, 'room_bill', 'status>0') ?> hóa đơn theo phòng</h4>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="panel panel-info">
                    <div class="panel-body text-center">
                        <h4><?php echo getCount($conn, 'users_bill', 'status>0') ?> hóa đơn theo người dùng</h4>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="panel panel-danger">
                    <div class="panel-body text-center">
                        <h4><?php echo getCount($conn, 'report', 'status=1') ?> thông báo chưa đọc</h4>
                    </div>
                </div>
            </div>
        </div>
        <?php
        $sql = "SELECT k.id AS ktx_id, k.name AS ktx_name, k.address,
            r.id AS room_id, r.name AS room_name,
            u.name AS student_name
            FROM ktx k
            LEFT JOIN room r ON r.ktx_id = k.id
            LEFT JOIN contract c ON c.room_id = r.id
            LEFT JOIN users u ON u.msv = c.users_id
            WHERE k.status > 0
            ORDER BY k.id, r.id ";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $res = $stmt->get_result();

        $data = [];
        while ($row = $res->fetch_assoc()) {
            $ktx_id = $row['ktx_id'];
            $room_id = $row['room_id'];

            if (!isset($data[$ktx_id])) {
                $data[$ktx_id] = [
                    'name' => $row['ktx_name'],
                    'address' => $row['address'],
                    'rooms' => []
                ];
            }

            if ($room_id) {
                if (!isset($data[$ktx_id]['rooms'][$room_id])) {
                    $data[$ktx_id]['rooms'][$room_id] = [
                        'name' => $row['room_name'],
                        'students' => []
                    ];
                }

                if ($row['student_name']) {
                    $data[$ktx_id]['rooms'][$room_id]['students'][] = $row['student_name'];
                }
            }
        }
        ?>

        <h4>Sơ đồ KTX</h4>
        <?php foreach ($data as $ktx): ?>
            <div class="panel panel-success">
                <div class="panel-heading text-center">
                    <h4 class="panel-title">
                        <?php echo htmlspecialchars($ktx['name']); ?> -
                        <small><?php echo htmlspecialchars($ktx['address']); ?></small>
                    </h4>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <?php if (count($ktx['rooms']) > 0): ?>
                            <?php foreach ($ktx['rooms'] as $room): ?>
                                <div class="col-sm-3">
                                    <div class="well" style="min-height: 100px;">
                                        <p class="text-center"><strong><?php echo htmlspecialchars($room['name']); ?></strong></p>
                                        <?php if (count($room['students']) > 0): ?>
                                            <?php foreach ($room['students'] as $student): ?>
                                                <p class="text-center"><?php echo htmlspecialchars($student); ?></p>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <p class="text-center text-muted">Chưa có sinh viên</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col-sm-12">
                                <p class="text-center text-muted">Chưa có phòng nào trong tòa này</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <h4>Biểu đồ thống kê doanh thu theo tòa KTX</h4>
        <canvas id="ktxBarChart" height="100"></canvas>

    </div>
    <script>
        $(document).ready(function() {
            $.ajax({
                url: 'ktx-stats.php',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    var labels = [];
                    var values = [];

                    response.forEach(function(item) {
                        labels.push(item.ktx);
                        values.push(item.total);
                    });

                    var ctx = $('#ktxBarChart')[0].getContext('2d');
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Doanh thu',
                                data: values,
                                backgroundColor: '#e90000',
                                borderColor: '#e90000',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                title: {
                                    display: true,
                                    text: 'Thống kê doanh thu theo các tòa KTX'
                                },
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: 'Doanh thu'
                                    }
                                },
                                x: {
                                    title: {
                                        display: true,
                                        text: 'Tòa KTX'
                                    }
                                }
                            }
                        }
                    });

                },
                error: function() {
                    alert('Không thể tải dữ liệu thống kê!');
                }
            });
        });
    </script>
    <script src="./script.js"></script>
</body>

</html>