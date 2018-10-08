<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\tinymce\TinyMce;

/* @var $this yii\web\View */
/* @var $model app\models\Pages */
/* @var $form yii\widgets\ActiveForm */

if($model->page_css)
    echo "<style>$model->page_css</style>";

?>

<div class="pages-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-2">
            <?= $form->field($model, 'alias')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'page_menu')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'page_title')->textarea(['rows' => 1]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'page_desc')->textarea(['rows' => 3]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">    <?= $form->field($model, 'page_js')->textarea(['rows' => 3]) ?>
        </div>
        <div class="col-md-6">    <?= $form->field($model, 'page_css')->textarea(['rows' => 3]) ?>
        </div>
    </div>
    <?//= $form->field($model, 'page_code')->textarea(['rows' => 6]) ?>
    <?= $form->field($model, 'page_code')->widget(TinyMce::className(), [
        'options' => ['rows' => 16],
        'language' => 'en_GB',
        'clientOptions' => [
            'plugins' => [
                "advlist autolink lists link charmap print preview anchor",
                "searchreplace visualblocks code fullscreen",
                "insertdatetime media table contextmenu paste"
            ],
            'toolbar' => "undo redo | styleselect fontsizeselect  | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
        ]
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
