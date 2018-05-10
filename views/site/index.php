<?php

/* @var $this yii\web\View */

$this->title = 'Get Bike';
?>
            <div class="content">      
				<form action='/second' name='go_second' id='go_second' method='post'>
				<input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />	
                <div class="content__first">
                    <div class="content__first__block">
                        <div class="content__first__block__title">
                            <p>Rent your perfect bike in&nbsp;Bali</p>
                        </div>
                 <div class="date__error block__form__date__item__text"></div>
                        <div class="content__first__block__form">
                            <div class="content__first__block__form__date">
                                <div class="content__first__block__form__date__item--wrapper">
                                    <div class="content__first__block__form__date__item">
                                        <p class="content__first__block__form__date__item__title">From</p>
                                        <input id="date-from" name="date-from" <?php if ($date_from!=''){ ?> value='<?php echo $date_from; ?>' <?php } ?> type="text" class="content__first__block__form__date__item__text" required placeholder="choose date"/>
                                    </div>
                                    <p class="content__first__block__form__location__title">Pickup and drop at</p>
                                </div>
                                <div class="content__first__block__form__date__item--wrapper">
                                <div class="content__first__block__form__date__item">
                                    <p class="content__first__block__form__date__item__title">To</p>
                                    <input id="date-to" name="date-to" <?php if ($date_to!=''){ ?> value='<?php echo $date_to; ?>' <?php } ?> type="text" class="content__first__block__form__date__item__text" required placeholder="choose date"/>
                                </div>
                                <a class="js-choose-location content__first__block__form__location__link content__first__block__form__location__link--choosed"><i class="icon icon-location"></i>Krasnoyarsk, Mira, 565 343</a>
                                <a class="js-choose-location content__first__block__form__location__link"><i class="icon icon-location"></i>choose location</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content__nav">
                    <div class="content__nav__list">
                        <div class="content__nav__list__item content__nav__list__item--active"></div>
                        <div class="content__nav__list__item"></div>
                        <div class="content__nav__list__item"></div>
                    </div>
                    <div class="content__nav__button" onclick="submit();">
                        <p>Choose bike<i class="icon icon-right-arrow"></i></p>
                    </div>
                </div>
                </form>
            </div>
<?php
$script_index = <<< JS
            function submit() {
               if (($('#date-from').val()=='') || (($('#date-to').val()==''))){
                   $('.date__error').text('Please choose date');
                   $('.date__error').css({'display':'block'});
               } else {
               var date_from=new Date($('#date-from').val());
               var date_to=new Date($('#date-to').val());
               if (date_from>date_to){
                   $('.date__error').text('The date of return should be after the date of the lease.');
                   $('.date__error').css({'display':'block'});               
               } else {
               $('#go_second').submit();
		       }
		       }
            }
JS;
$this->registerJs($script_index, yii\web\View::POS_END);
?>
