<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Zakaz */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="zakaz-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'rental_id')->textInput() ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'garage_id')->textInput() ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'region_id')->textInput() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'user_phone')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'user_name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'user_email')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'date_for')->textInput() ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'date_to')->textInput() ?>
        </div>
        <div class="col-md-4">
            <?//= $form->field($model, 'curr_date')->textInput() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'price')->textInput() ?>
        </div>
<!--        <div class="col-md-3">
            <?/*//= $form->field($model, 'pay_id')->textInput() */?>
        </div>-->
        <div class="col-md-3">
            <?= $form->field($model, 'zakaz_info')->textInput() ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'service_tax')->textInput() ?>
        </div>
    </div>
    <div class="row">

    </div>
    <?= $form->field($model, 'comment')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
