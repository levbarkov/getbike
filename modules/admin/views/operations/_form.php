<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Operations */
/* @var $form yii\widgets\ActiveForm */
$rental_list = \app\models\Rental::find()->asArray()->all();
$rental_list = \yii\helpers\ArrayHelper::map($rental_list, 'id', 'name');
?>

<div class="operations-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-2">
            <?= $form->field($model, 'rental_id')->dropDownList($rental_list) ?>
            <?//= $form->field($model, 'rental_id')->textInput() ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'sum')->textInput() ?>
        </div>

        <div class="col-md-2">
            <?= $form->field($model, 'date')->textInput() ?>

        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'operations')->textInput() ?>
        </div>
    </div>


    <?= $form->field($model, 'order_id')->hiddenInput(['value' => null])->label('') ?>


    <div class="form-group">
        <?= Html::submitButton(Yii::t('admin/operations', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
