<?php

/** @var $this \yii\web\View */
/** @var $link string */
/** @var $paramExample string */

?>
<p><b>NEW ORDER FROM GETBIKE.IO</b></p>

<p>Hey boss Lev!<br>
    We have new order.</p>

<p><?=$o_name?> need bike <b><?=$b_model?> like <?=$condition?> helmets <?=$helmets_count?></b>.
<p>Delivery to <?=$adress?>.</p>
<p>Dates: <?=$date?>. Price: <?=$price?> Rp., Sum: <?=$summ?> Rp.</p>
GETBIKE.IO commission = <?=$comission?>Rp
<p>Available rental list:<br>
    <ul>
    <?php
    if(isset($rental_list) && is_array($rental_list)){
    foreach ($rental_list as $val){
        ?>
        <li>Rental: <?=$val['name']?> - ID: <?=$val['rental_id']?>; Email: <?=$val['email']?>; Region: <?=$val['region']?>; </li>
    <?php
    }
    }else{
        echo '<li>Rental: not found </li>';
    }
    ?>
</ul>

</p>
