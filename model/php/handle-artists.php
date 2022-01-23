<?php
    require('./db.php');
    if(!empty($_GET['id_artist'])) {
        $id_artist = $_GET['id_artist'];
        $get_all_song_of_artists_sql = "SELECT *  FROM songs JOIN detail_songs on songs.id = detail_songs.id_song
                            WHERE detail_songs.id_artist = $id_artist";
        $result_all_song_of_artist = $conn->query($get_all_song_of_artists_sql);
        if ($result_all_song_of_artist->num_rows>0) {
            while ($row_song_of_artist = $result_all_song_of_artist->fetch_assoc()) {
                $sql_check_song_of_favourite = "SELECT * FROM favourite
                        WHERE username_account = '" . $_SESSION['user_info']['username'] . "' AND id_song = " . $row_song_of_artist['id'];
                $result_check_favourite = $conn->query($sql_check_song_of_favourite);
?>
    <div class="song__item" data-path="../<?=$row_song_of_artist['audio']?>" data-id-song="<?=$row_song_of_artist['id']?>">
        <div class="row alig-cen-flx">
            <div class="col l-6">
                <div class="song__left">
                    <img src="../<?=$row_song_of_artist['thumb']?>" alt="" class="song__thumb">
                    <div class="song__infor">
                        <span class="song__name"><?=$row_song_of_artist['name']?></span>
                        <span class="song__artists">
                            <?php
                                $sql_get_artists_of_song = "SELECT name, artists.id as id_art FROM detail_songs JOIN artists ON detail_songs.id_artist = artists.id
                                WHERE detail_songs.id_song = " . $row_song_of_artist['id'];
                                $result_artists_of_song = $conn->query($sql_get_artists_of_song);
                                $num_artists = $result_artists_of_song->num_rows;
                                if ($num_artists > 0) {
                                    $temp_index_artists = 0;
                                    while ($row_artist = $result_artists_of_song->fetch_assoc()) {
                            ?> 
                            <a class="song__artist" data-id-artist="<?=$row_artist['id_art']?>"><?=$row_artist['name']?></a>
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
                    <div class="song__time"><?=$row_song_of_artist['time']?></div>
                    <div class="song__actions">
                        <a class="song__action-tym <?= ($result_check_favourite->num_rows > 0) ? 'liked' : null ?>" data-id-song="<?=$row_song_of_artist['id']?>">
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
                                    <a download href="../<?=$row_song_of_artist['audio']?>" class="song__option-link" id="song__option-link-download">Tải xuống</a>
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
            echo "<h3>Không có bài hát nào</h3>";
        }
    }
    
    $conn->close();
?>