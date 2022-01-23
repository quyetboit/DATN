<?php
    require('./db.php');
    $username = $_SESSION['user_info']['username'];
    $id_song = $_GET['id_song'];
    $add_favourite_sql = "";

    if (!empty($_POST)) {
        if ($_POST['action'] === "nav_to_artist") {
            $id_artist = $_POST['id_artist'];
            // list artist
            $get_artists_sql = "SELECT * FROM artists ORDER BY name";
            $result_artists = $conn->query($get_artists_sql);
            ?>
                <div class="col l-3">
                    <ul class="artists__wrap-artist">
                        <h3 class="artists__head-artist">Danh sách các nghệ sĩ</h3>
                        <?php
                            if ($result_artists->num_rows > 0) {
                                while ($row_artist = $result_artists->fetch_assoc()) {
                        ?>
                        <li class="artists__item" data-id-artist="<?= $row_artist['id'] ?>">
                            <a class="artists__item-link">
                                <img src="../<?= $row_artist['thumb'] ?>" alt="" class="artists__item-img">
                                <span class="artists__item-info">
                                    <span class="artists__item-name"><?= $row_artist['name'] ?></span>
                                    <span class="artists__item-type">Nghệ sĩ</span>
                                </span>
                            </a>
                        </li>
                        <?php
                                }
                            }
                        ?>
                        
                    </ul>
                </div>

                <div class="col l-9 songs__seccsion">
                    <div class="songs__wrapper artists__wrap-songs">

                    </div>
                </div>
            <?php
        }
    }

    if ($_GET['action'] == 'add_favourite') {
        $add_favourite_sql = "INSERT INTO favourite(username_account, id_song)
                            VALUES('$username', $id_song)";
        if ($conn->query($add_favourite_sql) === true) {
            echo "susscess";
        } else {
            echo "error" . $conn-> error;
        }
    } else if ($_GET['action'] == 'remove_favourite') {
        $remove_favourite_sql = "DELETE FROM favourite
                                WHERE username_account = '$username' and id_song = $id_song";
        if ($conn->query($remove_favourite_sql) === true) {
            echo "success";
        } else {
            echo "error" . $conn->error;
        }
    } else if ($_GET['action'] == 'increase_num_play') {
        $increase_num_play_sql = "UPDATE songs SET num_plays = num_plays + 1 WHERE id = $id_song";
        if ($conn->query($increase_num_play_sql) == true) {
            echo "Success";
        } else {
            echo "Error: " . $conn->error;
        }
    } else if ($_GET['action'] == 'increase_num_download') {
        $increase_num_play_sql = "UPDATE songs SET num_download = num_download + 1 WHERE id = $id_song";
        if ($conn->query($increase_num_play_sql) == true) {
            echo "Success";
        } else {
            echo "Error: " . $conn->error;
        }
    }

    $conn->close();
?>