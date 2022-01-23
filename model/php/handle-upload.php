<?php
    require('./db.php');
    if (!empty($_POST['action'])) {
        if (!empty($_POST['id_song'])) {
            $id_song_user = $_POST['id_song'];
            if ($_POST['action'] === 'remove_song_user') {
                $username = $_SESSION['user_info']['username'];
                $get_old_thumb_and_audio = "SELECT thumb, audio FROM songs_user WHERE id = $id_song_user";
                $result_old_thumb_and_audio = ($conn->query($get_old_thumb_and_audio))->fetch_assoc();
                $old_path_thumb = '../../' . $result_old_thumb_and_audio['thumb'];
                $old_path_audio = '../../' . $result_old_thumb_and_audio['audio'];

                $sql_del_song_user = "DELETE FROM songs_user  WHERE id = $id_song_user AND username_account = '$username'";
                if ($conn->query($sql_del_song_user) === true) {
                    unlink($old_path_thumb);
                    unlink($old_path_audio);
                    echo "del_success";
                } else {
                    echo "Xảy ra lỗi khi xoá: " . $conn->error;
                }
            }
        }
    }
    $conn->close();
?>