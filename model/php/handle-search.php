<?php
    require('./db.php');
    if (!empty($_POST['action'])) {
        $keyword = $_POST['keyword'];
        if ($_POST['action'] == 'search') {
            echo "
            <div class='row content_wrapper content__result-search'>
                <div class='col l-12'>
                    <h3 class='search__keyword'>Kết quả tìm kiếm cho '<i>$keyword</i>'</h3>
                    <ul class='search__options-list'>
                        <li class='search__option-item search__option-songs'>
                            <a class='search__option-link btn btn-size-l'>Bài hát</a>
                        </li>
                        <li class='search__option-item search__option-artists'>
                            <a class='search__option-link btn btn-size-l'>Tác giả</a>
                        </li>
                    </ul>
                </div>
                <div class='col l-12'>
                    <div class='search__result'>
                        
                        
                    </div>
                </div>
            </div>
            ";
        }

        if ($_POST['action'] === 'get_song_search') {
            $sql_get_song_search = "SELECT * FROM songs WHERE name LIKE '%$keyword%'";
            $result_song_search = $conn->query($sql_get_song_search);
            if ($result_song_search->num_rows > 0) {
                echo "<div class='songs__wrapper' style='margin-bottom: 0;'>";
                foreach ($result_song_search as $row_song) {
                    $sql_check_song_of_favourite = "";
                    $result_check_favourite;
                    if (!empty($_SESSION['user_info'])) {
                        $sql_check_song_of_favourite = "SELECT * FROM favourite
                                WHERE username_account = '" . $_SESSION['user_info']['username'] . "' AND id_song = " . $row_song['id'];
                        $result_check_favourite = $conn->query($sql_check_song_of_favourite);
                    }
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
                                        <a class="song__action-tym <?= (isset($_SESSION['user_info']) && ($result_check_favourite->num_rows > 0)) ? 'liked' : null ?>" data-id-song="<?=$row_song['id']?>">
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
                echo "</div>";
            } else {
                echo "<h1>Không tìm thấy bài hát phù hợp</h1>";
            }
        }

        if ($_POST['action'] === 'get_artists_search') {
            $sql_get_artists_search = "SELECT * FROM artists WHERE name LIKE '%$keyword%'";
            $result_artists = $conn->query($sql_get_artists_search);

            if ($result_artists->num_rows > 0) {
                echo "<div class='result__artist-list'>";
                foreach ($result_artists as $row_artist) {
                    $id_artist = $row_artist['id'];
                    $sql_get_num_song_of_artist = "SELECT COUNT(id_song) AS 'num_song' FROM detail_songs WHERE id_artist = $id_artist GROUP BY id_artist";
                    $get_num_song = $conn->query($sql_get_num_song_of_artist);
                    $result_num_song = $get_num_song->fetch_assoc();
                    ?>
                    <div class="result__artist" data-id-artist='<?=$row_artist['id']?>'>
                        <img src="../<?=$row_artist['thumb']?>" alt="" class="result__artist-img">
                        <span class="result__artist-info">
                            <span class="result__artist-name"><?=$row_artist['name']?></span>
                            <span class="result__artist-total-song"><?=$result_num_song['num_song']?> bài hát</span>
                        </span>
                    </div>
                    <?php
                }
                echo "</div>";
            } else {
                echo "<h1>Không tìm thấy nghệ sĩ phù hợp</h1>";
            }
        }

        if ($_POST['action'] === 'get_song_of_artist_search') {
            $sql_get_ids_song = "SELECT * FROM detail_songs WHERE id_artist = " . $_POST['id_artist'];
            $result_ids_song_search = $conn->query($sql_get_ids_song);
            if ($result_ids_song_search->num_rows > 0) {
                echo "<div class='songs__wrapper' style='margin-bottom: 0;'>";
                foreach ($result_ids_song_search as $row_id_song) {
                    $sql_check_song_of_favourite = "";
                    $result_check_favourite;
                    if (!empty($_SESSION['user_info'])) {
                        $sql_check_song_of_favourite = "SELECT * FROM favourite
                                WHERE username_account = '" . $_SESSION['user_info']['username'] . "' AND id_song = " . $row_id_song['id_song'];
                        $result_check_favourite = $conn->query($sql_check_song_of_favourite);
                    }
                    $sql_get_song_by_id = "SELECT * FROM songs WHERE id = " . $row_id_song['id_song'];
                    $song = ($conn->query($sql_get_song_by_id))->fetch_assoc();
                ?>
                    <div class="song__item" data-path="../<?=$song['audio']?>" data-id-song="<?=$song['id']?>">
                        <div class="row alig-cen-flx">
                            <div class="col l-6">
                                <div class="song__left">
                                    <img src="../<?=$song['thumb']?>" alt="" class="song__thumb">
                                    <div class="song__infor">
                                        <span class="song__name"><?=$song['name']?></span>
                                        <span class="song__artists">
                                            <?php
                                                $sql_get_artists_of_song = "SELECT name FROM detail_songs JOIN artists ON detail_songs.id_artist = artists.id
                                                WHERE detail_songs.id_song = " . $song['id'];
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
                                    <div class="song__time"><?=$song['time']?></div>
                                    <div class="song__actions">
                                        <a class="song__action-tym <?= (isset($_SESSION['user_info']) && ($result_check_favourite->num_rows > 0)) ? 'liked' : null ?>" data-id-song="<?=$song['id']?>">
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
                                                    <a download href="../<?=$song['audio']?>" class="song__option-link" id="song__option-link-download">Tải xuống</a>
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
                echo "</div>";
            } else {
                echo "<h1>Không tìm thấy bài hát phù hợp</h1>";
            }
        }
    }
    $conn->close();
?>