<?php
    require('../model/php/db.php');
    if($_GET['logout'] == true) {
        unset($_SESSION['user_info']);
        header('location: ./user.php');
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuyetboitMP3</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,500;0,600;1,300;1,500;1,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../view/css/base.css">
    <link rel="stylesheet" href="../view/css/grid.css">
    <link rel="stylesheet" href="../view/css/_song.css">
    <link rel="stylesheet" href="../view/css/_albums.css">
    <link rel="stylesheet" href="../view/css/_artists.css">
    <link rel="stylesheet" href="../view/css/_comments.css?version=51">
    <link rel="stylesheet" href="../view/css/_search.css">
</head>
<body>
    <script src="../model/js/play-song.js"></script>
    <script src="../model/js/handle-artists.js"></script>
    <div id="app">
        <div class="grid">
            <div class="app__container">
                <div class="row">
                    <!-- comment -->
                    <div id="app__comments" class="hidden">
                        <div class="overlay">
                            <div class="comment__wrap">
                                <span class="comment__close-icon">
                                    <ion-icon name="close-outline"></ion-icon>
                                </span>
                                <h3 class="comment__head">Bình luận</h3>
                                <div class="comment__list">

                                </div>
                            </div>
                        </div>
                    </div>
                    <script src='../model/js/handle-comments.js'></script>

                    <!-- nav bar -->
                    <?php require('../view/navbar.php') ?>

                    <!-- content -->
                    <div class="col l-10">
                        <div class="app__content">
                            <?php require('../view/header.php'); ?>
                            <?php
                                if(isset($_GET['act'])) {
                                    switch ($_GET['act']) {
                                        case 'favourite':
                                            require('../view/favourite.php');
                                            break;
                                        case 'artists':
                                            require('../view/artists.php');
                                            break;
                                        case 'albums':
                                            require('../view/albums.php');
                                            break;
                                        case 'uploads':
                                            require('../view/uploads.php');
                                            break;
                                        default:
                                            require('../view/songs.php');
                                            break;
                                    }
                                } else {
                                    require('../view/songs.php');
                                }
                            ?>
                        </div>
                    </div>

                    <!-- controler -->
                    <?php require('../view/controller.php') ?>

                </div>
            </div>
        </div>
    </div>
    <?php $conn->close(); ?>
</body>
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
<script src="../model/js/slider-layer.js"></script>
<script src="../model/js/handle-search.js"></script>
<script>
    sliderLayerChange('#slider-layer__wrap', '.slider-layer__item');
</script>
</html>