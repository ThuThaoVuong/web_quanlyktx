<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="vi">

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
                    <?php if(isset($_SESSION['userstatus']) && $_SESSION['userstatus']>1){?>
                        <li><a href="./admin/index.php">Admin</a></li>
                    <?php }?>
                    <li><a href="./dorm.php">Ký Túc Xá</a></li>
                    <li class="active"><a href="./about.php">Về Chúng Tôi</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                        <?php if(isset($_SESSION['userid'])){?>
                            <li><a href="./news.php"><span class="glyphicon glyphicon-bell"></span> Thông báo</a></li>
                            <li><a href="./payment.php"><span class="glyphicon glyphicon-usd"></span> Thanh Toán</a></li>
                            <li><a href="./account.php"><span class="glyphicon glyphicon-user"></span> Tài khoản của tôi</a></li>
                        <?php } else {?>
                            <li><a href="./login.php"><span class="glyphicon glyphicon-log-in"></span> Đăng Nhập</a></li>
                            <li><a href="./register.php"><span class="glyphicon glyphicon-user"></span> Đăng Ký</a></a></li>
                        <?php }?>
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
		<h2>Giới thiệu về kí túc xá</h2>
	</div>

	<div class="container" style="margin-bottom: 30px;">
		<div class="row">
			<div class="col-sm-6">
				<div thumbnail>
					<img src="./images/anh_ktx_c.jpg" width="100%">
				</div>
			</div>
			<h4 class="col-sm-6">
				Kí túc xá (KTX) là nơi ở dành cho sinh viên, cung cấp các phòng ngủ đơn giản với tiện nghi cơ bản. 
                Các dịch vụ tại kí túc xá bao gồm vệ sinh, internet miễn phí, an ninh 24/7, phòng sinh hoạt chung, và dịch vụ giặt ủi. 
                KTX mang đến một môi trường sống tiện lợi, 
                tiết kiệm chi phí và thuận lợi cho việc học tập, làm việc và giao lưu cộng đồng.
			</h4>
		</div>
	</div>

	<div class="container" style="margin-bottom: 30px;">
		<div class="row">
			<h4 class="col-sm-6 text-center">
				<p>Nội quy kí túc xá:</p>
                <p>Giữ gìn vệ sinh chung</p>
                <p>Tôn trọng mọi người</p>
                <p>Không được sử dụng chất kích thích</p>
			</h4>
			<h4 class="col-sm-6 text-center">
                <p>Mọi thắc mắc có thể liên hệ qua:</p>
				<a style="color: #e90000;" target="_blank"
					href="https://www.google.com/maps/place/H%E1%BB%8Dc+vi%E1%BB%87n+C%C3%B4ng+ngh%E1%BB%87+B%C6%B0u+ch%C3%ADnh+vi%E1%BB%85n+th%C3%B4ng/@20.980918,105.7848416,17z/data=!3m1!4b1!4m6!3m5!1s0x3135accdd8a1ad71:0xa2f9b16036648187!8m2!3d20.980913!4d105.7874165!16s%2Fg%2F12168p16?hl=vi-VN&entry=ttu&g_ep=EgoyMDI1MDIyMy4xIKXMDSoASAFQAw%3D%3D">
					<i class="fa fa-map-marker" aria-hidden="true"></i>
					<p>Địa điểm</p>
				</a>
				<a style="color: #e90000;" target="_blank" href="https://zalo.me/pc">
					<i class="fa fa-phone" aria-hidden="true"></i>
					<p>Gọi +01 1234567890</p>
				</a>
				<a style="color: #e90000;" target="_blank" href="https://gmail.com">
					<i class="fa fa-envelope" aria-hidden="true"></i>
					<p>demo@gmail.com</p>
				</a>
			</h4>
		</div>
	</div>

    <div id="services" class="container-fluid text-center">
        <h2>Chúng tôi cung cấp các dịch vụ</h2>
        <br>
        <div class="row">
            <div class="col-sm-4">
                <span class="glyphicon glyphicon-home logo-small"></span>
                <h4>Chỗ ở</h4>
                <p>Cung cấp phòng ngủ, giường, và các tiện nghi cơ bản cho sinh viên</p>
            </div>
            <div class="col-sm-4">
                <span class="glyphicon glyphicon-tint logo-small"></span>
                <h4>Dịch vụ vệ sinh</h4>
                <p>Dọn dẹp phòng và khu vực chung, đảm bảo môi trường sống sạch sẽ.</p>
            </div>
            <div class="col-sm-4">
                <span class="glyphicon glyphicon-globe logo-small"></span>
                <h4>Internet miễn phí</h4>
                <p>Cung cấp kết nối Wi-Fi cho người dùng trong khuôn viên kí túc xá.</p>
            </div>
        </div>
        <br><br>
        <div class="row">
            <div class="col-sm-4">
                <span class="glyphicon glyphicon-facetime-video logo-small"></span>
                <h4>An ninh 24/7</h4>
                <p>Đảm bảo an toàn cho người ở thông qua bảo vệ và hệ thống camera giám sát.</p>
            </div>
            <div class="col-sm-4">
                <span class="glyphicon glyphicon-cutlery logo-small"></span>
                <h4>Phòng sinh hoạt chung</h4>
                <p>Cung cấp không gian để người ở sinh hoạt, học tập hoặc giải trí chung.</p>
            </div>
            <div class="col-sm-4">
                <span class="glyphicon glyphicon-adjust logo-small"></span>
                <h4>Dịch vụ giặt ủi</h4>
                <p>Cung cấp máy giặt, máy sấy hoặc dịch vụ giặt ủi cho người ở.</p>
            </div>
        </div>
    </div>

	<div class="container" style="margin-bottom: 30px;">
		<div class="row">
			<div class="col-md-6">
				<iframe
					src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d59587.94638110634!2d105.79576374133383!3d21.022814759721303!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ab9bd9861ca1%3A0xe7887f7b72ca17a9!2zSMOgIE7hu5lpLCBWaeG7h3QgTmFt!5e0!3m2!1svi!2s!4v1743011915937!5m2!1svi!2s"
					width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"
					referrerpolicy="no-referrer-when-downgrade"></iframe>
			</div>
			<div class="col-md-6">
				<div id="contact">
					<h2 class="text-center">Liên hệ với chúng tôi</h2>
					<form class="row" action="./contact.php" id="contactForm">
						<div class="col-sm-12">
							<div class="row">
								<div class="col-md-12 form-group">
									<input class="form-control" id="email" name="email" placeholder="Email" type="email">
                                    <p class="error-message" id="error-email" style="color:red;"></p>
								</div>
							</div>
							<textarea class="form-control" id="content" name="content" placeholder="Thắc mắc của bạn"
								rows="5"></textarea><br>
                            <p class="error-message" id="error-content" style="color:red;"></p>
							<div class="row">
								<div class="col-sm-12 form-group">
									<button class="btn btn-lg pull-right" type="submit">Gửi</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
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
    <script src="./script.js"></script>
</body>
<script>
        $(document).ready(function(){
            $('#email').on('blur', function(){
                let email=$('#email').val().trim();
                if (email !== '') {
                    $.post('contact.php', {
                        type: 'email',
                        email: email
                    }, function(data) {
                        $('#error-email').html(data);
                    });
                }
            })
            $('#contactForm').on('submit', function(e){
                e.preventDefault();
                let ok=1; $('.error-message').text('');
                if($('#email').val().trim()==''){
                    ok=0; $('#error-email').text('Bạn cần nhập email');
                }
                if($('#content').val().trim()==''){
                    ok=0; $('#error-content').text('Bạn cần nhập nội dung');
                }
                if(ok==1){
                    let email=$('#email').val().trim();
                    let content=$('#content').val().trim();
                    $.ajax({
                        url:'contact.php',
                        method:'post',
                        data: {email: email, content: content},
                        success: function(){
                            alert('Thắc mắc của bạn đã được gửi. Chúng tôi sẽ trả lời lại qua email');
                        },
                        error: function(){
                            alert('Lỗi');
                        }
                    })
                }
            })
        })
        
    </script>
</html>