<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
$this->title = 'Third step';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content">
        <div class="content__third" style="background-image: url('img/second_bg.png')">
            <div class="content__third__title">
                <p>Personal info</p>
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
                    <form action='/final' name='go_final' id='go_final' method='post'>
					<input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
                        <div class="content__third__left__form__item">
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name" placeholder="type your name">
                        </div>
                        <div class="content__third__left__form__item" style="margin-bottom:16px;">
                            <div class="content__third__left__form__item__phone_checked" style="display:none;"></div>
                            <label for="phone">Phone Number</label>
                            <input type="text" name="phone" id="phone" placeholder="type your WhatsApp">
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
                            <label for="name">E-mail</label>
                            <input type="text" name="email" id="email" placeholder="type your email">
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
                        <div class="content__third__right__bill__item__wrapper">
                            <p class="content__third__right__bill__item__left">Rent bike for</p>
                            <p class="content__third__right__bill__item__center"></p>
                            <p class="content__third__right__bill__item__right"><?php echo ($model['days']+1);?> days</p>
                        </div>
                        <p class="content__third__right__bill__item__description">from <span><?php echo $model['date_from'];?></span> to
                            <span><?php echo $model['date_to'];?></span></p>
                    </div>
                    <div class="content__third__right__bill__item">
                        <div class="content__third__right__bill__item__wrapper">
                            <p class="content__third__right__bill__item__left">Pick and drop at</p>
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
                            <p class="content__third__right__bill__item__left"><?php echo $model['helmets_count'];?></p>
                            <p class="content__third__right__bill__item__center"></p>
                            <p class="content__third__right__bill__item__right">free</p>
                        </div>
                    </div>
                    <div class="content__third__right__bill__item">
                        <div class="content__third__right__bill__item__wrapper">
                            <p class="content__third__right__bill__item__left">Service fee</p>
                            <p class="content__third__right__bill__item__center"></p>
                            <p class="content__third__right__bill__item__right"><?php echo number_format($model['service_fee']);?> IDR</p>
                        </div>
                    </div>
                    <div class="content__third__right__bill__item content__third__right__bill__item--total">
                        <div class="content__third__right__bill__item__wrapper">
                            <p class="content__third__right__bill__item__left content__third__right__bill__item__left--total">
                                Reservation Total</p>
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
                <p>back</p>
            </div>
            <div class="content__nav__button" onclick="submit();">
                <p>Booking<i class="icon icon-right-arrow"></i></p>
            </div>
        </div>
    </div>
<?php
$script_third = <<< JS
            function submit() {
               var d=0;
               if ($('#name').val()==''){
                   $('#name_error').text('Please enter a name');
                   $('#name_error').css({'display':'block'});
               } else {
                   $('#name_error').text('');
                   $('#name_error').css({'display':'none'});               
                   d=d+1;
               }
               
               if ($('#phone').val()=='') {
                   $('#phone_error').text('Please enter a phone');
                   $('#phone_error').css({'display':'block'});
		       } else {
                   $('#phone_error').text('');
                   $('#phone_error').css({'display':'none'});		       
		           d=d+1;
		       }
		       if ($('#email').val()=='') {
                   $('#email_error').text('Please enter a email');
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
            window.location.href = '/second';
            
            }            
JS;
$this->registerJs($script_third, yii\web\View::POS_END);
?>
