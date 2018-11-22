<?php

/* @var $this yii\web\View */

$this->title = 'Second step | '.Yii::t('main', 'Choose bike rental days and your location on ' . ucfirst(Yii::$app->session->get('region') ? Yii::$app->session->get('region') : 'Bali'));
$base_url = Yii::$app->session->get('base_url');
if(!$base_url && empty($base_url)){
    $base_url = '/'.Yii::$app->language;
}

?>
            <div class="content">
                <p class="step_title step_font"><?=Yii::t('main', 'STEP {0} OF {1}', [2,3])?></p>
                <form action='<?=$base_url?>/third' name='go_second' id='go_second' method='post'>
				<input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />	
				<input type="hidden" name="location_from_map" id="location_from_map" <?php if ($location_from_map!=''){ ?> value='<?php echo $location_from_map; ?>' <?php } else { echo 'value="-8.6908602|115.169085"';} ?> />
				<input type="hidden" name="name_from_map" id="name_from_map" <?php if ($name_from_map!=''){ ?> value='<?php echo $name_from_map; ?>' <?php  } else { echo 'value="Kuta, Bali"' ;} ?> />
                <div class="content__first">
                    <div class="content__first__block">
                        <div class="content__first__block__title">
                            <p><?=Yii::t('main', Yii::$app->session->get('tag_line') ? Yii::$app->session->get('tag_line') : 'Rent a perfect bike in Bali')?></p>
                        </div>
                 <div class="date__error block__form__date__item__text"></div>
                        <div class="content__first__block__form">
                            <div class="content__first__block__form__date">
                                <div class="content__first__block__form__date__item--wrapper">
                                    <div class="content__first__block__form__date__item">
                                        <p class="content__first__block__form__date__item__title"><?=Yii::t('main', 'From')?></p>
                                        <input id="date-from" name="date-from" <?php if ($date_from!=''){ ?> value='<?php echo $date_from; ?>' <?php } ?> type="text" class="content__first__block__form__date__item__text" required placeholder="<?=Yii::t('main', 'choose date')?>"/>
                                    </div>
                                    <p class="content__first__block__form__location__title"><?=Yii::t('main', 'Pickup and drop at')?></p>
                                </div>
                                <div class="content__first__block__form__date__item--wrapper">
                                <div class="content__first__block__form__date__item">
                                    <p class="content__first__block__form__date__item__title"><?=Yii::t('main', 'To')?></p>
                                    <input id="date-to" name="date-to" <?php if ($date_to!=''){ ?> value='<?php echo $date_to; ?>' <?php } ?> type="text" class="content__first__block__form__date__item__text" required placeholder="<?=Yii::t('main', 'choose date')?>"/>
                                </div>
                                    <!--                                <a class="js-choose-location content__first__block__form__location__link"><i class="icon icon-location"></i>choose location</a>-->
                                </div>
                            </div>
                        </div>
                        <div class="content__first__block__form" style="margin-top: 0">
                            <a class="js-choose-location content__first__block__form__location__link content__first__block__form__location__link--choosed"><i class="icon icon-location"></i><span class="__cur_location"><?php if ($name_from_map!=''){ echo $name_from_map;  } else { ?>Kuta, Bali<?php } ?></span></a>

                        </div>
                    </div>
                </div>
                <div class="content__nav">
                    <div class="content__nav__list">
                        <div class="content__nav__list__item"></div>
                        <div class="content__nav__list__item content__nav__list__item--active"></div>
                        <div class="content__nav__list__item"></div>
                    </div>
                    <div class="content__nav__back" onclick="back_index();">
                        <p><?=Yii::t('main', 'back')?></p>
                    </div>
                    <div class="content__nav__button" onclick="submit();">
                        <p><?=Yii::t('main', 'Booking')?><i class="icon icon-right-arrow"></i></p>
                    </div>
                </div>
                </form>
            </div>
<?php
$error1 = Yii::t('main', 'Please choose date.');
$error2 = Yii::t('main', 'The date of return should be after the date of the lease.');
$script_index = <<< JS
            function submit() {
               if (($('#date-from').val()=='') || (($('#date-to').val()==''))){
                   $('.date__error').text('$error1');
                   $('.date__error').css({'display':'block'});
               } else {
               var date_from=new Date($('#date-from').val());
               var date_to=new Date($('#date-to').val());
               if (date_from>date_to){
                   $('.date__error').text('$error2');
                   $('.date__error').css({'display':'block'});               
               } else {
               //if (($('#location_from_map').val()=='') || (($('#name_from_map').val()==''))){
               if (($('#location_from_map').val()=='')){
                                  $('.date__error').text('Please choose location');
                                  $('.date__error').css({'display':'block'});
		       } else {
               $('#go_second').submit();
		       }
		       }
		       }
            }
            function back_index(){

            window.location.href = '$base_url/index'
            
            }
JS;
$this->registerJs($script_index, yii\web\View::POS_END);
?>
