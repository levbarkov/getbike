<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
$this->title = 'Third step | '.Yii::t('main', 'Type your name, telephone number, email and press Booking');
//$this->params['breadcrumbs'][] = $this->title;
$base_url = Yii::$app->session->get('base_url');
if(!$base_url && empty($base_url)){
    $base_url = '/'.Yii::$app->language;
}

?>
<noindex><div class="content">
        <div class="content__third" style="background-image: url('img/second_bg.png')">
            <div class="content__third__title">
                <p class="step_title step_font"><?=Yii::t('main', 'STEP {0} OF {1}', [3,3])?></p>

                <p><?=Yii::t('main', 'Personal info')?></p>
            </div>
            <div class="content__third__left">
                <div class="content__third__left__error">
                    <p id="name_error" style="display:none;"></p>                                      
                </div>
                <div class="content__third__left__error">
                    <p id="phone_error" style="display:none;"></p>                                        
                </div>
                <div class="content__third__left__error">
                    <p id="email_error" style="display:none;"></p>                                        
                </div>                                
                <div class="content__third__left__form">
                    <form action='<?=$base_url?>/final' name='go_final' id='go_final' method='post'>
					<input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
                        <div class="content__third__left__form__item">
                            <label for="name"><?=Yii::t('main', 'Name')?></label>
                            <input type="text" name="name" id="name" placeholder="<?=Yii::t('main', 'type your name')?>">
                        </div>
                        <div class="content__third__left__form__item" style="margin-bottom:16px;">
                            <div class="content__third__left__form__item__phone_checked" style="display:none;"></div>
                            <label for="phone"><?=Yii::t('main', 'Phone Number')?></label>
                            <input type="text" name="phone" id="phone" placeholder="<?=Yii::t('main', 'type your WhatsApp (with country code)')?>">
                            <div class="content__third__left__form__item__code" style="display:none;">
                                <label for="code">We sent confirmation code for you</label>
                                <div class="content__third__left__form__item__code__send">
                                    <input type="text" maxlength="4" onkeyup="if(this.value.length === 4){this.blur(); this.screenLeft = 0;}  console.log(this.value);" name="code" value="">
                                    <input type="button" value="Confirm">
                                    <input type="button" value="Resend">
                                </div>
                            </div>
                        </div>
                        <div class="content__third__left__form__item">
                            <label for="name"><?=Yii::t('main', 'E-mail')?></label>
                            <input type="text" name="email" id="email" placeholder="<?=Yii::t('main', 'type your email')?>">
                        </div>                        
                    </form>
                </div>
            </div>
            <div class="content__third__right">
                <div class="content__third__right__bill">
                    <div class="content__third__right__bill__title">
                        <p>Summary info</p>
                    </div>
                    <div class="content__third__right__bill__item">
                        <p class="content__third__right__bill__item__description">
                            - <?=Yii::t('main', 'Delivery the bike throughout the day.')?><br>
                            - <?=Yii::t('main', 'Payment on delivery, we only accept cash.')?><br>
                            <?=Yii::t('main', 'If you have any question please')?> <a href="https://api.whatsapp.com/send?phone=79059766651"><?=Yii::t('main', 'contact us')?></a>.
                        </p>
                    </div>
                    <div class="content__third__right__bill__item">
                        <div class="content__third__right__bill__item__wrapper">
                            <p class="content__third__right__bill__item__left"><?=Yii::t('main', 'Rent bike for')?></p>
                            <p class="content__third__right__bill__item__center"></p>
                            <p class="content__third__right__bill__item__right"><?php echo ($model['days']+1);?> <?=Yii::t('main', 'days')?></p>
                        </div>
                        <p class="content__third__right__bill__item__description"><?=Yii::t('main', 'from')?> <span><?php echo $model['date_from'];?></span> <?=Yii::t('main', 'to')?>
                            <span><?php echo $model['date_to'];?></span></p>
                    </div>
                    <div class="content__third__right__bill__item">
                        <div class="content__third__right__bill__item__wrapper">
                            <p class="content__third__right__bill__item__left"><?=Yii::t('main', 'Pick and drop at')?></p>
                            <p class="content__third__right__bill__item__center"></p>
                            <p class="content__third__right__bill__item__right"><?php echo $model['name_from_map'];?></p>
                        </div>
                    </div>
                    <div class="content__third__right__bill__item">
                        <div class="content__third__right__bill__item__wrapper">
                            <p class="content__third__right__bill__item__left"><?php echo $model['bike_model']?></p>
                            <p class="content__third__right__bill__item__center"></p>
                            <p class="content__third__right__bill__item__right"><?php echo number_format($model['bike_price']);?> IDR</p>
                        </div>
                    </div>
                    <div class="content__third__right__bill__item">
                        <div class="content__third__right__bill__item__wrapper">
                            <p class="content__third__right__bill__item__left"><?php $helmets = explode(' ',$model['helmets_count']); echo Yii::t('main', '{0} '.$helmets[1], $helmets[0])?></p>
                            <p class="content__third__right__bill__item__center"></p>
                            <p class="content__third__right__bill__item__right"><?=Yii::t('main', 'free')?></p>
                        </div>
                    </div>
                    <div class="content__third__right__bill__item">
                        <div class="content__third__right__bill__item__wrapper">
                            <p class="content__third__right__bill__item__left"><?=Yii::t('main', 'Service fee')?></p>
                            <p class="content__third__right__bill__item__center"></p>
                            <p class="content__third__right__bill__item__right"><?php echo number_format($model['service_fee']);?> IDR</p>
                        </div>
                    </div>
                    <div class="content__third__right__bill__item content__third__right__bill__item--total">
                        <div class="content__third__right__bill__item__wrapper">
                            <p class="content__third__right__bill__item__left content__third__right__bill__item__left--total">
                                <?=Yii::t('main', 'Reservation Total')?></p>
                            <p class="content__third__right__bill__item__center"></p>
                            <p class="content__third__right__bill__item__right content__third__right__bill__item__right--total">
                                <?php echo number_format($model['total']);?> IDR</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content__nav">
            <div class="content__nav__list">
                <div class="content__nav__list__item"></div>
                <div class="content__nav__list__item"></div>
                <div class="content__nav__list__item content__nav__list__item--active"></div>
            </div>
            <div class="content__nav__back" onclick="back_second();">
                <p><?=Yii::t('main', 'back')?></p>
            </div>
            <div class="content__nav__button" onclick="submit();">
                <p><?=Yii::t('main', 'Booking')?><i class="icon icon-right-arrow"></i></p>
            </div>
        </div>
    </div></noindex>
