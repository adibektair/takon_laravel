<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Lobster&family=Montserrat:wght@400;700&family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

    <!-- Fav icon -->
    <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
    <link rel="manifest" href="/favicon/site.webmanifest">
    <link rel="mask-icon" href="/favicon/safari-pinned-tab.svg" color="#161a1b">
    <meta name="msapplication-TileColor" content="#160734">
    <meta name="theme-color" content="#ffffff">

    <title>Takon</title>
</head>
<body>


<header class="header" id="header">
    <div class="container">
        <div class="header__inner">
            <a class="header__logo" data-scroll="#intro" href="{{ url('/') }}">Takon</a>

            <nav class="nav" id="nav">
                <a class="nav__link" href="#" data-scroll="#goal">Цель</a>
                <a class="nav__link" href="#" data-scroll="#role">Роль</a>
                <a class="nav__link" href="#" data-scroll="#footer">Контакты</a>
                @if (Route::has('login'))
                    <div class="top-right links">
                        @auth
                            <a class="btn__log" href="{{ url('/home') }}"><b>Профиль</b></a>
                        @else
                            <a class="btn__log" href="{{ route('login') }}"><b>Войти</b></a>

                            @if (Route::has('register'))
                                {{--                                <a href="{{ route('register') }}">Register</a>--}}
                            @endif
                        @endauth
                    </div>
                @endif

            </nav>

            <button class="nav-toggle" id="nav_toggle" type="button">
                <span class="nav-toggle__item">Menu</span>
            </button>

        </div>
    </div>
</header>


<div class="page">

    <div class="intro" id="intro">
        <div class="container">
            <div class="intro__inner">
                <h2 class="intro__suptitle">Электронно-цифровые талоны</h2>
                <h1 class="intro__title">Welcome to Takon</h1>

                <a class="btn" href="#" data-scroll="#goal">Подробнее</a>
            </div>
        </div>



    </div><!-- /.intro -->


    <section class="section" id="goal">
        <div class="container">

            <div class="section__header">
                <h3 class="section__suptitle">Что это?</h3>
                <h2 class="section__title">Цель Takon</h2>
                <div class="section__text">
                    <p>Вся идея ТАКОН заключается в цифровизации расходов компаний и прозрачности действий. Электронные процессы способствуют экономии времени, легкости в ведении бухгалтерского учета, четкой статистике и продвижении на рынке услуг.</p>
                </div>
            </div>

            <div class="card">
                <div class="card__item">
                    <div class="card__inner">
                        <div class="card__img">
                            <img src="assets/images/card/1.jpg" alt="">
                        </div>
                        <div class="card__text">Экономичность</div>
                    </div>
                </div>

                <div class="card__item">
                    <div class="card__inner">
                        <div class="card__img">
                            <img src="assets/images/card/2.jpg" alt="">
                        </div>
                        <div class="card__text">Лёгкое использование</div>
                    </div>
                </div>

                <div class="card__item">
                    <div class="card__inner">
                        <div class="card__img">
                            <img src="assets/images/card/3.jpg" alt="">
                        </div>
                        <div class="card__text">Повышение продаж</div>
                    </div>
                </div>
            </div>

        </div><!-- /.container -->
    </section>

    <div class="statistics">
        <div class="container">

            <div class="stat">
                <div class="stat__item">
                    <div class="stat__title">01</div>
                    <div class="stat__text">Такон всегда под рукой и готов к использованию</div>
                </div>
                <div class="stat__item">
                    <div class="stat__title">02</div>
                    <div class="stat__text">Никакой привязки к бумажным талонам и рискам потери</div>
                </div>
                <div class="stat__item">
                    <div class="stat__title">03</div>
                    <div class="stat__text">Свобода от комиссии</div>
                </div>
                <div class="stat__item">
                    <div class="stat__title">04</div>
                    <div class="stat__text">Прозрачность операций и дистанционное отслеживание</div>
                </div>
            </div>

        </div>
    </div>



    <section class="section" id="role">
        <div class="container">

            <div class="section__header">
                <h3 class="section__suptitle">Что я могу?</h3>
                <h2 class="section__title">Роль</h2>
            </div>

            <div class="services">
                <div class="services__item">
                    <img class="services__icon" src="assets/images/role/seller.png" alt="">

                    <div class="services__title">Продавец</div>
                    <div class="services__text">Продавец может с легкостью создать такон на нашей платформе без какой-либо технической помощи и затрат.</div>
                </div>
                <div class="services__item">
                    <img class="services__icon" src="assets/images/role/person.png" alt="">

                    <div class="services__title">Физическое лицо</div>
                    <div class="services__text">Может купить такон в самом приложении выбрав из маркета необходимый товар или услугу.</div>
                </div>
                <div class="services__item">
                    <img class="services__icon" src="assets/images/role/boss.png" alt="">

                    <div class="services__title">Юридическое лицо</div>
                    <div class="services__text">Может купить таконы, заключив договор с поставщиком, безналичным путем через сайт выбрав из меню товары и услуги.</div>
                </div>
            </div>

        </div><!-- /.container -->
    </section>

    <section class="section section--phone">
        <div class="container">
            <div class="section__header">
                <h3 class="section__suptitle">Скачай</h3>
                <h2 class="section__title">Мобильное приложение</h2>
                <div class="section__text">
                    <p>Мобильное приложение TAKON позволяет с легкостью создавать, приобретать, хранить и использовать таконы. Приложение помогает планировать и вести учет своих расходов, и, в целом, перенести их в онлайн.</p>
                </div>
            </div>

            <div class="stat">
                <div class="stat__item">
                    <a href="https://apps.apple.com/kz/app/takon/id1380041214" target="_blank" class="stat__title"><i class="fab fa-app-store"></i></a>
                    <div class="stat__text">App Store</div>
                </div>
                <div class="stat__item">
                    <a href="https://play.google.com/store/apps/details?id=kz.maint.www.takonmain" target="_blank" class="stat__title"><i class="fab fa-google-play"></i></a>
                    <div class="stat__text">Google Play</div>
                </div>
            </div>

        </div><!-- /.container -->
    </section>

    <footer class="footer">
        <div class="container">

            <div class="footer__inner" id="footer">
                <div class="footer__col  footer__col--first">
                    <div class="footer__logo">Takon</div>
                    <div class="footer__text">Наша платформа обеспечивает надежность и сохранность всех данных. Вся информация хранится на защищенном сервере, что обеспечивает полную защищенность от кражи и потери.</div>
                </div><!-- /.footer__col -->

                <div class="footer__col  footer__col--second">
                    <div class="footer__title"><b>Контакты</b></div>

                    <div class="blogs">
                        <div class="contacts__item">
                            <div class="contacts__content">
                                <div class="contacts__title"> Номер телефона:</div>
                                <a href="tel:+7-708-999-50-55" class="contacts__info">+7 (708) 999 50 55</a>
                            </div>
                        </div>

                        <div class="contacts__item">
                            <div class="contacts__content">
                                <div class="contacts__title">
                                    E-mail:
                                </div>
                                <a href = "mailto: info@takon.kz"class="contacts__info">info@takon.kz</a>
                            </div>
                        </div>

                    </div><!-- /.blogs -->
                </div>

            </div><!-- /.footer__inner -->

            <div class="copyright">
                © 2018 - <?php echo date("Y"); ?> Takon | Все права защищены
            </div>

        </div><!-- /.container -->
    </footer>

</div><!-- /.page -->


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<script src="assets/js/app.js"></script>

</body>
</html>
