<div class="row content__head">
    <div class="col l-9-6">
        <form action="" class="head__form-search" method="get">
            <input type="text" name="key-word" class="head__search-value" placeholder="Nhập từ khoá tìm kiếm">
            <button name="search-tmp" class="btn btn-size-s head__search-btn">
                <ion-icon name="search-outline"></ion-icon>
            </button>
        </form>
    </div>
    <div class="col l-2-4 border-left">
        <?php
            if(!empty($_SESSION['user_info'])) {
        ?>
        <div class="head__user-info">
            <img src="../<?= $_SESSION['user_info']['avatar']?>" alt="" class="head__user-avatar">
            <span class="head__user-name"><?= $_SESSION['user_info']['fullname']?></span>
            <span class="head__user-option">
                <span class="head__user-option-icon">
                    <ion-icon name="chevron-down-circle-outline"></ion-icon>
                </span>
                <ul class="option__list">
                    <li class="option__item"><a href="./user.php?logout=true">Đăng xuất</a></li>
                </ul>
            </span>
        </div>
        <?php
            } else {
        ?>
        <div class="head__user-action">
            <a href="../view/loggin.php" class="btn btn-size-l">Đăng nhập</a>
            <a href="../view/register.php" class="btn btn-size-l">Đăng kí</a>
        </div>
        <?php }?>
    </div>
</div>