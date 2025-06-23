<?php
session_start();
require_once('connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $msv = $_POST['msv'];
    $name = $_POST['name'];
    $gender = $_POST['gender'] - 1;
    $dob=$_POST['dob'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Xử lý avatar nếu có
    $avatar = null;
    if (!empty($_FILES['image']['name'])) {
        $uploadPath = __DIR__ . '/images/'; // Đường dẫn tuyệt đối (dùng cho PHP lưu file)

        $fileName = time() . '_' . basename($_FILES["image"]["name"]);
        $targetFile = $uploadPath . $fileName;

        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png'];

        if (in_array($imageFileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                // Lưu đường dẫn tương đối để dùng trong web hoặc CSDL
                $avatar = $fileName;
            } else {
                echo "<script>alert('Tải ảnh thất bại.');</script>";
            }
        } else {
            echo "<script>alert('Chỉ chấp nhận định dạng jpg, jpeg, png.');</script>";
        }
    }
    else $avatar='avatar.jpg';

    // Chuẩn bị câu lệnh insert
    $stmt = $conn->prepare("INSERT INTO users (msv, name, gender, dob, phone, address, email, password, avatar, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1)");
    $stmt->bind_param("ssissssss", $msv, $name, $gender, $dob, $phone, $address, $email, $password, $avatar);

    if ($stmt->execute()) {
        header('location: login.php');
        exit;
    } else {
         echo "<span class='alert alert-danger'>Hệ thống đang gặp lỗi. Vui lòng thử lại sau<span>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KTX</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="./style.css">
</head>

<body>
    <!--Header: navigation-->
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <a class="navbar-brand" href="#"><span class="glyphicon glyphicon-home"></span> Logo</a>
            </div>
            <div class="collapse navbar-collapse" id="myNavbar">
                <ul class="nav navbar-nav">
                    <li><a href="./index.php">Trang Chủ</a></li>
                    <li><a href="./dorm.php">Ký Túc Xá</a></li>
                    <li><a href="./about.php">Về Chúng Tôi</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <?php if (isset($_SESSION['userid'])) { ?>
                        <li><a href="./news.php"><span class="glyphicon glyphicon-bell"></span> Thông báo</a></li>
                        <li><a href="./payment.php"><span class="glyphicon glyphicon-usd"></span> Thanh Toán</a></li>
                        <li><a href="./account.php"><span class="glyphicon glyphicon-user"></span> Tài khoản của tôi</a></li>
                    <?php } else { ?>
                        <li><a href="./login.php"><span class="glyphicon glyphicon-log-in"></span> Đăng Nhập</a></li>
                        <li class="active"><a href="./register.php"><span class="glyphicon glyphicon-user"></span> Đăng Ký</a></a></li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </nav>


    <!--Icon-->
    <div class="icon">
        <ul class="call">
            <li>
                <a href="https://zalo.me/pc" target="_blank">
                    <span class="glyphicon glyphicon-earphone gly"></span>
                </a>
            </li>
            <li>
                <a href="https://maps.google.com" target="_blank">
                    <span class="glyphicon glyphicon-map-marker gly"></span>
                </a>
            </li>
            <li>
                <a href="#title"><span class="glyphicon glyphicon-arrow-up gly"></span></a>
            </li>
        </ul>
    </div>


    <div id="title" class="jumbotron text-center">
        <h2>Đăng ký</h2>
    </div>

    <div class="container-fluid row text-center">
        <div class="col-sm-3"></div>
        <div class="col-sm-6">
            <form class="form-horizontal" method="post" id="register" enctype="multipart/form-data"
                action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <div class="form-group">
                    <label class="control-label col-sm-4" for="msv">MSV:</label>
                    <div class="col-sm-8">
                        <input class="form-control" id="msv" placeholder="Nhập MSV" name="msv" value="">
                        <p id="msv-result" style='color:#e90000; font-size: 15px'></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-4" for="name">Họ và tên:</label>
                    <div class="col-sm-8">
                        <input class="form-control" id="name" placeholder="Nhập tên" name="name" value="">
                        <p id="name-result" class="error-message" style='color:#e90000; font-size: 15px'></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-4" for="gender">Giới tính:</label>
                    <div class="col-sm-8">
                        <label><input type="radio" id="1" name="gender" value="1"> Nam</label>
                        <label><input type="radio" id="2" name="gender" value="2"> Nữ</label>
                        <p id="gender-result" class="error-message"  style='color:#e90000; font-size: 15px'></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-4" for="dob">Ngày sinh: </label>
                    <div class="col-sm-8">
                        <input class="form-control" id="dob" type="date" value="" name="dob">
                        <p id="dob-result"  class="error-message" style='color:#e90000; font-size: 15px'></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-4" for="phone">Số điện thoại:</label>
                    <div class="col-sm-8">
                        <input class="form-control" id="phone" placeholder="Nhập sdt" name="phone" value="">
                        <p id="phone-result" style='color:#e90000; font-size: 15px'></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-4" for="address">Quê quán:</label>
                    <div class="col-sm-8">
                        <input class="form-control" id="address" placeholder="Nhập địa chỉ" name="address" value="">
                        <p id="address-result"  class="error-message" style='color:#e90000; font-size: 15px'></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-4" for="email">Email:</label>
                    <div class="col-sm-8">
                        <input class="form-control" type="email" id="email" placeholder="Nhập email" name="email" value="">
                        <p id="email-result" style='color:#e90000; font-size: 15px'></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-4" for="password">Password:</label>
                    <div class="col-sm-8">
                        <input type="password" class="form-control" id="password" placeholder="Nhập password" name="password" value="">
                        <p id="password-result"  class="error-message"  style='color:#e90000; font-size: 15px'></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-4" for="image">Avatar (Nếu có):</label>
                    <div class="col-sm-8">
                        <input type="file" class="form-control" id="image" name="image" accept="image/png, image/jpeg" value="">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <button type="submit" class="btn btn-lg">Đăng ký</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-sm-3"></div>
    </div>

    <footer class="bg-main">
        <div class="container">
            <div class="row">
                <div class="col-sm-4 text-center">
                    <div>
                        <h5 class="footer-col">Liên hệ với chúng tôi</h5>
                        <div>
                            <a class="footer-col" target="_blank"
                                href="https://www.google.com/maps/place/H%E1%BB%8Dc+vi%E1%BB%87n+C%C3%B4ng+ngh%E1%BB%87+B%C6%B0u+ch%C3%ADnh+vi%E1%BB%85n+th%C3%B4ng/@20.980918,105.7848416,17z/data=!3m1!4b1!4m6!3m5!1s0x3135accdd8a1ad71:0xa2f9b16036648187!8m2!3d20.980913!4d105.7874165!16s%2Fg%2F12168p16?hl=vi-VN&entry=ttu&g_ep=EgoyMDI1MDIyMy4xIKXMDSoASAFQAw%3D%3D">
                                <i class="fa fa-map-marker" aria-hidden="true"></i>
                                <h5>Địa điểm</h5>
                            </a>
                            <a class="footer-col" target="_blank" href="https://zalo.me/pc">
                                <i class="fa fa-phone" aria-hidden="true"></i>
                                <h5>Gọi +01 1234567890</h5>
                            </a>
                            <a class="footer-col" target="_blank" href="https://gmail.com">
                                <i class="fa fa-envelope" aria-hidden="true"></i>
                                <h5>demo@gmail.com</h5>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 text-center">
                    <div>
                        <h5 class="footer-col">KTX</h5>
                        <p class="footer-col">Kí túc xá (KTX) dành cho sinh viên,
                            với mục đích cung cấp không gian sinh hoạt chung, tiện nghi cơ bản. KTX giúp tiết kiệm chi
                            phí
                            và tạo môi trường sống thuận lợi cho việc học tập, làm việc, và giao lưu cộng đồng.</p>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <div class="footer-contact">
                        <h5 class="footer-col">Nội quy</h5>
                        <p class="footer-col">Giữ gìn vệ sinh chung</p>
                        <p class="footer-col">Tôn trọng mọi người</p>
                        <p class="footer-col">Bảo vệ tài sản chung</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <script>
        $(document).ready(function() {
            $('#msv').on('blur', function() {
                let msv = $(this).val();
                if (msv !== '') {
                    $.post('registerForm.php', {
                        type: 'Msv',
                        value: msv
                    }, function(data) {
                        $('#msv-result').html(data);
                    });
                }
            });

            $('#phone').on('blur', function() {
                let phone = $(this).val();
                if (msv !== '') {
                    $.post('registerForm.php', {
                        type: 'phone',
                        value: phone
                    }, function(data) {
                        $('#phone-result').html(data);
                    });
                }
            });

            $('#email').on('blur', function() {
                let email = $(this).val();
                if (email !== '') {
                    $.post('registerForm.php', {
                        type: 'Email',
                        value: email
                    }, function(data) {
                        $('#email-result').html(data);
                    });
                }
            });

            $('#register').on('submit', function(e) {
                let isValid = true;
                $(".error-message").text('');
                // MSV
                console.log($("#name").val().trim());
                if ($("#msv").val().trim() == '') {
                    $("#msv-result").text('Bạn cần nhập MSV');
                    isValid = false;
                } else if ($("#msv-result").text().trim() !== '') {
                    isValid = false;
                }

                // Họ và tên
                if ($("#name").val().trim() == '') {
                    $("#name-result").text('Bạn cần nhập họ và tên');
                    isValid = false;
                }

                // Giới tính
                if ($('input[name=gender]:checked').length == 0) {
                    $("#gender-result").text('Bạn cần chọn giới tính');
                    isValid = false;
                }

                // Ngày sinh
                if ($("#dob").val().trim() == '') {
                    $("#dob-result").text('Bạn cần nhập ngày sinh');
                    isValid = false;
                }

                // Số điện thoại
                if ($("#phone").val().trim() == '') {
                    $("#phone-result").text('Bạn cần nhập số điện thoại');
                    isValid = false;
                } else if ($("#phone-result").text().trim() !== '') {
                    isValid = false;
                }

                // Địa chỉ
                if ($("#address").val().trim() == '') {
                    $("#address-result").text('Bạn cần nhập quê quán');
                    isValid = false;
                }

                // Email
                if ($("#email").val().trim() == '') {
                    $("#email-result").text('Bạn cần nhập email');
                    isValid = false;
                } else if ($("#email-result").text().trim() !== '') {
                    isValid = false;
                }

                // Mật khẩu
                if ($("#password").val().trim() =='') {
                    $("#password-result").text('Bạn cần nhập mật khẩu');
                    isValid = false;
                }

                if (!isValid) {
                    e.preventDefault();
                }
            });

        });
    </script>
</body>

</html>