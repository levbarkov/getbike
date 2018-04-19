<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\RentalGarageSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rental-garage-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'rental_id') ?>

    <?= $form->field($model, 'bike_id') ?>

    <?= $form->field($model, 'condition_id') ?>

    <?= $form->field($model, 'number') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'year') ?>

    <?php // echo $form->field($model, 'millage') ?>

    <?php // echo $form->field($model, 'radius') ?>

    <?php // echo $form->field($model, 'region_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
