<?php
    // genres
    function add_genre($file_image, $name_genres, $connection) {
        $file_name = handle_file_picture($file_image, '../data/genres/');
        $path = 'data/genres/' . $file_name;

        $sql_add_genres = "INSERT INTO genres(name, thumb)
                            VALUES('$name_genres', '$path')";
        
        if($file_name !== null) {
            if($connection->query($sql_add_genres) === true) {
                echo "<script>alert('Thêm thành công')</script>";
            } else {
                echo"<script>alert('Xảy ra lỗi khi thêm vào cơ sở dữ liệu: " . $connection->error . "')</script>";
            }
        }
    }

    function update_genre($id, $name, $file_thumb, $conn) {
        $sql = '';
        $path_old_thumb = '';
        if (empty($file_thumb['name'])) {
            $sql = "UPDATE genres SET name = '$name', thumb = thumb
                    WHERE id = $id";
        } else {
            $file_name = handle_file_picture($file_thumb, '../data/genres/');
            $path = 'data/genres/' . $file_name;
            $sql = "UPDATE genres SET name = '$name', thumb = '$path'
                    WHERE id = $id";
            $get_current_thumb = $conn->query("Select thumb from genres where id = $id");
            $path_old_thumb = '.' . $get_current_thumb->fetch_assoc()['thumb'];
        }

        if (!empty($sql)) {
            if ($conn->query($sql) === true) {
                unlink($path_old_thumb);
                echo "<script>alert('Cập nhật thành công')</script>";
            } else {
                echo "<script>alert('Xảy ra lỗi khi cập nhật: " . $conn->error . "')</script>";
            }
        }
    }

    // account
    function add_account($file_avatar, $username, $fullname, $password, $email, $conn) {
        $file_name = handle_file_picture($file_avatar, '../data/accounts/');
        if($file_name !== null) {
            $path = 'data/accounts/' . $file_name;
            $sql_add_account = "INSERT INTO accounts(username, password_account, fullname, email, avatar)
                                VALUES('$username', '$password', '$fullname', '$email', '$path')";
            if($conn->query($sql_add_account) === true) {
                echo "<script>alert('Thêm thành công')</script>";
            } else {
                echo "<script>alert('Xảy ra lỗi khi thêm: " . $conn->error . "')</script>";
            }
        }
    }

    // artist
    function add_artist($file_image, $name, $conn) {
        $file_name = handle_file_picture($file_image, '../data/artists/');
        $path = 'data/artists/' . $file_name;

        $sql_add_artist = "INSERT INTO artists(name, thumb)
                            VALUES('$name', '$path')";
        
        if($file_name !== null) {
            if($conn->query($sql_add_artist) === true) {
                echo "<script>alert('Thêm thành công')</script>";
            } else {
                echo"<script>alert('Xảy ra lỗi khi thêm vào cơ sở dữ liệu: " . $conn->error . "')</script>";
            }
        }
    }

    function update_artist($id, $name, $file_thumb, $conn) {
        $sql = '';
        $path_old_thumb = '';
        if (empty($file_thumb['name'])) {
            $sql = "UPDATE artists SET name = '$name'
                    WHERE id = $id";
        } else {
            $file_name = handle_file_picture($file_thumb, '../data/artists/');
            $path = 'data/artists/' . $file_name;
            $sql = "UPDATE artists SET name = '$name', thumb = '$path'
                    WHERE id = $id";
            $get_current_thumb = $conn->query("Select thumb from artists where id = $id");
            $path_old_thumb = '../' . $get_current_thumb->fetch_assoc()['thumb'];
        }

        if (!empty($sql)) {
            if ($conn->query($sql) === true) {
                unlink($path_old_thumb);
                echo "<script>alert('Cập nhật thành công')</script>";
            } else {
                echo "<script>alert('Xảy ra lỗi khi cập nhật: " . $conn->error . "')</script>";
            }
        }
    }

    // songs
    function add_song($thumb, $audio, $name, $time, $artist, $genre, $conn) {
        $insert_ok = 1;
        // $mess_error = [];
        $file_audio_name = handle_file_audio($audio, '../data/audioes/');
        $file_thumb_name = handle_file_picture($thumb, '../data/imgs/');

        $path_audio = 'data/audioes/' . $file_audio_name;
        $path_thumb = 'data/imgs/' . $file_thumb_name;

        $sql_add_song = "INSERT INTO songs(name, time, id_genre, thumb, audio)
                        VALUES('$name', '$time', $genre, '$path_thumb', '$path_audio')";
        
        if($file_audio_name !== null && $file_thumb_name !== null) {
            if($conn->query($sql_add_song) === true) {
                // add artist for song
                $sql_get_id_song = "SELECT id FROM songs
                                    ORDER BY id DESC
                                    LIMIT 0, 1";
                                    
                $result_id_song = $conn->query($sql_get_id_song);
                if ($result_id_song->num_rows > 0) {
                    $id_song = ($result_id_song->fetch_assoc())['id'];
                    
                    $sql_insert_artist = "INSERT INTO detail_songs(id_song, id_artist)
                                        VALUES($id_song, $artist)";
                    if ($conn->query($sql_insert_artist)) {
                        $insert_ok = 1;
                    } else {
                        $insert_ok = 0;
                    }
                } else {
                    $insert_ok = 0;
                }
            } else {
                $insert_ok = 0;
            }
        }

        if ($insert_ok === 1) {
            echo "<script>alert('Thêm thành công')</script>";
        } else {
            echo"<script>alert('Xảy ra lỗi khi thêm: " . $conn->error . "')</script>";
        }
    }

    function update_song($id, $thumb, $audio, $name, $time, $artist, $genre, $conn) {
        $sql = '';
        $path_old_thumb = '';
        $path_old_audio = '';
        $is_has_old_thumb = 0;
        $is_has_old_audio = 0;
        
        if (!empty($artist)) {
            $update_artist_sql = "UPDATE detail_songs SET id_artist = $artist
                                  WHERE id_song = $id";
            $conn->query($update_artist_sql);
        }

        if (empty($thumb['name']) && empty($audio['name'])) {

            $sql = "UPDATE songs
                    SET name = '$name', time = '$time', id_genre = $genre
                    WHERE id = $id";
            
        } 
        else {

            $sql = "UPDATE songs
                    SET name = '$name', time = '$time', id_genre = $genre";
            if (!empty($thumb['name'])) {
                $file_thumb_name = handle_file_picture($thumb, '../data/imgs/');
                $path_thumb = 'data/imgs/' . $file_thumb_name;
                $sql .= ", thumb = '$path_thumb'";

                $get_current_thumb = $conn->query("SELECT thumb from songs where id = $id");
                $path_old_thumb = '../' . $get_current_thumb->fetch_assoc()['thumb'];
                $is_has_old_thumb = 1;
            }

            if (!empty($audio['name'])) {
                $file_audio_name = handle_file_audio($audio, '../data/audioes/');
                $path_audio = 'data/audioes/' . $file_audio_name;
                $sql .= ", audio = '$path_audio'";

                $get_current_audio = $conn->query("SELECT audio from songs where id = $id");
                $path_old_audio = '../' . $get_current_audio->fetch_assoc()['audio'];
                $is_has_old_audio = 1;
            }

            $sql .= " WHERE id = $id";
            
        }

        if (!empty($sql)) {
            if ($conn->query($sql) === true) {
                if ($is_has_old_thumb === 1) {
                    unlink($path_old_thumb);
                }
                if($is_has_old_audio === 1) {
                    unlink($path_old_audio);
                }
                echo "<script>alert('Cập nhật thành công')</script>";
            } else {
                echo "<script>alert('Xảy ra lỗi khi cập nhật: " . $conn->error . "')</script>";
            }
        }
    }

    // user
    // register
    function user_register($username, $password, $fullname, $email, $file_avatar, $conn) {
        $file_name = handle_file_picture($file_avatar, '../data/accounts/');
        $path_avatar = 'data/accounts/' . $file_name;

        $sql_insert_account = "INSERT INTO accounts(username, password_account, fullname, email, avatar)
                                VALUES('$username', '$password', '$fullname', '$email', '$path_avatar')";
        
        if ($file_name !== null) {
            if ($conn->query($sql_insert_account) === true) {
                echo "<script>alert('Đăng kí thành công')</script>";
                return true;
            } else {
                echo "<script>alert('Đăng kí thất bại')</script>";
            }
        } else {
            echo "Không lấy được file name";
        }
        return false;
    }

    // use common
    function delete_by_id($id, $table_name, $conn) {
        $delete_ok = 1;
        $message_error = '';
        $is_has_thumb = $is_has_audio = $is_has_avatar = 0;
        $old_path_thumb = $old_path_audio = $old_path_avatar = '';
        $sql = "";

        if ($table_name === 'accounts') {
            $sql = "DELETE FROM $table_name
                    WHERE username = '" . "$id'";
        } else {
            $sql = "DELETE FROM $table_name
                    WHERE id = $id";
        }
        
        // delete artist of song
        if ($table_name === "songs") {
            $sql_delete_detail_songs = "DELETE FROM detail_songs
                                        WHERE id_song = $id";
            if (!($conn->query($sql_delete_detail_songs) === true)) {
                $delete_ok = 0;
                $message_error = $conn->error;
            }
        }

        $get_current_thumb = $conn->query("SELECT thumb FROM $table_name where id = $id");
        $get_current_audio = $conn->query("SELECT audio FROM $table_name where id = $id");
        $get_current_avatar = $conn->query("SELECT avatar FROM $table_name where username = '$id'");

        if ($get_current_thumb->num_rows > 0) {
            $old_path_thumb = '../' . ($get_current_thumb->fetch_assoc())['thumb'];
            $is_has_thumb = 1;
        }

        if ($get_current_audio->num_rows > 0) {
            $old_path_audio = '../' . ($get_current_audio->fetch_assoc())['audio'];
            $is_has_audio = 1;
        }

        if ($get_current_avatar->num_rows > 0) {
            $old_path_avatar = '../' . ($get_current_avatar->fetch_assoc())['avatar'];
            $is_has_avatar = 1;
        }

        if ($delete_ok === 1) {
            if ($conn->query($sql) === true) {
                if($is_has_thumb === 1) {
                    unlink($old_path_thumb);
                }
                if($is_has_audio === 1) {
                    unlink($old_path_audio);
                }
                if($is_has_avatar === 1) {
                    unlink($old_path_avatar);
                }
                echo "<script>alert('Xoá thành công')</script>";
                return true;
            } else {
                echo "<script>alert('Xảy ra lỗi khi xoá: " . $conn->error . "')</script>";
                return false;
            }
        } else {
            echo "<script>alert('Xảy ra lỗi khi xoá: " . $message_error . "')</script>";
            return false;
        }
    }

    function del_all_referance_of_artist($id_artist, $conn) {
        $sql_del_table_detail_song = "DELETE FROM detail_songs WHERE id_artist = $id_artist";
        if ($conn->query($sql_del_table_detail_song) === true) {
            return true;
        } else {
            echo "<scrip>alert('Xảy ra lỗi khi xoá bảng detail comment: " . $conn->error . "')";
            return false;
        } 

    }

    function del_all_referance_of_account($username, $conn) {
        // del song user
        $sql_get_song_user = "SELECT id FROM songs_user WHERE username_account = '$username'";
        $result_song_user = $conn->query($sql_get_song_user);
        if ($result_song_user->num_rows > 0) {
            foreach ($result_song_user as $id_song_user) {
                delete_by_id($id_song_user['id'], 'songs_user', $conn);
            }
        }

        // del favourite
        $sql_del_favourite = "DELETE FROM favourite WHERE username_account = '$username'";
        if ($conn->query($sql_del_favourite) !== true) {
            echo "<script>alert('Có lỗi xảy ra khi xoá bài hát yêu thích của user này: " . $conn->error . "');</script>";
            return false;
        }

        // get id comment of user
        $sql_get_id_comment_of_user = "SELECT id_comment FROM detail_comments WHERE username_account = '$username'";
        $ids_comment_of_user = $conn->query($sql_get_id_comment_of_user);

        // del table detail_comment
        $sql_del_detail_comment = "DELETE FROM detail_comments WHERE username_account = '$username'";
        if ($conn->query($sql_del_detail_comment) !== true) {
            echo "<script>alert('Có lỗi xảy ra khi xoá comment của user này: " . $conn->error . "');</script>";
            return false;
        }

        // del comment
        if ($ids_comment_of_user->num_rows > 0) {
            foreach ($ids_comment_of_user as $id_comment) {
                $sql_del_comment = "DELETE FROM comments WHERE id = " . $id_comment['id'];
                if ($conn->query($sql_del_comment) !== true) {
                    echo "<script>alert('Có lỗi xảy ra khi xoá comment của user này: " . $conn->error . "');</script>";
                    return false;
                }
            }
        }

        // delete detail album
        $get_id_album_of_user = "SELECT id FROM albums WHERE username_accounts = '$username'";
        $ids_album = $conn->query($get_id_album_of_user);
        if ($ids_album->num_rows > 0) {
            foreach ($ids_album as $id_album) {
                $sql_delete_album = "DELETE FROM detail_albums WHERE id_album = " . $id_album['id'];
                if (($conn->query($sql_delete_album)) !== true) {
                    echo "<script>alert('Xảy ra lỗi khi xoá album của người dùng này: " . $conn->error . "')</script>";
                    return false;
                }
            }
        }
        // delete album
        $sql_delete_album_of_user = "DELETE FROM albums WHERE username_accounts = '$username'";
        if ($conn->query($sql_delete_album_of_user) !== true) {
            echo "<script>alert('Xảy ra lỗi khi xoá album của người dùng này: " . $conn->error . "')</script>";
            return false;
        }
        return true;
    }

    function del_all_referance_of_genre($id_genre, $conn) {
        $ready = 1;
        // del detail song -> ok
        // del detail album -> ok
        // del detail comment -> ok
        // del comment -> ok
        // del favourite -> ok
        // del song -> ok
        $get_ids_song = "SELECT id FROM songs WHERE id_genre = $id_genre";
        $result_id_song = $conn->query($get_ids_song);
        if ($result_id_song->num_rows > 0) {
            foreach ($result_id_song as $row_id) {
                $id_song = $row_id['id'];
                // del song in table detail album
                $sql_del_detail_album = "DELETE FROM detail_albums WHERE id_song = $id_song";
                if ($conn->query($sql_del_detail_album) !== true) {
                    $ready = 0;
                    echo "Xảy ra lỗi khi xoá bài hát trong album: " . $conn->error;
                    return false;
                }

                // get all comment of song
                $sql_get_ids_comment = "SELECT id_comment FROM detail_comments WHERE id_song = $id_song";
                $result_ids_comment_list = $conn->query($sql_get_ids_comment);

                // delete table detail comment of song
                $sql_del_detail_comment = "DELETE FROM detail_comments WHERE id_song = $id_song";
                if ($conn->query($sql_del_detail_comment) !== true) {
                    $ready = 0;
                    echo "Xảy ra lỗi khi xoá bảng detail comment: " . $conn->error;
                    return false;
                }

                // del all comment of song
                if ($result_ids_comment_list->num_rows > 0) {
                    foreach ($result_ids_comment_list as $id_comment) {
                        $sql_del_comment = "DELETE FROM comments WHERE id = " . $id_comment['id_comment'];
                        if ($conn->query($sql_del_comment) !== true) {
                            $ready = 0;
                            echo $sql_del_comment;
                            echo "<br>";
                            echo "Có lỗi xảy ra khi xoá comment của bài hát: " . $conn->error;
                            return false;
                        }
                    }
                }

                // delete detail song (referances song to artist)
                $sql_del_detail_song = "DELETE FROM detail_songs WHERE id_song = $id_song";
                if ($conn->query($sql_del_detail_song) !== true) {
                    $ready = 0;
                    echo "Có lỗi xảy ra khi xoá bảng detail song: " . $conn->error;
                    return false; 
                }

                // delete farourite (referance song to account)
                $sql_del_favourite = "DELETE FROM favourite WHERE id_song = $id_song";
                if ($conn->query($sql_del_favourite) !== true) {
                    $ready = 0;
                    echo "Có lỗi xảy ra khi xoá bảng favourite: " . $conn->error;
                    return false;
                }

                // del song
                if (delete_by_id($id_song, 'songs', $conn) !== true) {
                    $ready = 0;
                    return false;
                }
            }
        }

        // del genre
        if ($ready === 1) {
            return true;
        }

        return false;
    }

    // function del_all_song_of_detail_comment($id_song, $conn) {
    //     $sql_del = "DELETE FROM detail_comments WHERE id_song = $id_song";
    //     if ($conn->query($sql_del) === true) {
    //         return true;
    //     }
    //     echo "<script>alert('" . $conn->error . "')</scrip>";
    //     return false;
    // }

    function handle_file_picture($file, $target_directory) {
        
        $ext = end(explode('.', $file['name']));
        $file_name = md5(date('dmyhis')) . '.' . $ext;
        $target_file = $target_directory . $file_name;

        // check file is actual or fake
        $check = getimagesize($file["tmp_name"]);
        if($check === false) {
            echo "<script>alert('File vừa chọn không thật sự là ảnh')</script>";
        } else {
            if (move_uploaded_file($file["tmp_name"], $target_file)) {
                return $file_name;
            } else {
                echo "<script>alert('Thêm thất bại')</script>";
                return null;
            }
        }
        return null;
    }

    function handle_file_audio($file, $target_directory) {
        if ($file['error'] === 0) {
            $ext = end(explode('.', $file['name']));
            $file_name = md5(date('dmyhis')) . '.' . $ext;
            $target_file = $target_directory . $file_name;
            if (move_uploaded_file($file["tmp_name"], $target_file)) {
                return $file_name;
            }
            else {
                echo "<script>alert('Thêm thất bại')</script>";
            }
        }
        return null;
    }
?>