<?php

/** @var $this \yii\web\View */
/** @var $link string */
/** @var $paramExample string */
use app\helper\PseudoCrypt;
?>
<p><b>NEW ORDER FROM GETBIKE.IO</b></p>

<p>We have new order.</p>

<p>
<ol>
    <li>Customer: <b><?= $o_name ?></b></li>
    <li>Whatsapp number: <b><a href="https://api.whatsapp.com/send?phone=<?= $phone ?>"><?= $phone ?></a></b></li>
    <li><a href='https://api.whatsapp.com/send?phone=<?= PseudoCrypt::phoneClear($phone)?>'>WhatsApp:<?= PseudoCrypt::phoneClear($phone)?></a></li>
    <li><b><?= $bike_model ?></b></li>
    <li><b><?= $condition ?>  <?= $helmets ?></b></li>
    <li>Dates: <b><?= $date ?></b></li>
    <li>Delivery now to: <b><?= $adress ?></b></li>
    <li>Price in agreement: <b><?= $summ ?> Rp</b></li>
    <li>Your part is <b><?= $price ?> Rp</b></li>
    <li>Please keep <b><?= $comission ?> Rp</b> for me, thank you</li>
</ol>
<!--<p><? /*=$o_name*/ ?> need bike <b><? /*=$b_model*/ ?> like <? /*=$condition*/ ?> helmets <? /*=$helmets_count*/ ?></b>.
<p>Delivery to <? /*=$adress*/ ?>.</p>
<p>Dates: <? /*=$date*/ ?>. Price: <? /*=$price*/ ?> Rp., Sum: <? /*=$summ*/ ?> Rp.</p>
GETBIKE.IO commission = <? /*=$comission*/ ?>Rp
<p>Available rental list:<br>
    <ul>
    <?php
/*    if(isset($rental_list) && is_array($rental_list)){
    foreach ($rental_list as $val){
        */ ?>
        <li>Rental: <? /*=$val['name']*/ ?> - ID: <? /*=$val['rental_id']*/ ?>; Email: <? /*=$val['email']*/ ?>; Region: <? /*=$val['region']*/ ?>; </li>
    <?php
/*    }
    }else{
        echo '<li>Rental: not found </li>';
    }
    */ ?>
</ul>-->

</p>
