<style>
    body {
        margin: 0;
        overflow-x: hidden;
    }
    button {
        background: #fff;
        border: 0;
        box-shadow: 0 0 5px 1px rgba(0, 0, 0 , 0.3);
        margin-top: 15px;
        font-size: 16px;
        padding: 6px 20px;
        border: 0;
        cursor: pointer;
    }
    main {
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction:column;
        justify-content: flex-start;
        align-items: center;
        font-family: "Arial";
        font-size: 1px;
        letter-spacing: 1px;
        font-weight: 300;
    }
    header {
        background: url('img/header-bg2.png') no-repeat #fff;
        -moz-background-size: cover; /* Firefox 3.6+ */
        -webkit-background-size: cover; /* Safari 3.1+ и Chrome 4.0+ */
        -o-background-size: cover; /* Opera 9.6+ */
        background-size: cover;
        width: 100%;
        height: 525px;
        position: relative;
    }
    header:after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: black;
        opacity: 0.6;
    }
    p, button {
        font-family: "Arial";
    }
    .top {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
        padding: 0 85px 0 85px;
        height: 75px;
        position: relative;
    }
    .top:after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: black;
        opacity: 0.5;
    }
    .icon {
        background: url('img/logo takon_0.png') no-repeat transparent;
        -moz-background-size: 100%; /* Firefox 3.6+ */
        -webkit-background-size: 100%; /* Safari 3.1+ и Chrome 4.0+ */
        -o-background-size: 100%; /* Opera 9.6+ */
        background-size: 100%;
        width: 220px;
        height: 55px;
        z-index: 99;
    }
    .header-buttons {
        color: #fff;
        z-index: 99;
        display: flex;
        flex-direction: row;
    }
    .header-buttons > button {
        margin-left: 15px;
        font-size: 1.5rem;
        font-weight: 300;
        letter-spacing: 1px;
        color: #a9a9a9;
        text-transform: uppercase;
    }
    .header-buttons > button:hover {
        color: #fafafa;
    }
    .middle {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
    }
    .midLeft {
        width: 65%;
    }
    .midRight {
        width: 35%;
        color: #fff;
        z-index: 99;
        min-height: 450px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .lozung {
        font-size: 2rem;
        text-align: right;
        font-weight: 300;
        letter-spacing: 1px;
        padding-right: 75px;
    }
    .pltImg {
        display: flex;
        flex-direction: row;
        justify-content: flex-end;
        margin-top: 60px;
        margin-right: 45px;
    }
    .pltIc {
        margin: 0 30px 0 30px;
    }
    .body {
        width: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .body-top {
        height: 1050px;
        width: 100%;
        display: flex;
        flex-direction: row;
        justify-content: center;
    }
    .body-top-left{
        height: 1050px;
        width: 75%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    .body-top-left-inner {
        width: 45%;
        height: 100%;
        font-size: 1.5rem;
        letter-spacing: 1px;
        font-weight: 300;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    .body-top-left-inner > h2 {
        color: #71c9bc;
        margin-bottom: 15px;
    }
    .body-top-left-inner > p {
        padding-left: 20px;
        margin-bottom: 15px;
    }
    .body-top-left-users {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        color: #71c9bc;
    }
    .body-top-left-users-divs {
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
    }
    .body-top-left-users-divs > span {
        text-align: center;
        margin: 30px;
    }
    .body-top-right {
        height: 1050px;
        width: 25%;
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
    }

    .body-second {
        padding: 4em 0 2em;
        color: #eeeeee;
        background-color: #000705;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
    }
    .body-second p {
        margin-top: 5px;
        font-size: 1.3rem;
        letter-spacing: 1px;
        font-weight: 300;
    }
    .body-second-left {
        width: 25%;
        height: 100%;
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
    }
    .body-second-left-inner {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        width: 80%;
        text-align: center;
    }
    .body-second-left-inner > h2 {
        margin-top: 25px;
        font-size: 1.3rem;
        letter-spacing: 1px;
        font-weight: 300;
    }

    .body-second-center {
        width: 45%;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    .body-second-center-inner {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        width: 90%;
        text-align: center;
    }
    .body-second-center-inner-top {
        height: 50%;
    }

    .body-second-center-inner-top > h2 {
        margin-top: 25px;
    }

    .body-second-center-inner-bottom {
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: flex-start;
        width: 100%;
        height: 50%;
        margin-top: 25px;
    }
    .body-second-center-inner-bottom h3 {
        margin-top: 15px;
    }
    .body-second-center-inner-bottom > div {
        width: 50%;
    }
    .body-second-center-inner-bottom > div p {
        margin-top: 10px;
    }
    .body-second-right {width:33%;}
    .footerTop    .body-second-right {
        width: 33%;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    .body-second-center-inner > p {
        margin-top: 15px;
    }

    .body-third {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 1025px;
        color: #212121;
        font-size: 1.8rem;
        letter-spacing: 1px;
        font-weight: 300;
        font-family: "Arial";
    }
    .body-third-div {
        width: 100%;
        height: 50%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    .mainbtn {
        margin-top: 15px;
        background-color: #75c590;
        color: #fff;
        font-size: 25px;
        letter-spacing: 1px;
        font-weight: 300;
        padding: 5px 25px;
    }
    .dis_main_btn {
        margin-top: 15px;
        background-color: #edeaea;
        color: #212121;
        font-size: 25px;
        letter-spacing: 1px;
        font-weight: 300;
        padding: 5px 25px;
    }
    .mainbtn:hover {
        background: #71c9bc;
    }
    .body-third-div-form {
        width: 50%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    .body-third-div-form > label {
        margin-top: 15px;
        width: 70%;
    }
    .body-third-div-form input {
        margin-top: 15px;
        padding: 5px 20px;
        color: #212121;
        background-color: #f5f5f5;
        border: 1px solid #ededed;
        width: 100%
    }
    .body-third-div-form input::-webkit-input-placeholder {
        padding: 3px;
        color: #212121;
    }
    .body-third-div-form input::-moz-placeholder {
        padding: 3px;
        color: #212121;
    }
    .body-third-div-bottom {
        width: 100%;
        height: 50%;
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
    }
    .body-third-div-bottom-left {
        width: 65%;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 0 40px;
        text-align: center;
    }
    .body-third-div-bottom-left > h1 {
        color: #75c590;
    }
    .body-third-div-bottom-right {
        width: 35%;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        align-items: center;
        padding-left: 15px;
    }

    .body-fourth {
        background-color: #000705;
        width: 100%;
        height: 525px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        font-size: 1.5rem;
        letter-spacing: 1px;
        font-weight: 300;
        color: #eeeeee;
        font-family: "Arial";
    }
    .body-fourth-top {
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
    }
    .body-fourth-divs {
        width: 33%;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
    }
    .body-fourth-divs-inner {
        height: 50%;
        padding-top: 30px;
    }
    .body-fourth-divs-inner > h1 {
        margin-top: 15px;
    }
    .body-fourth-bottom {
        color: #75c590;
        text-align: center;
        margin-top: 50px;
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
    }
    .body-fourth-bottom > p{
        width: 55%;
    }

    .body-fifth {
        width: 100%;
        font-size: 1.5rem;
        letter-spacing: 1px;
        font-weight: 300;
        color: #000;
        font-family: "Arial";
        display: flex;
        flex-direction: row;
        justify-content: flex-start;
        align-items: center;
    }
    .body-fifth-left {
        width: 60%;
        height: 100%;
        padding: 50px;
        text-align: left;
    }
    .body-fifth-left > h1 {
        color: #75c590;
        margin-top: 15px;
    }
    .body-fifth-left > h3 {
        color: #a6a6a6;
        margin-top: 5px;
    }
    .body-fifth-left > p {
        margin-top: 40px;
        width: 100%;
    }
    .body-fifth-right {
        width: 40%;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: flex-start;
    }

    .body-sixth {
        width: 100%;
        padding: 1.5em 0;
        background: #000705;
        font-size: 1.5rem;
        letter-spacing: 1px;
        font-weight: 300;
        color: #eeeeee;
        font-family: "Arial";
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
    }
    .body-sixth h1 {
        font-size: 1.8rem;
        letter-spacing: 1px;
        font-weight: 300;
    }
    .body-sixth p {
        width: 50%;
    }
    .body-sixth > .body-fourth-divs > .body-fourth-divs-inner {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .body-seventh {
        width: 100%;
        padding: 1.5em 0;
        background: url('img/OTOUKC1 (1).png') no-repeat;
        -moz-background-size: cover; /* Firefox 3.6+ */
        -webkit-background-size: cover; /* Safari 3.1+ и Chrome 4.0+ */
        -o-background-size: cover; /* Opera 9.6+ */
        background-size: cover;
        font-size: 1.6rem;
        color: #000;
        font-family: "Arial";
        letter-spacing: 1px;
        font-weight: 300;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: flex-start;
    }
    .body-seventh > p {
        width: 30%;
        margin-left: 150px;
        margin-top: 15px;
    }

    .body-eight {
        height: 520px;
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
    }
    .body-eight > .middle > .midRight{
        font-size: 1.6rem;
        color: #000;
        font-family: "Arial";
        letter-spacing: 1px;
        font-weight: 300;
        width: 65%;
    }
    .body-eight > .middle > .midLeft{
        width: 35%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    footer {
        width: 100%;
        background-color: #75c58f;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
    }
    .footerTop {
        color: #fff;
        font-size: 1.9rem;
        letter-spacing: 1px;
        font-weight: 300;
        display: flex;
        flex-direction: row;
        justify-content: flex-start;
    }
    .footerTopLeft {
        border-right: 3px solid #fff;
        width: 66%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: flex-start;
    }
    .footerTopLeftInner {
        width: 80%;
        margin-left: 105px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        align-items: stretch;
    }
    .footerTopLeftInner > div {
        margin-top: 35px;
    }
    .footerTopLeftInner textarea {
        width: 91.5%;
        height: 180px;
        color: #fff;
        background-color: #96d8ac;
    }
    .footerTopLeftInner > button {
        align-self: flex-start;
        background-color: #fff;
        color: #000;
        font-size: 1.9rem;
        letter-spacing: 1px;
        font-weight: 300;
        padding: 8px 80px;
    }
    .inputs {
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: flex-start;
        width: 100%;
    }
    .inputs > div {
        width: 100%;
    }
    .inputs input {
        padding: 15px 20px;
        color: #fff;
        background-color: #96d8ac;
    }
    .inputs input::-webkit-input-placeholder {
        padding: 3px;
        color: #fff;
    }
    .inputs input::-moz-placeholder {
        padding: 3px;
        color: #fff;
    }
    .footerTopRight {
        width: 34%;
    }
    .footerTopRightTop {
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
        height: 298px;
    }
    .footerTopRightBottom {
        border-top: 1px solid #fff;
        height: 208px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    .footerPhone {
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
        color: #fff;
        font-size: 1.4rem;
        letter-spacing: 1px;
        font-weight: 300;
    }
    .footerPhone > img {
        margin-right: 15px;
    }
    .footerPhone > span > p {
        margin: 0;
    }
    .footerEmail {
        margin-top: 10px;
        color: #fff;
        font-size: 1.4rem;
        letter-spacing: 1px;
        font-weight: 300;
    }
    .footerEmail > img {
        margin-right: 15px;
    }
    .footerBottom {
        border-top: 1px solid #fff;
        height: 115px;
        display: flex;
        flex-direction: row;
        justify-content: space-around;
        align-items: center;
        color: #fff;
        padding: 0 250px;
    }
    .footerBottom > div {
        font-size: 1.8rem;
        letter-spacing: 1px;
        height: 25px;
        line-height: 25px;
    }
    .allRights {
        border-left: 1px solid #fff;
        border-right: 1px solid #fff;
        padding: 0 100px;
    }
    .v--modal {
        font-size: 1.5rem;
    }
</style>
<HTML>
<link rel="stylesheet" href="./style.css">

<header>
    <div class="top">
        <div class="icon">
        </div>
        <div class="header-buttons">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}"><button class="mainbtn">Вход</button></a>
                    @else
                        <a href="{{ route('login') }}"><button class="mainbtn">Вход</button></a>

                        @if (Route::has('register'))
                            {{--                                <a href="{{ route('register') }}">Register</a>--}}
                        @endif
                    @endauth
                </div>
            @endif

            {{--                <button @click="openmodalMailer">--}}
            {{--                    Заявка на регистрацию--}}
            {{--                </button>--}}
            {{--                <button @click="openEnterModal">--}}
            {{--                    Вход--}}
            {{--                </button>--}}
        </div>
    </div>
    <div class="middle">
        <div class="midLeft">

        </div>
        <div class="midRight">
            <div class="lozung">
                Комплексная платформа, где размещены электронные товары на бензин
            </div>
            <div class="pltImg">
                <div class="pltIc">
                    <img src="img/app-store-logo_1.png" alt="">
                </div>
                <div class="pltIc">
                    <img src="img/google-play_1.png" alt="">
                </div>
            </div>
        </div>
    </div>
</header>
<div class="body" style="margin-top: 3em; margin-bottom: 10em;">
    <div class="body-top">
        <div class="body-top-left">
            <div class="body-top-left-inner">
                <h2>Таконы - электронно-цифровая форма товаров и услуг</h2>
                <p>
                    Мобильное приложение Takon позволяет с легкостью создавать,
                    реализовывать, приобретать, хранить, использовать и распространять таконы.
                </p>
                <p>
                    Приложение помогает планировать и вести учет своих расходов, и, в целом, перенести их в онлайн.
                </p>
                <p>
                    Мобильное приложение TAKON - единственное в своем роде, доступно и удобно для использования.
                </p>
                <span class="body-top-left-users">
                  <h1>ПОЛЬЗОВАТЕЛИ</h1>
                  <div class="body-top-left-users-divs">
                    <span>
                      <img src="img/manager.png" alt="">
                      <p>
                        Физические лица
                      </p>
                    </span>
                    <span>
                      <img src="img/boss.png" alt="">
                      <p>
                        Юридические лица
                      </p>
                    </span>
                  </div>
                </span>
                {{--                    <p>--}}
                {{--                        Компании, как пользователи товаров и услуг, используя все возможности TAKON, могут в реальном времени наблюдатьза электронными операциями и движениями по таконам кто, где, когда и как использовал такон.--}}
                {{--                    </p>--}}
            </div>
        </div>
        <div class="body-top-right" style="margin-right: 5em;">
            <img src="img/Iphone_2.png" alt="" width="325" height="690">
        </div>
    </div>

    <div class="body-second" style="padding-bottom: 7em;">
        <div class="body-second-left">
            <div class="body-second-left-inner">
                <img src="img/create.png" alt="">
                <h2>СОЗДАТЬ ТАКОН</h2>
                <p>
                    Вендор может с легкостью создать такон на нашей платформебез какой-либо технической помощи и затрат.
                </p>
            </div>
        </div>

        <div class="body-second-center">
            <div class="body-second-center-inner">
                <div class="body-second-center-inner-top">
                    <img src="img/telezhka.png" alt="">
                    <h2>Купить ТАКОН</h2>
                </div>

                <div class="body-second-center-inner-bottom">
                    <div>
                        <img src="img/manager1.png" alt="">
                        <h3>ФИЗИЧЕСКИЕ ЛИЦА</h3>
                        <p>
                            Могут купить такон в самом приложении выбрав из маркета необходимый товар или услугу и оплатить онлайн.
                        </p>
                    </div>

                    <div>
                        <img src="img/boss1.png" alt="">
                        <h3>ЮРИДИЧЕСКИЕ ЛИЦА</h3>
                        <p>
                            Могут купить таконы для производственный нужд, заключив договор с поставщиком, безналичным путем через сайт выбрав из меню товары и услуги представленные в виде таконов.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="body-second-right">
            <div class="body-second-center-inner">
                <div class="body-second-center-inner-top">
                    <img src="img/development-and-progress.png" alt="">
                    <h2>УПРАВЛЯТЬ ТАКОНОМ</h2>
                </div>

                <div class="body-second-center-inner-bottom">
                    <div>
                        <img src="img/manager1.png" alt="">
                        <h3>ФИЗИЧЕСКИЕ ЛИЦА</h3>
                    </div>

                    <div>
                        <img src="img/company-workers.png" alt="">
                        <h3>КОМПАНИИ</h3>
                    </div>
                </div>

                <p>
                    Вам открывается функция управления и раздачи таконов, а также контроль и прозрачность.
                </p>

            </div>
        </div>
    </div>

    <div class="body-third">
        <div class="body-third-div">
            <div class="body-third-div-form">
                <label>
                    ФИО<br>
                    <input type="text" v-model="name" placeholder="ФИО">
                </label>
                <label>
                    Тeлефон<br>
                    <input type="text" v-model="phone" placeholder="+7 (777) 777 77 77">
                </label>
                <!--                 <label>
                                  E-mail<br>
                                  <input type="text" placeholder="hello@example.com">
                                </label> -->
            </div>
            <button class="buttonClass" >
                Оставить заявку
            </button>
        </div>
        <div class="body-third-div-bottom">
            <div class="body-third-div-bottom-left">
                <h1>
                    Миссия TAKON - упростить и улучшить жизнь людей
                </h1>
                <p>
                    На нашей платформе люди могут приобрести идеальный вариант предложения товаров и услуг.
                </p>
                <br>
                <p>
                    Вся идея TAKON заключается в оптимизации расходов и прозрачности действий путем упрощения процессов реализации товаров, а также их использования.
                </p>
            </div>
            <div class="body-third-div-bottom-right">
                <img src="img/girl.png" alt="">
            </div>
        </div>
    </div>

    <div class="body-fourth">
        <div class="body-fourth-top">
            <div class="body-fourth-divs">
                <div class="body-fourth-divs-inner">
                    <img src="img/money-bag.png" alt="">
                    <h1>
                        Экономичность
                    </h1>
                    <p>
                        Вы не тратите время на печать, изготовление и распределение.
                    </p>
                </div>
            </div>
            <div class="body-fourth-divs">
                <div class="body-fourth-divs-inner">
                    <img src="img/smartphones.png" alt="">
                    <h1>
                        Легкое использование
                    </h1>
                    <p>
                        Вы можете легко передать такон на любое растояние.
                    </p>
                </div>
            </div>
            <div class="body-fourth-divs">
                <div class="body-fourth-divs-inner">
                    <img src="img/line-chart.png" alt="">
                    <h1>
                        Повышение продаж
                    </h1>
                    <p>
                        Высокая скорость работы приложения при реализации таконов.
                    </p>
                </div>
            </div>
        </div>
        <br> <br> <br> <br> <br>
        <div class="body-fourth-bottom">
            <p>
                Вся идея ТАКОН заключается в цифровизации расходов компаний и прозрачности действий. Электронные процессы способствуют экономии времени, легкости в ведении бухгалтерского учета, четкой статистике и продвижении на рынке услуг.
            </p>
        </div>
    </div>

    <div class="body-fifth">
        <div class="body-fifth-left">
            <h1>
                Почему выбирают цифровые талоны
            </h1>
            <h3>
                Быстро, просто, удобно, эффективно.
            </h3>
            <p>
                Оцифровка - самый дешевый и наиболее эффективный способ продажи. Для оцивровки в такон требуется очень короткий промежуток времени, поэтому вендор (поставщик товаров и услуг) может моментально стать участником рынка. Электронные процессы способствуют экономии времени и денег, легкости ведения бухгалтерского учета, четкой статистике, продвижению на рынке услуг.
            </p>
        </div>
        <div class="body-fifth-right">
            <img src="img/samsung_4.png" alt="" width="100%" >
        </div>
    </div>

    <div class="body-sixth">
        <div class="body-fourth-divs">
            <div class="body-fourth-divs-inner">
                <img src="img/customer.png" alt="">
                <h1>
                    НОВЫЕ КЛИЕНТЫ
                </h1>
                <p>
                    Используйте таконы чтобы привлечь больше клиентов и увеличить продажи.
                </p>
            </div>
        </div>
        <div class="body-fourth-divs">
            <div class="body-fourth-divs-inner">
                <img src="img/group (1).png" alt="">
                <h1>
                    КЛИЕНТСКАЯ БАЗА
                </h1>
                <p>
                    Учет количества данных ваших клиентов.
                </p>
            </div>
        </div>
        <div class="body-fourth-divs">
            <div class="body-fourth-divs-inner">
                <img src="img/paper-plane.png" alt="">
                <h1>
                    РАСШИРЕНИЕ ГРАНИЦ
                </h1>
                <p>
                    Мгновенный процесс транзакций в независимости от территориального расположения.
                </p>
            </div>
        </div>
    </div>

    <div class="body-seventh">
        <p>
            Наша платформа обеспечивает надежность и сохранность всех данных. Вся информация хранится на защищенном сервере, что обеспечивает полную защищенность от кражи и потери.
        </p>
        <p>
            Масштабный проект позволяет Вам выпускать неограниченное количество таконов, избегая затрат на производство.
        </p>
    </div>

    <div class="body-eight">
        <div class="middle">
            <div class="midLeft">
                <img src="img/OT13041.png" alt="">
            </div>
            <div class="midRight">
                <div class="lozung">
                    Приложение доступно для устройств Android и IOS. Услуга бесплатна для физических и юридических лиц. Также если Вы являетесь вендором - приложение предоставляется бесплатно на первый месяц пользования.
                </div>
                <div class="pltImg">
                    <div class="pltIc">
                        <img src="img/app-store-logo_1.png" alt="">
                    </div>
                    <div class="pltIc">
                        <img src="img/google-play_1.png" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<footer>
    <div class="footerTop">
        <div class="footerTopLeft">
            <div class="footerTopLeftInner" style="margin-bottom: 2em;">
                <div class="inputs">
                    <div>
                        <p>Имя</p> <input type="text" placeholder="ФИО">
                    </div>
                    <div>
                        <p>Телефон</p> <input type="text" placeholder="+7 (777) 777 77 77">
                    </div>
                </div>
                <div >
                    <p>Сообщение</p>
                    <textarea></textarea>
                </div>
                <button>
                    Отправить
                </button>
            </div>
        </div>
        <br><br>
        <div class="footerTopRight">
            <div class="footerTopRightTop">
                <img src="img/qr-code_takon.png" alt="" height="200" width="200">
            </div>
            <div class="footerTopRightBottom">
                <div class="textAlignLeft">
                    <div class="footerPhone">
                        <img src="img/telephone.png" alt="" height="25">
                        <span>
                        <p>+7 (708) 978 99 66</p>
                        <p>+7 (708) 705 70 51</p>
                      </span>
                    </div>
                    <div class="footerEmail">
                        <img src="img/email.png" alt="" height="25">
                        <span>
                        info@takon.kz
                      </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footerBottom">
        <div>
            TAKON
        </div>
        <div class="allRights">
            Все права защищены
        </div>
        <div>
            2018
        </div>
    </div>
</footer>
<mailer-modal/>
</HTML>
