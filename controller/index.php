<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuyetboitMP3 - Admin</title>
    <!-- link fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,500;0,600;1,300;1,500;1,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../view/css/base.css">
    <link rel="stylesheet" href="../view/css/style-admin.css">
    <link rel="stylesheet" href="../view/css/grid.css">
    <link rel="stylesheet" href="../view/css/style-artists.css">
    <link rel="stylesheet" href="../view/css/style-genres.css">
    <link rel="stylesheet" href="../view/css/style-account.css">
    <link rel="stylesheet" href="../view/css/style-statistical.css?version=51">
</head>
<body>
    <?php
        require('../model/php/db.php');
        require('../model/php/func.php');
    ?>
    <!-- loggin -->
    <?php
        if (empty($_SESSION['admin_info'])) {
            echo "<script>window.location.href='../view/loggin-admin.php';</script>";
            exit;
        }
    ?>
    <div class="grid wide">
        <div id="app-admin">
            <div class="row">

                <!-- nhúng -->
                <?php
                    require("../view/navbar-ad.php");
                    if(isset($_GET['act'])) {
                        switch ($_GET['act']) {
                            case 'artists-ad':
                                require("../view/artists-ad.php");
                                break;
                            
                            case 'genres-ad':
                                require("../view/genres-ad.php");
                                break;

                            case 'accounts-ad':
                                require("../view/accounts-ad.php");
                                break;

                            case 'statistical-ad':
                                require("../view/statistical-ad.php");
                                break;

                            default:
                                require("../view/songs-ad.php");
                                break;
                        }
                    } else {
                        require("../view/songs-ad.php");
                    }
                ?>


            </div>
        </div>
    </div>
</body>
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
<script src="../js/handle-navbar.js"></script>
</html>

<?php
    $conn->close();
?>