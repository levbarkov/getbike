<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
$this->title = Yii::t('main', 'Final step');
$this->params['breadcrumbs'][] = $this->title;
?>
    <noindex><div class="content">
        <div class="content__final">
            <div class="content__final__title">
                <p data-order_id="<?=$order?>"><?=Yii::t('main', 'Thank you')?>, <?php echo $model;?>!</p>
            </div>
            <div class="content__final__description">
                <p><?=Yii::t('main', 'Your order has been processed. We will contact you shortly!')?></p>
            </div>
        </div>
        <div class="content__nav" style="display: none">
            <div class="content__nav__list">
                <div class="content__nav__list__item content__nav__list__item--active"></div>
                <div class="content__nav__list__item"></div>
                <div class="content__nav__list__item"></div>
            </div>
            <div class="content__nav__button">
                <p>Booking<i class="icon icon-right-arrow"></i></p>
            </div>
        </div>
    </div></noindex>
<?php
$script_index = <<< JS

            gtag('event', 'conversion', { 'send_to': 'AW-795380455/UrMrCODY8YoBEOeVovsC', 'value': $price, 'currency': 'IDR' });
            window.onload = function (ev) { 
                setTimeout(function() {
                    window.open('https://api.whatsapp.com/send?phone=79059766651');
                }, 3000)
                    
             }
JS;
$this->registerJs($script_index, yii\web\View::POS_END);
?>
