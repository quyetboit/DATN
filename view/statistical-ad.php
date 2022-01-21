<?php
    require('../model/php/db.php');
    $sql_get_top_10_num_play = "SELECT * FROM songs ORDER BY num_plays DESC LIMIT 0,10";
    $result_top_10_num_play = $conn->query($sql_get_top_10_num_play);

    $sql_get_top_10_num_download = "SELECT * FROM songs ORDER BY num_download DESC, name ASC LIMIT 0,10";
    $result_top_10_num_download = $conn->query($sql_get_top_10_num_download);

    $sql_get_top_10_num_like = "SELECT id_song, COUNT(username_account) as num_like FROM favourite
    GROUP BY id_song ORDER BY num_like DESC LIMIT 0, 10";
    $result_top_10_num_like = $conn->query($sql_get_top_10_num_like);
?>
<div class="col l-10">
    <div class="row">

        <div class="col l-4">
            <h1 class="statistical__head">Top 10 bài hát có lượt nghe nhiều nhất</h1>
            <div class="statistical__title">
                <div class="row">
                    <div class="col l-2"><span class="statistical__title-content">STT</span></div>
                    <div class="col l-7"><span class="statistical__title-content">Bài hát</span></div>
                    <div class="col l-3"><span class="statistical__title-content">Lượt nghe</span></div>
                </div>
            </div>

            <div class="statistical__list">
                <?php
                    if ($result_top_10_num_play->num_rows > 0) {
                        $numerical_order = 0;
                        foreach ($result_top_10_num_play as $row_top_play) {
                            $numerical_order++;
                ?>
                <div class="statistical__item">
                    <div class="row alig-cen-flx">
                        <div class="col l-2">
                            <span class="statistical__numerical-order"><?=$numerical_order?></span>
                        </div>
                        <div class="col l-7">
                            <div class="statistical__song-info">
                                <img src="../<?=$row_top_play['thumb']?>" alt="" class="statistical__song-img">
                                <span class="statistical__song-detail">
                                    <span class="statistical__song-name"><?=$row_top_play['name']?></span>
                                    <span class="statistical__song-author">
                                        <?php
                                            // get artists
                                            $get_artist_of_song = "SELECT * FROM detail_songs WHERE id_song = " . $row_top_play['id'];
                                            $id_artist = $conn->query($get_artist_of_song);
                                            if ($id_artist->num_rows > 0) {
                                                foreach ($id_artist as $row_id_artist) {
                                                    $sql_get_name_artitst = "SELECT id, name FROM artists WHERE id = " . $row_id_artist['id_artist'];
                                                    $result_name_artist = ($conn->query($sql_get_name_artitst))->fetch_assoc();
                                                    echo $result_name_artist['name'] . " ";
                                                }
                                            } else {   
                                                echo "<span class='statistical__song-name'>Chưa xác định</span>";
                                            }
                                        ?>
                                    </span>
                                </span>
                            </div>
                        </div>
                        <div class="col l-3">
                            <div class="statistical__toltal-number"><?=$row_top_play['num_plays']?></div>
                        </div>
                    </div>
                </div>
                <?php
                        }
                    }
                ?>
                
            </div>
        </div>

        <!-- statistical download -->
        <div class="col l-4">
            <h1 class="statistical__head">Top 10 bài hát có lượt tải nhiều nhất</h1>
            <div class="statistical__title">
                <div class="row">
                    <div class="col l-2"><span class="statistical__title-content">STT</span></div>
                    <div class="col l-7"><span class="statistical__title-content">Bài hát</span></div>
                    <div class="col l-3"><span class="statistical__title-content">Lượt tải</span></div>
                </div>
            </div>

            <div class="statistical__list">
                <?php
                    if ($result_top_10_num_download->num_rows > 0) {
                        $numerical_order = 0;
                        foreach ($result_top_10_num_download as $row_top_download) {
                            $numerical_order++;
                ?>
                <div class="statistical__item">
                    <div class="row alig-cen-flx">
                        <div class="col l-2">
                            <span class="statistical__numerical-order"><?=$numerical_order?></span>
                        </div>
                        <div class="col l-7">
                            <div class="statistical__song-info">
                                <img src="../<?=$row_top_download['thumb']?>" alt="" class="statistical__song-img">
                                <span class="statistical__song-detail">
                                    <span class="statistical__song-name"><?=$row_top_download['name']?></span>
                                    <span class="statistical__song-author">
                                        <?php
                                            // get artists
                                            $get_artist_of_song = "SELECT * FROM detail_songs WHERE id_song = " . $row_top_download['id'];
                                            $id_artist = $conn->query($get_artist_of_song);
                                            if ($id_artist->num_rows > 0) {
                                                foreach ($id_artist as $row_id_artist) {
                                                    $sql_get_name_artitst = "SELECT id, name FROM artists WHERE id = " . $row_id_artist['id_artist'];
                                                    $result_name_artist = ($conn->query($sql_get_name_artitst))->fetch_assoc();
                                                    echo $result_name_artist['name'] . " ";
                                                }
                                            } else {   
                                                echo "<span class='statistical__song-name'>Chưa xác định</span>";
                                            }
                                        ?>
                                    </span>
                                </span>
                            </div>
                        </div>
                        <div class="col l-3">
                            <div class="statistical__toltal-number"><?=$row_top_download['num_download']?></div>
                        </div>
                    </div>
                </div>
                <?php
                        }
                    }
                ?>
            </div>
        </div>

        <!-- statistical love -->
        <div class="col l-4">
            <h1 class="statistical__head">Top 10 bài hát có nhiều lượt yêu thích</h1>
            <div class="statistical__title">
                <div class="row">
                    <div class="col l-2"><span class="statistical__title-content">STT</span></div>
                    <div class="col l-7"><span class="statistical__title-content">Bài hát</span></div>
                    <div class="col l-3"><span class="statistical__title-content">Lượt yêu thích</span></div>
                </div>
            </div>

            <div class="statistical__list">
                <?php
                    if ($result_top_10_num_like->num_rows > 0) {
                        $numerical_order = 0;
                        foreach ($result_top_10_num_like as $row_top_love) {
                            $numerical_order++;
                            $sql_get_song = "SELECT * FROM songs WHERE id = " . $row_top_love['id_song'];
                            $song = ($conn->query($sql_get_song))->fetch_assoc();
                ?>
                <div class="statistical__item">
                    <div class="row alig-cen-flx">
                        <div class="col l-2">
                            <span class="statistical__numerical-order"><?=$numerical_order?></span>
                        </div>
                        <div class="col l-7">
                            <div class="statistical__song-info">
                                <img src="../<?=$song['thumb']?>" alt="" class="statistical__song-img">
                                <span class="statistical__song-detail">
                                    <span class="statistical__song-name"><?=$song['name']?></span>
                                    <span class="statistical__song-author">
                                        <?php
                                            // get artists
                                            $get_artist_of_song = "SELECT * FROM detail_songs WHERE id_song = " . $song['id'];
                                            $id_artist = $conn->query($get_artist_of_song);
                                            if ($id_artist->num_rows > 0) {
                                                foreach ($id_artist as $row_id_artist) {
                                                    $sql_get_name_artitst = "SELECT id, name FROM artists WHERE id = " . $row_id_artist['id_artist'];
                                                    $result_name_artist = ($conn->query($sql_get_name_artitst))->fetch_assoc();
                                                    echo $result_name_artist['name'] . " ";
                                                }
                                            } else {   
                                                echo "<span class='statistical__song-name'>Chưa xác định</span>";
                                            }
                                        ?>
                                    </span>
                                </span>
                            </div>
                        </div>
                        <div class="col l-3">
                            <div class="statistical__toltal-number"><?=$row_top_love['num_like']?></div>
                        </div>
                    </div>
                </div>
                <?php
                        }
                    }
                ?>
            </div>
        </div>
    </div>
</div>
<?php
    $conn->close();
?>