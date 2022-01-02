<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuyetboitMP3 - Loggin</title>
    <link rel="stylesheet" href="../view/css/base.css">
    <link rel="stylesheet" href="../view/css/_loggin.css">
</head>
<body>
    <?php
        require('../model/php/db.php');
        // handle loggin
        if(!empty($_POST)) {
            $username_loggin = $_POST['loggin-username'];
            $password_loggin = $_POST['loggin-password'];
            
            $sql_check_loggin = "CALL loggin_admin('$username_loggin', '$password_loggin')";
            $data_result = $conn->query($sql_check_loggin);

            if($data_result->num_rows > 0) {
                $_SESSION['admin_info'] = $data_result->fetch_assoc();
                header('Location: ../controller/index.php');
                $conn->close();
                exit();
            } else {
                echo "<script>alert('Thông tin tài khoản hoặc mật khẩu không chính xác')</script>";
            }
        }
    ?>
    <form class="overlay" id="form-loggin" method="post" action=""> 
        <div class="form__wrap">
            <div class="form__header">Đăng nhập vào trang quản trị</div>
            <div class="form__group">
                <label for="loggin__username" class="form__label">Tên đăng nhập</label>
                <input type="text" id="username" class="form__input" name="loggin-username" id="loggin__username" placeholder="Nhập tên đăng nhập" value="<?= !empty($username_loggin) ? $username_loggin : null ?>">
                <span class="form__error"></span>
            </div>
            <div class="form__group">
                <label for="loggin__password" class="form__label">Mật khẩu</label>
                <input type="password" id="password" class="form__input" name="loggin-password" id="loggin__password" placeholder="Nhập mật khẩu" value="<?= !empty($password_loggin) ? $password_loggin : null ?>">
                <span class="form__error"></span>
            </div>
            <div class="form__wrap-btn">
                <input type="submit" class="btn form__submit-btn" name="loggin-submit" value="Đăng nhập">
            </div>
            <div class="form__more-option">
                <span class="form__option"><a href="../controller/user.php">Về trang phát nhạc</a></span>
            </div>
        </div>
    </form>
</body>
<script src="../model/js/validate.js"></script>
<script>
    Validator({
        form: '#form-loggin',
        errorMessageSelector: '.form__error',
        rules: [
            Validator.isRequired('#username', 'Vui lòng nhập tên đăng nhập'),
            Validator.isRequired('#password', 'Vui lòng nhập mật khẩu'),
            Validator.minLength('#password', 6)
        ]
    });
</script>
</html>