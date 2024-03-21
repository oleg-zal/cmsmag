</main>
<footer class="footer">
    <div class="container">
        <div class="footer__wrapper">
            <div class="footer__top">
                <div class="footer__top_logo">
                    <img src="<?=PATH . TEMPLATE?>assets/img/Logo.svg" alt="">
                </div>
                <div class="footer__top_menu">
                    <ul>

                        <li>
                            <a href="http://somesite.ru/catalog/"><span>Каталог</span></a>
                        </li>

                        <li>
                            <a href="http://somesite.ru/about/"><span>О нас</span></a>
                        </li>

                        <li>
                            <a href="http://somesite.ru/delivery/"><span>Доставка и оплата</span></a>
                        </li>

                        <li>
                            <a href="http://somesite.ru/contacts/"><span>Контакты</span></a>
                        </li>

                        <li>
                            <a href="http://somesite.ru/news/"><span>Новости</span></a>
                        </li>

                        <li>
                            <a href="http://somesite.ru/sitemap/"><span>Карта сайта</span></a>
                        </li>

                    </ul>
                </div>
                <div class="footer__top_contacts">
                    <div><a href="../../../index.php">test@test.ru</a></div>
                    <div><a href="tel:+74842750204">+7 (4842) 75-02-04</a></div>
                    <div><a class="js-callback">Связаться с нами</a></div>
                </div>
            </div>
            <div class="footer__bottom">
                <div class="footer__bottom_copy">Copyright</div>
            </div>
        </div>
    </div>
</footer>

<div class="hide-elems">
    <svg>
        <defs>
            <linearGradient id="rainbow" x1="0" y1="0" x2="50%" y2="50%">
                <stop offset="0%" stop-color="#7282bc" />
                <stop offset="100%" stop-color="#7abfcc" />
            </linearGradient>
        </defs>
    </svg>
</div>
<?php /*if (!$this->userData):*/?>
    <div class="login-popup">
        <div class="order-popup__inner">

            <h2><span>Регистрация</span><span>Вход</span></h2>
            <form action="<?=$this->alias(['login' => 'registration'])?>" method="post">
                <input type="text" name="name" required placeholder="Ваше имя"
                       value="<?=$this->setFormValues('name', 'userData')?>">
                <input type="tel" name="phone" required placeholder="Телефон"
                       value="<?=$this->setFormValues('phone', 'userData')?>">
                <input type="email" name="email" required placeholder="E-mail"
                       value="<?=$this->setFormValues('email', 'userData')?>">
                <input type="password" name="password" required placeholder="Пароль">
                <input type="password" name="confirm_password" required placeholder="Подтверждение пароля">
                <div class="send-order">
                    <input class="execute-order_btn" type="submit" value="Регистрация">
                </div>
            </form>
            <form action="<?=$this->alias(['login' => 'login'])?>" method="post" style="display: none">
                <input type="text" name="login" required placeholder="E-mail"
                       value="<?=$this->setFormValues('email')?>">
                <input type="password" name="password" required placeholder="Пароль">
                <div class="send-order">
                    <input class="execute-order_btn" type="submit" value="Вход">
                </div>
            </form>
        </div>
    </div>
<?php /*endif;*/?>
<?php $this->getScripts(); ?>
<?php if ( !empty($_SESSION['res']['answer']) ):?>
    <div class="wq-message__wrap"><?=$_SESSION['res']['answer']?></div>
<?php endif;?>
<?php unset( $_SESSION['res'] )?>
</body>

</html>