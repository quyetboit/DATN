<?php
    if (empty($_SESSION['user_info'])) {
        echo "<script>
            var isConfirm = confirm('Bạn chưa đăng nhập, chuyển sang trang đăng nhập ?');
            if (isConfirm) {
                window.location.href='../view/loggin.php';
            } else {
                window.location.href='./user.php';
            }
        </script>";
        exit;
    }
?>
<div id="message__album"></div>
<div class="row content__albums">
    <div class="col l-3">
        <div class="albums__wrapper">
            <div class="albums__wrap-btn">
                <label for="show-box-create-album" class="albums__create-btn btn btn-size-s">Tạo album</label>
                <input type="checkbox" name="" id="show-box-create-album">
                <div class="albums__create-box">
                    <div class="albums__wrap-box">
                        <form action="" method="post" id="albums__create-from">
                            <label for="show-box-create-album" class="albums__box-close-icon">
                                <ion-icon name="close-outline"></ion-icon>
                            </label>
                            <div class="form__group">
                                <label for="form__input" class="form__label">Tên albums</label>
                                <input type="text" class="form__input" name="album-name" placeholder="Nhập tên album">
                                <span class="form__error"></span>
                            </div>
                            <div class="albums__form-wrap-btn">
                                <a class="btn btn-size-l albums__form-create-btn">Tạo</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <ul class="albums__list-album">
                <h3 class="albums__list-head">Danh sách albums</h3>
                <div class="albums__alter-box hidden">
                    <div class="overlay">
                        <div class="albums__alter-info">
                            <span class="albums__alter-icon">
                                <ion-icon name="close-outline"></ion-icon>
                            </span>
                            <div class="form__group">
                                <label for="album_alter-name" class="form__label">Nhập tên album mới</label>
                                <input type="text" class="form__input" name="album-alter-name" id="album_alter-name">
                                <span class="form__error"></span>
                            </div>
                            <div class="form__wrap-btn">
                                <a class="btn form__submit-btn" id="album_update-name">Cập nhật</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                    $get_albums_of_account_sql = "SELECT id, name FROM albums
                                                WHERE username_accounts = '" . $_SESSION['user_info']['username'] . "'";
                    $result_album_of_account = $conn->query($get_albums_of_account_sql);
                    if ($result_album_of_account->num_rows > 0) {
                        foreach ($result_album_of_account as $row_album_of_account) {
                ?>
                <li class="albums__list-item" data-id-album="<?= $row_album_of_account['id']?>">
                    <a class="albums__item-link"><?=$row_album_of_account['name']?></a>
                    <span class="albums__option">
                        <span class="albums__option-icon">
                            <ion-icon name="ellipsis-horizontal-circle-outline"></ion-icon>
                        </span>
                        <ul class="albums__option-list">
                            <li class="albums__option-item">
                                <a class="albums__option-link albums__option-link-update">Sửa</a>
                            </li>
                            <li class="albums__option-item">
                                <a class="albums__option-link albums__option-link-delete">Xoá</a>
                            </li>
                        </ul>
                    </span>
                </li>
                <?php
                        }
                    } else {
                        echo "<h2>Không có album nào</h2>";
                    }
                ?>

            </ul>
        </div>
    </div>

    <div class="col l-9 songs__seccsion albums__wrap-songs mt-26">
        <div class="songs__wrapper">
            
        </div>
    </div>
</div>

<script src="../model/js/handle-albums.js"></script>