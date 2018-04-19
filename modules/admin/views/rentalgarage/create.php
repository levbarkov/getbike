<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\RentalGarage */

$this->title = 'Create Rental Garage';
$this->params['breadcrumbs'][] = ['label' => 'Rental Garages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rental-garage-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
