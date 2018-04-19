<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\RentalGarage */

$this->title = 'Update Rental Garage: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Rental Garages', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="rental-garage-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
