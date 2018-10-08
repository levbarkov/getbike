<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Regions */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="regions-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-6">    <?= $form->field($model, 'country_id')->dropDownList($country_list) ?>
        </div>
        <div class="col-md-6">    <?= $form->field($model, 'text')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <input type="hidden" name="type" value="Region">
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
