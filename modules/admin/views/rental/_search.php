<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\RentalSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rental-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'phone') ?>

    <?= $form->field($model, 'mail') ?>

    <?= $form->field($model, 'adress') ?>

    <?= $form->field($model, 'radius') ?>

    <?php // echo $form->field($model, 'name') ?>

    <?php // echo $form->field($model, 'hash') ?>

    <?php // echo $form->field($model, 'region_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
