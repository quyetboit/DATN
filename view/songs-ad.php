<?php
    if (isset($_GET['logout_ad'])) {
        unset($_SESSION['admin_info']);
        echo "<script>window.location.href='./index.php';</script>";
        exit;
    }
    if(!empty($_POST)) {
        $id = $_POST['song-id'];
        $name = $_POST['song-name'];
        $time = $_POST['song-time'];
        $artist = $_POST['song-author'];
        $genre = $_POST['song-genres'];
        $thumb = $_FILES['song-thumb'];
        $audio = $_FILES['song-audio'];

        if (isset($_POST['add'])) {
            add_song($thumb, $audio, $name, $time, $artist, $genre, $conn);
        }
        else if (isset($_POST['update'])) {
            update_song($id, $thumb, $audio, $name, $time, $artist, $genre, $conn);
        }
        else if (isset($_POST['delete'])) {
            delete_by_id($id, 'songs', $conn);
        }
    }

    $select_songs = "SELECT songs.id as 'song_id', songs.name as 'song_name', songs.thumb as 'song_thumb', time, genres.name as 'genre_name', genres.id as 'genre_id'  
                    FROM songs JOIN genres ON songs.id_genre = genres.id
                    ORDER BY songs.name";
    $result_songs = $conn->query($select_songs);
?>

<!-- main-content -->
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

            <!-- main content -->
            <div class="container__main-content">
                <div class="grid">
                    <div class="row">
                        <!-- wrap item -->
                        <div class="col l-8">
                            <h3 class="main__head">Tất cả bài hát</h3>
                            <div class="main__wrap-items">
                                <div class="main__list-head">
                                    <div class="row">
                                        <div class="col l-6">
                                            <span class="main__list-head-text">ID/Track/Artist</span>
                                        </div>
                                        <div class="col l-6">
                                            <div class="row">
                                                <div class="col l-3">
                                                    <span class="main__list-head-text">Time</span>
                                                </div>
                                                <div class="col l-3">
                                                    <span class="main__list-head-text">Genre</span>
                                                </div>
                                                <div class="col l-6">
                                                    <span class="main__list-head-text">Option</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- wrap songs -->
                                <div class="main__wrap-songs">

                                    <?php 
                                        if ($result_songs->num_rows > 0) {
                                            while ($row = $result_songs->fetch_assoc()) {
                                    ?>

                                    <div class="songs__items">
                                        <div class="row alig-cen-flx">
                                            <div class="col l-6">
                                                <div class="songs__content">
                                                    <span class="songs__id"><?=$row['song_id']?></span>
                                                    <img src="../<?=$row['song_thumb']?>" alt="" class="songs__thumbnail">
                                                    <div class="songs__info">
                                                        <h4 class="songs__info-name"><?=$row['song_name']?></h4>

                                                        <?php
                                                            $get_authors_sql = 'SELECT artists.id ,name
                                                                                FROM detail_songs JOIN artists ON detail_songs.id_artist = artists.id
                                                                                WHERE id_song = ' . $row['song_id'];
                                                            $result_authors_song = $conn->query($get_authors_sql);
                                                            $num_rows_author_rs = $result_authors_song->num_rows;
                                                            if ($num_rows_author_rs > 0) {
                                                                $index_tmp = 0;
                                                                while ($authors_rs = $result_authors_song->fetch_assoc()) {
                                                                    echo '<a href="" class="songs__info-author" data-id-author="' . $authors_rs['id'] . '">' . $authors_rs['name']. '</a>';
                                                                    if ($index_tmp < $num_rows_author_rs - 1) {
                                                                        echo ", &nbsp;";
                                                                    }
                                                                    $index_tmp++;
                                                                }
                                                            } else {
                                                                echo '<a href="" class="songs__info-author" data-id-author="1">Vô danh</a>';
                                                            }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col l-6">
                                                <div class="row alig-cen-flx">
                                                    <div class="col l-3">
                                                        <span class="songs__time"><?=$row['time']?></span>
                                                    </div>
                                                    <div class="col l-3">
                                                        <span class="songs__genre" data-id-genre="<?=$row['genre_id']?>"><?=$row['genre_name']?></span>
                                                    </div>
                                                    <div class="col l-6">
                                                        <button class="btn btn-size-s song-btn-modify">Sửa</button>
                                                        <button class="btn btn-size-s song-btn-del">Xoá</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <?php
                                            } //end while
                                        } else {
                                            echo "<h1>Không có bài hát nào </h1>";
                                        }
                                    ?>

                                </div>
                            </div>
                        </div>

                        <!-- form CRUD -->
                        <div class="col l-4">
                            <div class="main__wrap-form">
                            <form action="" method="post" id="form__songs" enctype="multipart/form-data">
                                    <div class="form__warp-field hidden">
                                        <label class="form__label" for="song-id">Mã bài hát</label>
                                        <input type="text" name="song-id" class="feild-content"><br>
                                    </div>

                                    <div class="form__warp-field">
                                        <label class="form__label" for="song-name">Tên bài hát</label>
                                        <input type="text" name="song-name" class="feild-content"><br>
                                    </div>

                                    <div class="form__warp-field">
                                        <label class="form__label" for="song-time">Thời lượng</label>
                                        <input type="text" name="song-time" class="feild-content"><br>
                                    </div>

                                    <div class="form__warp-field">
                                        <label class="form__label" for="song-author">Tên tác giả</label>
                                        <select class="feild-content" name="song-author" id="song-author">
                                            <option>Chọn tác giả</option>
                                            <?php
                                                $get_all_artists = "SELECT id, name FROM artists
                                                                    ORDER BY name";
                                                $result_all_artists = $conn->query($get_all_artists);
                                                if($result_all_artists->num_rows > 0) {
                                                    while ($row_artist = $result_all_artists->fetch_assoc()) {
                                                        echo '<option value="' . $row_artist['id'] . '">' . $row_artist['name'] . '</option>';
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="form__warp-field">
                                        <label class="form__label" for="song-genres">Thể loại</label>
                                        <select class="feild-content" name="song-genres" id="song-genres">
                                            <option>Chọn thể loại</option>
                                            <?php
                                                $get_all_genres = "SELECT id, name FROM genres
                                                                    ORDER BY name";
                                                $result_all_genres = $conn->query($get_all_genres);
                                                if($result_all_genres->num_rows > 0) {
                                                    while ($row_genre = $result_all_genres->fetch_assoc()) {
                                                        echo '<option value="' . $row_genre['id'] . '">' . $row_genre['name'] . '</option>';
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="form__warp-field">
                                        <label class="form__label" for="song-thumb">Thumbnail</label>
                                        <input type="file" name="song-thumb" class="feild-content"><br>
                                    </div>

                                    <div class="form__warp-field">
                                        <label class="form__label" for="song-audio">Audio</label>
                                        <input type="file" name="song-audio" class="feild-content"><br>
                                        <audio src="" id="songs__audio"></audio>
                                    </div>
                                    <input class="btn btn-size-s" id="form__btn-song-add" type="submit" name="add" value="Thêm">
                                    <input class="btn btn-size-s" id="form__btn-song-update" type="submit" name="update" value="Cập nhật">
                                    <input class="btn btn-size-s hidden" id="form__btn-song-del" type="submit" name="delete" value="Xoá" onclick="return confirmDel()">
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
    getCurrentSongToForm('.main__wrap-items', '#form__songs');
    // getAudioTime('#form__songs', '#songs__audio', 'input[name=song-time]', 'input[name=song-audio]');
</script>