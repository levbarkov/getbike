<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\RentalGarage */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rental-garage-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'rental_id')->textInput() ?>

    <?= $form->field($model, 'bike_id')->textInput() ?>

    <?= $form->field($model, 'condition_id')->textInput() ?>

    <?= $form->field($model, 'number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'year')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'millage')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'radius')->textInput() ?>

    <?= $form->field($model, 'region_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
