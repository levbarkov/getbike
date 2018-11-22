<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\BikesPrice */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bikes-price-form">

    <?php $form = ActiveForm::begin([
        'options' => ['class' => '','enctype'=>'multipart/form-data'],
    ]); ?>

        <div class="row">
            <div class="col-md-4">
                <?= $form->field($model, 'bike_id')->dropDownList($data['bike_list']) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'condition_id')->dropDownList($data['condition_list']) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'region_id')->dropDownList($data['region_list']) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'pricepm')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <?= $form->field($model, 'imgFile')->fileInput() ?>
            </div>
        </div>
    <div class="row">
        <div class="col-md-3">
            <div style="    line-height: 55px;">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>


    <div class="form-group">
    </div>

    <?php ActiveForm::end(); ?>

</div>
