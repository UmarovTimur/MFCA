<div class="mfca-main container">
    <div class="mfca-main__row">
        <a href="./category/az-book/" class="mfca-main__item">
            <div class="mfca-main__image">
                <img src="wp-content/themes/sydney.2.13/images/flags/az.png" alt="">
            </div>
            <div class="mfca-main__text">
                Azərbaycan
            </div>
        </a>
        <a href="./category/kz-book/" class="mfca-main__item">
            <div class="mfca-main__image">
                <img src="wp-content/themes/sydney.2.13/images/flags/kz.png" alt="">
            </div>
            <div class="mfca-main__text">
                Казах
            </div>
        </a>
        <a href="./category/ka-book/" class="mfca-main__item">
            <div class="mfca-main__image">
                <img src="wp-content/themes/sydney.2.13/images/flags/ka.png" alt="">
            </div>
            <div class="mfca-main__text">
                Қорақалпоқ
            </div>
        </a>
        <a href="./category/kg-book/" class="mfca-main__item">
            <div class="mfca-main__image">
                <img src="wp-content/themes/sydney.2.13/images/flags/kg.png" alt="">
            </div>
            <div class="mfca-main__text">
                Кыргыз
            </div>
        </a>
        <a href="./category/ce-book/" class="mfca-main__item">
            <div class="mfca-main__image">
                <img src="wp-content/themes/sydney.2.13/images/flags/ce.png" alt="">
            </div>
            <div class="mfca-main__text">
                Нохчи
            </div>
        </a>
        <a href="./category/ru-book/" class="mfca-main__item">
            <div class="mfca-main__image">
                <img src="wp-content/themes/sydney.2.13/images/flags/ru.png" alt="">
            </div>
            <div class="mfca-main__text">
                Русский
            </div>
        </a>
        <a href="./category/tj-book/" class="mfca-main__item">
            <div class="mfca-main__image">
                <img src="wp-content/themes/sydney.2.13/images/flags/tj.png" alt="">
            </div>
            <div class="mfca-main__text">
                Тоҷик
            </div>
        </a>
        <a href="./category/tk-book/" class="mfca-main__item">
            <div class="mfca-main__image">
                <img src="wp-content/themes/sydney.2.13/images/flags/tk.png" alt="">
            </div>
            <div class="mfca-main__text">
                Türkmen
            </div>
        </a>
        <a href="./category/uz-book/" class="mfca-main__item">
            <div class="mfca-main__image">
                <img src="wp-content/themes/sydney.2.13/images/flags/uz.png" alt="">
            </div>
            <div class="mfca-main__text">
                Ўзбек
            </div>
        </a>
        <a href="./category/ug-book/" class="mfca-main__item">
            <div class="mfca-main__image">
                <img src="wp-content/themes/sydney.2.13/images/flags/ug.png" alt="">
            </div>
            <div class="mfca-main__text">
                Уйғур
            </div>
        </a>
    </div>
</div>
<style>
    #content {
        justify-content:center;
    }
    .mfca-main {
        margin:0 0 0 -10px;
        width:calc(100% + 20px);
    }
    .container {
        max-width: 1170px;
        margin: 0 auto;
        /* padding: 0 15px; */
    }
    .mfca-main__row {
        flex-wrap: wrap;
        justify-content: center;
        display: flex;
    }

    .mfca-main__item {
        display: flex;
        flex-direction: column;
        flex:0 0 20%;
        padding: 0px 10px;
        text-decoration: none;
        margin-bottom: 20px;
        min-width: 182px;
    }

    .mfca-main__image {
        position: relative;
        padding-top:66.6%;
        flex: 0 0 auto;
        transition: all 0.3s ease 0s;
    }

    .mfca-main__image:active {
        transform: scale(0.9);
    }
    @media (any-hover: hover) {
        .mfca-main__image:hover {
            transform: scale(1.05);
        }
    }

    .mfca-main__image img {
        position: absolute;
        top:0;
        left:0;
        width: 100%;
        height: 100%;
        object-fit: cover;        
    }

    .mfca-main__text {
        text-align: center;
        font-size: 16px;
        color: black;
    }


    @media (max-width:992px) {
        .mfca-main__text {
            font-size: 1.1em;
        }
    }
    .title-post {
        display:none;
    }
</style>