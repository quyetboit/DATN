<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- link fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,500;0,600;1,300;1,500;1,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../css/base.css?version=51">
    <link rel="stylesheet" href="../view/css/style-admin.css?version=51">
    <link rel="stylesheet" href="../../css/grid.css?version=51">
    <link rel="stylesheet" href="../view/css/style-artists.css?version=51">
</head>
<body>
    <div class="grid wide">
        <div id="app-admin">
            <div class="row">

                <!-- nhúng -->
                <?php
                    require("../view/navbar.php");
                    if(isset($_GET['act'])) {
                        switch ($_GET['act']) {
                            case 'artists':
                                require("../view/artists.php");
                                break;
                            
                            default:
                                require("../view/home.php");
                                break;
                        }
                    } else {
                        require("../view/home.php");
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