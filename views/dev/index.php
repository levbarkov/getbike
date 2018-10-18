<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
$this->title = 'GetBike.io | Rental service bike on Bali with free delivery';
//$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="content">
        <p class="step_title">Book a bike in 2 minutes! Free delivery to hotel or your villa.</p>
        <p class="step_title step_font">STEP 1 OF 3</p>
		<form action='/second' name='go_third' id='go_third' method='post'>
		<input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
        <div class="content__second">
            <div class="content__second__list">
				<?php

				foreach($model as $key=>$value){
					
				?>
                <div class="content__second__list__item" id="bike_<?php echo $key;?>" bike="<?php echo $key;?>" style="background-image: url('<?php echo Yii::getAlias('@uploadBikePhotoWeb/') . $value['first_img']?>')">
					<?php foreach($value as $key1=>$value1){ 
						if (is_numeric($key1)){
						if ($key1!=$value['first_condition']){
							$style='style="display: none;"';
							} else {
								$style='';
								}
						
						?>
					
					<div id="bike_<?php echo $key; ?>condition_<?php echo $key1; ?>" condition="<?php echo $key1; ?>" <?php echo $style; ?>>	
					<input type="hidden" id="img_bike_<?php echo $key; ?>condition_<?php echo $key1; ?>" value="<?php echo Yii::getAlias('@uploadBikePhotoWeb/') .  $value1['bikeprice']['photo']; ?>">
                    <div class="content__second__list__item__title">
                        <p><?php echo $value1['bike']['model'];?></p>
                    </div>
                    <div class="content__second__list__item__price">
                        <p><span><?php echo number_format($value1['bikeprice']['price']);?> IDR</span> / day</p>
                    </div>
                        <?php if(isset($value1['bikeprice']['pricepm']) && !empty($value1['bikeprice']['pricepm'])){?>
                    <div class="content__second__list__item__price">
                        <p><span><?php echo number_format($value1['bikeprice']['pricepm']);?> IDR</span> / month</p>
                    </div>
                                <?php }?>
                    <div class="content__second__list__item__mileage">
						<?php if (isset($model[$key][1])){?>
                        <div onclick="show_hide('bike_<?php echo $key; ?>condition_1', '<?php echo $key; ?>')" class="content__second__list__item__mileage__item<?php if ($value1['condition_id']==1){ echo " content__second__list__item__mileage__item--active"; } ?>">
                            <p>New bike</p>
                        </div>
                        <?php
					    }
					    if (isset($model[$key][2])){
                        ?>
                        <div onclick="show_hide('bike_<?php echo $key; ?>condition_2', '<?php echo $key; ?>')" class="content__second__list__item__mileage__item<?php if ($value1['condition_id']==2){ echo " content__second__list__item__mileage__item--active"; } ?>">
                            <p>Low mileage</p>
                        </div>
                        <?php
					    }
					    if (isset($model[$key][3])){
                        ?>
                        <div onclick="show_hide('bike_<?php echo $key; ?>condition_3', '<?php echo $key; ?>')" class="content__second__list__item__mileage__item<?php if ($value1['condition_id']==3){ echo " content__second__list__item__mileage__item--active"; } ?>">
                            <p>Big mileage</p>
                        </div>
                        <?php
					    }
                        ?>
                    </div>
                    <div class="content__second__list__item__helmets">
                        <p class="content__second__list__item__helmets__title">Helmets</p>
                        <p class="content__second__list__item__helmets__description">Your scooter or motorbike will be delivered with<br> either 1 or 2 clean and sanitized helmets.</p>
                        <div class="content__second__list__item__helmets__list">
                            <div id="helmet1_bike_<?php echo $key; ?>condition_<?php echo $key1; ?>" helmet="1" onclick="helmet_select('helmet1_bike_<?php echo $key; ?>condition_<?php echo $key1; ?>', 'helmet2_bike_<?php echo $key; ?>condition_<?php echo $key1; ?>')" class="content__second__list__item__helmets__list__item content__second__list__item__helmets__list__item--active">
                                <p>1</p>
                            </div>
                            <div id="helmet2_bike_<?php echo $key; ?>condition_<?php echo $key1; ?>" helmet="2" onclick="helmet_select('helmet2_bike_<?php echo $key; ?>condition_<?php echo $key1; ?>', 'helmet1_bike_<?php echo $key; ?>condition_<?php echo $key1; ?>')" class="content__second__list__item__helmets__list__item">
                                <p>2</p>
                            </div>
                        </div>
                    </div>
                    </div>
                    
                    <?php
				     }
				 }
                    ?>
                </div>
                                    <?php
								
				     }
                    ?>
            </div>
            <div class="content__second__navigation">
                <div class="content__second__navigation__arrow js-slick-prev">
                    <div class="content__second__navigation__previous"></div>
                </div>
                <div class="content__second__navigation__current">
                    <p class="content__second__navigation__current--swipe">swipe</p>
                    <p><span class="js-slick-current">1</span><i></i><span class="js-slick-total"><?php echo count($model); ?></span></p>
                </div>
                <div class="content__second__navigation__arrow js-slick-next">
                    <div class="content__second__navigation__next"></div>
                </div>
            </div>
        </div>
        <div class="content__nav">
            <div class="content__nav__list">
                <div class="content__nav__list__item content__nav__list__item--active"></div>
                <div class="content__nav__list__item"></div>
                <div class="content__nav__list__item"></div>
            </div>
            <noindex><div class="content__nav__button" onclick="$('#go_third').submit();">
                    <p>Choose bike<i class="icon icon-right-arrow"></i></p>
                </div></noindex>
        </div>
        <input type="hidden" name="bike_id" id="bike_id">
        <input type="hidden" name="condition_id" id="condition_id">
        <input type="hidden" name="helmets_count" id="helmets_count">
        </form>
    </div>
