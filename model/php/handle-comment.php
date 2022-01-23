<?php
    require('./db.php');
    if (isset($_GET['action']) && isset($_GET['id_song'])) {
        $id_song = $_GET['id_song'];
        if ($_GET['action'] === 'show_comments') {
            $sql_get_all_comment_song = "SELECT accounts.avatar, accounts.fullname, comments.content, accounts.username, comments.id
                FROM detail_comments JOIN comments ON detail_comments.id_comment = comments.id
                JOIN accounts ON accounts.username = detail_comments.username_account
                WHERE detail_comments.id_song = $id_song
                ORDER BY detail_comments.id_comment ASC";
            $result_all_comment_song = $conn->query($sql_get_all_comment_song);
            if ($result_all_comment_song->num_rows > 0) {
                while ($rows_comment = $result_all_comment_song->fetch_assoc()) {
                ?>
                    <div class="comment__item" data-id-comment="<?= $rows_comment['id'] ?>">
                        <img src="../<?= $rows_comment['avatar']?>" alt="" class="comment__user-avatar">
                        <div class="comment__user-info">
                            <span class="comment__user-name"><?= $rows_comment['fullname']?></span>
                            <span class="comment__content"><?= $rows_comment['content']?></span>
                        </div>
                        <?php if (!empty($_SESSION['user_info']) && ($_SESSION['user_info']['username'] === $rows_comment['username'])) { ?>
                        <div class="comment__wrap-option">
                            <span class="comment__option-icon">
                                <ion-icon name="ellipsis-horizontal-circle-outline"></ion-icon>
                            </span>
                            <ul class="comment__option-list">
                                <li class="comment__option-item">
                                    <label id="comment__edit-comment" class="comment__option-link">Sửa</label>
                                </li>
                                <li class="comment__option-item">
                                    <a id="comment__del-comment" class="comment__option-link">Xoá</a>
                                </li>
                            </ul>
                        </div>
                        <?php } ?>
                    </div>
                <?php
                }
            } else {
                echo "<h1>Chưa có bình luận cho bài hát này</h1>";
            }
            if (isset($_SESSION['user_info']['username'])) {
                echo "
                <div class='comment__my-comment'>
                    <img src='../" . $_SESSION['user_info']['avatar'] . "' class='comment__my-avatar'>
                    <div class='comment__my-info'>
                        <span class='comment__my-name'>" . $_SESSION['user_info']['fullname'] . "</span>
                        <textarea name='comment-content' id='' cols='100%' rows='2' class='comment__my-content' placeholder='Nhập bình luận'></textarea>
                        <div class='wrap-btn'>
                            <button name='comment__update' class='comment__btn btn btn-size-s hidden'>Cập nhật</button>
                            <button name='comment__cancel' class='comment__btn btn btn-size-s hidden'>Huỷ</button>
                            <button name='comment__submit' class='comment__btn btn btn-size-s'>Bình luận</button>
                        </div>
                    </div>
                </div>";
            }
        }
    } 
    
    else if (!empty($_POST['action']) && !empty($_POST['id_song'])) {
        $id_song = $_POST['id_song'];
        $username = $_SESSION['user_info']['username'];
        if ($_POST['action'] === 'send_comment') {
            $content = $_POST['content'];
            $insert_comment_sql = "INSERT INTO comments(content) VALUES ('$content')";
            if ($conn->query($insert_comment_sql) === true) {
                $get_current_id_comment_sql = "SELECT * FROM comments ORDER BY id DESC LIMIT 1";
                $result_id_comment = $conn->query($get_current_id_comment_sql);
                if ($result_id_comment->num_rows > 0) {
                    $id_comment = ($result_id_comment->fetch_assoc())['id'];
                    $insert_detail_comment = "INSERT INTO detail_comments(id_song, id_comment, username_account) VALUES($id_song, $id_comment, '$username')";
                    if ($conn->query($insert_detail_comment) === true) {
                        $get_new_comment_sql = "SELECT accounts.avatar, accounts.fullname, comments.content, accounts.username, comments.id
                        FROM detail_comments JOIN comments ON detail_comments.id_comment = comments.id
                        JOIN accounts ON accounts.username = detail_comments.username_account
                        WHERE detail_comments.id_song = $id_song
                        ORDER BY comments.id DESC LIMIT 1";
                        $result_new_comment = $conn->query($get_new_comment_sql);
                        if ($result_new_comment->num_rows > 0) {
                            $row_result_new_comment = ($result_new_comment->fetch_assoc());
                            echo "
                            <div class='comment__item' data-id-comment='" . $row_result_new_comment['id'] . "'>
                                <img src='../" . $row_result_new_comment['avatar'] . "' alt='' class='comment__user-avatar'>
                                <div class='comment__user-info'>
                                    <span class='comment__user-name'>" . $row_result_new_comment['fullname'] . "</span>
                                    <span class='comment__content'>" . $row_result_new_comment['content'] . "</span>
                                </div>
                                <div class='comment__wrap-option'>
                                    <span class='comment__option-icon'>
                                        <ion-icon name='ellipsis-horizontal-circle-outline'></ion-icon>
                                    </span>
                                    <ul class='comment__option-list'>
                                        <li class='comment__option-item'>
                                            <label id='comment__edit-comment' class='comment__option-link'>Sửa</label>
                                        </li>
                                        <li class='comment__option-item'>
                                            <a id='comment__del-comment' class='comment__option-link'>Xoá</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            ";
                        }
                    } else {
                        echo "Error";
                    }
                }
            } else {
                echo "Xảy ra lỗi khi thêm vào comments: " . $conn->error;
            }
        }
    }

    else if (!empty($_POST['action'])) {
        $id_comment = $_POST['id_comment'];
        $username = $_SESSION['user_info']['username'];
        if ($_POST['action'] === 'del_comment') {
            $sql_del_detail_comment = "DELETE FROM detail_comments WHERE id_comment = $id_comment AND username_account = '$username'";
            if ($conn->query($sql_del_detail_comment) === true) {
                $sql_del_comment = "DELETE FROM comments WHERE id = $id_comment";
                if ($conn->query($sql_del_comment) === true) {
                    echo "Xoá thành công";
                } else {
                    echo "Xảy ra lỗi khi xoá";
                }
            } else {
                echo "Xảy ra lỗi khi xoá";
            }
        }

        else if ($_POST['action'] === 'update_comment') {
            $new_comment = $_POST['new_comment'];
            $check_comment_of_user = "SELECT * FROM detail_comments WHERE username_account = '$username' AND id_comment = $id_comment";
            $result_check = $conn->query($check_comment_of_user);
            if ($result_check->num_rows > 0) {
                $sql_update_comment = "UPDATE comments SET content = '$new_comment'  WHERE id = $id_comment";
                if ($conn->query($sql_update_comment) === true) {
                    echo "Cập nhật thành công";
                } else {
                    echo "Cập nhật thất bại";
                }
            } else {
                echo "Bình luận này không phải của bạn";
            }
        }
    }
    $conn->close();
?>