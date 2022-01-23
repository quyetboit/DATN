<?php
    require('./db.php');
    if (isset($_GET['action']) && isset($_GET['id_album'])) {
        $id_album = $_GET['id_album'];
        if ($_GET['action'] == 'delete_album') {
            $sql_delete_details_album = "DELETE FROM detail_albums WHERE id_album = $id_album";
            if ($conn->query($sql_delete_details_album) === true) {
                $sql_delete_album = "DELETE FROM albums WHERE id = $id_album";
                if ($conn->query($sql_delete_album) === true) {
                    echo "Xoá thành công";
                } else {
                    echo "Xảy ra lỗi khi xoá";
                }
            } else {
                echo "Xảy ra lỗi khi xoá";
            }
        } else if ($_GET['action'] == 'update_album') {
            $new_name = $_GET['new_name_album'];
            $sql_update_album = "UPDATE albums  SET name = '$new_name'
            WHERE id = $id_album AND username_accounts = '" . $_SESSION['user_info']['username'] . "'";
            if ($conn->query($sql_update_album) === true) {
                echo "Cập nhật thành công";
            } else {
                echo "Có lỗi khi cập nhật: " . $conn->error;
            }
        } else if ($_GET['action'] == 'add_song_album') {
            $id_song = $_GET['id_song'];
            $sql_insert_detail_album = "INSERT INTO detail_albums(id_album, id_song) VALUES($id_album, $id_song)";
            $check_exist_song = "SELECT * FROM detail_albums WHERE id_album = $id_album AND id_song = $id_song";
            $result_check = $conn->query($check_exist_song);
            if ($result_check->num_rows > 0) {
                echo "Bài hát đã tồn tại trong album";
            } else {
                if ($conn->query($sql_insert_detail_album) === true) {
                    echo "Thêm thành công";
                } else {
                    echo "Thêm thất bại";
                }
            }
        } else if ($_GET['action'] == 'del_song_album') {
            $id_song = $_GET['id_song'];
            $del_song_in_album_sql = "DELETE FROM detail_albums WHERE id_song = $id_song AND id_album = $id_album";
            if ($conn->query($del_song_in_album_sql) === true) {
                echo "Xoá thành công";
            } else {
                echo "Xoá thất bại";
            }
        }
        
        else if ($_GET['action'] == 'get_song_album') {
            $get_song_album_sql = "SELECT songs.id as id, songs.name as name, songs.time as time, songs.thumb, songs.id_genre, songs.audio
                        FROM detail_albums JOIN songs ON detail_albums.id_song = songs.id
                        JOIN albums ON detail_albums.id_album = albums.id
                        JOIN accounts ON albums.username_accounts = accounts.username
                        WHERE detail_albums.id_album = $id_album AND albums.username_accounts = '" . $_SESSION['user_info']['username'] . "'";
            $result_song_of_album = $conn->query($get_song_album_sql);
            if ($result_song_of_album->num_rows > 0) {
                while($row_songs_of_album = $result_song_of_album->fetch_assoc()) {
                    $sql_check_song_of_favourite = "SELECT * FROM favourite
                                WHERE username_account = '" . $_SESSION['user_info']['username'] . "' AND id_song = " . $row_songs_of_album['id'];
                    $result_check_favourite = $conn->query($sql_check_song_of_favourite);
            ?>
            <div class="song__item" data-path="../<?=$row_songs_of_album['audio']?>" data-id-song="<?=$row_songs_of_album['id']?>">
                <div class="row alig-cen-flx">
                    <div class="col l-6">
                        <div class="song__left">
                            <img src="../<?=$row_songs_of_album['thumb']?>" alt="" class="song__thumb">
                            <div class="song__infor">
                                <span class="song__name"><?=$row_songs_of_album['name']?></span>
                                <span class="song__artists">
                                    <?php
                                        $sql_get_artists_of_song = "SELECT name, artists.id as id_art FROM detail_songs JOIN artists ON detail_songs.id_artist = artists.id
                                        WHERE detail_songs.id_song = " . $row_songs_of_album['id'];
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
                            <div class="song__time"><?=$row_songs_of_album['time']?></div>
                            <div class="song__actions">
                                <a class="song__action-tym <?= ($result_check_favourite->num_rows > 0) ? 'liked' : null ?>" data-id-song="<?=$row_songs_of_album['id']?>">
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
                                                    WHERE username_accounts = '" . $_SESSION['user_info']['username'] . "' AND id != $id_album";
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
                                            <a download href="../<?=$row_songs_of_album['audio']?>" class="song__option-link" id="song__option-link-download">Tải xuống</a>
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
        }


    } else if (isset($_GET['action'])) {
        if ($_GET['action'] == 'create_album') {
            $album_name = $_GET['album_name'];
            $sql_insert_album = "INSERT INTO albums(name, username_accounts)
                                VALUES('$album_name', '" . $_SESSION['user_info']['username'] . "')";
            if ($conn->query($sql_insert_album) === true) {
                $get_new_id = "SELECT * FROM albums ORDER BY id DESC LIMIT 1";
                $new_id = ($conn->query($get_new_id))->fetch_assoc();
                echo "
                <li class='albums__list-item' data-id-album='" . $new_id['id'] . "'>
                    <a class='albums__item-link'>" . $new_id['name'] . "</a>
                    <span class='albums__option'>
                        <span class='albums__option-icon'>
                            <ion-icon name='ellipsis-horizontal-circle-outline'></ion-icon>
                        </span>
                        <ul class='albums__option-list'>
                            <li class='albums__option-item'>
                                <a class='albums__option-link albums__option-link-update'>Sửa</a>
                            </li>
                            <li class='albums__option-item'>
                                <a class='albums__option-link albums__option-link-delete'>Xoá</a>
                            </li>
                        </ul>
                    </span>
                </li>
                ";
            } else {
                echo "<script>alert('Có lỗi khi thêm')</script>";
            }
        }
    }
    $conn->close();
?>