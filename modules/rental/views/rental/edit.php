<?php
/* @var $bikes \app\models\Bikes */
/* @var $conditions \app\models\Condition */
/* @var $garage \app\models\RentalGarage */
use yii\helpers\Html;
?>
    <div class="admin__content__top">
        <div class="admin__content__top__title">
            <p>Edit bike</p>
        </div>
    </div>

    <div class="admin__content__main admin__content__main--add-bike">
        <div class="admin__content__main__form">
            <form action="" id="edit_garage_form" method="post">
                <?=Html::hiddenInput('RentalGarage[rental_id]', Yii::$app->user->getId())?>
                <?=Html::hiddenInput('RentalGarage[region_id]', Yii::$app->user->getIdentity()->region_id)?>
                <?=Html::hiddenInput('RentalGarage[radius]', Yii::$app->user->getIdentity()->radius)?>
                <?=Html::hiddenInput('RentalGarage[status]', 1)?>
                <div class="admin__content__main__form__item admin__content__main__form__item--selector">
                    <label for="model">Bike model</label>
                    <a class="js-model-list-toggle"><span class="js-model-list-toggle-text">Choose bike model</span><i class="icon icon-right-arrow"></i></a>
                    <ul style="display: none" class="js-model-list">
                        <?php foreach ($bikes as $val){ if(empty($val->bikesPrices)) continue; ?>
                            <li>
                                <input type="radio" data-type="model"   <?=$garage->bike_id == $val->id ? 'checked' : ''?> required id="bike_id_<?=$val->id?>" value="<?=$val->id?>" name="RentalGarage[bike_id]">
                                <label for="<?=$val->id?>"><?=$val->model?></label>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
                <div class="admin__content__main__form__item">
                    <label for="number">State number of bike</label>
                    <input type="text" name="RentalGarage[number]" value="<?=$garage->number?>" required placeholder="Type your bike state number">
                </div>
                <div class="admin__content__main__form__item">
                    <label for="year">Year of the bike</label>
                    <input type="text" name="RentalGarage[year]" value="<?=$garage->year?>" required placeholder="Type your bike year of issue">
                </div>
                <div class="admin__content__main__form__item">
                    <label for="mileage">Mileage bike</label>
                    <input type="text" required name="RentalGarage[millage]" value="<?=$garage->millage?>" placeholder="Type your bike mileage">
                </div>
                <div class="admin__content__main__form__condition">
                    <div class="admin__content__main__form__condition__title">
                        <p>Bike condition</p>
                    </div>
                    <div class="admin__content__main__form__condition__list">
                        <?php foreach ($conditions as $val) { ?>
                            <div class="admin__content__main__form__condition__list__item">
                                <input type="radio" required name="RentalGarage[condition_id]" <?=$garage->condition_id == $val->id ? 'checked' : ''?> value="<?=$val->id?>" id="mileage<?=$val->id?>">
                                <label for="mileage<?=$val->id?>"><?=$val->text?></label>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <?=Html::hiddenInput(\Yii::$app->getRequest()->csrfParam, \Yii::$app->getRequest()->getCsrfToken(), [])?>
                <?=Html::button('send',['type'=>'submit', 'style'=>'display: none;', 'id'=>'submit_button'])?>
            </form>
        </div>
    </div>
    <div class="admin__content__bottom">
        <div class="admin__content__bottom__delete">
<!--            <p>delete</p>-->
            <?= Html::a('delete', ['delete', 'id' => $garage->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
        </div>
        <div class="admin__content__bottom__cancel" onclick="location='/rental/'">
            <p>cancel</p>
        </div>
        <div class="admin__content__bottom__add admin__content__bottom__add--save" onclick="submit_button.click();">
            <p>Save</p><i class="icon icon-right-arrow"></i>
        </div>
    </div>