<?php
$script_index = <<< JS
            function show_hide(id_block, key_bike) {
            $("#bike_"+key_bike).css({"background-image":"url('"+$("#img_"+id_block).val()+"')"});
            $("#"+id_block).show();
            var id_block1='bike_'+key_bike+'condition_1';
            if (id_block1!=id_block){
            $("#"+id_block1).hide();
            }
            var id_block2='bike_'+key_bike+'condition_2';
            if (id_block2!=id_block){
            $("#"+id_block2).hide();
            }
            var id_block3='bike_'+key_bike+'condition_3';
            if (id_block3!=id_block){
            $("#"+id_block3).hide();
            }   
            var current_slide_id=$("div[aria-hidden=false]").children("div[id *= 'bike']:not([style*='display: none;'])").attr("id"); 
		    $("#bike_id").val($("#"+$("div[aria-hidden=false]").attr("id")).attr("bike"));
		    $("#condition_id").val($("#"+$("div[aria-hidden=false]").children("div[id *= 'bike']:not([style*='display: none;'])").attr("id")).attr("condition"));
		    var current_helmets_count=$("#"+$("div[aria-hidden=false]").children("div[id *= 'bike']:not([style*='display: none;'])").attr("id")+" .content__second__list__item__helmets .content__second__list__item__helmets__list").children("div[class *= 'content__second__list__item__helmets__list__item--active']").attr("helmet");
		    $("#helmets_count").val(current_helmets_count);                     
            }
            function helmet_select(show_count, hide_count){
            $("#"+hide_count).removeClass("content__second__list__item__helmets__list__item--active");
            $("#"+show_count).addClass("content__second__list__item__helmets__list__item--active");
		    $("#bike_id").val($("#"+$("div[aria-hidden=false]").attr("id")).attr("bike"));
		    $("#condition_id").val($("#"+$("div[aria-hidden=false]").children("div[id *= 'bike']:not([style*='display: none;'])").attr("id")).attr("condition"));
		    var current_helmets_count=$("#"+$("div[aria-hidden=false]").children("div[id *= 'bike']:not([style*='display: none;'])").attr("id")+" .content__second__list__item__helmets .content__second__list__item__helmets__list").children("div[class *= 'content__second__list__item__helmets__list__item--active']").attr("helmet");
		    $("#helmets_count").val(current_helmets_count);             
            }
           
JS;
$this->registerJs($script_index, yii\web\View::POS_END);
?>
