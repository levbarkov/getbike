<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\BikesPrice */

$this->title = 'Create Bikes Price';
$this->params['breadcrumbs'][] = ['label' => 'Bikes Prices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bikes-price-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'data' => $data,
        'mass' => $mass
    ]) ?>

</div>
