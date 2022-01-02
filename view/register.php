<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuyetboitMP3 - Register</title>
    <link rel="stylesheet" href="../view/css/base.css">
    <link rel="stylesheet" href="../view/css/_loggin.css">
</head>
<body>
    <?php
        require('../model/php/db.php');
        require('../model/php/func.php');
        $mess_error['register'] = [];
        // handle loggin
        $ready_insert = 1;
        if(!empty($_POST)) {
            $fullname_register = $_POST['register-fullname'];
            $email_register = $_POST['register-email'];
            $username_register = $_POST['register-username'];
            $password_register = $_POST['register-password'];
            $avatar = $_FILES['register-avatar'];

            // check is not exist username
            $sql_check_username = "SELECT * FROM accounts
                                    WHERE username = '$username_register'";
            $result_check_username = $conn->query($sql_check_username);
            if ($result_check_username->num_rows > 0) {
                $mess_error['register']['username'] = 'Username đã tồn tại';
                $ready_insert = 0;
            }

            // check is not exist email
            $sql_check_email = "SELECT * FROM accounts
                                    WHERE email = '$email_register'";
            $result_check_email = $conn->query($sql_check_email);
            if ($result_check_email->num_rows > 0) {
                $mess_error['register']['email'] = 'Email đã tồn tại';
                $ready_insert = 0;
            }

            if ($ready_insert === 1) {
                $temp = user_register($username_register,$password_register, $fullname_register, $email_register, $avatar, $conn);
                if ($temp === true) {
                    echo "<script>alert('Đăng ký thành công')</script>";
                    header('location: ./loggin.php');
                    exit();
                }
            }
        }
    ?>
    <div class="overlay">
        <form class="form__wrap" method="post" action="" id="form-register" enctype="multipart/form-data">
            <div class="form__header">Nhập thông tin đăng ký</div>
            <div class="form__group">
                <label for="loggin__fullname" class="form__label">Họ và tên</label>
                <input type="text" id="fullname" class="form__input" name="register-fullname" value="<?=(!empty($fullname_register) ? $fullname_register : null)?>" id="loggin__fullname" placeholder="Nhập họ và tên">
                <span class="form__error"></span>
            </div>

            <div class="form__group">
                <label for="loggin__email" class="form__label">Email</label>
                <input type="text" id="email" class="form__input" name="register-email" value="<?=(!empty($email_register) ? $email_register : null)?>" id="loggin__email" placeholder="Nhập tên email">
                <span class="form__error"><?=(!empty($mess_error['register']['email'])) ? $mess_error['register']['email'] : null?></span>
            </div>

            <div class="form__group">
                <label for="loggin__username" class="form__label">Tên đăng nhập</label>
                <input type="text" id="username" class="form__input" name="register-username" value="<?=(!empty($username_register) ? $username_register : null)?>" id="loggin__username" placeholder="Nhập tên đăng nhập">
                <span class="form__error"><?=(!empty($mess_error['register']['username'])) ? $mess_error['register']['username'] : null?></span>
            </div>

            <div class="form__group">
                <label for="loggin__password" class="form__label">Mật khẩu</label>
                <input type="password" id="password" class="form__input" name="register-password" value="<?=(!empty($password_register) ? $password_register : null)?>" id="loggin__password" placeholder="Nhập mật khẩu">
                <span class="form__error"></span>
            </div>

            <div class="form__group">
                <label for="loggin__password-confirm" class="form__label">Xác nhậc mật khẩu</label>
                <input type="password" id="password-confirm" class="form__input" name="register-password-confirm" value="<?=(!empty($password_register) ? $password_register : null)?>" id="loggin__password-confirm" placeholder="Nhập mật khẩu">
                <span class="form__error"></span>
            </div>

            <div class="form__group">
                <label for="loggin__avatar" class="form__label">Avatar</label>
                <input type="file" id="avatar" class="form__input" name="register-avatar" id="loggin__avatar">
                <span class="form__error"></span>
            </div>

            <div class="form__wrap-btn">
                <input type="submit" class="btn form__submit-btn" name="loggin-submit" value="Đăng ký">
            </div>
            <div class="form__more-option">
                <span class="form__option">Bạn đã có tài khoản? <a href="./loggin.php">Đăng nhập</a></span>
                <span class="form__option"><a href="../controller/user.php">Về trang chủ</a></span>
            </div>
        </form>
    </div>
</body>
    <script src="../model/js/validate.js"></script>
    <script>
        Validator({
            form: '#form-register',
            errorMessageSelector: '.form__error',
            rules: [
                Validator.isRequired('#fullname', 'Vui lòng nhập tên đầy đủ'),
                Validator.isRequired('#email', 'Vui lòng nhập email'),
                Validator.isEmail('#email', 'Trường này phải là email'),
                Validator.isRequired('#username', 'Vui lòng nhập tên đăng nhập'),
                Validator.isRequired('#password', 'Vui lòng nhập mật khẩu'),
                Validator.isRequired('#password-confirm', 'Vui lòng nhập trường này'),
                Validator.minLength('#password', 6),
                Validator.confirm('#password-confirm', function () {
                    return document.querySelector('#form-register #password').value
                })
            ]
        });
    </script>
</html>