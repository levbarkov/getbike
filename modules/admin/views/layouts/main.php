<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\modules\admin\AppAsset;
$this->params['showMain'] = true;
AppAsset::register($this);
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
<?php $this->beginBody() ?>
<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'Admin panel: getbike.io',
        'brandUrl' => '/admin/zakaz',
        'options' => [
            'class' => 'navbar-inverse',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'Orders', 'url' => ['/admin/zakaz']],
            ['label' => 'Rentals', 'items' =>[
                    ['label' => 'Rental',
                    'url' => '/admin/rental'],
                    ['label' => 'Rental Garages',
                    'url' => '/admin/rentalgarage'],
                    ['label' => 'Rental operations',
                    'url' => '/admin/operations'],
            ]],
            ['label' => 'Bikes', 'items' => [
                    ['label' => 'Bikes',
                        'url' => '/admin/bikes'],
                    ['label' => 'Prices',
                        'url' => '/admin/bikesprice'],
                    ['label' => 'Condition',
                        'url' => '/admin/condition'],
            ]],
            ['label' => 'GEO', 'items' => [
                    ['label' => 'Regions',
                        'url' => '/admin/regionlist'],
                    ['label' => 'Countrys',
                        'url' => '/admin/countrylist'],

            ]],

            ['label' => 'Pages', 'url' => ['/admin/pages']],
            ['label' => 'Article',  'items' => [
                [
                    'label' => 'Articles',
                    'url' => ['/admin/article/index']
                ],
                [
                    'label' => 'Country and regions',
                    'url' => ['/admin/country/index']
                ],
            ]],
            ['label' => 'Locale',  'items' => [
                [
                    'label' => 'Messages',
                    'url' => ['/admin/sourcemessage/index']
                ],
                [
                    'label' => 'Locale',
                    'url' => ['/admin/message/index']
                ],
            ]],

            Yii::$app->user->isGuest ? (
            ['label' => 'Login', 'url' => ['/site/login']]
            ) : (
                '<li>'
                . Html::beginForm(['/site/logout'], 'post', ['class' => 'navbar-form'])
                . Html::submitButton(
                    'Выйти (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-link']
                )
                . Html::endForm()
                . '</li>'
            )
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container-fluid">
        <?= Breadcrumbs::widget([
            'homeLink' => [$this->params['showMain'] ? null : false, 'label' => 'Главная', 'url' => '/admin'],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?php if( Yii::$app->session->hasFlash('success') ): ?>
            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?php echo Yii::$app->session->getFlash('success'); ?>
            </div>
        <?php endif;?>
        <?php if( Yii::$app->session->hasFlash('error') ): ?>
            <div class="alert alert-warning alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?php echo Yii::$app->session->getFlash('error'); ?>
            </div>
        <?php endif;?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
<div class="alerts"></div>
</body>
</html>
<?php $this->endPage() ?>
