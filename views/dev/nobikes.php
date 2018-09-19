<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
$this->title = 'Final step';
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="content">
        <div class="content__final">
            <div class="content__final__title">
                <p data-order_id="">Sorry!</p>
            </div>
            <div class="content__final__description">
                <p>Unfortunately in the location you have chosen there are no bikes, choose another location.</p>
                    <p><a href="/">Try with a new location.</a></p>
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
    </div>
