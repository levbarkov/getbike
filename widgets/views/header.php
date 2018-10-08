<?php
/**
 * Created by PhpStorm.
 * User: schr1ger
 * Date: 005 05.10.18
 * Time: 18:03
 */
?>
<div class="slide_menu">
    <div class="admin__menu__title">
        <a href="/">getbike.io</a>
    </div>
    <!-- Меню -->
    <ul>
        <?php foreach ($links as $link){ ?>
            <li><a href="/pg-<?=$link['alias']?>"><?=$link['title']?></a></li>
        <?php }?>
    </ul>
</div>
<div class="header">
    <div class="header__logo">
        <div class="icon-menu header__logo__menu icon icon-burger"></div>
        <a href="/" class="header__logo__logo">getbike</a>
    </div>
    <div class="header__social hidden">
        <div class="header__social__item">
            <a href="#"><span class=" icon icon-fb"></span></a>
        </div>
        <div class="header__social__item">
            <a href="#"><span class="icon icon-tw"></span></a>
        </div>
        <div class="header__social__item">
            <a href="#"><span class="icon icon-insta"></span></i></a>
        </div>
    </div>
    <div class="header__nav">
        <?php foreach ($links as $link){ ?>
            <div class="header__nav__item">
                <a href="/pg-<?=$link['alias']?>"><?=$link['title']?></a>
            </div>
        <?php }?>
        <div class="header__nav__item hidden">
            <a href="">Contact</a>
        </div>
        <div class="header__nav__item hidden">
            <a href="">Prices</a>
        </div>
        <div class="header__nav__item hidden">
            <a href="">FAQ</a>
        </div>
        <div class="header__nav__item hidden">
            <a href="">Terms</a>
        </div>
    </div>
    <div class="header__auth hidden">
        <div class="header__auth__reg">
            <a href="">Register</a>
        </div>
        <div class="header__auth__login">
            <a href="">Log In</a>
        </div>
    </div>
</div>
