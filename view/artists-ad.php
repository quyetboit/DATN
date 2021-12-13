<?php
    if(!empty($_POST)) {
        $id = $_POST['artist-id'];
        $name = $_POST['artist-name'];
        $file = $_FILES['artist-avatar'];

        if (isset($_POST['add'])) {
            add_artist($file, $name, $conn);
        } else if (isset($_POST['update'])) {
            update_artist($id, $name, $file, $conn);
        } else if (isset($_POST['delete'])) {
            delete_by_id($id, 'artists', $conn);
        }
    }

    $select_artists = "SELECT * FROM artists ORDER BY name ASC";
    $result_artists = $conn->query($select_artists);
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

            <!-- main content -->
            <div class="container__main-content">
                <div class="grid">
                    <div class="row">
                        <!-- wrap item -->
                        <div class="col l-8">
                            <h3 class="main__head">Tất cả các nghệ sĩ</h3>
                            <div class="main__wrap-items">
                                <div class="main__list-head">
                                    <div class="row">
                                        <div class="col l-2">
                                            <span class="main__list-head-text">ID</span>
                                        </div>
                                        <div class="col l-10">
                                            <div class="row">
                                                <div class="col l-2">
                                                    <span class="main__list-head-text">Ảnh</span>
                                                </div>
                                                <div class="col l-5">
                                                    <span class="main__list-head-text">Tên</span>
                                                </div>
                                                <div class="col l-5">
                                                    <span class="main__list-head-text">Option</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- wrap artists -->
                                <div class="main__wrap-artists">

                                    <?php 
                                        if ($result_artists->num_rows > 0) {
                                            while ($row = $result_artists->fetch_assoc()) {
                                    ?>

                                    <div class="artists__items">
                                        <div class="row alig-cen-flx">
                                            <div class="col l-2">
                                                <span class="artists__id"><?=$row['id']?></span>
                                            </div>
                                            <div class="col l-10">
                                                <div class="row alig-cen-flx">
                                                    <div class="col l-2">
                                                        <img src="../<?=$row['thumb']?>" alt="" class="artists__avatar">
                                                    </div>
                                                    <div class="col l-5">
                                                        <span class="artists__name"><?=$row['name']?></span>
                                                    </div>
                                                    <div class="col l-5">
                                                        <button id="artists-modify-btn" class="btn btn-size-s">Sửa</button>
                                                        <button id="artists-delete-btn" class="btn btn-size-s">Xoá</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <?php
                                            } //end while
                                        } else {
                                            echo "<h1>Không có nghệ sĩ nào </h1>";
                                        }
                                    ?>

                                </div>
                            </div>
                        </div>

                        <!-- form CRUD -->
                        <div class="col l-4">
                            <div class="main__wrap-form">
                                <form action="" method="post" id="form__artists" enctype="multipart/form-data">
                                    <div class="form__warp-field">
                                        <label class="form__label" for="artist-id">Mã tác giả</label>
                                        <input type="text" name="artist-id" class="feild-content"><br>
                                    </div>

                                    <div class="form__warp-field">
                                        <label class="form__label" for="artist-name">Tên tác giả</label>
                                        <input type="text" name="artist-name" class="feild-content"><br>
                                    </div>

                                    <div class="form__warp-field">
                                        <label class="form__label" for="artist-avatar">Ảnh</label>
                                        <input type="file" name="artist-avatar" class="feild-content"><br>
                                    </div>
                                    <input class="btn btn-size-s" id="btn-artist-add" type="submit" name="add" value="Thêm">
                                    <input class="btn btn-size-s" id="btn-artist-update" type="submit" name="update" value="Cập nhật">
                                    <input class="btn btn-size-s" id="btn-artist-del" type="submit" name="delete" onclick="return confirmDel()" value="Xoá">
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
    getCurrentArtistToForm('.main__wrap-artists', '#form__artists');
</script>