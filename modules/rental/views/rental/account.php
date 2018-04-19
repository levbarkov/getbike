<?php
/* @var $model \app\models\Rental */
use yii\helpers\Html;
?>
    <div class="admin__content__top">
        <div class="admin__content__top__title">
            <p>Accounting</p>
        </div>
    </div>

    <div class="admin__content__main admin__content__main--add-bike">
        <div class="admin__content__main__form">
            <form action="" id="add_garage_form" method="post">
                <div class="admin__content__main__form__item">
                    <label for="number">Your name</label>
                    <input type="text" name="Rental[name]" value="<?=$model->name?>" required>
                </div>
                <div class="admin__content__main__form__item">
                    <label for="year">Bikes delivery radius</label>
                    <input type="text" name="Rental[radius]" value="<?=$model->radius?>" required>
                </div>
                <?=Html::hiddenInput(\Yii::$app->getRequest()->csrfParam, \Yii::$app->getRequest()->getCsrfToken(), [])?>
                <?=Html::button('send',['type'=>'submit', 'style'=>'display: none;', 'id'=>'submit_button'])?>
            </form>
        </div>
    </div>
    <div class="admin__content__bottom">
        <div class="admin__content__bottom__cancel" onclick="location='/rental/'">
            <p>cancel</p>
        </div>
        <div class="admin__content__bottom__add admin__content__bottom__add--save" onclick="submit_button.click();">
            <p>Save</p><i class="icon icon-right-arrow"></i>
        </div>
    </div>
