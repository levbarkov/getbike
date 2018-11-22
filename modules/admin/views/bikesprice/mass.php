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
        'action' => ['mass']
    ]); ?>
        <div class="row">
            <div class="col-md-4">
                <?//= $form->field($model, 'bike_id')->dropDownList($data['bike_list']) ?>
                <div class="form-group">
                    <label for="bike_id">Bike</label>
                    <select class="form-control" id="bike_id" name="bike_id">
                        <?php foreach ($data['bike_list'] as $k => $v){ ?>
                            <option value="<?=$k?>"><?=$v?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <?//= $form->field($model, 'region_id')->dropDownList($data['region_list']) ?>
                <div class="form-group">
                    <label for="region_id">Region</label>
                    <select class="form-control" id="region_id" name="region_id">
                        <?php foreach ($data['region_list'] as $k => $v){ ?>
                            <option value="<?=$k?>"><?=$v?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </div>
    <?php foreach ($data['condition_list'] as $k => $v){ ?>
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <label for="condition_id<?=$k?>">Condition</label>
                    <select class="form-control" id="condition_id<?=$k?>" name="params[<?=$k?>][condition]">
                            <option value="<?=$k?>"><?=$v?></option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="price<?=$k?>">Price</label>
                    <input type="text" class="form-control" pattern="^[ 0-9]+$" id="price<?=$k?>" name="params[<?=$k?>][price]">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label for="pricepm<?=$k?>">Price/Month</label>
                    <input type="text" class="form-control" pattern="^[ 0-9]+$" id="pricepm<?=$k?>" name="params[<?=$k?>][pricepm]">
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    <label for="img<?=$k?>">Image</label>
                    <input type="file"  id="img<?=$k?>" name="condition_<?=$k?>">
                </div>
            </div>
        </div>
    <?php } ?>

   <!-- <?php /*foreach ($data['condition_list'] as $k => $v){ */?>
        <div class="row">
            <div class="col-md-3">
                <?/*= $form->field($model, '['.$k.']condition_id')->dropDownList($data['condition_list'],['value' => $k, 'readonly'=>'readonly']) */?>
            </div>
            <div class="col-md-3">
                <?/*= $form->field($model, '['.$k.']price')->textInput(['maxlength' => true]) */?>
            </div>
            <div class="col-md-3">
                <?/*= $form->field($model, '['.$k.']pricepm')->textInput(['maxlength' => true]) */?>
            </div>
            <div class="col-md-2">
                <?/*= $form->field($model, '['.$k.']imgFile')->fileInput() */?>
            </div>
            <div class="col-md-3">
                <?/*= $form->field($model, '['.$k.']cCheck')->checkbox() */?>
            </div>
        </div>
    --><?php /*} */?>
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
