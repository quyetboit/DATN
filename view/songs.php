<?php
    $get_genres_sql = "SELECT * FROM genres";
    $result_genres = $conn->query($get_genres_sql);

    $get_songs_sql = "";
    if ($_GET['genres'] && !empty($_GET['type_genre'])) {
        if ($_GET['type_genre'] == 'all') {
            $get_songs_sql = "SELECT * FROM songs ORDER BY name ASC";
        } else if ($_GET['type_genre'] == 'new') {
            $get_songs_sql = "SELECT * FROM songs ORDER BY id DESC";
        } else {
            $get_songs_sql = "SELECT *  FROM songs 
            WHERE id_genre =" . $_GET['type_genre'] . " ORDER BY name ASC";
        }
    } else {
        $get_songs_sql = "SELECT * FROM songs ORDER BY name ASC";
    }
    $result_songs = $conn->query($get_songs_sql);
    $result_songs2 = $conn->query($get_songs_sql);
?>
<div id="message"></div>
<!-- pick genre -->
<div class="row content__pick-genre">
    <ul class="genre__list">
        <li class="genre__item"><a href="./user.php?genres=true&type_genre=all" class="btn btn-size-l">Tất cả</a></li>
        <li class="genre__item"><a href="./user.php?genres=true&type_genre=new" class="btn btn-size-l">Mới nhất</a></li>
        <?php while ($row_genre = $result_genres->fetch_assoc()) { ?>
            <li class="genre__item">
                <a href="./user.php?genres=true&type_genre=<?= $row_genre['id']?>" class="btn btn-size-l"><?= $row_genre['name']?></a>
            </li>
        <?php } ?>
    </ul>
</div>

<!-- wrap songs -->
<div class="row content__songs">
    <div class="col l-12">
        <div class="row songs__container">
            <div class="col l-3">
                <ul class="songs__slider" id="slider-layer__wrap">
                    <?php
                        $index_temp = 0;
                        if ($result_songs->num_rows > 0) {
                            while ($row_song_slider = $result_songs->fetch_assoc()) {
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

            <div class="col l-9 songs__seccsion">
                <div class="songs__wrapper">
                    <?php
                        if ($result_songs2->num_rows > 0) {
                            while ($row_song = $result_songs2->fetch_assoc()) {
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
    </div>
</div>
<script>
    setTimeout(function () {
        play.start();
    }, 0);
</script>
