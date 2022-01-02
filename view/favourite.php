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
    $get_songs_of_favourite = "SELECT * 
                    FROM songs JOIN favourite ON songs.id = favourite.id_song
                    WHERE username_account = '" . $_SESSION['user_info']['username'] . "'";
    $result_song_of_favourite = $conn->query($get_songs_of_favourite);
?>

<!-- wrap songs -->
<div class="row content__songs mt-26">
    <div class="col l-12">
        <div class="row songs__container">
            <div class="col l-3">
                <ul class="songs__slider" id="slider-layer__wrap">
                    <?php
                        $index_temp = 0;
                        if ($result_song_of_favourite->num_rows > 0) {
                            foreach ($result_song_of_favourite as $song) {
                    ?>
                    <li class="slider-layer__item songs__item  <?= ($index_temp === 0 ? 'first' : ($index_temp === 1 ? 'second' : 'third'))?>">
                        <img src="../<?= $song['thumb']?>" title="<?= $song['name']?>" alt="<?= $song['name']?>" class="songs__img">
                    </li>
                    <?php
                                $index_temp++;
                            }
                        }
                    ?>
                </ul>
            </div>

            <div class="col l-9 songs__seccsion">
                <div class="songs__wrapper height-warp-song-favourite">
                <?php
                    if ($result_song_of_favourite->num_rows > 0) {
                        foreach ($result_song_of_favourite as $row_song) {
                            $sql_check_song_of_favourite = "SELECT * FROM favourite
                                    WHERE username_account = '" . $_SESSION['user_info']['username'] . "' AND id_song = " . $row_song['id'];
                            $result_check_favourite = $conn->query($sql_check_song_of_favourite);
                ?>
                <div class="song__item" data-path="../<?=$row_song['audio']?>" data-id-song="<?=$row_song['id']?>">
                    <div class="row alig-cen-flx">
                        <div class="col l-6">
                            <div class="song__left">
                                <img src="../<?=$row_song['thumb']?>" alt="" class="song__thumb">
                                <div class="song__infor">
                                    <span class="song__name"><?=$row_song['name']?></span>
                                    <span class="song__artists">
                                        <?php
                                            $sql_get_artists_of_song = "SELECT name FROM detail_songs JOIN artists ON detail_songs.id_artist = artists.id
                                            WHERE detail_songs.id_song = " . $row_song['id'];
                                            $result_artists_of_song = $conn->query($sql_get_artists_of_song);
                                            $num_artists = $result_artists_of_song->num_rows;
                                            if ($num_artists > 0) {
                                                $temp_index_artists = 0;
                                                while ($row_artist = $result_artists_of_song->fetch_assoc()) {
                                        ?> 
                                        <a href="" class="song__artist"><?=$row_artist['name']?></a>
                                        <?php
                                                    if ($temp_index_artists < $num_artists - 1) {
                                                        echo "<span>, &nbsp</span>";
                                                    }
                                                    $temp_index_artists++;
                                                }
                                            } else {
                                                echo '<a class="song__artist">Chưa xác định</a>';
                                            }
                                        ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col l-6">
                            <div class="song__right">
                                <div class="song__time"><?=$row_song['time']?></div>
                                <div class="song__actions">
                                    <a class="song__action-tym <?= ($result_check_favourite->num_rows > 0) ? 'liked' : null ?>" data-id-song="<?=$row_song['id']?>">
                                        <span class="hear__outline">
                                            <ion-icon name="heart-outline"></ion-icon>
                                        </span>
                                        <span class="hear__fill">
                                            <ion-icon name="heart"></ion-icon>
                                        </span>
                                    </a>
                                    <div class="song__action-option">
                                        <span class="song__action-option-icon">
                                            <ion-icon name="ellipsis-horizontal-circle-outline"></ion-icon>
                                        </span>
                                        <ul class="song__option-list">
                                            <?php if (!empty($_SESSION['user_info'])) { ?>
                                            <li class="song__option-item" id="song__get-all-album">
                                                <a class="song__option-link">Thêm vào album</a>
                                                <ul class="song__album-list">
                                                    <?php
                                                        $get_my_album_sql = "SELECT * FROM albums
                                                        WHERE username_accounts = '" . $_SESSION['user_info']['username'] . "'";
                                                        $result_album = $conn->query($get_my_album_sql);
                                                        if ($result_album->num_rows > 0) {
                                                            while ($row_album = $result_album->fetch_assoc()) {
                                                    ?>
                                                    <li class="song__album-item" data-id-album="<?=$row_album['id']?>">
                                                        <a class="song__album-link"><?=$row_album['name']?></a>
                                                    </li>
                                                    <?php
                                                            }
                                                        } else {
                                                            echo '<li class="song__album-item">
                                                                <a class="song__album-link">Chưa có album nào</a>
                                                            </li>';
                                                        }
                                                    ?>
                                                </ul>
                                            </li>
                                            <?php } ?>
                                            <li class="song__option-item">
                                                <a download href="../<?=$row_song['audio']?>" class="song__option-link" id="song__option-link-download">Tải xuống</a>
                                            </li>
                                            <li class="song__option-item song__option-comment">
                                                <a class="song__option-link">Bình luận</a>
                                            </li>
                                            <?php if (!empty($_SESSION['user_info'])) { ?>
                                            <li class="song__option-item song__del_song_album">
                                                <a class="song__option-link">Xoá khỏi album</a>
                                            </li>
                                            <?php } ?>
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
                    <!-- <div class="song__item" data-path="../data/audioes/maps.mp3" data-index="0">
                        <audio src="../data/audioes/maps.mp3"></audio>
                        <div class="row alig-cen-flx">
                            <div class="col l-6">
                                <div class="song__left">
                                    <img src="../data/imgs/maps.jfif" alt="" class="song__thumb">
                                    <div class="song__infor">
                                        <span class="song__name">Maps</span>
                                        <span class="song__artists">
                                            <a href="" class="song__artist">Marron 5</a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col l-6">
                                <div class="song__right">
                                    <div class="song__time">3:12</div>
                                    <div class="song__actions">
                                        <a href="" class="song__action-tym">
                                            <span class="hear__outline">
                                                <ion-icon name="heart-outline"></ion-icon>
                                            </span>
                                            <span class="hear__fill">
                                                <ion-icon name="heart"></ion-icon>
                                            </span>
                                        </a>
                                        <div class="song__action-option">
                                            <span class="song__action-option-icon">
                                                <ion-icon name="ellipsis-horizontal-circle-outline"></ion-icon>
                                            </span>
                                            <ul class="song__option-list">
                                                <li class="song__option-item">
                                                    <a href="" class="song__option-link">Thêm vào album</a>
                                                </li>
                                                <li class="song__option-item">
                                                    <a href="" class="song__option-link">Tải xuống</a>
                                                </li>
                                                <li class="song__option-item">
                                                    <a href="" class="song__option-link">Bình luận</a>
                                                </li>
                                                <li class="song__option-item">
                                                    <a href="" class="song__option-link">Xoá khỏi album</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="song__item" data-path="../data/audioes/maps.mp3" data-index="0">
                        <audio src="../data/audioes/maps.mp3"></audio>
                        <div class="row alig-cen-flx">
                            <div class="col l-6">
                                <div class="song__left">
                                    <img src="../data/imgs/maps.jfif" alt="" class="song__thumb">
                                    <div class="song__infor">
                                        <span class="song__name">Maps</span>
                                        <span class="song__artists">
                                            <a href="" class="song__artist">Marron 5</a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col l-6">
                                <div class="song__right">
                                    <div class="song__time">3:12</div>
                                    <div class="song__actions">
                                        <a href="" class="song__action-tym">
                                            <span class="hear__outline">
                                                <ion-icon name="heart-outline"></ion-icon>
                                            </span>
                                            <span class="hear__fill">
                                                <ion-icon name="heart"></ion-icon>
                                            </span>
                                        </a>
                                        <div class="song__action-option">
                                            <span class="song__action-option-icon">
                                                <ion-icon name="ellipsis-horizontal-circle-outline"></ion-icon>
                                            </span>
                                            <ul class="song__option-list">
                                                <li class="song__option-item">
                                                    <a href="" class="song__option-link">Thêm vào album</a>
                                                </li>
                                                <li class="song__option-item">
                                                    <a href="" class="song__option-link">Tải xuống</a>
                                                </li>
                                                <li class="song__option-item">
                                                    <a href="" class="song__option-link">Bình luận</a>
                                                </li>
                                                <li class="song__option-item">
                                                    <a href="" class="song__option-link">Xoá khỏi album</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="song__item" data-path="../data/audioes/maps.mp3" data-index="0">
                        <audio src="../data/audioes/maps.mp3"></audio>
                        <div class="row alig-cen-flx">
                            <div class="col l-6">
                                <div class="song__left">
                                    <img src="../data/imgs/maps.jfif" alt="" class="song__thumb">
                                    <div class="song__infor">
                                        <span class="song__name">Maps</span>
                                        <span class="song__artists">
                                            <a href="" class="song__artist">Marron 5</a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col l-6">
                                <div class="song__right">
                                    <div class="song__time">3:12</div>
                                    <div class="song__actions">
                                        <a href="" class="song__action-tym">
                                            <span class="hear__outline">
                                                <ion-icon name="heart-outline"></ion-icon>
                                            </span>
                                            <span class="hear__fill">
                                                <ion-icon name="heart"></ion-icon>
                                            </span>
                                        </a>
                                        <div class="song__action-option">
                                            <span class="song__action-option-icon">
                                                <ion-icon name="ellipsis-horizontal-circle-outline"></ion-icon>
                                            </span>
                                            <ul class="song__option-list">
                                                <li class="song__option-item">
                                                    <a href="" class="song__option-link">Thêm vào album</a>
                                                </li>
                                                <li class="song__option-item">
                                                    <a href="" class="song__option-link">Tải xuống</a>
                                                </li>
                                                <li class="song__option-item">
                                                    <a href="" class="song__option-link">Bình luận</a>
                                                </li>
                                                <li class="song__option-item">
                                                    <a href="" class="song__option-link">Xoá khỏi album</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    setTimeout(function () {
        play.start();
    }, 0);
</script>