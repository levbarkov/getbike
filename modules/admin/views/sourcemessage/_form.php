<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SourceMessage */
/* @var $form yii\widgets\ActiveForm */

$category_list = \app\models\SourceMessage::find()->select('category')->groupBy('category')->asArray()->all();


?>

<div class="source-message-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'category')->dropDownList(\yii\helpers\ArrayHelper::map($category_list, 'category', 'category'),['value' => !empty($model->category) ? $model->category : 'main']) ?>

    <?= $form->field($model, 'message')->textarea(['rows' => 2]) ?>

    <?php
    foreach (Yii::$app->params['languages'] as $val) {
        echo $form->field($model, 'languages['.$val.']')->textInput()->label($val);
    }
        ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('admin/sourcemessage', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
