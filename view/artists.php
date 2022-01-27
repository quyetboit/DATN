<?php
    $get_artists_sql = "SELECT * FROM artists ORDER BY name";
    $result_artists = $conn->query($get_artists_sql);
?>
 
<div class="row content__artists content_wrapper">
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
</div>
<script>
    handleArtist();
</script>