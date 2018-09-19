<?php

/** @var $this \yii\web\View */
/** @var $link string */
/** @var $paramExample string */

?>
<p><b>NEW ORDER FROM GETBIKE.IO</b></p>

<p>Hey boss <?=$name?>!<br>
    We have new order for you.</p>

<p><?=$o_name?> need bike <b><?=$b_model?> like <?=$condition?></b>.

<p>We choose bike number <?=$number?>.</p>

<p>Delivery to <?=$adress?>.</p>

<p>Dates: <?=$date?>. Price: <?=$price?>/day, Sum: <?=$summ?> Rp.</p>
GETBIKE.IO commission = <?=$comission?>Rp

<p>I take the order and i give to client <?=$number?>. Go to line to get the order:<br>
    <a href="<?=$confirm_link?>"><?=$confirm_link?></a></p>

<p>I take the order but i give client another bike. Go to link to choose bike:<br>
    <a href="<?=$change_link?>"><?=$change_link?></a></p>

