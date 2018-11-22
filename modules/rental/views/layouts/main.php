<?php

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\modules\rental\AppAsset;

AppAsset::register($this);
$auth = Yii::$app->session->get('auth');
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
    <!--[if lte IE 9]>
    <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a
            href="https://browsehappy.com/">upgrade
        your browser</a> to improve your experience and security.</p>
    <![endif]-->
    <?php $this->beginBody() ?>
    <div class="admin">
        <div class="header">
            <div class="header__logo">
                <div class="header__logo__menu icon icon-burger"></div>
                <a href="#" class="header__logo__logo">getbike</a>
            </div>
            <div class="header__social">
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
                <div class="header__nav__item">
                    <a href="">About us</a>
                </div>
                <div class="header__nav__item">
                    <a href="">Contact</a>
                </div>
                <div class="header__nav__item">
                    <a href="">Prices</a>
                </div>
                <div class="header__nav__item">
                    <a href="">FAQ</a>
                </div>
                <div class="header__nav__item">
                    <a href="">Terms</a>
                </div>
            </div>
            <div class="header__auth">
                <div class="header__auth__reg">
                    <a href="">Register</a>
                </div>
                <div class="header__auth__login">
                    <a href="">Log In</a>
                </div>
            </div>
            <div class="admin__menu__bottom">
                <p><i class="icon icon-person"></i>Kriss<i class="icon icon-right-arrow"></i></p>
            </div>
        </div>
        <div class="admin__menu">
            <div class="admin__menu__title">
                <a href="#">getbike.io</a>
            </div>
            <?php if (!Yii::$app->user->isGuest) { ?>
                <div class="admin__menu__links">
                    <a href="/rental/" class="admin__menu__links__item">Garage</a>
                    <a href="/rental/account" class="admin__menu__links__item">Accounting</a>
                </div>
                <div class="admin__menu__bottom">
                    <!--            <p><i class="icon icon-person"></i>Kriss<i class="icon icon-right-arrow"></i></p>-->
                    <p style="max-width: 120px;"><i class="icon icon-person"></i><?= Yii::$app->user->getIdentity()->name ?></p>
                </div>
            <?php } ?>
        </div>
        <div class="admin__content">
        <?php if (Yii::$app->session->hasFlash('success')): ?>
            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <?php echo Yii::$app->session->getFlash('success'); ?>
            </div>
        <?php endif; ?>
        <?php if (Yii::$app->session->hasFlash('error')): ?>
            <div class="alert alert-warning alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <?php echo Yii::$app->session->getFlash('error'); ?>
            </div>
        <?php endif; ?>
            <?= $content ?>
        </div>

    </div>
    <div class="layout" style="display: none"></div>
    <div class="datepicker_bottom" style="display:none;">
        <div class="datepicker_bottom_current">
            <p>I need bike now</p>
        </div>
        <div class="datepicker_bottom_chosen">
            <p>June 13, 2018</p>
        </div>
        <div class="datepicker_bottom_arrow"></div>
    </div>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>