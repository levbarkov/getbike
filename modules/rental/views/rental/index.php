<?php
/**
 * Created by PhpStorm.
 * User: trufanov
 * Date: 16.04.2018
 * Time: 17:03
 */
/* @var $garage \app\models\RentalGarage */
?>
        <div class="admin__content__top">
            <div class="admin__content__top__title">
                <p>Garage</p>
            </div>
            <div class="admin__content__top__sort">
                <p class="admin__content__top__sort__title">Sort by:</p>
                <p class="admin__content__top__sort__list">availability<i class="icon icon-right-arrow"></i></p>
            </div>
        </div>
        <div class="admin__content__main">
            <ol class="admin__content__main__list">
                <?php foreach ($garage as $val){ /* @var $val \app\models\RentalGarage */?>
                    <li <?=$val->status == 0 ? 'class="js-bike-inactive"' : ''?>>
                        <div class="admin__content__main__list__item">
                            <p class="admin__content__main__list__item__link" style="max-width: 160px;"><a href="/rental/edit?id=<?=$val->id?>"><?=$val->bike->model?></a></p>
                            <p class="admin__content__main__list__item__plate"><?=$val->number?></p>
                            <input class="admin__content__main__list__item__button js-bike-availability" data-toggle="garage_status" data-garage_id="<?=$val->id?>" <?=$val->status == 0 ? 'checked' : ''?> type="checkbox"/>
                            <span class="admin__content__main__list__item__button__bg"></span>
                        </div>
                    </li>
                <?php }?>
            </ol>
        </div>
        <div class="admin__content__bottom">
            <div class="admin__content__bottom__add" onclick='location="/rental/add";'>
                <p>Add bike</p><i class="icon icon-right-arrow"></i>
            </div>
        </div>
