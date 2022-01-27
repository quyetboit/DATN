<!-- check is loggin -->
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


<?php
    // upload
    require('../model/php/func.php');
    if (!empty($_POST['upload-file-user'])) {
        $username = $_SESSION['user_info']['username'];
        $song_name = $_POST['upload-song-name'];
        $song_time = $_POST['upload-song-time'];
        $song_thumb = $_FILES['upload-song-thumb'];
        $song_audio = $_FILES['upload-song-audio'];

        // handle upload
        $insert_ok = 1;
        $file_audio_name = handle_file_audio($song_audio, '../data/audioes/');
        $file_thumb_name = handle_file_picture($song_thumb, '../data/imgs/');

        $path_audio = 'data/audioes/' . $file_audio_name;
        $path_thumb = 'data/imgs/' . $file_thumb_name;

        $sql_add_song = "INSERT INTO songs_user(name, time, thumb, audio, username_account)
            VALUES('$song_name', '$song_time', '$path_thumb', '$path_audio', '$username')";
        
        if($file_audio_name !== null && $file_thumb_name !== null) {
            if(!($conn->query($sql_add_song) === true)) {
                $insert_ok = 0;
            }
        } else {
            $insert_ok = 0;
        }

        if ($insert_ok === 1) {
            echo "<script>alert('Thêm thành công')</script>";
        } else {
            echo "<script>alert('Có lỗi khi thêm')</script>";
        }
    }

    // get song
    $sql_get_song_user = "SELECT id, name, time, thumb, audio, accounts.fullname
    FROM songs_user JOIN accounts ON songs_user.username_account = accounts.username
    WHERE songs_user.username_account = '" . $_SESSION['user_info']['username'] . "'";

    $result_song_user = $conn->query($sql_get_song_user);
?>
<div class="row content__upload  content_wrapper">
    <div class="col l-3">
        <div class="albums__wrapper">
            <div class="albums__wrap-btn">
                <label for="show-box-create-album" class="albums__create-btn btn btn-size-s">Upload</label>
                <input type="checkbox" name="" id="show-box-create-album">
                <div class="albums__create-box">
                    <div class="albums__wrap-box form__wrap">
                        <h3 class="form__header">Nhập thông tin bài hát</h3>
                        <form action="" method="post" id="uploads__form-upload" enctype="multipart/form-data">
                            <label for="show-box-create-album" class="albums__box-close-icon">
                                <ion-icon name="close-outline"></ion-icon>
                            </label>
                            <div class="form__group">
                                <label for="form__input" class="form__label">Tên bài hát</label>
                                <input type="text" class="form__input" name="upload-song-name" placeholder="Nhập tên bài hát">
                                <span class="form__error"></span>
                            </div>
                            <div class="form__group">
                                <label for="form__input" class="form__label">Thời lượng</label>
                                <input type="text" class="form__input" name="upload-song-time" placeholder="Nhập thời lượng">
                                <span class="form__error"></span>
                            </div>
                            <div class="form__group">
                                <label for="form__input" class="form__label">Thumbnail</label>
                                <input type="file" class="form__input" name="upload-song-thumb" placeholder="Chọn tệp hình ảnh">
                                <span class="form__error"></span>
                            </div>
                            <div class="form__group">
                                <label for="form__input" class="form__label">Audio</label>
                                <input type="file" class="form__input" name="upload-song-audio" placeholder="Chọn tệp âm thanh">
                                <span class="form__error"></span>
                            </div>
                            <div class="albums__form-wrap-btn">
                                <input type="submit" name="upload-file-user" id="upload-file-user" class="btn btn-size-s" value="Tải lên">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <ul class="songs__slider" id="slider-layer__wrap">
            <?php
                $index_temp = 0;
                if ($result_song_user->num_rows > 0) {
                    foreach($result_song_user as $row_song_slider) {
            ?>
                <li class="slider-layer__item songs__item <?= ($index_temp === 0 ? 'first' : ($index_temp === 1 ? 'second' : 'third'))?>">
                    <img src="../<?=$row_song_slider['thumb']?>" alt="<?=$row_song_slider['name']?>" title="<?=$row_song_slider['name']?>" class="songs__img">
                </li>
            <?php
                        $index_temp++;
                    }
                } else {
                    echo "<h2>Không có bài hát nào</h2>";
                }
            ?>
        </ul>
    </div>

    <div class="col l-9 songs__seccsion albums__wrap-songs mt-26">
        <div class="songs__wrapper">

            <?php
                if ($result_song_user->num_rows > 0) {
                    foreach($result_song_user as $row_song) {
            ?>
            <div class="song__item" data-path="../<?=$row_song['audio']?>" data-id-song="<?=$row_song['id']?>">
                <div class="row alig-cen-flx">
                    <div class="col l-6">
                        <div class="song__left">
                            <img src="../<?=$row_song['thumb']?>" alt="" class="song__thumb">
                            <div class="song__infor">
                                <span class="song__name"><?=$row_song['name']?></span>
                                <span class="song__artists">
                                    <?= $_SESSION['user_info']['username']?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col l-6">
                        <div class="song__right">
                            <div class="song__time"><?=$row_song['time']?></div>
                            <div class="song__actions">
                                <div class="song__action-option">
                                    <span class="song__action-option-icon">
                                        <ion-icon name="ellipsis-horizontal-circle-outline"></ion-icon>
                                    </span>
                                    <ul class="song__option-list">
                                        <li class="song__option-item song__option-del-song-user">
                                            <a class="song__option-link">Xoá</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
                    }
                } else {
                    echo "<h1>Không có bài hát nào</h1>";
                }
            ?>

        </div>
    </div>
</div>
<script src="../model/js/play-song.js"></script>
<script>
    setTimeout(function () {
        play.start();
    }, 20)
</script>
<script src="../model/js/handle-upload.js"></script>
<script>
    handleUploadUser();
</script>