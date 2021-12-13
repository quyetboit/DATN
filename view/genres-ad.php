<?php
    if(!empty($_POST)) {
        if(!empty($_POST['add'])) {
            add_genre($_FILES['genre-avatar'], $_POST['genre-name'], $conn);
        } else if (!empty($_POST['delete'])) {
            delete_by_id($_POST['genre-id'], 'genres', $conn);
        } elseif (!empty($_POST['update'])) {
            update_genre($_POST['genre-id'], $_POST['genre-name'], $_FILES['genre-avatar'], $conn);
        }
    }

    // get genres from db
    $select_genres = "SELECT * FROM genres ORDER BY id DESC";
    $result_genres = $conn->query($select_genres);
?>

<div class="col l-10">
    <div class="admin__container">
        <div class="grid">
            <!-- head -->
            <div class="row container__head">
                <!-- search -->
                <div class="col l-9-6">
                    <div class="container__head-search">
                        <form action="" method="get" class="container__head-form">
                            <input type="text" placeholder="Nhập tên bài hát ..." class="container__head-input">
                            <button class="container__head-submit">
                                <ion-icon class="container__submit-icon" name="search-outline"></ion-icon>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- admin info -->
                <div class="col l-2-4">
                    <div class="container__info">
                        <img src="https://th.bing.com/th/id/OIP.mC208xfJNCP3KdgiWuETLQHaEK?pid=ImgDet&rs=1" alt="" class="container__info-img">
                        <span class="container__info-name">Quang Hải</span>
                        <span class="container__info-option">
                            <ion-icon name="chevron-down-circle-outline"></ion-icon>
                        </span>
                    </div>
                </div>
            </div>

            <!-- genres section -->
            <div class="container__main-content">
                <div class="grid">
                    <div class="row">
                        <!-- wrap item -->
                        <div class="col l-8">
                            <h3 class="main__head">Tất cả các thể loại</h3>
                            <div class="main__wrap-items">
                                <div class="main__list-head">
                                    <div class="row">
                                        <div class="col l-2">
                                            <span class="main__list-head-text">ID</span>
                                        </div>
                                        <div class="col l-2">
                                            <span class="main__list-head-text">Ảnh thể loại</span>
                                        </div>
                                        <div class="col l-4">
                                            <span class="main__list-head-text">Tên thể loại</span>
                                        </div>
                                        <div class="col l-4">
                                            <span class="main__list-head-text">Option</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- wrap artists -->
                                <div class="main__wrap-genres">

                                    <?php 
                                        if ($result_genres->num_rows > 0) {
                                            while ($row = $result_genres->fetch_assoc()) {
                                    ?>
                                        <div class="genre__items">
                                            <div class="row alig-cen-flx">
                                                <div class="col l-2">
                                                    <span class="genre__id"><?=$row['id']?></span>
                                                </div>

                                                <div class="col l-2">
                                                    <img src="../<?=$row['thumb']?>" alt="" class="genre-img">
                                                </div>

                                                <div class="col l-4">
                                                    <span class="genre__name"><?=$row['name']?></span>
                                                </div>

                                                <div class="col l-4">
                                                    <button class="btn btn-size-s" id="btn-modifi">Sửa</button>
                                                    <button class="btn btn-size-s" id="btn-del">Xoá</button>
                                                </div>
                                            </div>
                                        </div>

                                    <?php
                                            } //end while
                                        } else {
                                            echo "<h1>Không có thể loại nào </h1>";
                                        }
                                    ?>
                                    
                                </div>
                            </div>
                        </div>

                        <!-- form CRUD -->
                        <div class="col l-4">
                            <div class="main__wrap-form">
                                <form action="" method="post" id="form__genres" enctype="multipart/form-data">
                                    <div class="form__warp-field">
                                        <label class="form__label" for="genre-id">Mã thể loại</label>
                                        <input type="text" name="genre-id" class="feild-content"><br>
                                    </div>

                                    <div class="form__warp-field">
                                        <label class="form__label" for="genre-name">Tên thể loại</label>
                                        <input type="text" name="genre-name" class="feild-content"><br>
                                    </div>

                                    <div class="form__warp-field">
                                        <label class="form__label" for="genre-avatar">Ảnh</label>
                                        <input type="file" name="genre-avatar" class="feild-content"><br>
                                    </div>
                                    <input class="btn btn-size-s" type="submit" name="add" value="Thêm">
                                    <input class="btn btn-size-s" type="submit" name="update" value="Cập nhật">
                                    <input class="btn btn-size-s hidden" type="submit" name="delete" value="Xoá" onclick="return confirmDel()">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../model/js/get_data_to_form.js"></script>
<script>
    getCurrentGenreToForm('.main__wrap-genres', '#form__genres');
</script>