<?php
$error_1 = Yii::t('main', 'Please enter a name');
$error_2 = Yii::t('main', 'Please enter a phone');
$error_3 = Yii::t('main', 'Please enter a email');

$script_third = <<< JS
            function submit() {
               var d=0;
               if ($('#name').val()==''){
                   $('#name_error').text('$error_1');
                   $('#name_error').css({'display':'block'});
               } else {
                   $('#name_error').text('');
                   $('#name_error').css({'display':'none'});               
                   d=d+1;
               }
               
               if ($('#phone').val()=='') {
                   $('#phone_error').text('$error_2');
                   $('#phone_error').css({'display':'block'});
		       } else {
                   $('#phone_error').text('');
                   $('#phone_error').css({'display':'none'});		       
		           d=d+1;
		       }
		       if ($('#email').val()=='') {
                   $('#email_error').text('$error_3');
                   $('#email_error').css({'display':'block'});		       
		       } else {
		           $('#email_error').text('');
                   $('#email_error').css({'display':'none'});
		           d=d+1;
		       } 
		       if (d==3) {
		           localStorage.clear();
		           $('#go_final').submit();
		       }
            }
            function back_second(){

            localStorage['name'] = $("#name").val();
            localStorage['email'] = $("#email").val();
            localStorage['phone'] = $("#phone").val();
            window.location.href = '$base_url/second';
            
            }            
JS;
$this->registerJs($script_third, yii\web\View::POS_END);
?>
