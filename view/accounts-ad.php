<?php
    if(!empty($_POST)) {
        $username = $_POST['account-username'];
        $password = $_POST['account-password'];
        $fullname = $_POST['account-fullname'];
        $email = $_POST['account-email'];
        $file = $_FILES['account-avatar'];

        if (isset($_POST['add'])) {
            add_account($file, $username, $fullname, $password, $email, $conn);
        } else if (isset($_POST['delete'])) {
            delete_by_id($username, 'accounts', $conn);
        }
    }
    
    $select_accounts = "SELECT * FROM accounts ORDER BY username ASC";
    $result_accounts = $conn->query($select_accounts);
?>

<div class="col l-10">
    <div class="admin__container">
        <div class="grid">
            <!-- head -->
            <div class="row container__head">
                <!-- search -->
                <div class="col l-9-6">
                    <div class="container__head-search">
                        <!-- <form action="" method="get" class="container__head-form">
                            <input type="text" placeholder="Nhập tên bài hát ..." class="container__head-input">
                            <button class="container__head-submit">
                                <ion-icon class="container__submit-icon" name="search-outline"></ion-icon>
                            </button>
                        </form> -->
                        <h1>Chào mừng bạn đến với trang quản trị</h1>
                    </div>
                </div>

                <!-- admin info -->
                <div class="col l-2-4">
                    <div class="container__info">
                        <img src="../<?=$_SESSION['admin_info']['avatar']?>" alt="" class="container__info-img">
                        <span class="container__info-name"><?=$_SESSION['admin_info']['fullname']?></span>
                        <span class="head__user-option">
                            <span class="head__user-option-icon">
                                <ion-icon name="chevron-down-circle-outline"></ion-icon>
                            </span>
                            <ul class="option__list">
                                <li class="option__item"><a href="./index.php?logout_ad=true">Đăng xuất</a></li>
                            </ul>
                        </span>
                    </div>
                </div>
            </div>

            <!-- account section -->
            <div class="container__main-content">
                <div class="grid">
                    <div class="row">
                        <!-- wrap item -->
                        <div class="col l-8">
                            <h3 class="main__head">Tất cả tài khoản</h3>
                            <div class="main__wrap-items">
                                <div class="main__list-head">
                                    <div class="row">
                                        <div class="col l-2">
                                            <span class="main__list-head-text">Username</span>
                                        </div>
                                        <div class="col l-2">
                                            <span class="main__list-head-text">Ảnh đại diện</span>
                                        </div>
                                        <div class="col l-3">
                                            <span class="main__list-head-text">Họ và tên</span>
                                        </div>
                                        <div class="col l-3">
                                            <span class="main__list-head-text">Email</span>
                                        </div>
                                        <div class="col l-2">
                                            <span class="main__list-head-text">Option</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- wrap account -->
                                <div class="main__wrap-accounts">

                                    <?php 
                                        if ($result_accounts->num_rows > 0) {
                                            while ($row = $result_accounts->fetch_assoc()) {
                                    ?>

                                    <div class="account__items">
                                        <div class="row alig-cen-flx">
                                            <div class="col l-2">
                                                <span class="account__username"><?=$row['username']?></span>
                                            </div>

                                            <div class="col l-2">
                                                <img src="../<?=$row['avatar']?>" alt="" class="account__img">
                                            </div>

                                            <div class="col l-3">
                                                <span class="account__fullname"><?=$row['fullname']?></span>
                                            </div>

                                            <div class="col l-3">
                                                <span class="account__email"><?=$row['email']?></span>
                                            </div>

                                            <div class="col l-2">
                                                <button class="btn btn-size-s">Xoá</button>
                                            </div>
                                        </div>
                                    </div>

                                    <?php
                                            } //end while
                                        } else {
                                            echo "<h1>Không có tài khản nào </h1>";
                                        }
                                    ?>

                                </div>
                            </div>
                        </div>

                        <!-- form CRUD -->
                        <div class="col l-4">
                            <div class="main__wrap-form">
                                <form action="" method="post" id="form__accounts" enctype="multipart/form-data">
                                    <div class="form__warp-field">
                                        <label class="form__label" for="account-username">Username</label> <br>
                                        <input type="text" name="account-username" class="feild-content"><br>
                                    </div>

                                    <div class="form__warp-field">
                                        <label class="form__label" for="account-password">Password</label> <br>
                                        <input type="text" name="account-password" class="feild-content"><br>
                                    </div>

                                    <div class="form__warp-field">
                                        <label class="form__label" for="account-fullname">Họ và tên</label> <br>
                                        <input type="text" name="account-fullname" class="feild-content"><br>
                                    </div>

                                    <div class="form__warp-field">
                                        <label class="form__label" for="account-email">Email</label> <br>
                                        <input type="text" name="account-email" class="feild-content"><br>
                                    </div>

                                    <div class="form__warp-field">
                                        <label class="form__label" for="account-avatar">Ảnh</label> <br>
                                        <input type="file" name="account-avatar" class="feild-content"><br>
                                    </div>
                                    <input class="btn btn-size-s" type="submit" name="add" value="Thêm">
                                    <input class="btn btn-size-s" type="submit" name="delete" value="Xoá" onclick="return confirmDel()">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../model/js/get_data_to_form.js"></script>
<script>
    getCurrentAccountToForm('.main__wrap-accounts', '#form__accounts')
</script>
